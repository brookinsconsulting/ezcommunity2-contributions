<?
// 
// $Id: search.php,v 1.8 2001/04/26 11:54:04 ce Exp $
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
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZArticleMain", "Language" );
$Limit = $ini->read_var( "eZArticleMain", "AdminListLimit" );

if( isset( $Delete ) && count( $ArticleArrayID ) > 0 )
{
    foreach( $ArticleArrayID as $articleID )
    {
        if( eZObjectPermisson::hasPermission( $articleID, "article_article", 'w' ) )
        {
            $article = new eZArticle( $articleID );
            $article->delete();
        }
    }
}

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "search.tpl"
    ) );

if ( !isset ( $Offset ) )
    $Offset = 0;

// article
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );
$t->set_block( "article_item_tpl", "article_is_published_tpl", "article_is_published" );
$t->set_block( "article_item_tpl", "article_not_published_tpl", "article_not_published" );
$t->set_block( "article_list_page_tpl", "article_delete_tpl", "article_delete" );

$category = new eZArticleCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );


if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
$t->set_var( "category_list", "" );

// if ( $SearchText )
{
    $article = new eZArticle();
    $articleList = $article->search( $SearchText, "time", true, $Offset, $Limit );
    $totalCount = $article->searchCount( $SearchText, "time", false );

    $t->set_var( "search_text", $SearchText );
    $t->set_var( "url_text", urlencode ( $SearchText ) );
}

if ( count ( $articleList ) > 0 )
{
    $locale = new eZLocale( $Language );
    $i=0;
    $t->set_var( "article_list", "" );
    foreach ( $articleList as $article )
    {
        $t->set_var( "article_name", $article->name() );

        $t->set_var( "article_id", $article->id() );

        if ( $article->isPublished() == true )
        {
            $t->parse( "article_is_published", "article_is_published_tpl" );
            $t->set_var( "article_not_published", "" );        
        }
        else
        {
            $t->set_var( "article_is_published", "" );
            $t->parse( "article_not_published", "article_not_published_tpl" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "article_list_page_tpl" );

if ( count( $articleList ) > 0 )
{
    $t->parse( "article_list", "article_list_tpl" );
    $t->parse( "article_delete", "article_delete_tpl" );
}
else
{
    $t->set_var( "article_list", "" );
    $t->set_var( "article_delete", "" );
}

$t->set_var( "article_start", $Offset + 1 );
$t->set_var( "article_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "article_total", $totalCount );

$t->pparse( "output", "article_list_page_tpl" );

?>
