<?
// 
// $Id: categorylist.php,v 1.1 2000/09/14 18:19:40 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Sep-2000 14:56:11 bf>
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

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/categorylist/",
                     $DOC_ROOT . "/admin/intl/", $Language, "categorylist.php" );

$t->setAllStrings();

$t->set_file( array(
    "category_list_page" => "categorylist.tpl",
    "category_item" => "categoryitem.tpl"
    ) );

$category = new eZProductCategory(  );
$category->get( $ParentID );

$categoryList = $category->getByParent( $category );

foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );

    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();
    
    if ( $categoryItem->parent() != 0 )
    {
        $parent = $categoryItem->parent();
        $t->set_var( "category_parent", $parent->name() );
    }
    else
    {
        $t->set_var( "category_parent", "&nbsp;" );
    }
    
    $t->set_var( "category_description", $categoryItem->description() );

    $t->parse( "category_list", "category_item", true );    
}



$t->set_var( "document_root", $DOC_ROOT );


$t->pparse( "output", "category_list_page" );

?>
