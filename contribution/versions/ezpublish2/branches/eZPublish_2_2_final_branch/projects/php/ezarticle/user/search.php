<?php
// 
// $Id: search.php,v 1.18.2.3 2002/01/11 15:55:38 bf Exp $
//
// Created on: <28-Oct-2000 15:56:58 bf>
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


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "classes/ezlist.php" );

$Language = $ini->read_var( "eZArticleMain", "Language" );
$Limit = $ini->read_var( "eZArticleMain", "SearchListLimit" );

// init the section
if ( isset ($SectionIDOverride) )
{
    $GlobalSectionID = $SectionIDOverride;
    include_once( "ezsitemanager/classes/ezsection.php" );

    $sectionObject =& eZSection::globalSectionObject( $SectionIDOverride );
    $sectionObject->setOverrideVariables();
}

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( "article_list_page_tpl", "search.tpl" );

// article
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

// Init url variables - for eZList...
$t->set_var( "url_start_stamp", urlencode( "+" ) );
$t->set_var( "url_stop_stamp", urlencode( "+" ) );
$t->set_var( "url_category_array", urlencode( "+" ) );
$t->set_var( "url_contentswriter_id", urlencode( "+" ) );
$t->set_var( "url_photographer_id", urlencode( "+" ) );

if ( checkdate ( $StartMonth, $StartDay, $StartYear ) )
{
    $startDate = new eZDateTime( $StartYear,  $StartMonth, $StartDay, $StartHour, $StartMinute, 0 );
    $StartStamp = $startDate->timeStamp();
}
if ( checkdate ( $StopMonth, $StopDay, $StopYear ) )
{
    $stopDate = new eZDateTime( $StopYear, $StopMonth, $StopDay, $StopHour, $StopMinute, 0 );
    $StopStamp = $stopDate->timeStamp();
}

$t->set_var( "search_text", "" );

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
    if ( isset( $StartStamp ) )
    {
        $paramsArray["FromDate"] = $StartStamp;
        $t->set_var( "url_start_stamp", urlencode( $StartStamp ) );
    }
        
    if ( isset( $StopStamp ) )
    {
        $paramsArray["ToDate"] = $StopStamp;
        $t->set_var( "url_stop_stamp", urlencode( $StopStamp ) );
    }

    if( $ContentsWriterID != 0 )
    {
        $paramsArray["AuthorID"] = $ContentsWriterID;
        $t->set_var( "url_contentswriter_id", urlencode( $ContentsWriterID ) );
    }

    if( $PhotographerID != 0 )
    {
        $paramsArray["PhotographerID"] = $PhotographerID;
        $t->set_var( "url_photographer_id", urlencode( $PhotographerID ) );
    }

    if( is_array( $CategoryArray ) && count( $CategoryArray ) > 0 && !in_array( 0, $CategoryArray ) )
    {
        $paramsArray["Categories"] = $CategoryArray;

        // fix output string for URL
        $t->set_var( "url_category_array", urlencode( implode( "-", $CategoryArray ) ) );
    }

    $t->set_var( "search_text", $SearchText );
    $article = new eZArticle();
    $totalCount = 0;
    $articleList = $article->search( $SearchText, "time", false, $Offset, $Limit, $paramsArray, $totalCount );

//    $totalCount = $article->searchCount( $SearchText, false, $paramsArray );

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

if ( $totalCount == 0 )
    $t->set_var( "article_start", 0 );
else
    $t->set_var( "article_start", $Offset + 1 );
$t->set_var( "article_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "article_total", $totalCount );

if ( isset ($SectionIDOverride) ) $t->set_var( "section_id", $SectionIDOverride );

$t->pparse( "output", "article_list_page_tpl" );

?>
