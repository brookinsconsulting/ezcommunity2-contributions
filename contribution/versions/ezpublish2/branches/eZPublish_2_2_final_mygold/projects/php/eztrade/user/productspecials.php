<?php
// 
// $Id: productspecials.php,v 1.1.2.1 2002/04/16 10:44:10 ce Exp $
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
include_once( "eztrade/classes/ezproductspecials.php" );

include_once( "classes/ezlist.php" );


$user =& eZUser::currentUser();

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) ,
                     "eztrade/user/intl/", $Language, "productspecials.php" );

$t->setAllStrings();

$t->set_file(  "product_specials_tpl", "productspecials.tpl" );

$t->set_block( "product_specials_tpl", "product_tpl", "product" );
$t->set_block( "product_specials_tpl", "error_max_specials_for_products_tpl", "error_max_specials_for_products" );

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

if ( $SpecialID == "" or !is_numeric( $SpecialID ) or !eZProductSpecial::idExists( $SpecialID ) )
{
    eZHTTPTool::header( "Location: /error/404/" );
    exit();
}

$special = new eZProductSpecial( $SpecialID );

$product_numbers = explode( ";", $special->getProductNumbers() );
$productList = array();

$t->set_var( "special_name", $special->getSpecialName() );
$t->set_var( "special_description", $special->getDescription() );

foreach ( $product_numbers as $product_number )
{
    $productList[] = new eZProduct( $product_number ); 
}


$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product", "" );

if ( count ( $productList ) > 0 )
{
    foreach ( $productList as $product )
    {
        $t->set_var( "product_name", $product->name() );

        if ( $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
        {
            $t->set_var( "product_price", $product->localePrice( $PricesIncludeVAT ) );
            $priceRange = $product->correctPriceRange( $PricesIncludeVAT );
            
            if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product->correctPrice( $PricesIncludeVAT ) > 0) )
            {
                $t->set_var( "product_price", "" );
            }
            $t->parse( "price", "price_tpl" );
        }
        else if( $product->showPrice() == false )
        {
            $t->set_var( "product_price", "" );
            $t->parse( "price", "price_tpl" );
        }
        else
        {
            $priceArray = "";
            $options =& $product->options();
            if ( count( $options ) == 1 )
            {
                $option = $options[0];
                if ( get_class( $option ) == "ezoption" )
                {
                    $optionValues =& $option->values();
                    if ( count( $optionValues ) > 1 )
                    {
                        $i=0;
                        foreach ( $optionValues as $optionValue )
                        {
                            $priceArray[$i] = $optionValue->localePrice( $PricesIncludeVAT, $product );
                            $i++;
                        }
                        $high = max( $priceArray );
                        $low = min( $priceArray );
                        
                        $t->set_var( "product_price", $low . " - " . $high );
                        
                        $t->parse( "price", "price_tpl" );
                    }
                }
            }
            else
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

        $t->parse( "product", "product_tpl", true );
        $i++;
    }
}

$t->pparse( "output", "product_specials_tpl" );

?>

