<?
// 
// $Id: classifiedcategoryedit.php,v 1.1 2000/12/21 12:02:02 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Sep-2000 14:46:19 bf>
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

if ( isset( $Cancel ) )
{
    Header( "Location: /classified/list/$categoryID/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZClassifiedMain", "Language" );

include_once( "ezclassified/classes/ezclassified.php" );
include_once( "ezclassified/classes/ezcategory.php" );


// Direct actions
if ( $Action == "Insert" )
{
    $parentCategory = new eZCategory();
    $parentCategory->get( $ParentID );

    $category = new eZCategory();
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );

    
    $category->store();

    $categoryID = $category->id();

    Header( "Location: /classified/list/$categoryID/" );
    exit();
}

if ( $Action == "Update" )
{
    $parentCategory = new eZCategory();
    $parentCategory->get( $ParentID );
    
    $category = new eZCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );

    $category->store();

    $categoryID = $category->id();

    Header( "Location: /classified/list/$categoryID/" );
    exit();
}

if ( $Action == "Delete" )
{
    $category = new eZCategory();
    $category->get( $CategoryID );

    $category->delete();
    
    Header( "Location: /classified/list/" );
    exit();
}

$t = new eZTemplate( "ezclassified/admin/" . $ini->read_var( "eZClassifiedMain", "AdminTemplateDir" ),
                     "ezclassified/admin/intl/", $Language, "classifiedcategoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_tpl" => "classifiedcategoryedit.tpl" ) );


$t->set_block( "category_edit_tpl", "value_tpl", "value" );
               
$category = new eZCategory();

$categoryArray = $category->getAll( );

$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "action_value", "insert" );
$t->set_var( "category_id", "" );


// edit
if ( $Action == "Edit" )
{
    $category = new eZCategory();
    $category->get( $CategoryID );

    $t->set_var( "name_value", $category->name() );
    $t->set_var( "description_value", $category->description() );
    $t->set_var( "action_value", "update" );
    $t->set_var( "category_id", $category->id() );
    $parentID = $category->parentID();
}

$category = new eZCategory();

$tree = $category->getTree();

foreach( $tree as $item )
{
    $t->set_var( "option_value", $item[0]->id() );
    $t->set_var( "option_name", $item[0]->name() );

    if ( $item[2] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $item[2] ) );
    else
        $t->set_var( "option_level", "" );

    if ( $item[0]->id() == $parentID )
    {
        $t->set_var( "selected", "selected" );
        $selected = true;
    }
    else
    {
        $t->set_var( "selected", "" );
    }            
    
    $t->parse( "value", "value_tpl", true );
}



$t->pparse( "output", "category_edit_tpl" );

?>
