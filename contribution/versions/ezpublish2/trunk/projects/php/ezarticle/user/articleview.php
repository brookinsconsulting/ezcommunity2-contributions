<?
// 
// $Id: articleview.php,v 1.9 2000/10/26 19:19:57 bf-cvs Exp $
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

$t->set_block( "article_view_page_tpl", "article_header_tpl", "article_header" );

$t->set_block( "article_view_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "article_view_page_tpl", "current_page_link_tpl", "current_page_link" );
$t->set_block( "article_view_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "article_view_page_tpl", "prev_page_link_tpl", "prev_page_link" );

if ( $StaticRendering == true )
{
    $t->set_var( "article_header", "" );
}
else
{
    $t->parse( "article_header", "article_header_tpl" );
}

$article = new eZArticle( $ArticleID );

$renderer = new eZArticleRenderer( $article );

$t->set_var( "article_name", $article->name() );
$t->set_var( "author_text", $article->authorText() );

$pageCount = $article->pageCount();
if ( $PageNumber > $pageCount )
    $PageNumber = $pageCount;

print( "Showing page: "  . $PageNumber . "<br>" );

$t->set_var( "article_body", $renderer->renderPage( $PageNumber - 1 ) );

$t->set_var( "link_text", $article->linkText() );

$t->set_var( "article_id", $article->id() );


$t->set_var( "current_page_link", "" );

if ( $pageCount > 1 )
{
    for ( $i=0; $i<$pageCount; $i++ )
    {
        $t->set_var( "article_id", $article->id() );    
        $t->set_var( "page_number", $i+1 );

        if ( ( $i + 1 )  == $PageNumber )
        {
            $t->parse( "page_link", "current_page_link_tpl", true );
        }
        else
        {
            $t->parse( "page_link", "page_link_tpl", true );            
        }
    }
}
else
{
    $t->set_var( "page_link", "" );
    
}

$locale = new eZLocale();
$published = $article->published();

$t->set_var( "article_created", $locale->format( $published ) );



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
