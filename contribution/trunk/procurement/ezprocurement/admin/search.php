<?php
// 
// $Id: search.php,v 1.15 2001/09/16 18:37:39 bf Exp $
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
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZRfpMain", "Language" );
$Limit = $ini->read_var( "eZRfpMain", "AdminListLimit" );


if( isset( $Delete ) && count( $RfpArrayID ) > 0 )
{
    foreach( $RfpArrayID as $rfpID )
    {
        if( eZObjectPermission::hasPermission( $rfpID, "rfp_rfp", 'w' ) )
        {
            $rfp = new eZRfp( $rfpID );
            $rfp->delete();
        }
    }
}

$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "rfp_list_page_tpl" => "search.tpl"
    ) );

if ( !isset ( $Offset ) )
    $Offset = 0;

// rfp
$t->set_block( "rfp_list_page_tpl", "rfp_list_tpl", "rfp_list" );
$t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );
$t->set_block( "rfp_item_tpl", "rfp_is_published_tpl", "rfp_is_published" );
$t->set_block( "rfp_item_tpl", "rfp_not_published_tpl", "rfp_not_published" );
$t->set_block( "rfp_list_page_tpl", "rfp_delete_tpl", "rfp_delete" );

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


// BUILDING THE SEARCH
// If url parameters are present when loading page, they are decoded in the datasupplier
$paramsArray = array();

if( strlen($SearchText) == ''){
	$t->set_var( "search_text", 'Empty Search String' );
}

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
//        $paramsArray["AuthorID"] = $ContentsWriterID;
	$paramsArray["ContentsWriterID"] = $ContentsWriterID;
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

    $paramsArray["SearchExcludedRfps"] = "true";
    
    $rfp = new eZRfp();
    $totalCount = 0;

if ( $SearchText )
{

//    if( strlen($SearchText) != ''){
      $rfpList = $rfp->search( $SearchText, "time", true, $Offset, $Limit, $paramsArray, $totalCount );
 //   } else {
//	 $rfpList = $rfp->search( '', "time", true, $Offset, $Limit, $paramsArray, $totalCount );
//	}


    if( strlen($SearchText) != ''){
	$t->set_var( "search_text", $SearchText );
    }


    $t->set_var( "url_text", urlencode ( $SearchText ) );
}


if ( count ( $rfpList ) > 0 )
{
    $locale = new eZLocale( $Language );
    $i=0;
    $t->set_var( "rfp_list", "" );
    foreach ( $rfpList as $rfp )
    {
        $t->set_var( "rfp_name", $rfp->name() );

        $t->set_var( "rfp_id", $rfp->id() );

        if ( $rfp->isPublished() == true )
        {
            $t->parse( "rfp_is_published", "rfp_is_published_tpl" );
            $t->set_var( "rfp_not_published", "" );        
        }
        else
        {
            $t->set_var( "rfp_is_published", "" );
            $t->parse( "rfp_not_published", "rfp_not_published_tpl" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->parse( "rfp_item", "rfp_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "rfp_list_page_tpl" );

if ( count( $rfpList ) > 0 )
{
    $t->parse( "rfp_list", "rfp_list_tpl" );
    $t->parse( "rfp_delete", "rfp_delete_tpl" );
}
else
{
    $t->set_var( "rfp_list", "" );
    $t->set_var( "rfp_delete", "" );
}

$t->set_var( "rfp_start", $Offset + 1 );
$t->set_var( "rfp_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "rfp_total", $totalCount );

$t->pparse( "output", "rfp_list_page_tpl" );

?>
