<?
// 
// $Id: search.php,v 1.10 2001/04/26 14:08:31 th Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Oct-2000 15:56:58 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "classes/ezlist.php" );

if ( isset( $GLOBALS["SiteIni"] ) )
{
    $ini =& $GLOBALS["SiteIni"];
}
else
{
    $ini = new INIFile( "site.ini" );
}

$Language = $ini->read_var( "eZArticleMain", "Language" );
$Limit = $ini->read_var( "eZArticleMain", "SearchListLimit" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "search.tpl"
    ) );

// article
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

$category = new eZArticleCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );

$t->set_var( "search_text", $SearchText );

if( !isset ( $Offset ) )
    $Offset = 0;

// articles

if ( $SearchText )
{
    $article = new eZArticle();
    $articleList = $article->search( $SearchText, "time", false, $Offset, $Limit );
    $totalCount = $article->searchCount( $SearchText, "time", false );

    $t->set_var( "url_text", urlencode ( $SearchText ) );
}

// if ( ( $MaxSearchForArticles != 0 ) && ( $MaxSearchForArticles < $totalCount ) )

if ( count ( $articleList ) > 0 )
{
    $locale = new eZLocale( $Language );
    $i=0;
    $t->set_var( "article_list", "" );
    foreach ( $articleList as $article )
    {
        $t->set_var( "article_name", $article->name() );

        $t->set_var( "article_id", $article->id() );

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
    
        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "article_list_page_tpl" );


if ( count( $articleList ) > 0 )    
    $t->parse( "article_list", "article_list_tpl" );
else
$t->set_var( "article_list", "" );

$t->set_var( "article_start", $Offset + 1 );
$t->set_var( "article_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "article_total", $totalCount );



$t->pparse( "output", "article_list_page_tpl" );






?>
