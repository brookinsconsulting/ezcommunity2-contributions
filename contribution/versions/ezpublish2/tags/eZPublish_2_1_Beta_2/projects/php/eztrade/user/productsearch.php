<?php
// 
// $Id: productsearch.php,v 1.17 2001/04/26 10:44:39 ce Exp $
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "true";
$MaxSearchForProducts = $ini->read_var( "eZTradeMain", "MaxSearchForProducts" );
$SmallImageWidth = $ini->read_var( "eZTradeMain", "SmallImageWidth" );
$SmallImageHeight = $ini->read_var( "eZTradeMain", "SmallImageHeight" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "classes/ezlist.php" );

$user = eZUser::currentUser();

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) ,
                     "eztrade/user/intl/", $Language, "productsearch.php" );

$t->setAllStrings();

$t->set_file(  "product_search_tpl", "productsearch.tpl" );

$t->set_block( "product_search_tpl", "product_tpl", "product" );
$t->set_block( "product_search_tpl", "error_max_search_for_products_tpl", "error_max_search_for_products" );

if ( !isset( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleList ) )
    $ModuleList = "productlist";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";
if ( !isset( $ModulePrint ) )
    $ModulePrint = "productprint";

$t->set_var( "module", $ModuleName );
$t->set_var( "module_list", $ModuleList );
$t->set_var( "module_view", $ModuleView );
$t->set_var( "module_print", $ModulePrint );

$t->set_block( "product_tpl", "image_tpl", "image" );
$t->set_block( "product_tpl", "price_tpl", "price" );

$t->set_var( "next", "" ); 
$t->set_var( "previous", "" );
$t->set_var( "error_max_search_for_products", "" );

// products
$product = new eZProduct();

if ( !isSet( $Limit ) )
    $Limit = 10;
if ( !isSet( $Offset ) )
    $Offset = 0;


if ( isset( $URLQueryString ) )
{
    $Query = $URLQueryString;
}

if ( $Query )
{
    $productList =& $product->activeProductSearch( $Query, $Offset, $Limit );
    $total_count = $product->activeProductSearchCount( $Query );
} 

$t->set_var( "url_text", urlencode( $Query ) );

if ( ( $MaxSearchForProducts != 0 ) && ( $MaxSearchForProducts < $total_count ) )
{
    $t->parse( "error_max_search_for_products", "error_max_search_for_products_tpl" );
    $t->set_var( "product_search_list", "" );
    $productList = array();
    $total_count = 0;
}

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product", "" );
if ( isSet( $Query ) && ( count ( $productList ) > 0 ) )
{
    foreach ( $productList as $product )
    {
        // preview image
        $thumbnailImage = $product->thumbnailImage();
        if ( $thumbnailImage )
        {
            $variation =& $thumbnailImage->requestImageVariation( $SmallImageWidth, $SmallImageHeight );
    
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

        if ( ( !$RequireUserLogin or get_class( $user ) == "ezuser"  ) and
             $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
        {
            $found_price = false;
            if ( $ShowPriceGroups and $PriceGroup > 0 )
            {
                $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup );
                if ( $price )
                {
                    $found_price = true;
                    $price = new eZCurrency( $price );
                }
            }
            if ( !$found_price )
            {
                $price = new eZCurrency( $product->price() );
            }
            $t->set_var( "product_price", $locale->format( $price ) );
            $t->parse( "price", "price_tpl" );
        }
        else
        {
            $t->set_var( "price", "" );
        }
        
        
        $t->set_var( "product_intro_text", $product->brief() );
        $t->set_var( "product_id", $product->id() );

        $defCat = $product->categoryDefinition();
        $t->set_var( "category_id", $defCat->id() );

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

eZList::drawNavigator( $t, $total_count, $Limit, $Offset, "product_search_tpl" );

$t->set_var( "url_query_string", $Query );
$t->set_var( "query_string", $Query );

$t->set_var( "query", $Query );
$t->set_var( "limit", $Limit );
$t->set_var( "product_start", $Offset + 1 );
$t->set_var( "product_end", min( $Offset + $Limit, $total_count ) );
$t->set_var( "product_total", $total_count );

$t->pparse( "output", "product_search_tpl" );
?>

