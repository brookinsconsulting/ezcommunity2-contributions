<?php
// 
// $Id: formedit.php,v 1.18 2001/12/17 09:42:45 br Exp $
//
// Created on: <12-Jun-2001 13:07:24 pkej>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezform/classes/ezformtable.php" );
include_once( "ezform/classes/ezformpage.php" );
include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /form/form/list/" );
    exit();
}

if ( is_Numeric( $FormID ) )
{
    $ActionValue = "edit";
}
else
{
    $ActionValue = "new";
}

$form = new eZForm( $FormID );


if ( is_Numeric( $MovePageUp ) )
{
    eZFormPage::moveUp( $MovePageUp );

    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if ( is_Numeric( $MovePageDown ) )
{
    eZFormPage::moveDown( $MovePageDown );

    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if ( count( $DeletePageArrayID ) > 0 )
{
    foreach( $DeletePageArrayID as $pageID )
    {
        if ( is_Numeric( $pageID ) )
        {
            $page = new eZFormPage( $pageID );
            $page->delete();
        }
    }
}

if ( isSet( $NewPage ) )
{
    if ( !$FormID )
    {
        $form = new eZForm();
        $form->store();
        $FormID = $form->id();

        $page = new eZFormPage();
        $page->setFormID( $FormID );
        $page->store();
        eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
        exit();
    }
    else
    {
        $page = new eZFormPage();
        $page->setFormID( $FormID );
        $page->store();
    }
}



$errorMessages = array();

if ( isSet( $OK ) || isSet( $Update ) || isSet( $Preview ) || isSet( $NewElement ) )
{
    if ( empty( $formSender ) )
    {
        if ( isSet( $formSendAsUser ) == false )
        {
            $errorMessages[] = "form_must_have_sender";
        }
    }
    else
    {
        if ( isSet( $formSendAsUser ) )
        {
            $errorMessages[] = "form_cant_have_both";
        }
        else
        {
            if ( eZMail::validate( $formReceiver ) == false )
            {
                $errorMessages[] = "form_sender_not_valid";
            }
        }
    }

    if ( empty( $formName ) )
    {
        $errorMessages[] = "form_name_not_set";
    }

    if ( empty( $formReceiver ) )
    {
        $errorMessages[] = "form_receiver_not_set";
    }
    else
    {
        if ( eZMail::validate( $formReceiver ) == false )
        {
            $errorMessages[] = "form_receiver_not_valid";
        }
    }

    if ( empty( $formCompletedPage ) )
    {
        $errorMessages[] = "form_completed_page_not_set";
    }

    if ( empty( $formCC ) == false )
    {
        if ( eZMail::validate( $formCC ) == false )
        {
            $errorMessages[] = "form_cc_not_valid";
        }
    }

    if ( count( $errorMessages ) == 0 || isSet( $NewElement ) || isSet( $Update ) )
    {
        $form->setName( $formName );
        $form->setReceiver( $formReceiver );
        $form->setCC( $formCC );
        $form->setCompletedPage( $formCompletedPage );
        $form->setInstructionPage( $formInstructionPage );
        $form->setSender( $formSender );
        
        if ( isSet( $formSendAsUser ) )
        {
            $form->setSendAsUser( true );
        }
        else
        {
            $form->setSendAsUser( false );
        }
        
        $form->store();
        $FormID = $form->id();
        
        $existingElementCount = $form->numberOfElements();
        $existingElementCount++;
        
        if ( isSet( $NewElement ) )
        {
            $newElementName =& $ini->read_var( "eZFormMain", "DefaultElementName" );
            $newElementName = $newElementName . " " . $existingElementCount;
            $element = new eZFormElement();
            $element->setName( $newElementName );
            $element->store();
        }
        
        if ( isSet( $element ) )
        {
            $form->addElement( $element );
        }

        $elementCount = count( $elementID );
        $elementTypeError = false;
        
        for ( $i = 0; $i < $elementCount; $i++ )
        {
            $element = new eZFormElement( $elementID[$i] );
            $elementType = new eZFormElementType( $elementTypeID[$i] );
            $element->setElementType( $elementType );

            if ( $elementType->id() == 0 && $elementTypeError == false )
            {
                $errorMessages[] = "all_elements_must_have_type";
                $elementTypeError = true;
            }

            $element->setName( $elementName[$i] );
            $element->setSize( $Size[$i] );


            $required = false;
            $break = false;
            if ( count( $elementRequired ) > 0 )
            {
                foreach ( $elementRequired as $requiredID )
                {
                    if ( $elementID[$i] == $requiredID )
                    {
                        $element->setRequired( true );
                        $required = true;
                    }
                }
            }
            if ( count( $ElementBreak ) > 0 )
            {
                foreach ( $ElementBreak as $breakID )
                {
                    if ( $elementID[$i] == $breakID )
                    {
                        $element->setBreak( true );
                        $break = true;
                    }
                }
            }

            $element->setBreak( $break );
            $element->setRequired( $required );

            $element->store();

            if ( $elementType->name() == "table_item" )
            {
                $table = new eZFormTable( $element->ID() );
                $table->setCols( $Size[$i] );
                $table->setRows( $Rows[$i] );
                $table->setElementID( $element->id() );
                $table->store();
            }
        }

        if ( isSet( $OK ) && count( $errorMessages ) == 0 )
        {
            eZHTTPTool::header( "Location: /form/form/list/" );
            exit();
        }

        if ( isSet( $Preview ) && count( $errorMessages ) == 0 )
        {
            eZHTTPTool::header( "Location: /form/form/preview/$FormID/" );
            exit();
        }
    }
}

$Language = $ini->read_var( "eZFormMain", "Language" );

// set the templates.
$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "form.php" );

$t->set_file( "form_edit_page_tpl", "formedit.tpl" );


$pageTemplate = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "form.php" );

$pageTemplate->set_file( "pagelist_tpl", "pagelist.tpl" );

// set all blocks for this file.
$t->set_block( "form_edit_page_tpl", "form_item_tpl", "form_item" );
$t->set_block( "form_edit_page_tpl", "error_list_tpl", "error_list" );
$t->set_block( "error_list_tpl", "error_item_tpl", "error_item" );
$t->set_block( "form_edit_page_tpl", "no_types_item_tpl", "no_types_item" );

// set all page template blocks.
$pageTemplate->set_block( "pagelist_tpl", "no_page_items_tpl", "no_page_items" );
$pageTemplate->set_block( "pagelist_tpl", "page_list_tpl", "page_list" );
$pageTemplate->set_block( "page_list_tpl", "page_item_tpl", "page_item" );

$pageTemplate->set_var( "no_page_items", "" );
$pageTemplate->set_var( "page_item", "" );



$t->set_var( "no_types_item", "" );
$t->set_var( "error_list", "" );

$t->set_var( "form_completed_page", "" );
$t->set_var( "form_instruction_page", "" );

if ( $form->completedPage() )
{
    $t->set_var( "form_completed_page", $form->completedPage() );
}
else
{
    if ( $ini->read_var( "eZFormMain", "UseDefaultRedirectPage" ) == "enabled" )
    {
        $t->set_var( "form_completed_page", $ini->read_var( "eZFormMain", "DefaultRedirectPage" ) );
    }
}

if ( $form->instructionPage() )
{
    $t->set_var( "form_instruction_page", $form->instructionPage() );
}
else
{
    if ( $ini->read_var( "eZFormMain", "UseDefaultInstructionPage" ) == "enabled" )
    {
        $t->set_var( "form_instruction_page", $ini->read_var( "eZFormMain", "DefaultInstructionPage" ) );
    }
}

if ( $action != "new" && $form->numberOfTypes() == 0 && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
{
    $t->parse( "no_types_item", "no_types_item_tpl" );
}


$t->set_var( "form_id", $FormID );
$t->set_var( "form_name", $form->name() );
$t->set_var( "form_receiver", $form->receiver() );
$t->set_var( "form_cc", $form->cc() );
$t->set_var( "form_sender", $form->sender() );


if ( $form->isSendAsUser() )
{
    $t->set_var( "form_send_as_user", "1" );
    $t->set_var( "checked", "checked" );
}
else
{
    $t->set_var( "form_send_as_user", "0" );
    $t->set_var( "checked", "" );
}

if ( $FormID )
{
    $pages =& eZFormPage::getByFormID( $FormID );

    if ( count( $pages ) > 0 )
    {
        $i = 0;
        foreach ( $pages as $page )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $pageTemplate->set_var( "td_class", "bglight" );
            }
            else
            {
                $pageTemplate->set_var( "td_class", "bgdark" );
            }
            
            $pageTemplate->set_var( "page_id", $page->id() );
            $pageTemplate->set_var( "action_value", $ActionValue );
            $pageTemplate->set_var( "form_id", $form->id() );
            $pageTemplate->set_var( "page_name", $page->name() );
            
            $pageTemplate->parse( "page_item", "page_item_tpl", true );
            $i++;
        }
    }
}

$pageTemplate->parse( "page_list", "page_list_tpl" );


$t->parse( "form_item", "form_item_tpl" );

$pageListBody = $pageTemplate->parse( $target, "pagelist_tpl" );
$t->set_var( "element_list", $elementListBody );
$t->set_var( "page_list", $pageListBody );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteStyle );
$t->setAllStrings();
$t->pparse( "output", "form_edit_page_tpl" );

?>
