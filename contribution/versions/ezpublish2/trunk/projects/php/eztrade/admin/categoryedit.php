<?
// 
// $Id: categoryedit.php,v 1.2 2000/09/20 15:09:04 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Sep-2000 14:46:19 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );

// Direct actions
if ( $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $ParentID );
    
    $category = new eZProductCategory();
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );

    $category->store();

    Header( "Location: /trade/categorylist/" );
    exit();
}

if ( $Action == "Update" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $ParentID );
    
    $category = new eZProductCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );
    $category->setParent( $parentCategory );
    $category->setDescription( $Description );

    $category->store();

    Header( "Location: /trade/categorylist/" );
    exit();
}

if ( $Action == "Delete" )
{
    $category = new eZProductCategory();
    $category->get( $CategoryID );

    $category->delete();
    
    Header( "Location: /trade/categorylist/" );
    exit();
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/categoryedit/",
                     $DOC_ROOT . "/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_page" => "categoryedit.tpl",
                      "option_item" => "optionitem.tpl") );

$category = new eZProductCategory();

$categoryArray = $category->getAll( );

$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "action_value", "insert" );

// edit
if ( $Action == "Edit" )
{
    $category = new eZProductCategory();
    $category->get( $CategoryID );

    $t->set_var( "name_value", $category->name() );
    $t->set_var( "description_value", $category->description() );
    $t->set_var( "action_value", "update" );
    $t->set_var( "category_id", $category->id() );
}

foreach ( $categoryArray as $catItem )
{
    if ( $CategoryID != $catItem->id() )
    {
        $t->set_var( "option_value", $catItem->id() );
        $t->set_var( "option_name", $catItem->name() );

        $t->parse( "option_values", "option_item", true );
    }
}

$t->pparse( "output", "category_edit_page" );

?>
