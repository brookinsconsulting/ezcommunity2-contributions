<?php
// 
// $Id: productsearch.php,v 1.6 2000/11/01 10:03:34 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Oct-2000 17:49:05 bf>
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

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) ,
                     "eztrade/user/intl/", $Language, "productsearch.php" );

$t->setAllStrings();

$t->set_file(  "product_search_tpl", "productsearch.tpl" );

$t->set_block( "product_search_tpl", "product_tpl", "product" );



$t->set_block( "product_tpl", "image_tpl", "image" );
$t->set_block( "product_tpl", "price_tpl", "price" );

$t->set_block( "product_search_tpl", "previous_tpl", "previous" );
$t->set_block( "product_search_tpl", "next_tpl", "next" );

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

        if ( $product->showPrice() == true  )
        {
            $price = new eZCurrency( $product->price() );
            $t->set_var( "product_price", $locale->format( $price ) );
            $t->parse( "price", "price_tpl" );
        }
        else
        {
            $t->set_var( "price", "" );
        }
        
        
        $t->set_var( "product_intro_text", $product->brief() );
        $t->set_var( "product_id", $product->id() );

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $prevOffs = $Offset - $Limit;
        $nextOffs = $Offset + $Limit;
        
        if ( $prevOffs >= 0 )
        {
            $t->set_var( "prev_offset", $prevOffs  );
            $t->parse( "previous", "previous_tpl" );
        }
        else
        {
        $t->set_var( "previous", "" );
        }
        
        if ( $nextOffs <= $total_count )
        {
            $t->set_var( "next_offset", $nextOffs  );
            $t->parse( "next", "next_tpl" );
        }
        else
        {
            $t->set_var( "next", "" );
        }

        $t->parse( "product", "product_tpl", true );
        $i++;
    }
}

$t->set_var( "query_string", $Query );
$t->set_var( "query", $Query );
$t->set_var( "limit", $Limit );
$prevOffs = $Offset - $Limit;
$nextOffs = $Offset + $Limit;

$t->set_var( "prev_offset", $prevOffs );
$t->set_var( "next_offset", $nextOffs );

$t->pparse( "output", "product_search_tpl" );
?>

