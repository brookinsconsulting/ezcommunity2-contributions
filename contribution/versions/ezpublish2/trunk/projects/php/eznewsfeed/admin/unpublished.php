<?php
// 
// $Id: unpublished.php,v 1.9 2001/02/01 13:03:04 th Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <29-Nov-2000 18:10:27 bf>
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

include_once( "eznewsfeed/classes/eznews.php" );
include_once( "eznewsfeed/classes/eznewscategory.php" );
include_once( "eznewsfeed/classes/eznewsimporter.php" );

include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlocale.php" );

if ( isset( $Publish ) )
    $Action = "Publish";

if ( isset( $Delete ) )
    $Action = "Delete";

if ( $Action == "Publish" )
{
    if ( count( $NewsPublishIDArray ) > 0 )
    {
        
        foreach ( $NewsPublishIDArray as $newsID )
        {
            $news = new eZNews( $newsID );
            $news->setName( addSlashes( $news->name() ) );
            $news->setIntro( addSlashes( $news->intro() ) );
            $news->setIsPublished( true );
            
            $news->store();
        }
    }
    
    // delete the cache
    $dir = dir( "eznewsfeed/cache/" );
    $files = array();
    while( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "latestnews,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $CategoryID )
                {
                    unlink( "eznewsfeed/cache/" . $entry );
                }
            }
            
            if ( ereg( "headlines,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $CategoryID )
                {
                    unlink( "eznewsfeed/cache/" . $entry );
                }
            }
        }
    } 
    $dir->close();
}


if ( $Action == "Delete" )
{
    if ( count( $NewsDeleteIDArray ) > 0 )
    {
        foreach ( $NewsDeleteIDArray as $newsID )
        {
            $news = new eZNews( $newsID );
            $news->delete();
        }
    }
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZNewsfeedMain", "Language" );

$t = new eZTemplate( "eznewsfeed/admin/" . $ini->read_var( "eZNewsfeedMain", "AdminTemplateDir" ),
                     "eznewsfeed/admin/intl/", $Language, "unpublished.php" );

$t->setAllStrings();

$t->set_file( array(
    "news_unpublished_page_tpl" => "unpublished.tpl"
    ) );

// path
$t->set_block( "news_unpublished_page_tpl", "path_item_tpl", "path_item" );

// category
$t->set_block( "news_unpublished_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// news
$t->set_block( "news_unpublished_page_tpl", "news_list_tpl", "news_list" );
$t->set_block( "news_list_tpl", "news_item_tpl", "news_item" );

$t->set_block( "news_unpublished_page_tpl", "previous_tpl", "previous" );
$t->set_block( "news_unpublished_page_tpl", "next_tpl", "next" );

$t->set_var( "site_style", $SiteStyle );

$category = new eZNewsCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
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

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


if ( !isSet( $Limit ) )
    $Limit = 20;
if ( !isSet( $Offset ) )
    $Offset = 0;


// news
$newsList =& $category->newsList( "time", "only", $Offset, $Limit );
$newsListCount = $category->newsListCount( "time", "only" );

//  print( "news count: " . $newsListCount );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "news_list", "" );
foreach ( $newsList as $news )
{
    if ( $news->name() == "" )
        $t->set_var( "news_name", "&nbsp;" );
    else
        $t->set_var( "news_name", $news->name() );

    $t->set_var( "news_id", $news->id() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->set_var( "news_origin", $news->origin() );

    $published = $news->originalPublishingDate();
    $date =& $published->date();            
    $t->set_var( "news_date", $locale->format( $date ) );


    $t->parse( "news_item", "news_item_tpl", true );
    $i++;
}

if ( count( $newsList ) > 0 )    
    $t->parse( "news_list", "news_list_tpl" );
else
    $t->set_var( "news_list", "" );


$prevOffs = $Offset - $Limit;
$nextOffs = $Offset + $Limit;
        
if ( $prevOffs >= 0 )
{
    $t->set_var( "prev_offset", $prevOffs  );
    $t->parse( "previous", "previous_tpl" );
}
else
{
    $t->set_var( "previous", "" );
}
        
if ( $nextOffs <= $newsListCount )
{
    $t->set_var( "next_offset", $nextOffs  );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
}


$t->pparse( "output", "news_unpublished_page_tpl" );

?>
