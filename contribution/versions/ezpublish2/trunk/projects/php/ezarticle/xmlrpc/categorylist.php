<?php
//
// $Id: categorylist.php,v 1.21 2001/11/08 15:11:05 jb Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

// eZ article classes
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

if ( $Command == "list" )
{
//      usleep( 1000000 );

    $list_categories = false;
    $list_articles = false;
    if ( is_object( $Data["ListType"] ) )
    {
        $ListType = $Data["ListType"]->value();
        if ( is_object( $ListType["Catalogues"] ) )
            $list_categories = true;
        if ( is_object( $ListType["Elements"] ) )
            $list_articles = true;
    }

    if ( !$list_categories and !$list_articles )
    {
        $list_categories = true;
        $list_articles = true;
    }

    $offset = 0;
    $max = -1;
    $total = 0;

    if ( is_object( $Data["Part"] ) )
    {
        $Part = $Data["Part"]->value();
        $offset = $Part["Offset"]->value();
        $max = $Part["Max"]->value();
//        eZLog::writeNotice( "Article: Offset: $offset, Max: $max" );
    }
//      eZLog::writeNotice( "Article: Command: $Command, URL: $Module/$Type/$ID, offs: $offset, max: $max" );


    $category = new eZArticleCategory( $ID );

    $loc_max = $max;
    $loc_offset = $offset;

    $cat = array();
    if ( $list_categories )
    {
        $categoryCount = $category->countByParent( $category, true, $User );
        $total += $categoryCount;
        if ( $loc_offset < $categoryCount )
        {
            $categoryList =& $category->getByParent( $category, true, "placement", $loc_offset, $loc_max, $User );
            $loc_max -= count( $categoryList );

            foreach ( $categoryList as $catItem )
            {
                $cat[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                              "category",
                                                                              $catItem->id() ),
                                                    "Name" => new eZXMLRPCString( $catItem->name( false ) )
                                                    )
                                             );
            }
        }
        $loc_offset = max( 0, $loc_offset - $categoryCount );
    }

    $art = array();
    if ( $list_articles )
    {
        $articleCount = $category->articleCount( true, true );
        $total += $articleCount;
        if ( $loc_max > 0 and $loc_offset >= 0 )
        {
            $articleList =& $category->articles( "alpha", true, true, $loc_offset, $loc_max );
            foreach( $articleList as $artItem )
            {
                $topic =& $artItem->topic();
                $cols = array( "Publish date" => createDateTimeStruct( $artItem->published() ),
                               "Published" => new eZXMLRPCBool( $artItem->isPublished() ),
                               "Modification date" => createDateTimeStruct( $artItem->modified() ),
                               "Author" => new eZXMLRPCString( $artItem->authorText( false ) ),
                               "Topic" => new eZXMLRPCString( $topic->name() ),
                               );
                $art[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                              "article",
                                                                              $artItem->id() ),
                                                    "Name" => new eZXMLRPCString( $artItem->name( false ) ),
                                                    "Columns" => new eZXMLRPCStruct( $cols )
                                                    )
                                             );
            }
        }
//      if ( $offset > 0 )
//          usleep( 5000000 );
    }

    $par = array();
    if ( $offset == 0 )
    {
        $path =& $category->path();
        if ( $category->id() != 0 )
        {
            $par[] = createURLStruct( "ezarticle", "category", 0 );
        }
        else
        {
            $par[] = createURLStruct( "ezarticle", "" );
        }
        foreach( $path as $item )
        {
            if ( $item[0] != $category->id() )
                $par[] = createURLStruct( "ezarticle", "category", $item[0] );
        }
    }

    $part_arr = array( "Offset" => new eZXMLRPCInt( $offset ),
                       "Total" => new eZXMLRPCInt( $total ) );
    if ( $offset == 0 )
    {
        $part_arr["Begin"] = new eZXMLRPCBool( true );
    }
    if ( $total == $offset + count( $cat ) + count( $art ) )
    {
        $part_arr["End"] = new eZXMLRPCBool( true );
    }
    $part = new eZXMLRPCStruct( $part_arr );

    if ( $offset == 0 )
        $cols = new eZXMLRPCStruct( array( "Author" => new eZXMLRPCString( "text" ),
                                           "Published" => new eZXMLRPCString( "bool" ),
                                           "Topic" => new eZXMLRPCString( "text" ),
                                           "Publish date" => new eZXMLRPCString( "datetime" ),
                                           "Modification date" => new eZXMLRPCString( "datetime" )
                                           ) );

    if ( $articleCount >= count( $art ) && $categoryCount >= count( $cat ) )
    {
        $ret = array( "Catalogues" => $cat,
                      "Elements" => $art,
                      "Path" => $par,
                      "Part" => $part );
        if ( $offset == 0 )
            $ret["Columns"] = $cols;
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
    else
    {
        if ( $articleCount < count( $art ) )
        {
            $Error = createErrorMessage( EZERROR_CUSTOM,
                                         "The article count was lower than listed articles, probably a bug in eZ article",
                                         EZARTICLE_WRONG_ARTICLE_COUNT );
        }
        else if ( $categoryCount < count( $cat ) )
        {
            $Error = createErrorMessage( EZERROR_CUSTOM,
                                         "The category count was lower than listed categories, probably a bug in eZ article",
                                         EZARTICLE_WRONG_CATEGORY_COUNT );
        }
    }
}
else if ( $Command == "tree" )
{
    $cat = new eZArticleCategory();
//      $cat->setName( "test" );
    $tree =& categoryTree( $cat );
    $ReturnData = createTreeStruct( $tree, "ezarticle", "category" );
}
else if ( $Command == "search" )
{
    $keywords = $Data["Keywords"]->value();
    $texts = array();
    foreach( $keywords as $keyword )
    {
        $texts[] = $keyword->value();
    }
    $elements = array();
    $result =& eZArticleCategory::search( $texts, true, "name", $User );
    foreach( $result as $item )
    {
        $itemid = $item->id();
        $catid = $item->parent( false );
        $cat = new eZArticleCategory();
        $cat->get( $catid );
        $element = array();
        $element["Name"] = new eZXMLRPCString( $item->name( false ) );
        $element["CategoryName"] = new eZXMLRPCString( $cat->name( false ) );
        $element["Location"] =  createURLStruct( "ezarticle", "category", $item->id() );
        $element["CategoryLocation"] = createURLStruct( "ezarticle", "category", $catid );
        $element["WebURL"] = new eZXMLRPCString( "/article/archive/$itemid/" );
        $element["CategoryWebURL"] = new eZXMLRPCString( "/article/archive/$catid/" );
        $elements[] = new eZXMLRPCStruct( $element );
    }
    $ret = array( "Elements" => new eZXMLRPCArray( $elements ) );
    handleSearchData( $ret );
    $ReturnData = new eZXMLRPCStruct( $ret );
}

function &categoryTree( $cat )
{
    $children =& eZArticleCategory::getByParent( $cat, true );
    $child_array = array();
    foreach( $children as $child )
    {
        $child_array[] = categoryTree( $child );
//          break;
    }
    $item = array( "ID" => $cat->id(),
                   "Name" => $cat->name( false ),
                   "Children" => $child_array );
    return $item;
}

?>
