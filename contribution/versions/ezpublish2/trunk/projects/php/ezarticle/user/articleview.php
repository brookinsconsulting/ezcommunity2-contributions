<?
// 
// $Id: articleview.php,v 1.23 2001/01/09 15:26:14 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 16:34:51 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );
                                                        
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "articleview.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_view_page_tpl" => "articleview.tpl"
    ) );

$t->set_block( "article_view_page_tpl", "article_header_tpl", "article_header" );

$t->set_block( "article_view_page_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );

$t->set_block( "article_view_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "article_view_page_tpl", "current_page_link_tpl", "current_page_link" );
$t->set_block( "article_view_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "article_view_page_tpl", "prev_page_link_tpl", "prev_page_link" );
$t->set_block( "article_view_page_tpl", "numbered_page_link_tpl", "numbered_page_link" );
$t->set_block( "article_view_page_tpl", "print_page_link_tpl", "print_page_link" );

if ( $StaticRendering == true )
{
    $t->set_var( "article_header", "" );
}
else
{
    $t->parse( "article_header", "article_header_tpl" );
}

$article = new eZArticle(  );

// check if the article exists
if ( $article->get( $ArticleID ) )
{
    if ( $article->isPublished() )
    {
        // published article.
    }
    else
    {
        Header( "Location: /404" );
        exit();
    }
    
    $renderer = new eZArticleRenderer( $article );

    if ( $CapitalizeHeadlines == "enabled" )
    {
        include_once( "classes/eztexttool.php" );
        $t->set_var( "article_name", eZTextTool::capitalize(  $article->name() ) );
    }
    else
    {
        $t->set_var( "article_name", $article->name() );
    }

    $t->set_var( "author_text", $article->authorText() );

    $pageCount = $article->pageCount();
    if ( $PageNumber > $pageCount )
        $PageNumber = $pageCount;

    if ( $PageNumber == -1 )
        $t->set_var( "article_body", $renderer->renderPage( -1 ) );
    else
        $t->set_var( "article_body", $renderer->renderPage( $PageNumber - 1 ) );

    $t->set_var( "link_text", $article->linkText() );

    $t->set_var( "article_id", $article->id() );

    $locale = new eZLocale();
    $published = $article->published();

    $t->set_var( "article_created", $locale->format( $published ) );
 
}

$files = $article->files();

if ( count( $files ) > 0 )
{
    foreach ( $files as $file )
    {
        
        $t->set_var( "file_id", $file->id() );
        $t->set_var( "original_file_name", $file->originalFileName() );
        $t->set_var( "file_name", $file->name() );
        $t->set_var( "file_url", $file->name() );
        
        $t->parse( "attached_file", "attached_file_tpl", true );
    }

    $t->parse( "attached_file_list", "attached_file_list_tpl" );
}
else
{
    $t->set_var( "attached_file_list", "" );
}


$t->set_var( "current_page_link", "" );

// page links
if ( $pageCount > 1 && $PageNumber != -1 && $PrintableVersion != "enabled" )
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


// non-printable version link
if ( ( $PageNumber == -1 ) && ( $PrintableVersion == "enabled" ) )
{
    $t->parse( "numbered_page_link", "numbered_page_link_tpl" );
}
else
{
    $t->set_var( "numbered_page_link", "" );
}

// printable version link
if ( ( $PrintableVersion != "enabled" ) && ( $StaticRendering != true )  )
{
    $t->parse( "print_page_link", "print_page_link_tpl" );
}
else
{
    $t->set_var( "print_page_link", "" );
}

// previous page link
if ( ( $PageNumber > 1 ) && ( $PrintableVersion != "enabled" ) )
{
    $t->set_var( "prev_page_number", $PageNumber - 1 );    
    $t->parse( "prev_page_link", "prev_page_link_tpl" );
}
else
{
    $t->set_var( "prev_page_link", "" );
}

// next page link
if ( $PageNumber < $pageCount && $PageNumber != -1 && $PrintableVersion != "enabled" )
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

    $output = $t->parse( $target, "article_view_page_tpl" );
    
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
