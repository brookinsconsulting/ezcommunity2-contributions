<?php
// 
// $Id: pageedit.php,v 1.21 2001/12/20 09:10:05 jhe Exp $
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

$ini =& INIFile::globalINI();

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}


$Language = $ini->read_var( "eZFormMain", "Language" );

// Make template for the page.
$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl", $Language, "pageedit.php" );

$t->set_file( "pageedit_tpl", "pageedit.tpl" );
$t->set_block( "pageedit_tpl", "element_choice_tpl", "element_choice" );
$t->set_block( "pageedit_tpl", "fixed_value_list_tpl", "fixed_value_list" );
$t->set_block( "fixed_value_list_tpl", "fixed_value_item_tpl", "fixed_value_item" );
$t->set_block( "fixed_value_item_tpl", "fixed_value_select_tpl", "fixed_value_select" );
$t->set_block( "fixed_value_select_tpl", "fixed_value_text_field_tpl", "fixed_value_text_field" );
$t->set_block( "fixed_value_select_tpl", "fixed_value_tpl", "fixed_value" );
$t->set_block( "fixed_value_item_tpl", "add_more_ranges_tpl", "add_more_ranges" );
$t->set_block( "fixed_value_select_tpl", "delete_range_tpl", "delete_range" );
$t->set_block( "add_more_ranges_tpl", "delete_range_button_tpl", "delete_range_button" );


$t->setAllStrings();

$t->set_var( "form_id", $FormID );
$t->set_var( "page_id", $PageID );
$t->set_var( "add_more_ranges", "" );
$t->set_var( "element_choice_name", "" );
$t->set_var( "fixed_value_select", "" );
$t->set_var( "fixed_value_item", "" );
$t->set_var( "fixed_value_list", "" );
$t->set_var( "delete_range", "" );
$t->set_var( "delete_range_button", "" );


// Make sub template for elements.
$elementTemplate = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl", $Language, "form.php" );

$elementTemplate->set_file( "elementlist_tpl", "elementlist.tpl" );


// set all element template blocks.
$elementTemplate->set_block( "elementlist_tpl", "error_list_tpl", "error_list" );

$elementTemplate->set_block( "elementlist_tpl", "no_elements_item_tpl", "no_elements_item" );
$elementTemplate->set_block( "elementlist_tpl", "element_list_tpl", "element_list" );
$elementTemplate->set_block( "element_list_tpl", "element_item_tpl", "element_item" );
$elementTemplate->set_block( "element_item_tpl", "typelist_item_tpl", "typelist_item" );
$elementTemplate->set_block( "element_item_tpl", "fixed_values_tpl", "fixed_values" );
$elementTemplate->set_block( "element_item_tpl", "table_edit_tpl", "table_edit" );
$elementTemplate->set_block( "element_item_tpl", "size_tpl", "size" );
$elementTemplate->set_block( "element_item_tpl", "table_size_tpl", "table_size" );
$elementTemplate->set_block( "element_item_tpl", "break_tpl", "break" );
$elementTemplate->set_block( "element_item_tpl", "text_block_edit_tpl", "text_block_edit" );
$elementTemplate->set_block( "element_item_tpl", "numerical_edit_tpl", "numerical_edit" );

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
$elementTemplate->set_var( "error_list", "" );

$elementTemplate->set_var( "form_id", $FormID );
$elementTemplate->set_var( "page_id", $PageID );

$page = new eZFormPage( $PageID );

if ( isSet( $PageName ) )
{
    $pageName = $PageName;
}
else
{
    $pageName = $page->name();
}

if ( isSet( $NewTextFieldRange ) )
{
    $next = max( $ElementRange ) + 1;
    $ElementRange[] = $next;
}

if ( isSet( $DeleteTextFieldRange ) && count( $DeleteRangeArrayID ) > 0 )
{
    $ElementRange = array_diff( $ElementRange, $DeleteRangeArrayID );
}

if ( $Action == "up" )
{
    $element = new eZFormElement( $ElementID );
    $page->moveElementUp( $element );
    eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID" );
    exit();
}

if ( $Action == "down" )
{
    $element = new eZFormElement( $ElementID );
    $page->moveElementDown( $element );
    eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID" );
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


$errorMessages = array();

if ( isSet( $OK ) || isSet( $Update ) || isSet( $NewElement ) )
{
    $page->setName( $pageName );
    
    if ( isSet( $NewElement ) || isSet( $Update ) )
    {
        $existingElementCount = $page->numberOfElements();
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
            $page->addElement( $element );
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
//            $element->setRequired( $required );

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
    }

    // store the page jumps.
    $elementID = $ElementChoiceID[0];
    $element = new eZFormElement( $elementID );
    $values =& $element->fixedValues();
    $elementType =& $element->elementType();
    $page->store();
    if ( $elementType && $elementType->name() == "text_field_item" )
    {
        if ( count( $ElementRange ) > 0 )
        {
            $i=0;
            $element->removeCondition();
            foreach ( $ElementRange as $range )
            {
                $checkID = "FixedPage_" . $range;
                $pageID = $$checkID;
                
                $element->addCondition( $pageID[0], $TextFieldFrom[$i], $TextFieldTo[$i] );
                
                $i++;
            }
        }
    }
    else
    {
        if ( count( $values ) > 0 )
        {
            $element->removeCondition();
            foreach ( $values as $value )
            {
                $checkID = "FixedPage_" . $value->id();
                $pageID = $$checkID;
                $element->addCondition( $pageID[0] , $value->id(), $value->id() );
            }
        }
    }
    
    
    if ( isSet( $OK ) && count( $errorMessages ) == 0 )
    {
        $page->store();
        eZHTTPTool::header( "Location: /form/form/edit/$FormID" );
        exit();
    }
    
    if ( isSet( $Preview ) && count( $errorMessages ) == 0 )
    {
        eZHTTPTool::header( "Location: /form/form/preview/$FormID/" );
        exit();
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


// check the jump conditions.
$elements = $page->pageElements();
$count = $page->numberOfElements();


if ( is_Numeric( $ElementChoiceID[0] ) && $ElementChoiceID[0] != 0 )
{
    $elementChoiceID = $ElementChoiceID[0];
}
else if ( isSet( $PageID ) && is_Array( $ElementChoiceID ) )
{
    foreach( $elements as $element )
    {
        $element->removeCondition();
    }
}
else if( isSet( $PageID ) )
{
    $elementChoiceID = $page->getConditionElement();
}
else if ( $elementChoiceID == 0 )
    unset( $elementChoiceID );

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

        // table is not used here, therefore set default to 0.
        $elementTemplate->set_var( "table_id", "0" );
        $elementTemplate->set_var( "element_page", "pageedit" );
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
        $elementTemplate->set_var( "text_block_edit", "" );
        $elementTemplate->set_var( "numerical_edit", "" );

        $name = $currentType->name();
        foreach ( $types as $type )
        {
            $elementTemplate->set_var( "selected", "" );
            
            if ( $type->id() == $currentType->id() )
            {
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
                
                if ( $name == "text_block_item" )
                {
                    $elementTemplate->parse( "text_block_edit", "text_block_edit_tpl" );
                }
                else
                {
                    $elementTemplate->set_var( "text_block_edit", "" );
                }
                
                if ( $name == "numerical_integer_item" ||
                     $name == "numerical_float_item" )
                {
                    $elementTemplate->parse( "numerical_edit", "numerical_edit_tpl" );
                }
                else
                {
                    $elementTemplate->set_var( "numerical_edit", "" );
                }
                
                $elementTemplate->set_var( "selected", "selected" );

                $elementTemplate->set_var( "element_nr", $i );
                if ( $name == "text_field_item" ||
                     $name == "numerical_float_item" ||
                     $name == "numerical_integer_item" )
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

if ( count( $errorMessages ) > 0 && !isSet( $NewElement ) && !isSet( $DeleteSelected )
      && count( $elements ) > 0 )
{
    $elementTemplate->parse( "error_list", "error_list_tpl" );
}


// parse the jump choices.

$element = new eZFormElement( $elementChoiceID );
if ( $element )
{
    $values =& $element->fixedValues();
    
    // parse the valid jump elements.
    $elements = $page->pageElements();
    
    if ( count( $elements ) > 0 )
    {
        foreach ( $elements as $pageElement )
        {
            $elementType =& $pageElement->elementType();
            $name = $elementType->name();
            
            if ( $name == "multiple_select_item" ||
            $name == "dropdown_item" ||
            $name == "radiobox_item" ||
            $name == "checkbox_item" ||
            $name == "text_field_item" )
            {
                if( $pageElement->id() == $elementChoiceID )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
                
                $t->set_var( "element_choice_id", $pageElement->id() );
                $t->set_var( "element_choice_name", $pageElement->name() );
                
                $t->parse( "element_choice", "element_choice_tpl", true );
            }
        }
    }
    
    if ( isSet( $elementChoiceID ) )
    {
        
        $elementType =& $element->elementType();
        if ( get_class( $elementType ) == "ezformelementtype" )
            $name = $elementType->name();
        else
            $name =  $elementType;
        
        if ( $elementType && $elementType->name() == "text_field_item" )
        {
            if ( count( $TextFieldFrom ) <= 0 )
            {
                $pageArray = $element->elementInCondition();
                for ( $i = 0; $i < count( $pageArray ); $i++ )
                {
                    $TextFieldFrom[$i] = $pageArray[$i]["Min"];
                    $TextFieldTo[$i] = $pageArray[$i]["Max"];
                    $TextFieldPage[$i] = $pageArray[$i]["Page"];
                    $ElementRange[$i] = $i;
                }

            }
        
            $i=0;
            if ( count( $ElementRange ) == 0 )
                $ElementRange = array( 1 );
            
            foreach ( $ElementRange as $range_id )
            {
                $t->set_var( "fixed_value_text_field", "" );
                $t->set_var( "fixed_value_select", "" );
                $t->set_var( "delete_range", "" );
                $t->set_var( "fixed_value_name", $element->name() );
                $t->set_var( "fixed_value_id", $range_id );
                $t->set_var( "from_value", $TextFieldFrom[$i] );
                $t->set_var( "to_value", $TextFieldTo[$i] );
                
                
                $t->set_var( "element_range", $i );
                
                $pages =& eZFormPage::getByFormID( $FormID );
                if ( count( $pages ) > 0 )
                {
                    $check_id = "FixedPage_" . $i;
                    $check = $$check_id;

                    $t->set_var( "fixed_value", "" );
                    
                    if ( !$check )
                    {
                        $pageArray = $element->elementInCondition();
                        if ( count( $pageArray ) > 0 )
                        {
                            foreach ( $pages as $pageValue )
                            {
                                if ( $page->id() != $pageValue->id() )
                                {

                                    if ( $TextFieldPage[$i] == $pageValue->id() )
                                    {
                                        $t->set_var( "selected", "selected" );
                                    }
                                    else
                                    {
                                        $t->set_var( "selected", "" );
                                    }
                                    $t->set_var( "page_id", $pageValue->id() );
                                    $t->set_var( "page_name", $pageValue->name() );
                                    
                                    $t->parse( "fixed_value", "fixed_value_tpl", true );
                                }
                            }
                        }
                    }
                    else
                    {
                        foreach ( $pages as $pageValue )
                        {
                            if ( $page->id() != $pageValue->id() )
                            {
                                
                                if ( $check[0] == $pageValue->id() )
                                {
                                    $t->set_var( "selected", "selected" );
                                }
                                else
                                {
                                    $t->set_var( "selected", "" );
                                }
                                
                                $t->set_var( "page_id", $pageValue->id() );
                                $t->set_var( "page_name", $pageValue->name() );
                                
                                $t->parse( "fixed_value", "fixed_value_tpl", true );
                            }
                        }
                    }
                }
                $i++;

                if ( $i != 1 )
                    $t->parse( "delete_range", "delete_range_tpl", true );
                
                $t->parse( "fixed_value_text_field", "fixed_value_text_field_tpl", true );
                $t->parse( "fixed_value_select", "fixed_value_select_tpl", true );
                
                if ( count( $ElementRange ) == $i )
                    $t->parse( "add_more_ranges", "add_more_ranges_tpl" );
                if ( count( $ElementRange ) != 1 )
                    $t->parse( "delete_range_button", "delete_range_button_tpl" );
                
                
                $t->parse( "fixed_value_item", "fixed_value_item_tpl", true );
            }
            $t->parse( "fixed_value_list", "fixed_value_list_tpl" );
        }
        else if ( count( $values ) > 0 )
        {
            $t->set_var( "fixed_value_text_field", "" );
            
            foreach( $values as $value )
            {
                $t->set_var( "fixed_value_name", $value->value() );
                $t->set_var( "fixed_value_id", $value->id() );
                $t->set_var( "fixed_value_select", "" );
                $t->set_var( "fixed_value", "" );
                
                $pages =& eZFormPage::getByFormID( $FormID );
                
                if( count( $pages ) > 0 )
                {
                    
                    $check_id = "FixedPage_" . $value->id();
                    $check = $$check_id;
                    
                    if ( !$check )
                    {
                        $check[0] = $element->getConditionMaxByPage( $value->id() );
                    }
                    
                    foreach( $pages as $pageValue )
                    {
                        if ( $page->id() != $pageValue->id() )
                        {
                            
                            
                            if ( $check[0] == $pageValue->id() )
                            {
                                $t->set_var( "selected", "selected" );
                            }
                            else
                            {
                                $t->set_var( "selected", "" );
                            }
                            $t->set_var( "page_id", $pageValue->id() );
                            $t->set_var( "page_name", $pageValue->name() );
                            $t->parse( "fixed_value", "fixed_value_tpl", true );
                        }
                    }                            
                }
                $t->parse( "fixed_value_select", "fixed_value_select_tpl", true );
                $t->parse( "fixed_value_item", "fixed_value_item_tpl", true );
            }
            $t->parse( "fixed_value_list", "fixed_value_list_tpl" );
        }
    }
}
$elementTemplate->set_var( "this_page", "pageedit" );

$elementTemplate->setAllStrings();
$elementListBody = $elementTemplate->parse( $target, "elementlist_tpl" );

$t->set_var( "page_name", $pageName );
$t->set_var( "action_value", "pageedit" );
$t->set_var( "page_id", $page->id() );
$t->set_var( "form_id", $FormID  );

$t->set_var( "element_list", $elementListBody );

$t->pparse( "output", "pageedit_tpl" );

?>
