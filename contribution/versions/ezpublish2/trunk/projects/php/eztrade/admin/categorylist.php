<?
// 
// $Id: categorylist.php,v 1.4 2000/09/27 07:08:28 bf-cvs Exp $
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );
include_once( $DOC_ROOT . "/classes/ezproduct.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/categorylist/",
                     $DOC_ROOT . "/admin/intl/", $Language, "categorylist.php" );

$t->setAllStrings();

$t->set_file( array(
    "category_list_page" => "categorylist.tpl",
    "category_item" => "categoryitem.tpl",
    "path_item" => "pathitem.tpl",
    "product_item" => "productitem.tpl"
    ) );

$category = new eZProductCategory(  );
$category->get( $ParentID );

// path
$pathArray = $category->path();

$t->set_var( "category_path", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "category_path", "path_item", true );
}


$categoryList = $category->getByParent( $category );

// categories
$i=0;
$t->set_var( "category_list", "" );
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

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->set_var( "category_description", $categoryItem->description() );

    $t->parse( "category_list", "category_item", true );
    $i++;
}

// products
$productList = $category->products();

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product_list", "" );
foreach ( $productList as $product )
{
    $t->set_var( "product_name", $product->name() );

    $price = new eZCurrency( $product->price() );
    
    $t->set_var( "product_price", $locale->format( $price ) );
    $t->set_var( "product_id", $product->id() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    

    $t->parse( "product_list", "product_item", true );
    $i++;
}

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "category_list_page" );

?>
