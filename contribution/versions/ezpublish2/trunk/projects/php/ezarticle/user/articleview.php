<?
// 
// $Id: articleview.php,v 1.2 2000/10/20 14:39:07 bf-cvs Exp $
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

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "articleview.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_view_page_tpl" => "articleview.tpl"
    ) );

$article = new eZArticle( $ArticleID );

$renderer = new eZArticleRenderer( $article );

$t->set_var( "article_name", $article->name() );
$t->set_var( "author_text", $article->authorText() );

print( "Showing page: "  . $PageNumber . "<br>" );

$t->set_var( "article_body", $renderer->renderPage( $PageNumber ) );

$t->set_var( "link_text", $article->linkText() );


if ( $GenerateStaticPage == "true" )
{
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse($target, "article_view_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "article_view_page_tpl" );
}


?>
