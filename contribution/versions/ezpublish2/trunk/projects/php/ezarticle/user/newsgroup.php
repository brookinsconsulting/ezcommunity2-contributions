<?php
// 
// $Id: newsgroup.php,v 1.1 2001/08/23 15:00:05 ce Exp $
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

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "newsgroup.php" );

$limit = 4;

$t->setAllStrings();

$t->set_file( array(
    "news_group_tpl" => "newsgroup.tpl"
    ) );

$t->set_block( "news_group_tpl", "category_item_tpl", "category_item" );

$t->set_block( "news_group_tpl", "start_with_break_tpl", "start_with_break" );
$t->set_block( "news_group_tpl", "start_without_break_tpl", "start_without_break" );

$t->set_block( "news_group_tpl", "end_with_break_tpl", "end_with_break" );
$t->set_block( "news_group_tpl", "end_without_break_tpl", "end_without_break" );

$t->set_block( "category_item_tpl", "article_item_tpl", "article_item" );
$t->set_block( "article_item_tpl", "article_image_tpl", "article_image" );
$t->set_block( "article_item_tpl", "no_image_tpl", "no_image" );

// image dir
$t->set_var( "image_dir", $ImageDir );

$category = new eZArticleCategory( 0 );

$categoryList =& $category->getByParent( $category, true, "placement" );

print( count ( $categoryList ) );
$i = 0;
foreach( $categoryList as $category )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->parse( "start_with_break", "start_without_break_tpl" );
        $t->parse( "end_with_break", "end_without_break_tpl" );
    }
    else
    {
        $t->parse( "start_without_break", "start_without_break_tpl" );
        $t->parse( "end_without_break", "end_without_break_tpl" );
    }

    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );


    $articles = $category->articles( "time", false, true, 0, $limit );

    $t->set_var( "article_item", "" );
    $j=0;
    foreach( $articles as $article )
    {
        $t->set_var( "article_name", $article->name() );
        $t->set_var( "article_id", $article->id() );

        $t->set_var( "article_image", "" );
        $t->set_var( "no_image", "" );    
        if ( $i == 0 )
        {
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
        }
        else
        {
            $t->parse( "no_image", "no_image_tpl" );
        }

        $renderer = new eZArticleRenderer( $article );
        
        $t->set_var( "article_intro", $renderer->renderIntro(  ) );


        $t->parse( "article_item", "article_item_tpl", true );
        $j++;
    }
                     

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( isset( $GenerateStaticPage ) and $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");

    $output = $t->parse( $target, "news_group_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "news_group_tpl" );
}


?>

