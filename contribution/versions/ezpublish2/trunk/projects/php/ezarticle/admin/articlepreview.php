<?
// 
// $Id: articlepreview.php,v 1.2 2000/10/20 13:31:24 bf-cvs Exp $
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

$article = new eZArticle( $ArticleID );

$renderer = new eZArticleRenderer( $article );

$t->set_var( "article_name", $article->name() );
$t->set_var( "author_text", $article->authorText() );

print( "Showing page: "  . $PageNumber . "<br>" );
$t->set_var( "article_body", $renderer->renderPage( $PageNumber ) );

$t->set_var( "link_text", $article->linkText() );
$t->set_var( "article_id", $article->id() );

$t->pparse( "output", "article_preview_page_tpl" );


?>
