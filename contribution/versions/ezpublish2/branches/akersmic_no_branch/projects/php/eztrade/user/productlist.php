<?php
//
// $Id: productlist.php,v 1.41.8.14 2002/01/31 10:05:33 bf Exp $
//
// Created on: <23-Sep-2000 14:46:20 bf>
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
include_once( "classes/eztexttool.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

// sections
include_once( "ezsitemanager/classes/ezsection.php" );


if ( $CategoryID != 0 )
{
    $GlobalSectionID = eZProductCategory::sectionIDStatic( $CategoryID );
}

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$Limit = $ini->read_var( "eZTradeMain", "ProductLimit" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireUserLogin = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );

$ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );


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

$t->set_block( "category_tpl", "sub_category_list_tpl", "sub_category_list" );
$t->set_block( "sub_category_list_tpl", "sub_category_tpl", "sub_category" );

if ( !isSet( $ModuleName ) )
    $ModuleName = "trade";
if ( !isSet( $ModuleList ) )
    $ModuleList = "productlist";
if ( !isSet( $ModuleView ) )
    $ModuleView = "productview";

// makes the section ID available in articleview template
$t->set_var( "section_id", $GlobalSectionID );

$t->set_var( "module", $ModuleName );
$t->set_var( "module_list", $ModuleList );
$t->set_var( "module_view", $ModuleView );

$t->setAllStrings();

$category = new eZProductCategory();
$category->get( $CategoryID );
$t->set_var( "main_category_id", $CategoryID );
$t->set_var( "main_category_name", $category->name() );

// path
$pathArray = $category->path();

$t->set_var( "path", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );
    $t->set_var( "category_name", $path[1] );
    $t->parse( "path", "path_tpl", true );

    $SiteTitleAppend .= $path[1] . " - ";
}

$categoryList =& $category->getByParentAsID( $category, "name", $Limit*4, $Offset );
$TotalTypes =& $category->countByParent( $category );

// categories
$i = 0;
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem["ID"] );
    $t->set_var( "category_name", $categoryItem["Name"] );
    $t->set_var( "category_description", "" );


    // get subcategories if only one letter, akersmic hack
    $db =& eZDB::globalDatabase();

    $sub_category_array = array();
    $db->array_query( $sub_category_array,
    "SELECT ID, Name FROM eZTrade_Category WHERE LENGTH(Name )=1 AND Parent='" . $categoryItem["ID"] . "' ORDER BY Name"  );

    $t->set_var( "sub_category_list",  "" );
    $t->set_var( "sub_category",  "" );
    foreach ( $sub_category_array as $subCategory )
    {
        $t->set_var( "sub_category_id", $subCategory["ID"] );
        $t->set_var( "sub_category_name", $subCategory["Name"] );
        $t->parse( "sub_category",  "sub_category_tpl", true );
    }

    if ( count( $sub_category_array ) > 0 )
        $t->parse( "sub_category_list",  "sub_category_list_tpl");
    else
        $t->set_var( "sub_category_list",  "");


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
    eZList::drawNavigator( $t, $TotalTypes, $Limit*4, $Offset, "product_list_page_tpl" );
}

if ( !isSet( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isSet( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

// products
$TotalTypes =& $category->productCount( $category->sortMode(), false );
$productList =& $category->productsAsArray( $category->sortMode(), false, $Offset, $Limit );

$locale = new eZLocale( $Language );
$i = 0;
$db =& eZDB::globalDatabase();

foreach ( $productList as $product )
{
    $t->set_var( "product_id", $product["ID"] );

    $thumbnailImage = false;
    $db->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition
                                     WHERE
                                     ProductID='" . $product["ID"] . "'
                                   " );
    
    if ( count( $res_array ) == 1 )
    {
        if ( is_numeric( $res_array[0][$db->fieldName( "ThumbnailImageID" )] ) )
        {
            $thumbnailImage = new eZImage( $res_array[0][$db->fieldName( "ThumbnailImageID" )], false );
        }
    }
    
    // preview image

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
        $t->set_var( "thumbnail_image_uri", "/sitedesign/am/img/a_100x100.gif" );
        $t->set_var( "thumbnail_image_width", "50" );
        $t->set_var( "thumbnail_image_height", "50" );
        $t->set_var( "thumbnail_image_caption", "" );

        $t->parse( "product_image", "product_image_tpl" );
    }

    $SiteDescriptionOverride = $SiteDescriptionOverride . $product["Name"] . " ";

    $t->set_var( "product_name", $product["Name"] );

    $t->set_var( "product_intro_text", "" );
    $t->set_var( "price", "" );

    $t->set_var( "category_id", $product["CatID"] );

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
    eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "product_list_page_tpl" );
}
else
{
    $t->set_var( "product_list", "" );
}

$SimilarCategoryID = 28;
include_once( "eztrade/user/similarproducts.php" );
$similarCode = similarProducts( $SimilarCategoryID );
$t->set_var( "similar_products", $similarCode );

if ( $GenerateStaticPage == "true" )
{
    if ( $user )
        $CategoryArray =& $user->groups( false );
    $cache = new eZCacheFile( "eztrade/cache/", array( "productlist", $CategoryArray, $Offset, $PriceGroup ),
                              "cache", "," );

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";
    $output .= "?>\n";

    $output = $t->parse( $target, "product_list_page_tpl" );
    print( $output );
    $CacheFile->store( $output );
}
else
{
    $t->pparse( "output", "product_list_page_tpl" );
}

?>
