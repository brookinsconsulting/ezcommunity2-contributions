<?php
// 
// $Id: search.php,v 1.18.2.6 2002/05/02 13:17:51 bf Exp $
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

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "classes/ezlist.php" );

$Language = $ini->read_var( "eZRfpMain", "Language" );
$Limit = $ini->read_var( "eZRfpMain", "SearchListLimit" );
$SearchWithinSections = $ini->read_var( "eZRfpMain", "SearchWithinSections" );

// init the section
if ( isset ($SectionIDOverride) )
{
    $GlobalSectionID = $SectionIDOverride;
    include_once( "ezsitemanager/classes/ezsection.php" );

    $sectionObject =& eZSection::globalSectionObject( $SectionIDOverride );
    $sectionObject->setOverrideVariables();
}

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( "rfp_list_page_tpl", "search.tpl" );

// rfp
$t->set_block( "rfp_list_page_tpl", "rfp_list_tpl", "rfp_list" );
$t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );

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

$category = new eZRfpCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );

$tmpSearchText = str_replace( "<", "&lt;", $SearchText );
$tmpSearchText = str_replace( ">", "&gt;", $tmpSearchText );
$t->set_var( "search_text", $tmpSearchText );

if( !isset ( $Offset ) )
    $Offset = 0;

// rfps

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
    
    if ( $SearchWithinSections == "enabled" )
    {
	if ( isset( $SectionsList ) )
	{
	    $paramsArray["SectionsList"] = $SectionsList;
	}
	else
	{
	    $paramsArray["SectionsList"] = "$SectionIDOverride";
	}
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

    $t->set_var( "search_text", $tmpSearchText );
    $rfp = new eZRfp();
    $totalCount = 0;
    $rfpList = $rfp->search( $SearchText, "time", false, $Offset, $Limit, $paramsArray, $totalCount );

//    $totalCount = $rfp->searchCount( $SearchText, false, $paramsArray );

    $t->set_var( "url_text", urlencode ( $SearchText ) );
}

// if ( ( $MaxSearchForRfps != 0 ) && ( $MaxSearchForRfps < $totalCount ) )

if ( count ( $rfpList ) > 0 )
{
    $locale = new eZLocale( $Language );
    $i=0;
    $t->set_var( "rfp_list", "" );
    foreach ( $rfpList as $rfp )
    {
        $t->set_var( "rfp_name", $rfp->name() );

        $t->set_var( "rfp_id", $rfp->id() );
	
	$t->set_var( "category_id", $rfp->GetCategory( $SectionIDOverride ) );

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $published = $rfp->published();

        $t->set_var( "rfp_published", $locale->format( $published ) );    
    
        $t->parse( "rfp_item", "rfp_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "rfp_list_page_tpl" );


if ( count( $rfpList ) > 0 )    
    $t->parse( "rfp_list", "rfp_list_tpl" );
else
$t->set_var( "rfp_list", "" );

if ( $totalCount == 0 )
    $t->set_var( "rfp_start", 0 );
else
    $t->set_var( "rfp_start", $Offset + 1 );
$t->set_var( "rfp_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "rfp_total", $totalCount );

if ( isset ($SectionIDOverride) ) $t->set_var( "section_id", $SectionIDOverride );

$t->pparse( "output", "rfp_list_page_tpl" );

?>
