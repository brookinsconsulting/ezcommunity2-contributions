<?php
// 
// $Id: frontpage.php,v 1.3 2001/07/19 12:19:21 jakobn Exp $
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
$GrayScaleImageList = $ini->read_var( "eZArticleMain", "GrayScaleImageList" );

$FrontPageCategory = $ini->read_var( "eZArticleMain", "FrontPageCategory" );
$FrontPageFirstList = $ini->read_var( "eZArticleMain", "FrontPageFirstList" );
$FrontPageSecondList = $ini->read_var( "eZArticleMain", "FrontPageSecondList" );


$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "frontpage.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "frontpage.tpl"
    ) );

$t->set_block( "article_list_page_tpl", "header_item_tpl", "header_item" );


// first articles
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );
$t->set_block( "article_item_tpl", "article_image_tpl", "article_image" );
$t->set_block( "article_item_tpl", "read_more_tpl", "read_more" );

// short articles 2nd list
$t->set_block( "article_list_page_tpl", "article_short_list_tpl", "article_short_list" );
$t->set_block( "article_short_list_tpl", "article_short_item_tpl", "article_short_item" );
$t->set_block( "article_short_item_tpl", "article_short_image_tpl", "article_short_image" );
$t->set_block( "article_short_item_tpl", "article_short_read_more_tpl", "article_short_read_more" );


// image dir
$t->set_var( "image_dir", $ImageDir );

$category = new eZArticleCategory( $FrontPageCategory );

$user =& eZUser::currentUser();


if ( $CategoryID == 0 )
{
    // do not set offset for the main page news
    // always sort by publishing date is the merged category
    $article = new eZArticle();
    $articleList =& $article->articles( "time", false, 0, $FrontPageFirstList + $FrontPageSecondList );
    $articleCount = $article->articleCount( false );
}
else
{
    $articleList =& $category->articles( $category->sortMode(), false, true, 0, $FrontPageFirstList + $FrontPageSecondList );
    $articleCount = $category->articleCount( false, true  );
}

$t->set_var( "category_current_id", $CategoryID );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "article_list", "" );

$firstArticleList =& array_slice( $articleList, 0, $FrontPageFirstList );
$secondArticleList =& array_slice( $articleList, $FrontPageFirstList, $FrontPageSecondList );

// first article list
foreach (  $firstArticleList as $article )
{
    // check if user has permission, if not break to next article.
    $aid = $article->id();
    if( eZObjectPermission::hasPermission( $aid, "article_article", 'r' )  ||
         eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
    {
        if ( $CategoryID == 0 )
        {
            $category =& $article->categoryDefinition();
            $CategoryID = $category->id();
        }
        
        $t->set_var( "category_id", $CategoryID );
    
        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );

        $t->set_var( "author_text", $article->authorText() );
    
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

            $t->parse( "article_image", "article_image_tpl" );
        }
        else
        {
            $t->set_var( "article_image", "" );    
        }
    

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "start_tr", "<tr>" );
            $t->set_var( "stop_tr", "" );
            
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "start_tr", "" );
            $t->set_var( "stop_tr", "</tr>" );
            
            $t->set_var( "td_class", "bgdark" );
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
            $t->set_var( "read_more", "" );
        }
        else
        {
            $t->parse( "read_more", "read_more_tpl" );
        }


        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

if ( count( $articleList ) > 0 )    
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );


$t->set_var( "article_short_list", "" );

$i = 0;
// short articles
foreach ( $secondArticleList as $article )
{
    // check if user has permission, if not break to next article.
    $aid = $article->id();
    if( eZObjectPermission::hasPermission( $aid, "article_article", 'r' )  ||
         eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
    {
        if ( $CategoryID == 0 )
        {
            $category =& $article->categoryDefinition();
            $CategoryID = $category->id();
        }
        
        $t->set_var( "category_id", $CategoryID );
    
        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );

        $t->set_var( "author_text", $article->authorText() );
    
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

            $t->parse( "article_short_image", "article_short_image_tpl" );
        }
        else
        {
            $t->set_var( "article_short_image", "" );    
        }
    
        
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
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
            $t->set_var( "read_more", "" );
        }
        else
        {
            $t->parse( "article_short_read_more", "article_short_read_more_tpl" );
        }


        $t->parse( "article_short_item", "article_short_item_tpl", true );
        $i++;
    }
}



if ( count( $articleList ) > 0 )    
    $t->parse( "article_short_list", "article_short_list_tpl" );
else
    $t->set_var( "article_short_list", "" );


if ( $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = fopen ( $cachedFile, "w+");

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


?>

