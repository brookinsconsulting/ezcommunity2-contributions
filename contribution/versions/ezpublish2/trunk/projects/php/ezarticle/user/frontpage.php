<?php
// 
// $Id: frontpage.php,v 1.13 2001/10/01 11:55:42 ce Exp $
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

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezsitemanager/classes/ezsection.php" );


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
$GrayScaleImageList = $ini->read_var( "eZArticleMain", "GrayScaleImageList" );

if ( !isset( $FrontPageCategory )  )
    $FrontPageCategory = $ini->read_var( "eZArticleMain", "FrontPageCategory" );

$FrontPageAdCategory = $ini->read_var( "eZArticleMain", "FrontPageAdCategory" );
$FrontPageSettings = $ini->read_var( "eZArticleMain", "FrontPageSettings" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "frontpage.php" );

$t->setAllStrings();

$t->set_file( "article_list_page_tpl", "frontpage.tpl" );

$t->set_block( "article_list_page_tpl", "header_item_tpl", "header_item" );


// one column article
$t->set_block( "article_list_page_tpl", "element_list_tpl", "element_list" );
$t->set_block( "article_list_page_tpl", "one_column_article_tpl", "one_column_article" );
$t->set_block( "one_column_article_tpl", "one_column_article_image_tpl", "one_column_article_image" );
$t->set_block( "one_column_article_tpl", "one_column_read_more_tpl", "one_column_read_more" );

// two column article
$t->set_block( "article_list_page_tpl", "two_column_article_tpl", "two_column_article" );
$t->set_block( "two_column_article_tpl", "left_article_tpl", "left_article" );
$t->set_block( "left_article_tpl", "left_article_image_tpl", "left_article_image" );
$t->set_block( "left_article_tpl", "left_read_more_tpl", "left_read_more" );

$t->set_block( "two_column_article_tpl", "right_article_tpl", "right_article" );
$t->set_block( "right_article_tpl", "right_article_image_tpl", "right_article_image" );
$t->set_block( "right_article_tpl", "right_read_more_tpl", "right_read_more" );

// short single article
$t->set_block( "article_list_page_tpl", "one_short_article_tpl", "one_short_article" );
$t->set_block( "one_short_article_tpl", "short_read_more_tpl", "short_read_more" );

// banner ad
$t->set_block( "article_list_page_tpl", "ad_column_tpl", "ad_column" );
$t->set_block( "ad_column_tpl", "standard_ad_tpl", "standard_ad" );
$t->set_block( "ad_column_tpl", "html_ad_tpl", "html_ad" );

$t->set_var( "element_list", "" );

$t->set_var( "one_column_article", "" );
$t->set_var( "two_column_article", "" );
$t->set_var( "one_short_article", "" );
$t->set_var( "ad_column", "" );


// image dir
$t->set_var( "image_dir", $ImageDir );

// parse the settings
$page_elements = explode( ";", $FrontPageSettings );
$articleCount = 0;
$productCount = 0;
$adCount = 0;
foreach ( $page_elements as $element )    
{
    if( $element == "1column" || $element == "2column" || $element == "1short" )
        $articleCount++;
    if ( $element == "ad"  )
        $adCount++;
    if ( $element == "1columnProduct" )
        $productCount++;
}

$category = new eZArticleCategory( $FrontPageCategory );
$productCategory = new eZProductCategory( $FrontPageProductCategory );

$user =& eZUser::currentUser();

if ( $FrontPageCategory == 0 )
{
    // do not set offset for the main page news
    // always sort by publishing date is the merged category
    $article = new eZArticle();
    $articleList =& $article->articles( "time", false, 0, $articleCount );
    $articleCount = $articleCount;
}
else
{
    $GlobalSectionID = eZArticleCategory::sectionIDStatic( $FrontPageCategory );

    // init the section
    $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
    $sectionObject->setOverrideVariables();
    
    $articleList =& $category->articles( $category->sortMode(), false, true, 0, $articleCount );
    $articleCount = $articleCount;
}

if ( $FrontPageProductCategory == 0 )
{
    // do not set offset for the main page news
    // always sort by publishing date is the merged category
    $product = new eZProduct();
    $productList =& $product->activeProducts( "time", 0, $productCount );
    $productCount = $productCount;

}
else
{
    $articleList =& $productCategory->products( $category->sortMode(), false, true, 0, $productCount );
    $articleCount = $articleCount;
}

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

$articleOffset = 0;
$adOffset = 0;
$pageContents = "";
// render the page elements
foreach ( $page_elements as $element )    
{
    switch ( $element )
    {
        case "1column":
        {            
            $article =& $articleList[$articleOffset];

            if ( get_class( $article ) == "ezarticle" )
                $pageContents .= renderFrontpageArticle( $t, $locale, $article );
            
            $articleOffset++;
        }break;

        case "2column":
        {
            $article1 =& $articleList[$articleOffset];
            $articleOffset++;
            $article2 =& $articleList[$articleOffset];

            if ( get_class( $article1 ) == "ezarticle" && get_class( $article2 ) == "ezarticle" ) 
                $pageContents .= renderFrontpageArticleDouble( $t, $locale, $article1, $article2 );
            
            $articleOffset++;
        }break;

        case "1short":
        {            
            $article =& $articleList[$articleOffset];

            if ( get_class( $article ) == "ezarticle" )
                $pageContents .= renderShortSingleArticle( $t, $locale, $article );
            
            $articleOffset++;
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
            $product =& $productList[0];

//            if ( get_class( $product ) == "ezproduct" )
//                $pageContents .= renderFrontpageProduct( $t, $locale, $product );
            
            $productOffset++;
        }break;
    }
}
$t->set_var( "element_list", $pageContents );



if ( isset( $GenerateStaticPage ) and $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");

    $output = $t->parse( $target, "article_list_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "article_list_page_tpl" );
}


function &renderFrontpageArticle( &$t, &$locale, &$article )
{
    global $ini;
	
	$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
    
	$aid = $article->id();

    if ( $CategoryID == 0 )
    {
        $category =& $article->categoryDefinition();
        $CategoryID = $category->id();
    }
        
    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "article_id", $article->id() );
    $t->set_var( "article_name", $article->name() );

    $t->set_var( "author_text", $article->authorText() );

    $categoryDef =& $article->categoryDefinition();
	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    // preview image
    $thumbnailImage =& $article->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "one_column_article_image", "one_column_article_image_tpl" );
    }
    else
    {
        $t->set_var( "one_column_article_image", "" );    
    }
    
    $published = $article->published();

    $t->set_var( "article_published", $locale->format( $published ) );
    

    $renderer = new eZArticleRenderer( $article );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );

        
    if ( $article->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }

    // check if the article contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "one_column_read_more", "" );
    }
    else
    {
        $t->parse( "one_column_read_more", "one_column_read_more_tpl" );
    }


    return $t->parse( "output", "one_column_article_tpl" );
}

function &renderFrontpageArticleDouble( &$t, &$locale, &$article1, &$article2 )
{
    global $ini;
    $aid = $article1->id();
	
	$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
	
    if ( $CategoryID == 0 )
    {
        $category =& $article1->categoryDefinition();
        $CategoryID = $category->id();
    }
        
    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "article_id", $article1->id() );
    $t->set_var( "article_name", $article1->name() );
    $t->set_var( "author_text", $article1->authorText() );

    $categoryDef =& $article1->categoryDefinition();	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    // preview image
    $thumbnailImage =& $article1->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "left_article_image", "left_article_image_tpl" );
    }
    else
    {
        $t->set_var( "left_article_image", "" );    
    }
    
    $published = $article1->published();

    $t->set_var( "article_published", $locale->format( $published ) );
    

    $renderer = new eZArticleRenderer( $article1 );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );

        
    if ( $article1->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article1->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }

    // check if the article contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "left_read_more", "" );
    }
    else
    {
        $t->parse( "left_read_more", "left_read_more_tpl" );
    }

    $t->parse( "left_article", "left_article_tpl"  );
    $aid = $article2->id();

    if ( $CategoryID == 0 )
    {
        $category =& $article2->categoryDefinition();
        $CategoryID = $category->id();
    }
        
    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "article_id", $article2->id() );
    $t->set_var( "article_name", $article2->name() );
    $t->set_var( "author_text", $article2->authorText() );

    $categoryDef =& $article2->categoryDefinition();	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    // preview image
    $thumbnailImage =& $article2->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
        $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ), $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "right_article_image", "right_article_image_tpl" );
    }
    else
    {
        $t->set_var( "right_article_image", "" );    
    }
    
    $published = $article2->published();

    $t->set_var( "article_published", $locale->format( $published ) );
    

    $renderer = new eZArticleRenderer( $article2 );
    $t->set_var( "article_intro", $renderer->renderIntro(  ) );

        
    if ( $article2->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article2->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }

    // check if the article contains more than intro
    $contents =& $renderer->renderPage();

    if ( trim( $contents[1] ) == "" )
    {
        $t->set_var( "right_read_more", "" );
    }
    else
    {
        $t->parse( "right_read_more", "right_read_more_tpl" );
    }

    $t->parse( "right_article", "right_article_tpl"  );
    
    return $t->parse( "output", "two_column_article_tpl" );    
}

function &renderShortSingleArticle( &$t, &$locale, &$article )
{
    global $ini;

    $aid = $article->id();
	
	$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
	
    if ( $CategoryID == 0 )
    {
        $category =& $article->categoryDefinition();
        $CategoryID = $category->id();
    }
        
    $t->set_var( "category_id", $CategoryID );
    
    $t->set_var( "article_id", $article->id() );
    $t->set_var( "article_name", $article->name() );

    $t->set_var( "author_text", $article->authorText() );

    $categoryDef =& $article->categoryDefinition();
	
    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    $published = $article->published();

    $t->set_var( "article_published", $locale->format( $published ) );
    

    if ( $article->linkText() != "" )
    {
        $t->set_var( "article_link_text", $article->linkText() );
    }
    else
    {
        $t->set_var( "article_link_text", $DefaultLinkText );
    }


    return $t->parse( "output", "one_short_article_tpl" );
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

    return $t->parse( "output", "ad_column_tpl" );
}

?>

