<?php
//
// $Id: tableedit.php,v 1.6 2001/12/14 14:25:43 jhe Exp $
//
// Created on: <13-Dec-2001 10:51:41 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

$ini =& INIFile::globalINI();

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID/" );
    exit();
}

$table = new eZFormTable( $TableID );

if ( $Action == "up" )
{
    $table->moveUp( $ElementID );
    eZHTTPTool::header( "Location: /form/form/tableedit/$FormID/$PageID/$TableID/" );
    exit();
}

if ( $Action == "down" )
{
    $table->moveDown( $ElementID );
    eZHTTPTool::header( "Location: /form/form/tableedit/$FormID/$PageID/$TableID/" );
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

$cells = $table->rows() * $table->cols();


if ( isSet( $OK ) || isSet( $Update ) )
{
    for ( $i = 0; $i < $cells; $i++ )
    {
        $element = new eZFormElement( $elementID[$i] );
        $elementType = new eZFormElementType( $elementTypeID[$i] );
        $element->setElementType( $elementType );
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
    }
    if ( isSet( $OK ) )
    {
        eZHTTPTool::header( "Location: /form/form/pageedit/$FormID/$PageID/" );
        exit();
    }
}


$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "table.php" );

$t->set_file( "table_edit_page_tpl", "tableedit.tpl" );

$t->set_block( "table_edit_page_tpl", "row_list_tpl", "row_list" );

$elementTemplate = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                                   "ezform/admin/intl/", $Language, "form.php" );

$elementTemplate->set_file( "elementlist_tpl", "elementlist.tpl" );

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
$elementTemplate->set_var( "element_page", "tableedit" );

$elementTemplate->set_var( "form_id", $FormID );
$elementTemplate->set_var( "page_id", $PageID );
$elementTemplate->set_var( "table_id", $TableID );

$elementList = eZFormTable::tableElements( $TableID );
$types = eZFormElementType::getAll();
$i = 0;

$t->set_var( "form_id", $FormID );
$t->set_var( "page_id", $PageID );
$t->set_var( "table_id", $TableID );
$t->set_var( "row_list", "" );

for ( $col = 0; $col < $table->cols(); $col++ )
{
    $t->set_var( "col", $col + 1 );
    for ( $row = 0; $row < $table->rows(); $row++ )
    {
        if ( ( $row % 2 ) == 0 )
            $elementTemplate->set_var( "td_class", "bglight" );
        else
            $elementTemplate->set_var( "td_class", "bgdark" );

        $element = $elementList[$i];
        if ( get_class( $element ) != "ezformelement" )
        {
            $newElementName = $ini->read_var( "eZFormMain", "DefaultElementName" );
            $newElementName = $newElementName . " " . $i;

            $element = new eZFormElement();
            $element->setName( $newElementName );
            $element->store();
            $table->addElement( $element );
        }

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

        $elementTemplate->set_var( "fixed_values", "" );
        $elementTemplate->set_var( "table_table", "" );
        $elementTemplate->set_var( "size", "" );
        $elementTemplate->set_var( "table_size", "" );
        $elementTemplate->set_var( "typelist_item", "" );
        $elementTemplate->set_var( "break", "" );
        $elementTemplate->set_var( "table_edit", "" );

        $currentType = $element->elementType();

        foreach ( $types as $type )
        {
            $elementTemplate->set_var( "selected", "" );
            
            if ( get_class( $currentType ) == "ezformelementtype" && $type->id() == $currentType->id() )
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

        
        $elementTemplate->set_var( "element_name", $element->name() );
        $elementTemplate->set_var( "element_id", $element->id() );
        $elementTemplate->set_var( "element_size", $element->size() );
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

        $elementTemplate->setAllStrings();

        if ( $row == 0 )
            $elementTemplate->parse( "element_item", "element_item_tpl" );
        else
            $elementTemplate->parse( "element_item", "element_item_tpl", true );

        $i++;
    }

    $elementTemplate->parse( "element_list", "element_list_tpl" );
    $elementListBody = $elementTemplate->parse( $target, "elementlist_tpl" );
    $t->set_var( "element_list", $elementListBody );
    $t->parse( "row_list", "row_list_tpl", true );
}

$t->setAllStrings();
$t->pparse( "output", "table_edit_page_tpl" );

?>
