<?
// 
// $Id: productlist.php,v 1.1 2000/09/24 11:51:37 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Sep-2000 14:46:20 bf>
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

include_once( $DOC_ROOT . "/classes/ezproduct.php" );
include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/productlist/",
                     $DOC_ROOT . "/intl/", $Language, "productlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "product_list_page" => "productlist.tpl",
    "product_item" => "productitem.tpl",
    "product_image" => "productimage.tpl",
    "path_item" => "pathitem.tpl",
    "category_item" => "categoryitem.tpl"
    ) );

$category = new eZProductCategory(  );
$category->get( $CategoryID );


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
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );

    $t->set_var( "category_name", $categoryItem->name() );

    $t->set_var( "category_description", $categoryItem->description() );

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
    // preview image
    $thumbnailImage = $product->thumbnailImage();
    if ( $thumbnailImage )
    {
        $variation = $thumbnailImage->requestImageVariation( 150, 150 );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "product_thumbnail_image", "product_image" );
    }
    else
    {
        $t->set_var( "product_image", "" );    
    }

    $t->set_var( "product_name", $product->name() );

    $price = new eZCurrency( $product->price() );
    
    $t->set_var( "product_price", $locale->format( $price ) );
    $t->set_var( "product_intro_text", $product->brief() );
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "category_id", $category->id() );

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




$t->pparse( "output", "product_list_page" );

?>
