<?php
// 
// $Id: extendedsearch.php,v 1.7 2001/03/21 15:10:49 bf Exp $
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
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "true";

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

if ( isSet ( $SearchButton ) )
{
    $Action = "SearchButton";
}

$user = eZUser::currentUser();

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) ,
                     "eztrade/user/intl/", $Language, "extendedsearch.php" );

$t->setAllStrings();

$t->set_file(  "extended_search_tpl", "extendedsearch.tpl" );


$t->set_block( "extended_search_tpl", "extended_tpl", "product" );
$t->set_block( "extended_search_tpl", "product_search_list_tpl", "product_search_list" );
$t->set_block( "extended_search_tpl", "category_item_tpl", "category_item" );

$t->set_block( "extended_search_tpl", "empty_search_tpl", "empty_search" );

$t->set_block( "product_search_list_tpl", "image_tpl", "image" );
$t->set_block( "product_search_list_tpl", "price_tpl", "price" );


$t->set_var( "price_lower", "" );
$t->set_var( "price_higher", "" );
$t->set_var( "text", "" );

$priceRange = explode( "-", $PriceRange );
$PriceLower = $priceRange[0];
$PriceHigher = $priceRange[1];

// products
$product = new eZProduct();

if ( $Action == "SearchButton" )
{
    if ( $Limit == "" )
        $Limit = 10;
    if ( $Offset == "" )
        $Offset = 0;

    if ( $Next || $Prev )
    {
        if ( $CategoryArray != "" )
            $CategoryArrayID = explode( "-", $CategoryArray );
        
        $productList =& $product->extendedSearch( $PriceLower, $PriceHigher, $Text, $Offset, $Limit, $CategoryArrayID );
        $totalCount = $product->extendedSearchCount( $PriceLower, $PriceHigher, $Text, $CategoryArrayID );
    }
    else
    {
        if ( $Limit == "" )
            $Limit = 10;
        if ( $Offset == "" )
            $Offset = 0;
        
        $productList =& $product->extendedSearch( $PriceLower, $PriceHigher, $Text, $Offset, $Limit, $CategoryArrayID );
        $totalCount = $product->extendedSearchCount( $PriceLower, $PriceHigher, $Text, $CategoryArrayID );
    }

    $t->set_var( "price_lower", $PriceLower );
    $t->set_var( "price_higher", $PriceHigher );
    $t->set_var( "text", $Text );

    $t->set_var( "url_text", urlencode( $Text ) );
    $t->set_var( "url_range", urlencode( $PriceRange ) );

    if ( is_array ( $CategoryArrayID ) )
    {
        $i=0;
        foreach ( $CategoryArrayID as $categoryID )
        {
            if ( $i == 1 )
                $urlCategory = $categoryID;
            else
                $urlCategory .= "-" . $categoryID;
            $i++;
        }
    }

    $t->set_var( "url_category", urlencode( $urlCategory ) );
}

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product", "" );
if ( count ( $productList ) > 0 )
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

       
        $t->parse( "product_search_list", "product_search_list_tpl", true );
        $i++;
    }

    $t->set_var( "empty_search", "" );    
}
else
{
    $t->set_var( "product_search_list", "" );
    $t->parse( "empty_search", "empty_search_tpl" );
     
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "extended_search_tpl" );

$category = new eZProductCategory();

$categoryList =& $category->getTree();

foreach( $categoryList as $categoryItem )
{
    $t->set_var( "category_name", $categoryItem[0]->name() );
    $t->set_var( "category_id", $categoryItem[0]->id() );

    if ( $categoryItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $t->set_var( "is_selected", "" );
    
    if ( is_array ( $CategoryArrayID ) )
    {
        foreach ( $CategoryArrayID as $categoryID )
        {
            if ( $categoryID == $categoryItem[0]->id() )
                $t->set_var( "is_selected", "selected" );
        }
    }

    $t->parse( "category_item", "category_item_tpl", true );
}

$t->set_var( "url_query_string", $Query );
$t->set_var( "query_string", $Query );

$t->set_var( "query", $Query );
$t->set_var( "limit", $Limit );
$prevOffs = $Offset - $Limit;
$nextOffs = $Offset + $Limit;

$t->set_var( "prev_offset", $prevOffs );
$t->set_var( "next_offset", $nextOffs );

$t->pparse( "output", "extended_search_tpl" );
?>

