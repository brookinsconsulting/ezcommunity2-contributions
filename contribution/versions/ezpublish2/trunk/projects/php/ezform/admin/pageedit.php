<?php
// 
// $Id: pageedit.php,v 1.6 2001/12/17 13:29:17 br Exp $
//
// Definition of ||| class
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <14-Dec-2001 12:44:00 br>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! |||
//! 
/*!
 
  Example code:
  \code
  \endcode

*/
       
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
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if ( isSet( $OK ) && count( $errorMessages ) == 0 )
{
  eZHTTPTool::header( "Location: /form/form/edit/$FormID" );
  exit();
}

if ( isSet( $Preview ) && count( $errorMessages ) == 0 )
{
    eZHTTPTool::header( "Location: /form/form/preview/$FormID/" );
    exit();
}

$Language = $ini->read_var( "eZFormMain", "Language" );

// Make template for the page.
$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl", $Language, "pageedit.php" );

$t->set_file( "pageedit_tpl", "pageedit.tpl" );
$t->setAllStrings();

$t->set_var( "form_id", $FormID );
$t->set_var( "page_id", $PageID );

// Make sub template for elements.
$elementTemplate = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl", $Language, "form.php" );

$elementTemplate->set_file( "elementlist_tpl", "elementlist.tpl" );


// set all element template blocks.
$elementTemplate->set_block( "elementlist_tpl", "no_elements_item_tpl", "no_elements_item" );
$elementTemplate->set_block( "elementlist_tpl", "element_list_tpl", "element_list" );
$elementTemplate->set_block( "element_list_tpl", "element_item_tpl", "element_item" );
$elementTemplate->set_block( "element_item_tpl", "typelist_item_tpl", "typelist_item" );
$elementTemplate->set_block( "element_item_tpl", "fixed_values_tpl", "fixed_values" );
$elementTemplate->set_block( "element_item_tpl", "table_edit_tpl", "table_edit" );
$elementTemplate->set_block( "element_item_tpl", "size_tpl", "size" );
$elementTemplate->set_block( "element_item_tpl", "table_size_tpl", "table_size" );
$elementTemplate->set_block( "element_item_tpl", "break_tpl", "break" );

$move_item = true;
$elementTemplate->set_block( "element_item_tpl", "item_move_up_tpl", "item_move_up" );
$elementTemplate->set_block( "element_item_tpl", "item_separator_tpl", "item_separator" );
$elementTemplate->set_block( "element_item_tpl", "item_move_down_tpl", "item_move_down" );
$elementTemplate->set_block( "element_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$elementTemplate->set_block( "element_item_tpl", "no_item_separator_tpl", "no_item_separator" );
$elementTemplate->set_block( "element_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

$elementTemplate->set_var( "no_elements_item", "" );
$elementTemplate->set_var( "element_list", "" );
$elementTemplate->set_var( "element_item", "" );
$elementTemplate->set_var( "typelist_item", "" );
$elementTemplate->set_var( "checked", "" );

$elementTemplate->set_var( "form_id", $FormID );
$elementTemplate->set_var( "page_id", $PageID );

$page = new eZFormPage( $PageID );

if ( $Action == "up" )
{
    $element = new eZFormElement( $ElementID );
    $form->moveUp( $element );
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if ( $Action == "down" )
{
    $element = new eZFormElement( $ElementID );
    $form->moveDown( $element );
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}


if ( isSet( $DeleteSelected ) )
{
    foreach ( $elementDelete as $deleteMe )
    {
        $element = new eZFormElement( $deleteMe );
        $element->delete();
    }
}


if ( $page->numberOfElements() == 0 )
{
    if ( $ini->read_var( "eZFormMain", "CreateEmailDefaults" ) == "enabled" )
    {
        $page->store();
        $PageID = $page->id();
        $elementTypeA = new eZFormElementType( 1 );
        $elementTypeB = new eZFormElementType( 2 );
        $elementA = new eZFormElement();
        $elementB = new eZFormElement();
        $name = $t->Ini->read_var( "strings", "subject_label" );
        $name = $t->Ini->read_var( "strings", "content_label" );
        $elementA->setName( $name );
        $elementB->setName( $name );
        $elementA->setElementType( $elementTypeA );
        $elementB->setElementType( $elementTypeB );
        $elementA->setRequired( true );
        $elementB->setRequired( true );
        $elementA->store();
        $elementB->store();
        $page->addElement( $elementA );
        $page->addElement( $elementB );
    }
    else
    {
        if ( $Action != "new" && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
            $elementTemplate->parse( "no_elements_item", "no_elements_item_tpl" );
    }
}


// ****************** BEGIN Elements ******************

$elements = $page->pageElements();
$count = $page->numberOfElements();

if ( $count > 0 )
{
    $i = 0;
    foreach ( $elements as $element )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $elementTemplate->set_var( "td_class", "bglight" );
        }
        else
        {
            $elementTemplate->set_var( "td_class", "bgdark" );
        }
        $elementTemplate->set_var( "element_name", $element->name() );
        $elementTemplate->set_var( "element_id", $element->id() );
        $elementTemplate->set_var( "element_size", $element->size() );
        
        if ( $element->isRequired() )
        {
            $elementTemplate->set_var( "element_required", "checked" );
        }
        else
        {
            $elementTemplate->set_var( "element_required", "" );
        }

        if ( $element->isBreaking() )
        {
            $elementTemplate->set_var( "element_is_breaking", "checked" );
        }
        else
        {
            $elementTemplate->set_var( "element_is_breaking", "" );
        }

        $currentType = $element->elementType();
        $types = $currentType->getAll();

        $elementTemplate->set_var( "fixed_values", "" );
        $elementTemplate->set_var( "table_table", "" );
        $elementTemplate->set_var( "size", "" );
        $elementTemplate->set_var( "table_size", "" );
        $elementTemplate->set_var( "typelist_item", "" );
        $elementTemplate->set_var( "break", "" );
        $elementTemplate->set_var( "table_edit", "" );

        foreach ( $types as $type )
        {
            $elementTemplate->set_var( "selected", "" );
            
            if ( $type->id() == $currentType->id() )
            {
                $name = $currentType->name();
                if ( $name == "multiple_select_item" ||
                     $name == "dropdown_item" ||
                     $name == "radiobox_item" ||
                     $name == "checkbox_item" )
                {
                    $elementTemplate->parse( "fixed_values", "fixed_values_tpl" );
                }
                else
                {
                    $elementTemplate->set_var( "fixed_values", "" );
                }
                
                $elementTemplate->set_var( "selected", "selected" );

                $elementTemplate->set_var( "element_nr", $i );
                if ( $name == "text_field_item" )
                {
                    $elementTemplate->parse( "size", "size_tpl" );
                    $elementTemplate->parse( "break", "break_tpl" );
                }
                else
                {
                    $elementTemplate->set_var( "break", "" );
                    if ( $name == "table_item" )
                    {
                        $table = new eZFormTable( $element->id() );
                        $elementTemplate->set_var( "element_size", $table->cols() );
                        $elementTemplate->set_var( "element_rows", $table->rows() );
                        $elementTemplate->parse( "size", "size_tpl" );
                        $elementTemplate->parse( "table_size", "table_size_tpl" );
                        $elementTemplate->parse( "table_edit", "table_edit_tpl" );
                    }
                }
            }
            
            $elementTemplate->set_var( "element_type_id", $type->id() );
            $elementTemplate->set_var( "element_type_name", $type->name() );
            $elementTemplate->parse( "typelist_item", "typelist_item_tpl", true );
        }
        
        $elementTemplate->set_var( "item_move_up", "" );
        $elementTemplate->set_var( "no_item_move_up", "" );
        $elementTemplate->set_var( "item_move_down", "" );
        $elementTemplate->set_var( "no_item_move_down", "" );
        $elementTemplate->set_var( "item_separator", "" );
        $elementTemplate->set_var( "no_item_separator", "" );

        if ( isSet( $move_item ) )
        {
            $elementTemplate->parse( "item_move_up", "item_move_up_tpl" );
        }
        
        if ( isSet( $move_item ) )
        {
            $elementTemplate->parse( "item_separator", "item_separator_tpl" );
        }
        
        if ( isSet( $move_item ) )
        {
            $elementTemplate->parse( "item_move_down", "item_move_down_tpl" );
        }

        $elementTemplate->parse( "element_item", "element_item_tpl", true );
        $i++;
    }
    
    $elementTemplate->parse( "element_list", "element_list_tpl" );
}

if ( count( $errorMessages ) > 0 && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
{
    foreach ( $errorMessages as $errorMessage )
    {
        $errorMessage =& $t->Ini->read_var( "strings", $errorMessage );
        $elementTemplate->set_var( "error_message", $errorMessage );
        $elementTemplate->parse( "error_item", "error_item_tpl", true );
    }
    
    $t->set_var( "form_name", $formName );
    $t->set_var( "form_receiver", $formReceiver );
    $t->set_var( "form_cc", $formCC );
    $t->set_var( "form_completed_page", $formCompletedPage );
    $t->set_var( "form_sender", $formSender );
    
    if ( isSet( $formSendAsUser ) )
    {
        $t->set_var( "checked", "checked" );
    }
    $t->set_var( "form_instruction_page", $formInstructionPage );

    $elementTemplate->parse( "error_list", "error_list_tpl" );
}

// ****************** END Elements ******************

$elementTemplate->setAllStrings();
$elementListBody = $elementTemplate->parse( $target, "elementlist_tpl" );

// print( $elementListBody );

$t->set_var( "page_name", $page->name() );
$t->set_var( "action_value", "pageedit" );
$t->set_var( "page_id", $page->id() );
$t->set_var( "form_id", $FormID  );

$t->set_var( "element_list", $elementListBody );

$t->pparse( "output", "pageedit_tpl" );

?>
