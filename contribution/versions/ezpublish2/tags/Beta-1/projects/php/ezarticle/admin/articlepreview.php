<?
// 
// $Id: articlepreview.php,v 1.6 2000/10/22 16:57:13 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 16:34:51 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articlepreview.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_preview_page_tpl" => "articlepreview.tpl"
    ) );

$t->set_block( "article_preview_page_tpl", "page_menu_separator_tpl", "page_menu_separator" );

$t->set_block( "article_preview_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "article_preview_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "article_preview_page_tpl", "prev_page_link_tpl", "prev_page_link" );

$article = new eZArticle( $ArticleID );

$renderer = new eZArticleRenderer( $article );

$t->set_var( "article_name", $article->name() );
$t->set_var( "author_text", $article->authorText() );

print( "Showing page: "  . $PageNumber . "<br>" );
$t->set_var( "article_body", $renderer->renderPage( $PageNumber - 1 ) );

$t->set_var( "link_text", $article->linkText() );
$t->set_var( "article_id", $article->id() );


$pageCount = $article->pageCount();

if ( $pageCount > 1 )
{
    for ( $i=0; $i<$pageCount; $i++ )
    {
        $t->set_var( "article_id", $article->id() );    
        $t->set_var( "page_number", $i+1 );

        $t->parse( "page_link", "page_link_tpl", true );
    }

    $t->parse( "page_menu_separator", "page_menu_separator_tpl" );    
}
else
{
    $t->set_var( "page_link", "" );
    $t->set_var( "page_menu_separator", "" );
}

if ( $PageNumber > 1 )
{
    $t->set_var( "prev_page_number", $PageNumber - 1 );    
    $t->parse( "prev_page_link", "prev_page_link_tpl" );
}
else
{
    $t->set_var( "prev_page_link", "" );
}

if ( $PageNumber < $pageCount )
{
    $t->set_var( "next_page_number", $PageNumber + 1 );    
    $t->parse( "next_page_link", "next_page_link_tpl" );
}
else
{
    $t->set_var( "next_page_link", "" );
}



$t->pparse( "output", "article_preview_page_tpl" );


?>
