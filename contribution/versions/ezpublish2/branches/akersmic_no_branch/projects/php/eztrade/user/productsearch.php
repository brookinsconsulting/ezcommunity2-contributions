<?php
// 
// $Id: productsearch.php,v 1.20.8.13 2002/01/30 12:12:09 bf Exp $
//
// Created on: <10-Oct-2000 17:49:05 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "classes/ezlist.php" );

$user =& eZUser::currentUser();

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

if ( $Query  || ( $SearchType == "AdvancedMusic" ) || ( $SearchType == "AdvancedDVD" ) ||
( $SearchType == "AdvancedMultimedia" ) || ( $SearchType == "AdvancedHIFI" )
     )
{
    $productList =& $product->search( $Query, $Offset, $Limit, array( "ProductType" => $Type,
                                                                      "SearchType" => $SearchType,
                                                                      "MusicType" => $MusicType,
                                                                      "AlbumTitle" => $AlbumTitle,
                                                                      "Artist" => $Artist,
                                                                      "Recording" => $Recording,
                                                                      "DVDTitle" => $DVDTitle,
                                                                      "DVDActor" => $DVDActor,
                                                                      "MultimediaType" => $MultimediaType,
                                                                      "GameTitle" => $GameTitle                                                                      
                                                                      ), $total_count );
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
$db =& eZDB::globalDatabase();
if ( isSet( $Query ) && ( count ( $productList ) > 0 ) )
{
    foreach ( $productList as $product )
    {
        // get thumbnail image, if exists
        $thumbnailImage = false;
        $db->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition
                                     WHERE
                                     ProductID='" . $product["ProductID"] . "'
                                   " );

        if ( count( $res_array ) == 1 )
        {
           if ( is_numeric( $res_array[0][$db->fieldName( "ThumbnailImageID" )] ) )
           {
               $thumbnailImage = new eZImage( $res_array[0][$db->fieldName( "ThumbnailImageID" )], false );
           }
        }

        $t->set_var( "type_name", $product["TypeName"] );
        $t->set_var( "product_name", $product["Name"] );
        $t->set_var( "product_price", number_format( $product["Price"], 2, ",", " " ) );
        
//        $t->set_var( "product_intro_text", $product->brief() );
        $t->set_var( "product_intro_text", "" );
        $t->set_var( "product_id", $product["ProductID"] );
        
        
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
            $t->set_var( "thumbnail_image_uri", "/sitedesign/am/img/a_50x50.gif" );
            $t->set_var( "thumbnail_image_width", "50" );
            $t->set_var( "thumbnail_image_height", "50" );
            $t->set_var( "thumbnail_image_caption", "" );
                
            $t->parse( "image", "image_tpl" );
        }


        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_var", "1" );
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_var", "2" );
            $t->set_var( "td_class", "bgdark" );
        }

        $t->parse( "product", "product_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $total_count, $Limit, $Offset, "product_search_tpl" );

$t->set_var( "url_query_string", $Query );
$t->set_var( "query_string", htmlspecialchars( $Query ) );


switch ( $SearchType )
{
    case "AdvancedMusic" :
    {
        $advQuery = "?MusicType=$MusicType&SearchType=$SearchType&AlbumTitle=$AlbumTitle&Artist=$Artist&Recording=$Recording";        
    }
    break;
        
    case "AdvancedDVD" :
    {
        $advQuery ="?SearchType=$SearchType&DVDTitle=$DVDTitle&DVDActor=$DVDActor";
    }
    break;

    case "AdvancedMultimedia" :
    {
        $advQuery = "?MultimediaType=$MultimediaType&SearchType=$SearchType&GameTitle=$GameTitle";
    }
    break;

    default:
    {
        $advQuery = "";
    }
    break;
}


$t->set_var( "adv_query", $advQuery );

$t->set_var( "query", $Query );
$t->set_var( "limit", $Limit );
if ( count( $productList ) == 0 )
    $t->set_var( "product_start", 0 );
else
    $t->set_var( "product_start", $Offset + 1 );
$t->set_var( "product_end", min( $Offset + $Limit, $total_count ) );
$t->set_var( "product_total", $total_count );

$t->pparse( "output", "product_search_tpl" );
?>

