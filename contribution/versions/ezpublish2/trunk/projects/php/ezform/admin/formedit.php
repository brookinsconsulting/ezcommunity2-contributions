<?php
// 
// $Id: formedit.php,v 1.26 2002/01/14 13:37:44 jhe Exp $
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

if ( isSet( $OK ) || isSet( $Update ) || isSet( $Preview ) )
{
    if ( empty( $formName ) )
    {
        $errorMessages[] = "form_name_not_set";
    }

    if ( $DataSender == "predefined" )
    {
        if ( empty( $formSender ) )
        {
            $errorMessages[] = "form_must_have_sender";
        }
        else
        {
            if ( eZMail::validate( $formSender ) == false )
            {
                $errorMessages[] = "form_sender_not_valid";
            }
        }        
    }

    if ( $DataHandlingSend == "send" )
    {
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

        if ( !empty( $formCC ) )
        {
            if ( eZMail::validate( $formCC ) == false )
            {
                $errorMessages[] = "form_cc_not_valid";
            }
        }
    }
    
    if ( $hasCompletion == "yes" && empty( $formCompletedPage ) )
    {
        $errorMessages[] = "form_completed_page_not_set";
    }

    if ( $hasInstructions == "no" )
    {
        $formInstructionPage = "no";
    }

    if ( count( $errorMessages ) == 0 )
    {
        $form->setName( $formName );
        
        if ( $DataHandlingSend == "send" )
        {
            $form->setReceiver( $formReceiver );
            $form->setCC( $formCC );
        }
        else
        {
            $formReceiver = "";
            $formCC = "";
            $form->setReceiver( $formReceiver );
            $form->setCC( $formCC );
        }

        if ( $DataHandlingDatabase == "database" )
        {
            $form->setUseDatabaseStorage( true );
        }
        else
        {
            $form->setUseDatabaseStorage( false );
        }
        
        if ( $DataSender == "predefined" )
        {
            $form->setSender( $formSender );
            $form->setSendAsUser( true );
        }
        else
        {
            $formSender = "";
            $form->setSender( $formSender );
            $form->setSendAsUser( false );
        }
        
        if ( $hasInstructions == "yes" )
        {
            $form->setInstructionPage( $formInstructionPage );
            $form->setInstructionPageName( $formInstructionPageName );
        }
        elseif ( $hasInstructions == "predefined" )
        {
            $formInstructionPage =& $ini->read_var( "eZFormMain", "DefaultInstructionPage" );
            $form->setInstructionPage( $formInstructionPage );
            $form->setInstructionPageName( $formInstructionPageNameB );
        }
        else
        {
            $formInstructionPage = "";
            $formInstructionPageName = "";
            $formInstructionPageNameB = "";
            $form->setInstructionPage( $formInstructionPage );
            $form->setInstructionPageName( $formInstructionPageName );
        }

        if ( $hasCompletion == "yes" )
        {
            $form->setCompletedPage( $formCompletedPage );
        }
        elseif ( $hasCompletion == "predefined" )
        {
            $formCompletedPage =& $ini->read_var( "eZFormMain", "DefaultRedirectPage" );
            $form->setCompletedPage( $formCompletedPage );
        }
        else
        {
            $formCompletedPage = "";
            $form->setCompletedPage( $formCompletedPage );
        }

        $form->setTitleField( $TitleField );
        $form->store();
        $FormID = $form->id();
        
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

$t->set_block( "form_item_tpl", "predefined_instructions_item_tpl", "predefined_instructions_item" );
$t->set_block( "form_item_tpl", "predefined_completion_item_tpl", "predefined_completion_item" );
$t->set_block( "form_item_tpl", "element_item_tpl", "element_item" );

// set all page template blocks.
$pageTemplate->set_block( "pagelist_tpl", "no_page_items_tpl", "no_page_items" );
$pageTemplate->set_block( "pagelist_tpl", "page_list_tpl", "page_list" );
$pageTemplate->set_block( "page_list_tpl", "page_item_tpl", "page_item" );

$pageTemplate->set_var( "no_page_items", "" );
$pageTemplate->set_var( "page_item", "" );

$t->set_var( "no_types_item", "" );
$t->set_var( "error_list", "" );
$t->set_var( "predefined_instructions_item", "" );
$t->set_var( "predefined_completion_item", "" );

$t->set_var( "form_completed_page", "" );
$t->set_var( "form_instruction_page", "" );
$t->set_var( "form_instruction_page_name", "" );
$t->set_var( "form_instruction_page_name_b", "" );
$t->set_var( "element_item", "" );

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

if ( $form->completedPage() != "" )
{
    if ( $ini->read_var( "eZFormMain", "DefaultRedirectPage" ) == $form->completedPage() )
    {
        $t->set_var( "form_completed_page", "" );
        $t->set_var( "hasCompletion-yes-checked", "" );
        $t->set_var( "hasCompletion-predefinend-checked", "checked" );
        $t->set_var( "hasCompletion-no-checked", "" );
        
    }
    else
    {
        $t->set_var( "form_completed_page", $form->completedPage() );
        $t->set_var( "hasCompletion-yes-checked", "checked" );
        $t->set_var( "hasCompletion-no-checked", "" );
        $t->set_var( "hasCompletion-predefinend-checked", "" );
    }
}
else
{
    $t->set_var( "form_completed_page", "" );
    $t->set_var( "hasCompletion-yes-checked", "" );
    $t->set_var( "hasCompletion-predefinend-checked", "" );
    $t->set_var( "hasCompletion-no-checked", "checked" );
}

if ( $ini->read_var( "eZFormMain", "UseDefaultRedirectPage" ) == "enabled" )
{
    $t->set_var( "form_predefined_completion_page", $ini->read_var( "eZFormMain", "DefaultRedirectPage" ) );
    $t->parse( "predefined_completion_item", "predefined_completion_item_tpl" );
}


if ( $form->instructionPage() != "" )
{
    if ( $ini->read_var( "eZFormMain", "DefaultInstructionPage" ) == $form->instructionPage() )
    {
        $t->set_var( "form_instruction_page", "" );
        $t->set_var( "hasInstructions-yes-checked", "" );
        $t->set_var( "hasInstructions-predefinend-checked", "checked" );
        $t->set_var( "hasInstructions-no-checked", "" );
        
    }
    else
    {
        $t->set_var( "form_instruction_page", $form->instructionPage() );
        $t->set_var( "form_instruction_page_name", $form->instructionPageName() );
        $t->set_var( "hasInstructions-yes-checked", "checked" );
        $t->set_var( "hasInstructions-no-checked", "" );
        $t->set_var( "hasInstructions-predefinend-checked", "" );
    }
}
else
{
    $t->set_var( "form_instruction_page", "" );
    $t->set_var( "hasInstructions-yes-checked", "" );
    $t->set_var( "hasInstructions-predefinend-checked", "" );
    $t->set_var( "hasInstructions-no-checked", "checked" );
}

if ( $ini->read_var( "eZFormMain", "UseDefaultInstructionPage" ) == "enabled" )
{
    $t->set_var( "form_predefined_page", $ini->read_var( "eZFormMain", "DefaultInstructionPage" ) );
    if ( $form->instructionPageName() == "" )
    {
        $t->set_var( "form_instruction_page_name_b", $ini->read_var( "eZFormMain", "DefaultInstructionPageName" ) );
    }
    else
    {
        $t->set_var( "form_instruction_page_name_b", $form->instructionPageName() );
    }
    $t->parse( "predefined_instructions_item", "predefined_instructions_item_tpl" );
}


if ( $action != "new" && $form->numberOfTypes() == 0 && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
{
    $t->parse( "no_types_item", "no_types_item_tpl" );
}


$t->set_var( "form_id", $FormID );
$t->set_var( "form_name", $form->name() );

if ( $form->receiver() == "" )
{
    $t->set_var( "form_receiver", "" );
    $t->set_var( "form_cc", "" );
    $t->set_var( "check_send_in_DataHandling", "" );    
}
else
{
    $t->set_var( "form_receiver", $form->receiver() );
    $t->set_var( "form_cc", $form->cc() );
    $t->set_var( "check_send_in_DataHandling", "checked" );    
}

if ( $form->useDatabaseStorage() )
{
    $t->set_var( "check_database_in_DataHandling", "checked" );    
}
else
{
    $t->set_var( "check_database_in_DataHandling", "" );    
}


if ( $form->isSendAsUser() )
{
    $t->set_var( "form_sender", $form->sender() );
    $t->set_var( "DataSender_is_user", "" );
    $t->set_var( "DataSender_is_predefined", "checked" );
}
else
{
    $t->set_var( "form_sender", "" );
    $t->set_var( "DataSender_is_user", "checked" );
    $t->set_var( "DataSender_is_predefined", "" );
}

$elementList = array();

if ( $FormID )
{
    $pages =& eZFormPage::getByFormID( $FormID );

    if ( count( $pages ) > 0 )
    {
        $form = new eZForm( $FormID );
        $elementList = $form->formElements();
        $ElementID = $form->titleField();
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

foreach ( $elementList as $element )
{
    $eType = $element->elementType();
    if ( $eType->name() == "table_item" )
    {
        $table = new eZFormTable( $element->id() );
        $tableElements = $table->tableElements();
        foreach ( $tableElements as $te )
        {
            $elementList[] = $te;
            $eT = $element->elementType();
            if ( !( $eT->name() == "text_label_item" ||
                    $eT->name() == "text_header_1_item" ||
                    $eT->name() == "text_header_2_item" ||
                    $eT->name() == "hr_line_item" ||
                    $eT->name() == "empty_item" ) )
            {
                $t->set_var( "element_id", $te->id() );
                if ( strlen( $te->name() ) > 40 )
                    $t->set_var( "element_name", substr( $te->name(), 0, 40 ) . "..." );
                else
                    $t->set_var( "element_name", $te->name() );

                $t->set_var( "selected", $ElementID == $te->id() ? "selected" : "" );
                $t->parse( "element_item", "element_item_tpl", true );
            }
        }
    }
    else
    {
        $elementList[] = $element;
        if ( !( $eType->name() == "text_label_item" ||
                $eType->name() == "text_header_1_item" ||
                $eType->name() == "text_header_2_item" ||
                $eType->name() == "hr_line_item" ||
                $eType->name() == "empty_item" ) )
        {
            $t->set_var( "element_id", $element->id() );
            if ( strlen( $element->name() ) > 40 )
                $t->set_var( "element_name", substr( $element->name(), 0, 40 ) . "..." );
            else
                $t->set_var( "element_name", $element->name() );
            $t->set_var( "selected", $ElementID == $element->id() ? "selected" : "" );
            $t->parse( "element_item", "element_item_tpl", true );
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
