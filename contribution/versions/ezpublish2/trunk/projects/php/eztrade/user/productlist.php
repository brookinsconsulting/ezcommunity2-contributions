<?
// 
// $Id: productlist.php,v 1.20 2001/03/26 14:23:20 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Sep-2000 14:46:20 bf>
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
include_once( "classes/eztexttool.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$Limit = $ini->read_var( "eZTradeMain", "ProductLimit" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "true";

$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );

$ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "productlist.php" );

$t->set_file( "product_list_page_tpl", "productlist.tpl" );

$t->set_block( "product_list_page_tpl", "price_tpl", "price" );

$t->set_block( "product_list_page_tpl", "path_tpl", "path" );
$t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );
$t->set_block( "product_list_tpl", "product_tpl", "product" );
$t->set_block( "product_tpl", "product_image_tpl", "product_image" );

$t->set_block( "product_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

if ( !isset( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleList ) )
    $ModuleList = "productlist";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";

$t->set_var( "module", $ModuleName );
$t->set_var( "module_list", $ModuleList );
$t->set_var( "module_view", $ModuleView );

$t->setAllStrings();

$category = new eZProductCategory(  );
$category->get( $CategoryID );


// path
$pathArray = $category->path();

$t->set_var( "path", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path", "path_tpl", true );
}

$categoryList =& $category->getByParent( $category );

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
    
    $t->parse( "category", "category_tpl", true );
    $i++;
}
if ( count( $categoryList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $t->parse( "category_list", "category_list_tpl" );
}

if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

// products
$TotalTypes =& $category->productCount( $category->sortMode(), false );
$productList =& $category->activeProducts( $category->sortMode(), $Offset, $Limit );

$locale = new eZLocale( $Language );
$i=0;
foreach ( $productList as $product )
{
    $t->set_var( "product_id", $product->id() );

    // preview image
    $thumbnailImage = $product->thumbnailImage();
    
    if ( $thumbnailImage )
    {
        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "product_image", "product_image_tpl" );
    }
    else
    {
        $t->set_var( "product_image", "" );    
    }

    $t->set_var( "product_name", $product->name() );

    $t->set_var( "product_intro_text", eZTextTool::nl2br( $product->brief() ) );

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
        $options =& $product->options();
        if ( count ( $options ) == 1 )
        {
            $option = $options[0];
            if ( get_class ( $option ) == "ezoption" )
            {
                $optionValues =& $option->values();
                if ( count ( $optionValues ) > 1 )
                {
                    $i=0;
                    foreach ( $optionValues as $optionValue )
                    {
                        $found_price = false;
                        if ( $ShowPriceGroups and $PriceGroup > 0 )
                        {
                            $priceArray[$i] = eZPriceGroup::correctPrice( $product->id(), $PriceGroup, $option->id(), $optionValue->id() );
                            if( $priceArray[$i] )
                            {
                                $found_price = true;
                                $priceArray[$i] = $priceArray[$i];
                            }
                        }
                        if ( !$found_price )
                        {
                            $priceArray[$i] = $optionValue->price();
                        }
                        $i++;
                    }
                    $high = new eZCurrency( max( $priceArray ) );
                    $low = new eZCurrency( min( $priceArray ) );

                    $t->set_var( "price", $locale->format( $low ) . " - " . $locale->format( $high ) );
                }
            }
        }
        else
            $t->set_var( "price", "" );
    }
    
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

if ( count( $productList ) > 0 )
{
    $t->parse( "product_list", "product_list_tpl" );
}
else
{
    $t->set_var( "product_list", "" );
}

eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "product_list_page_tpl" );

if ( $GenerateStaticPage == "true" )
{
//      $cache = new eZCacheFile( "eztrade/cache/", array( "productlist", $CategoryID, $Offset, $PriceGroup ),
//                                "cache", "," );
    $output = $t->parse( $target, "product_list_page_tpl" );
    print( $output );
    $CacheFile->store( $output );
}
else
{
    $t->pparse( "output", "product_list_page_tpl" );
}


?>
