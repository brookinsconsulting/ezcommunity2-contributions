<?php
//
// $Id: productlist.php,v 1.41.8.21 2002/04/10 11:57:20 ce Exp $
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

$eZDebug = false;
if ( $GLOBALS["REMOTE_ADDR"] == "217.65.231.18" )
{
$eZDebug = true;
}

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

$sectionOverride = "_sectionoverride_$GlobalSectionID";
$TemplateDir =  $ini->read_var( "eZTradeMain", "TemplateDir" );

if ( eZFile::file_exists( "eztrade/user/$TemplateDir/productlist" . $sectionOverride  . ".tpl" ) )
{
    $t->set_file( "product_list_page_tpl", "productlist" . $sectionOverride  . ".tpl"  );
}
else
{
    $t->set_file( "product_list_page_tpl", "productlist.tpl" );
}

$t->set_block( "product_list_page_tpl", "price_tpl", "price" );
$t->set_block( "product_list_page_tpl", "path_tpl", "path" );
$t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );

$t->set_block( "product_list_tpl", "product_tpl", "product" );
$t->set_block( "product_tpl", "product_image_tpl", "product_image" );

$t->set_block( "product_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$t->set_block( "category_tpl", "sub_category_list_tpl", "sub_category_list" );
$t->set_block( "sub_category_list_tpl", "sub_category_tpl", "sub_category" );

$t->set_block( "product_tpl", "kjop_item_tpl", "kjop_item" );
$t->set_block( "product_tpl", "bestill_item_tpl", "bestill_item" );

$t->set_block( "product_tpl", "attribute_item_tpl", "attribute_item" );


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
	$t->set_var( "td_class", "categorylist1" );
    }
    else
    {
	$t->set_var( "td_class", "categorylist2" );
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
$productList =& $category->productsAsArray( $category->sortMode(), false, $Offset, $Limit, false, 0, $attributeID );
$db =& eZDB::globalDatabase();
$attributeArrayID = false;
// Special cases for diffrent sections.
switch( $GlobalSectionID )
{
    case 1:
    {
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
	$filename = "sitedesign/am/staticpages/";
    }
    break;
    case 2:
    {
        $db->query_single( $categoryDefArray, "SELECT Category.ID FROM eZTrade_ProductCategoryDefinition AS Def, eZTrade_Category AS Category WHERE Def.ProductID='" . $productList[0]["ID"] . "' AND Def.CategoryID = Category.ID" );
        $categoryDef = new eZProductCategory( $categoryDefArray["ID"] );
	// path
	$pathArray = $categoryDef->path();
	$t->set_var( "path", "" );
	for ( $i=0; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 0 )
	    {
            $t->set_var( "path_url", "/musikk/" );
            $t->set_var( "category_name", $pathArray[$i][1] );
            $t->parse( "path", "path_tpl", true );
	    }
        elseif ( $i == 1 )
        {
            $t->set_var( "path_url", "/musikk/productlist/" . $pathArray[$i][0] . "/" );
            $t->set_var( "category_name", $pathArray[$i][1] );
            $t->parse( "path", "path_tpl", true );
        }
        elseif ( $i == 2 )
        {
            $t->set_var( "path_url", "/musikk/productlist/" . $pathArray[$i-1][0] . "/" . $pathArray[$i][0] . "/" );
            $t->set_var( "category_name", $pathArray[$i][1] );
            $t->parse( "path", "path_tpl", true );
        }
	    else
	    {
            $t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
            $t->set_var( "category_name", $pathArray[$i][1] );
            $t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/musikk_content.html";
    }
    break;
    case 3:
    {
	// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );
    $attributeArrayID = array ( "Forhond" => 9 );

	for ( $i=0; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 0 )
	    {
		$t->set_var( "path_url", "/dvd/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/dvd_content.html";
    }
    break;
    case 4:
    {
			// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );
	for ( $i=0; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 0 )
	    {
		$t->set_var( "path_url", "/hi-fi/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/hifi_content.html";
    }
    break;
    case 5:
    {
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

	$filename = "sitedesign/am/staticpages/multimedia_content.html";
    }
    break;
    case 6:
    {
	// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );
    $attributeArrayID = array (
                                "Forhond" => 31 );
	for ( $i=1; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 1 )
	    {
		$t->set_var( "path_url", "/multimedia/playstation/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    elseif ( $i == 2 )
	    {
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/playstation_content.html";
    }
    break;

    case 7:
    {
	// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );
    $attributeArrayID = array ( "Forhond" => 31 );
    for ( $i=1; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 1 )
	    {
		$t->set_var( "path_url", "/multimedia/pc/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    elseif ( $i == 2 )
	    {
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/pc_content.html";
    }
    break;


    case 8:
    {
	// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );
	for ( $i=1; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 1 )
	    {
		$t->set_var( "path_url", "/multimedia/nintendo/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    elseif ( $i == 2 )
	    {
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/nintendo_content.html";
    }
    break;


    case 9:
    {
		// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );
        $attributeArrayID = array ( "Forhond" => 31 );

	for ( $i=1; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 1 )
	    {
		$t->set_var( "path_url", "/multimedia/xbox/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    elseif ( $i == 2 )
	    {
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/xbox_content.html";
    }
    break;

    case 11:
    {
		// path
	$pathArray = $category->path();
	$t->set_var( "path", "" );

	for ( $i=1; $i < count ( $pathArray ); $i++ )
	{
	    if ( $i == 1 )
	    {
		$t->set_var( "path_url", "/multimedia/sega/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    elseif ( $i == 2 )
	    {
	    }
	    else
	    {
		$t->set_var( "path_url", "/trade/productlist/" . $pathArray[$i][0] . "/" );
		$t->set_var( "category_name", $pathArray[$i][1] );
		$t->parse( "path", "path_tpl", true );
	    }
	    $SiteTitleAppend .= $path[1] . " - ";
	}

	$filename = "sitedesign/am/staticpages/sega_content.html";
    }
    break;

    default:
    {
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
    }
}

$locale = new eZLocale( $Language );
$i = 0;
$currency = new eZCurrency();

foreach ( $productList as $product )
{
    $t->set_var( "product_id", $product["ID"] );

    $thumbnailImage = false;
    $db->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition
				     WHERE
				     ProductID='" . $product["ID"] . "'
				   " );

    $db->query_single( $categoryDefArray, "SELECT Category.ID, Category.Name FROM eZTrade_ProductCategoryDefinition AS Def, eZTrade_Category AS Category WHERE Def.ProductID='" . $product["ID"] . "' AND Def.CategoryID = Category.ID" );

    if ( count( $res_array ) == 1 )
    {
        if ( is_numeric( $res_array[0][$db->fieldName( "ThumbnailImageID" )] ) )
        {
            $thumbnailImage = new eZImage( $res_array[0][$db->fieldName( "ThumbnailImageID" )], false );
        }
    }

    $t->set_var( "current_category_id", $categoryDefArray["ID"] );
    $t->set_var( "current_category_name", $categoryDefArray["Name"] );

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

    if ( $product["ProductType"] == 3 )
    {
        $t->set_var( "kjop_item", "" );
        $t->parse( "bestill_item", "bestill_item_tpl" );
    }
    else
    {
        $t->set_var( "bestill_item", "" );
        $t->parse( "kjop_item", "kjop_item_tpl" );
    }

    $t->set_var( "attribute_item", "" );
    if ( ( is_array ( $attributeArrayID ) ) and ( count ( $attributeArrayID ) > 0 ) )
    {
        $first = true;
        foreach( $attributeArrayID as $attriubte )
        {
            if ( $first )
                $ValueSQL = "A.ID=$attriubte";
            else
                $ValueSQL .= " OR A.ID=$attriubte";
            $first = false;
        }
        $db->array_query( $attribute_value_array, "SELECT AV.Value,
                                                          A.Name
                                                   FROM eZTrade_Product as P,
                                                        eZTrade_Attribute AS A,
                                                        eZTrade_AttributeValue AS AV
                                                   WHERE A.TypeID=P.TypeID
                                                         AND AV.AttributeID=A.ID
                                                         AND AV.ProductID=P.ID
                                                         AND P.ID='" . $product["ID"].  "'
                                                         AND ( $ValueSQL )
                                                   ORDER BY A.Placement" );

        $i = 0;
        if ( count ( $attribute_value_array ) > 0 )
        {
            foreach ( $attributeArrayID as $key => $item )
            {
                $t->set_var( "attribute_value", $attribute_value_array[$i]["Value"] );
                $t->set_var( "attribute_name", $attribute_value_array[$i]["Name"] );

                if ( $key == "Forhond" )
                {
                    if ( $product["ProductType"] == 3 )
                    {
                        if ( $key == "Forhond" )
                        {
                            $test = $t->parse( "dummy", "attribute_item_tpl", true );
                            $t->set_var( "attribute_value", $attribute_value_array[$i]["Value"] );
                            $t->set_var( "attribute_name", $attribute_value_array[$i]["Name"] );
                            if ( ( is_numeric( $attribute_value_array[$i]["Value"] ) and ( $attribute_value_array[$i]["Value"] > 0 ) ) || ( !is_numeric( $attribute_value_array[$i]["Value"] ) and $attribute_value_array[$i]["Value"] != "" ) )
                            {
                                $t->parse( "attribute_item", "attribute_item_tpl", true );
                            }
                        }
                    }
                }
                elseif ( $key == "Normal" )
                {
                    if ( ( is_numeric( $attribute_value_array[$i]["Value"] ) and ( $attribute_value_array[$i]["Value"] > 0 ) ) || ( !is_numeric( $attribute_value_array[$i]["Value"] ) and $attribute_value_array[$i]["Value"] != "" ) )
                    {
                        $t->parse( "attribute_item", "attribute_item_tpl", true );
                    }
                }
                $i++;
            }
        }
    }
    $t->set_var( "product_name", $product["Name"] );

    $t->set_var( "product_intro_text", "" );
    $t->set_var( "price", "" );

    $currency->setValue( $product["Price"] );
    $t->set_var( "product_price", $locale->format( $currency ) );

    $t->set_var( "category_id", $product["CatID"] );

    if ( ( $i % 2 ) == 0 )
    {
	$t->set_var( "td_class", "categorylist1" );
    }
    else
    {
	$t->set_var( "td_class", "categorylist2" );
    }
    $t->parse( "price", "price_tpl" );
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

if ( ( count ( $productList ) == 0 ) and count ( $categoryList ) == 0 )
{
    eZList::drawNavigator( $t, 0, 0, 0, "product_list_page_tpl" );
}


if ( file_exists ( $filename ) )
{
    $file = eZFile::fopen( $filename, "r" );
    if ( $file )
    {
	$content =& fread( $file, eZFile::filesize( $filename ) );
	fclose( $file );
    }
}

$t->set_var( "static_page", $content );

/*
$SimilarCategoryID = 28;
include_once( "eztrade/user/similarproducts.php" );
$similarCode = similarProducts( $SimilarCategoryID );
$t->set_var( "similar_products", $similarCode );
*/

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
