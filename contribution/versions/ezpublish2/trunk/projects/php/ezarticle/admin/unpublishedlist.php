<?
// 
// $Id: unpublishedlist.php,v 1.8 2001/04/27 12:12:39 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 14:41:37 bf>
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
include_once( "ezarticle/classes/ezarticletool.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$Locale = new eZLocale( $Language );
$AdminListLimit = $ini->read_var( "eZArticleMain", "AdminListLimit" );
$languageIni = new INIFIle( "ezarticle/admin/intl/" . $Language . "/unpublishedlist.php.ini", false );


if( isset( $DeleteCategories ) )
{
    if ( count ( $CategoryArrayID ) != 0 )
    {
        if ( file_exists( "ezarticle/cache/menubox.cache" ) )
            unlink( "ezarticle/cache/menubox.cache" );

        $categories = array();
        foreach( $CategoryArrayID as $ID )
        {
            $categories[] = $ID;
            $category = new eZArticleCategory( $ID );
            $categories[] = $category->parent( false );
            if( eZObjectPermission::hasPermission( $ID , "article_category", 'w' ) ||
                eZArticleCategory::isOwner( eZUser::currentUser(), $ID ) )
                $category->delete();
        }
        $categories = array_unique( $categories );
        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( "articlelist",
                                             $categories, NULL ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
    }
}

if( isset( $DeleteArticles ) )
{
    if ( count ( $ArticleArrayID ) != 0 )
    {
        foreach( $ArticleArrayID as $TArticleID )
        {
            if( eZObjectPermission::hasPermission( $TArticleID, "article_article", 'w' )
                || eZArticle::isAuthor( eZUser::currentUser(), $TArticleID ) )
            {
                $article = new eZArticle( $TArticleID );

                // get the category to redirect to
                $articleID = $article->id();

                $categoryArray = $article->categories();
                $categoryIDArray = array();
                foreach ( $categoryArray as $cat )
                {
                    $categoryIDArray[] = $cat->id();
                }
                $categoryID = $article->categoryDefinition();
                $categoryID = $categoryID->id();

                // clear the cache files.
                eZArticleTool::deleteCache( $TArticleID, $categoryID, $categoryIDArray );
                $article->delete();
            }
        }
    }
}

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "unpublishedlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "unpublished_list_page_tpl" => "unpublishedlist.tpl"
    ) );

// path
$t->set_block( "unpublished_list_page_tpl", "path_item_tpl", "path_item" );

// category
$t->set_block( "unpublished_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );
$t->set_block( "category_item_tpl", "category_edit_tpl", "category_edit" );

// article
$t->set_block( "unpublished_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

$t->set_block( "article_item_tpl", "article_is_published_tpl", "article_is_published" );
$t->set_block( "article_item_tpl", "article_not_published_tpl", "article_not_published" );
$t->set_block( "article_item_tpl", "article_edit_tpl", "article_edit" );

// move up / down
$t->set_block( "article_list_tpl", "absolute_placement_header_tpl", "absolute_placement_header" );
$t->set_block( "article_item_tpl", "absolute_placement_item_tpl", "absolute_placement_item" );

$t->set_var( "site_style", $SiteStyle );

$category = new eZArticleCategory( $CategoryID );

// move articles up / down

if ( $category->sortMode() == "absolute_placement" )
{
    if ( is_numeric( $MoveUp ) )
    {
        $category->moveUp( $MoveUp );
    }

    if ( is_numeric( $MoveDown ) )
    {
        $category->moveDown( $MoveDown );
    }
}

if ( $category->name() == "" )
    $t->set_var( "current_category_name", $languageIni->read_var( "strings", "topcategory" ) );
else
    $t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_description", $category->description() );

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList =& $category->getByParent( $category, true );


// categories
$i=0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    if( eZObjectPermission::hasPermission( $categoryItem->id(), "article_category", 'r' ) ||
        eZArticleCategory::isOwner( eZUser::currentUser(), $categoryItem->id() ) )
    {
        $t->set_var( "category_id", $categoryItem->id() );

        $t->set_var( "category_name", $categoryItem->name() );

        $parent = $categoryItem->parent();

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
    
        $t->set_var( "category_description", $categoryItem->description() );

        if( eZObjectPermission::hasPermission( $categoryItem->id(), "article_category", 'w' )  ||
            eZArticleCategory::isOwner( eZUser::currentUser(), $categoryItem->id() ) ) 
            $t->parse( "category_edit", "category_edit_tpl", false );
        else
            $t->set_var( "category_edit", "" );
        
        $t->parse( "category_item", "category_item_tpl", true );
        $i++;
    }
}

if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// set the offset/limit
if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = $AdminListLimit;

// articles
$articleList =& $category->articles( $category->sortMode(), false, false, $Offset, $Limit );
$articleCount = $category->articleCount( false, false );

$i=0;
$t->set_var( "article_list", "" );

if ( $category->sortMode() == "absolute_placement" )
{
    $t->parse( "absolute_placement_header", "absolute_placement_header_tpl" );
}
else
{
    $t->set_var( "absolute_placement_header", "" );
}

foreach ( $articleList as $article )
{
    if( eZObjectPermission::hasPermission( $article->id(), "article_article", 'r') ||
        eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
    {
        if ( $article->name() == "" )
            $t->set_var( "article_name", "&nbsp;" );
        else
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

        if ( $category->sortMode() == "absolute_placement" )
        {
            $t->parse( "absolute_placement_item", "absolute_placement_item_tpl" );
        }
        else
        {
            $t->set_var( "absolute_placement_item", "" );
        }
        if( eZObjectPermission::hasPermission( $article->id(), "article_article", 'w') ||
            eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
        {
            $t->parse( "article_edit", "article_edit_tpl", false );
        }
        else
            $t->set_var( "article_edit", "" );


        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $articleCount, $AdminListLimit, $Offset, "unpublished_list_page_tpl" );

$t->set_var( "archive_id", $CategoryID );

if ( count( $articleList ) > 0 )    
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );


$t->pparse( "output", "unpublished_list_page_tpl" );


//  /*
//    Delete cache.
//  */
//  function deleteCache( $ArticleID, $CategoryID, $CategoryArray )
//  {
//      $user = eZUser::currentUser();
//      $groupstr = "";
//      if( get_class( $user ) == "ezuser" )
//      {
//          $groupIDArray = $user->groups( true );
//          sort( $groupIDArray );
//          $first = true;
//          foreach( $groupIDArray as $groupID )
//          {
//              $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
//              $first = false;
//          }
//      }

//      $files = eZCacheFile::files( "ezarticle/cache/",
//                                   array( array( "articleprint", "articleview", "articlestatic" ),
//                                          $ArticleID, NULL, $groupstr ), "cache", "," );
//      foreach( $files as $file )
//      {
//          $file->delete();
//      }

//      $files = eZCacheFile::files( "ezarticle/cache/",
//                                   array( "articlelist",
//                                          array_merge( 0, $CategoryID, $CategoryArray ),
//                                          NULL, array( "", $groupstr ) ),
//                                   "cache", "," );
//      foreach( $files as $file )
//      {
//          $file->delete();
//      }
//  }

?>
