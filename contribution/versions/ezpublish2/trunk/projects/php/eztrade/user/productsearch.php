<?php
// 
// $Id: productsearch.php,v 1.1 2000/10/22 10:33:23 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Oct-2000 17:49:05 bf>
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

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

$t = new eZTemplate( "eztrade/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) ,
                     "eztrade/intl/", $Language, "productsearch.php" );

$t->setAllStrings();

$t->set_file(  "product_search_tpl", "productsearch.tpl" );

$t->set_block( "product_search_tpl", "product_tpl", "product" );
$t->set_block( "product_tpl", "image_tpl", "image" );

// products
$product = new eZProduct();

if ( !isSet( $Limit ) )
    $Limit = 2;
if ( !isSet( $Offset ) )
    $Offset = 0;


$productList =& $product->activeProductSearch( $Query, $Offset, $Limit );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product", "" );
if ( isSet( $Query ) )
{
    foreach ( $productList as $product )
    {
        // preview image
        $thumbnailImage = $product->thumbnailImage();
//          if ( $thumbnailImage )
        if ( 0 )
        {
            $variation =& $thumbnailImage->requestImageVariation( 150, 150 );
    
            $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
            $t->set_var( "thumbnail_image_width", $variation->width() );
            $t->set_var( "thumbnail_image_height", $variation->height() );
            $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

            $t->parse( "image", "image_tpl" );
        }
        else
        {
            $t->set_var( "image", "" );    
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

        $t->parse( "product", "product_tpl", true );
        $i++;
    }
}

$t->set_var( "query", $Query );
$t->set_var( "limit", $Limit );
$prevOffs = $Offset - $Limit;
$nextOffs = $Offset + $Limit;

$t->set_var( "prev_offset", $prevOffs );
$t->set_var( "next_offset", $nextOffs );

$t->pparse( "output", "product_search_tpl" );

?>
