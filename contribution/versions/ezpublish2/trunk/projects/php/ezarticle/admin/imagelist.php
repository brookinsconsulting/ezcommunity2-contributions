<?
// 
// $Id: imagelist.php,v 1.1 2000/10/20 12:49:23 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 10:32:19 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );


$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "imagelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_list_page_tpl" => "imagelist.tpl"
    ) );

$t->set_block( "image_list_page_tpl", "image_tpl", "image" );

print( "ArtikkelID: " . $ArticleID );

$article = new eZArticle( $ArticleID );


$thumbnail = $article->thumbnailImage();

$t->set_var( "article_name", $article->name() );

$images = $article->images();

$i=0;
foreach ( $images as $image )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->set_var( "main_image_checked", "" );
    if ( $main != 0 )
    {
        if ( $main->id() == $image->id() )
        {
            $t->set_var( "main_image_checked", "checked" );
        }
    }

    $t->set_var( "thumbnail_image_checked", "" );
    if ( $thumbnail != 0 )
    {
        if ( $thumbnail->id() == $image->id() )
        {
            $t->set_var( "thumbnail_image_checked", "checked" );
        }
    }
    
    $t->set_var( "image_name", $image->caption() );
    $t->set_var( "image_id", $image->id() );
    $t->set_var( "article_id", $ArticleID );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_url", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height",$variation->height() );
    
//      $t->set_var( "image_url", $image->filePath() );

    $t->parse( "image", "image_tpl", true );
    
    $i++;
}

$t->set_var( "article_id", $article->id() );

$t->pparse( "output", "image_list_page_tpl" );

?>
