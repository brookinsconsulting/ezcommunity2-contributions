<?php
// 
// $Id: frontpage.php,v 1.28.2.14 2003/06/06 10:33:22 vl Exp $
//
// Created on: <30-May-2001 14:06:59 bf>
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
include_once( "classes/ezlist.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezsitemanager/classes/ezsection.php" );

include_once( "ezad/classes/ezadcategory.php" );
include_once( "ezad/classes/ezad.php" );

//$CategoryID = $url_array[3];

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$ImageDir = $ini->read_var( "eZRfpMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZRfpMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZRfpMain", "DefaultLinkText" );
$GrayScaleImageList = $ini->read_var( "eZRfpMain", "GrayScaleImageList" );


$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "frontpage.php" );

$t->setAllStrings();

$t->set_file( "rfp_list_page_tpl", "frontpage.tpl" );

$t->set_block( "rfp_list_page_tpl", "header_item_tpl", "header_item" );


// one column rfp
$t->set_block( "rfp_list_page_tpl", "element_list_tpl", "element_list" );
$t->set_block( "rfp_list_page_tpl", "one_column_rfp_tpl", "one_column_rfp" );
$t->set_block( "one_column_rfp_tpl", "one_column_rfp_image_tpl", "one_column_rfp_image" );
$t->set_block( "one_column_rfp_tpl", "one_column_read_more_tpl", "one_column_read_more" );

// one column product
$t->set_block( "rfp_list_page_tpl", "one_column_product_tpl", "one_column_product" );
$t->set_block( "one_column_product_tpl", "product_image_tpl", "product_image" );
$t->set_block( "one_column_product_tpl", "price_tpl", "price" );

// two column product
$t->set_block( "rfp_list_page_tpl", "two_column_product_tpl", "two_column_product" );
$t->set_block( "two_column_product_tpl", "left_product_tpl", "left_product" );
$t->set_block( "left_product_tpl", "left_product_image_tpl", "left_product_image" );
$t->set_block( "left_product_tpl", "left_price_tpl", "left_price" );

$t->set_block( "two_column_product_tpl", "right_product_tpl", "right_product" );
$t->set_block( "right_product_tpl", "right_product_image_tpl", "right_product_image" );
$t->set_block( "right_product_tpl", "right_price_tpl", "right_price" );

// two column rfp
$t->set_block( "rfp_list_page_tpl", "two_column_rfp_tpl", "two_column_rfp" );
$t->set_block( "two_column_rfp_tpl", "left_rfp_tpl", "left_rfp" );
$t->set_block( "left_rfp_tpl", "left_rfp_image_tpl", "left_rfp_image" );
$t->set_block( "left_rfp_tpl", "left_read_more_tpl", "left_read_more" );

$t->set_block( "two_column_rfp_tpl", "right_rfp_tpl", "right_rfp" );
$t->set_block( "right_rfp_tpl", "right_rfp_image_tpl", "right_rfp_image" );
$t->set_block( "right_rfp_tpl", "right_read_more_tpl", "right_read_more" );

// short single rfp
$t->set_block( "rfp_list_page_tpl", "one_short_rfp_tpl", "one_short_rfp" );
$t->set_block( "one_short_rfp_tpl", "short_read_more_tpl", "short_read_more" );

// banner ad
$t->set_block( "rfp_list_page_tpl", "ad_column_tpl", "ad_column" );
$t->set_block( "ad_column_tpl", "standard_ad_tpl", "standard_ad" );
$t->set_block( "ad_column_tpl", "html_ad_tpl", "html_ad" );

$t->set_var( "element_list", "" );

$t->set_var( "one_column_rfp", "" );
$t->set_var( "two_column_rfp", "" );
$t->set_var( "two_column_product", "" );
$t->set_var( "one_short_rfp", "" );
$t->set_var( "ad_column", "" );
$t->set_var( "one_column_product", "" );


// image dir
$t->set_var( "image_dir", $ImageDir );

$rfpCount = 0;
$productCount = 0;
$adCount = 0;

// section
$t->set_var( "section_id", $GlobalSectionID );

$rows =& $sectionObject->frontPageRows();

$page_element = array();

$tempRfp = new eZRfp();
unset( $rfpList );

if ( is_array ( $rows ) and count ( $rows ) > 0 )
{
    foreach ( $rows as $row )    
    {
        $value = $row->settingByID( $row->settingID() );
        if ( $value == "2column" )
        {
            $category = new eZRfpCategory( $row->categoryID() );
            if ( $category->id() == "0" )
            {
                $rfpList =& array_merge( $rfpList, $tempRfp->rfps( "time", false, $offsetRfpArray[$row->categoryID()], 2 ) );
            }
            else
            {
                $rfpList =& array_merge( $rfpList, eZRfpCategory::rfps( $category->sortMode(), false, true, $offsetRfpArray[$row->categoryID()], 2, $row->categoryID() ) );
            }
            
            $offsetRfpArray[$row->categoryID()] = $offsetRfpArray[$row->categoryID()] + 2;
        }
        if ( $value == "1column" || $value == "1short" )
        {
            $category = new eZRfpCategory( $row->categoryID() );
            if ( $category->id() == "0" )
            {
                $rfpList =& array_merge( $rfpList, $tempRfp->rfps( "time", false, $offsetRfpArray[$row->categoryID()], 1 ) );
            }
            else
            {
                $rfpList =& array_merge( $rfpList, eZRfpCategory::rfps( $category->sortMode(), false, true, $offsetRfpArray[$row->categoryID()], 1, $row->categoryID() ) );
            }
            $offsetRfpArray[$row->categoryID()] = $offsetRfpArray[$row->categoryID()] + 1;
        }
        if ( $value == "ad"  )
        {
            $category = new eZAdCategory( $row->categoryID() );
            $adList =& array_merge( $adList, $category->ads( "count", false, $offsetAdArray[$row->categoryID()], 1 ) );
            $offsetAdArray[$row->categoryID()] = $offsetAdArray[$row->categoryID()] + 1;
        }
        if ( $value == "1columnProduct" )
        {
            $category = new eZProductCategory( $row->categoryID() );
            $productList =& array_merge( $productList, eZProductCategory::products( $category->sortMode(), false, $offsetProductArray[$row->categoryID()], 1, false, $row->categoryID() ) );
            $offsetProductArray[$row->categoryID()] = $offsetProductArray[$row->categoryID()] + 1;
        }
        if ( $value == "2columnProduct" )
        {
            $category = new eZProductCategory( $row->categoryID() );
            $productList =& array_merge( $productList, eZProductCategory::products( $category->sortMode(), false, $offsetProductArray[$row->categoryID()], 2, false, $row->categoryID() ) );
            $offsetProductArray[$row->categoryID()] = $offsetProductArray[$row->categoryID()] + 2;
        }
        $page_elements[] = $value;
    }
}

$user =& eZUser::currentUser();

//$sectionObject->setOverrideVariables();

if ( $adCount > 0 )
{
    include_once( "ezad/classes/ezadcategory.php" );
    include_once( "ezad/classes/ezad.php" );

    $adCategory = new eZAdCategory( $FrontPageAdCategory );

    $adList =& $adCategory->ads( "count", false, 0, $adCount );
}

$t->set_var( "category_current_id", $CategoryID );

$locale = new eZLocale( $Language );
$i=0;

$rfpOffset = 0;
$productOffset = 0;
$adOffset = 0;
$pageContents = "";
$counter = -1;
// render the page elements
if ( count( $page_elements ) > 0 )
foreach ( $page_elements as $element )    
{
    $counter++;	

    switch ( $element )
    {
        case "1column":
        {            
            $rfp =& $rfpList[$rfpOffset];

            if ( get_class( $rfp ) == "ezrfp" )
                $pageContents .= renderFrontpageRfp( $t, $locale, $rfp );
            
            $rfpOffset++;
        }break;

        case "2column":
        {
            $rfp1 =& $rfpList[$rfpOffset];
            $rfpOffset++;
            $rfp2 =& $rfpList[$rfpOffset];

            if ( get_class( $rfp1 ) == "ezrfp" && get_class( $rfp2 ) == "ezrfp" ) 
                $pageContents .= renderFrontpageRfpDouble( $t, $locale, $rfp1, $rfp2 );
            
            $rfpOffset++;
        }break;

        case "1short":
        {            
            $rfp =& $rfpList[$rfpOffset];

            if ( get_class( $rfp ) == "ezrfp" )
                $pageContents .= renderShortSingleRfp( $t, $locale, $rfp );
            
            $rfpOffset++;
        }break;

        case "ad":
        {
            $ad =& $adList[$adOffset];
            if ( get_class( $ad ) == "ezad" )
                $pageContents .= renderAd( $t, $locale, $ad );
                    
            $adOffset++;
        }break;

        case "1columnProduct":
        {            
            $product =& $productList[$productOffset];
            if ( get_class( $product ) == "ezproduct" )
                $pageContents .= renderFrontpageProduct( $t, $locale, $product );
            
            $productOffset++;
        }break;

        case "2columnProduct":
        {            
            $product1 =& $productList[$productOffset];
            $productOffset++;
            $product2 =& $productList[$productOffset];

            if ( get_class( $product1 ) == "ezproduct" && get_class( $product2 ) == "ezproduct" ) 
                $pageContents .= renderFrontpageProductDouble( $t, $locale, $product1, $product2 );
            
            $productOffset++;
        }break;


    }
}
$t->set_var( "element_list", $pageContents );


function &renderFrontpageRfp( &$t, &$locale, &$rfp )
{
    global $ini, $counter, $rows, $GrayScaleImageList;
	
    $DefaultLinkText =  $ini->read_var( "eZRfpMain", "DefaultLinkText" );
    
    $aid = $rfp->id();

    $CategoryID = $rows[$counter]->CategoryID;
    
    if ( $CategoryID == 0 )
    {
        $category =& $rfp->categoryDefinition();
        $CategoryID = $category->id();
    }
    
    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "rfp_id", $rfp->id() );
    $t->set_var( "rfp_name", $rfp->name() );

    $t->set_var( "author_text", $rfp->authorText() );

    $categoryDef =& $rfp->categoryDefinition();
	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    // preview image
    $thumbnailImage =& $rfp->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZRfpMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZRfpMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "one_column_rfp_image", "one_column_rfp_image_tpl" );
    }
    else
    {
        $t->set_var( "one_column_rfp_image", "" );    
    }
    
    $published = $rfp->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "rfp_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_timevalue", $locale->format( $publishedTimeValue ) );
    

    $t->set_var( "rfp_published", $locale->format( $published ) );
    

    $renderer = new eZRfpRenderer( $rfp );
    $t->set_var( "rfp_intro", $renderer->renderIntro(  ) );

        
    if ( $rfp->linkText() != "" )
    {
        $t->set_var( "rfp_link_text", $rfp->linkText() );
    }
    else
    {
        $t->set_var( "rfp_link_text", $DefaultLinkText );
    }

    // check if the rfp contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "one_column_read_more", "" );
    }
    else
    {
        $t->parse( "one_column_read_more", "one_column_read_more_tpl" );
    }


    return $t->parse( "output", "one_column_rfp_tpl" );
}

function &renderFrontpageRfpDouble( &$t, &$locale, &$rfp1, &$rfp2 )
{
    global $ini, $counter, $rows, $GrayScaleImageList;
    $aid = $rfp1->id();
	
    $DefaultLinkText =  $ini->read_var( "eZRfpMain", "DefaultLinkText" );
	
    $CategoryID = $rows[$counter]->CategoryID;
    
    if ( $CategoryID == 0 )                  
    {                            
	$category =& $rfp1->categoryDefinition();
	$CategoryID = $category->id();
    }

    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "rfp_id", $rfp1->id() );
    $t->set_var( "rfp_name", $rfp1->name() );
    $t->set_var( "author_text", $rfp1->authorText() );

    $categoryDef =& $rfp1->categoryDefinition();	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    // preview image
    $thumbnailImage =& $rfp1->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;        

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZRfpMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZRfpMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "left_rfp_image", "left_rfp_image_tpl" );
    }
    else
    {
        $t->set_var( "left_rfp_image", "" );    
    }
    
    $published = $rfp1->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "rfp_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_timevalue", $locale->format( $publishedTimeValue ) );
    

    $t->set_var( "rfp_published", $locale->format( $published ) );

    
    $renderer = new eZRfpRenderer( $rfp1 );
    $t->set_var( "rfp_intro", $renderer->renderIntro(  ) );

        
    if ( $rfp1->linkText() != "" )
    {
        $t->set_var( "rfp_link_text", $rfp1->linkText() );
    }
    else
    {
        $t->set_var( "rfp_link_text", $DefaultLinkText );
    }

    // check if the rfp contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "left_read_more", "" );
    }
    else
    {
        $t->parse( "left_read_more", "left_read_more_tpl" );
    }

    $t->parse( "left_rfp", "left_rfp_tpl"  );
    $aid = $rfp2->id();
    
//    if ( $CategoryID == 0 )
//    {
//        $category =& $rfp2->categoryDefinition();
//        $CategoryID = $category->id();
//    }
//    $category =& $rfp2->categoryDefinition();
//    $CategoryID = $category->id();
        
    $t->set_var( "category_id", $CategoryID );

    $t->set_var( "rfp_id", $rfp2->id() );
    $t->set_var( "rfp_name", $rfp2->name() );
    $t->set_var( "author_text", $rfp2->authorText() );

    $categoryDef =& $rfp2->categoryDefinition();	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    // preview image
    $thumbnailImage =& $rfp2->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZRfpMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZRfpMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "right_rfp_image", "right_rfp_image_tpl" );
    }
    else
    {
        $t->set_var( "right_rfp_image", "" );    
    }
    
    $published = $rfp2->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "rfp_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_timevalue", $locale->format( $publishedTimeValue ) );
   

    $t->set_var( "rfp_published", $locale->format( $published ) );
    

    $renderer = new eZRfpRenderer( $rfp2 );
    $t->set_var( "rfp_intro", $renderer->renderIntro(  ) );

        
    if ( $rfp2->linkText() != "" )
    {
        $t->set_var( "rfp_link_text", $rfp2->linkText() );
    }
    else
    {
        $t->set_var( "rfp_link_text", $DefaultLinkText );
    }

    // check if the rfp contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "right_read_more", "" );
    }
    else
    {
        $t->parse( "right_read_more", "right_read_more_tpl" );
    }

    $t->parse( "right_rfp", "right_rfp_tpl"  );

    return $t->parse( "output", "two_column_rfp_tpl" );    
}

function &renderShortSingleRfp( &$t, &$locale, &$rfp )
{
    global $ini, $counter, $rows, $GrayScaleImageList;

    $aid = $rfp->id();
	
    $DefaultLinkText =  $ini->read_var( "eZRfpMain", "DefaultLinkText" );

    $CategoryID = $rows[$counter]->CategoryID;
	
    if ( $CategoryID == 0 )
    {
	$category =& $rfp->categoryDefinition();
	$CategoryID = $category->id();
    }
        
    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "rfp_id", $rfp->id() );
    $t->set_var( "rfp_name", $rfp->name() );

    $t->set_var( "author_text", $rfp->authorText() );

    $categoryDef =& $rfp->categoryDefinition();
	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    $published = $rfp->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "rfp_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_timevalue", $locale->format( $publishedTimeValue ) );
   
    $t->set_var( "rfp_published", $locale->format( $published ) );
    

    if ( $rfp->linkText() != "" )
    {
        $t->set_var( "rfp_link_text", $rfp->linkText() );
    }
    else
    {
        $t->set_var( "rfp_link_text", $DefaultLinkText );
    }


    return $t->parse( "output", "one_short_rfp_tpl" );
}

function &renderAd( &$t, &$locale, &$ad )
{
    global $ini;

    if ( $ad->useHTML() )
    {
        $t->set_var( "standard_ad", "" );
                
        $t->set_var( "html_ad_contents", $ad->htmlBanner() );
        $t->parse( "html_ad", "html_ad_tpl" );
    }
    else
    {
        $t->set_var( "html_ad", "" );
        $image =& $ad->image();

        if ( $image )
        {
            $imgSRC =& $image->filePath();
            $imgWidth =& $image->width();
            $imgHeight =& $image->height();
        }

        $t->set_var( "ad_id", $ad->id() );
        $t->set_var( "image_src", $imgSRC );
        $t->set_var( "image_width", $imgWidth );
        $t->set_var( "image_height", $imgHeight );
        $t->parse( "standard_ad", "standard_ad_tpl" );
    }

    $ad->addPageView();

    return $t->parse( "output", "ad_column_tpl" );
}


function &renderFrontpageProduct( &$t, &$locale, &$product )
{
    global $ini;
    $pid = $product->id();

    $ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
    $ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );
	
    // preview image
    $thumbnailImage = $product->thumbnailImage();
    $t->set_var( "product_id", $product->id() );

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

    $categoryDefinition = $product->categoryDefinition();
    $t->set_var( "category_id", $categoryDefinition->id() );

    if ( $product->showPrice() == true and $product->hasPrice() )
    {
        $t->set_var( "product_price", $product->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product->correctPriceRange( $PricesIncludeVAT );

        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }
        $t->parse( "price", "price_tpl" );
    }
    elseif( $product->showPrice() == false )
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
    

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    return $t->parse( "output", "one_column_product_tpl" );    
}


function &renderFrontpageProductDouble( &$t, &$locale, &$product1, &$product2 )
{
    global $ini;
    $pid = $product1->id();

    $PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" );

    $ThumbnailImageWidth = $ini->read_var( "eZTradeMain", "ThumbnailImageWidth" );
    $ThumbnailImageHeight = $ini->read_var( "eZTradeMain", "ThumbnailImageHeight" );
	
    // preview image
    $thumbnailImage = $product1->thumbnailImage();

    if ( $thumbnailImage )
    {
        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "left_product_image", "left_product_image_tpl" );
    }
    else
    {
        $t->set_var( "left_product_image", "" );    
    }

    $t->set_var( "product_name", $product1->name() );
    $t->set_var( "product_id", $product1->id() );

    $t->set_var( "product_intro_text", eZTextTool::nl2br( $product1->brief() ) );

    $categoryDefinition = $product1->categoryDefinition();
    $t->set_var( "category_id", $categoryDefinition->id() );

    if ( $product1->showPrice() == true and $product1->hasPrice() )
    {
        $t->set_var( "product_price", $product1->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product1->correctPriceRange( $PricesIncludeVAT );

        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product1->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }
        $t->parse( "left_price", "left_price_tpl" );
    }
    elseif( $product1->showPrice() == false )
    {
        $t->set_var( "product_price", "" );
        $t->parse( "left_price", "left_price_tpl" );
    }
    else
    {
        $priceArray = "";
        $options =& $product1->options();
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
                        $priceArray[$i] = $optionValue->localePrice( $PricesIncludeVAT, $product1 );
                        $i++;
                    }
                    $high = max( $priceArray );
                    $low = min( $priceArray );
                    
                    $t->set_var( "product_price", $low . " - " . $high );
                    
                    $t->parse( "left_price", "left_price_tpl" );
                }
            }
        }
        else
            $t->set_var( "left_price", "" );
    }
    

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "left_product", "left_product_tpl"  );

    $pid = $product2->id();

    // preview image
    $thumbnailImage = $product2->thumbnailImage();
    
    $t->set_var( "product_id", $product2->id() );
    
    if ( $thumbnailImage )
    {
        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight );

        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "right_product_image", "right_product_image_tpl" );
    }
    else
    {
        $t->set_var( "right_product_image", "" );    
    }

    $t->set_var( "product_name", $product2->name() );


    $categoryDefinition = $product2->categoryDefinition();
    $t->set_var( "category_id", $categoryDefinition->id() );

    $t->set_var( "product_intro_text", eZTextTool::nl2br( $product2->brief() ) );

    if ( $product2->showPrice() == true and $product2->hasPrice() )
    {
        $t->set_var( "product_price", $product2->localePrice( $PricesIncludeVAT ) );
        $priceRange = $product2->correctPriceRange( $PricesIncludeVAT );

        if ( ( empty( $priceRange["min"] ) and empty( $priceRange["max"] ) ) and !($product2->correctPrice( $PricesIncludeVAT ) > 0) )
        {
            $t->set_var( "product_price", "" );
        }
        $t->parse( "right_price", "right_price_tpl" );
    }
    elseif( $product2->showPrice() == false )
    {
        $t->set_var( "product_price", "" );
        $t->parse( "right_price", "right_price_tpl" );
    }
    else
    {
        $priceArray = "";
        $options =& $product2->options();
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
                        $priceArray[$i] = $optionValue->localePrice( $PricesIncludeVAT, $product2 );
                        $i++;
                    }
                    $high = max( $priceArray );
                    $low = min( $priceArray );
                    
                    $t->set_var( "product_price", $low . " - " . $high );
                    
                    $t->parse( "right_price", "right_price_tpl" );
                }
            }
        }
        else
            $t->set_var( "right_price", "" );
    }
    

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "right_product", "right_product_tpl"  );
    
    return $t->parse( "output", "two_column_product_tpl" );    
}



$t->pparse( "output", "rfp_list_page_tpl" );

// set variables for meta information
// $SiteTitleAppend = $category->name();

if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");
    
    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";    
    $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $output .= ob_get_contents();

    // print the output the first time while printing the cache file.
    fwrite ( $fp, $output );
    fclose( $fp );
}

?>

