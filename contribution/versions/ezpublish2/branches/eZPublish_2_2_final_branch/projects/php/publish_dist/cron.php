<?
// 
// $Id: cron.php,v 1.14.2.3 2001/11/02 08:13:29 br Exp $
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

// Tell PHP where it can find our files.
if ( file_exists( "sitedir.ini" ) )
{
	include_once( "sitedir.ini" );

	if ( isset( $siteDir ) and !empty( $siteDir ) )
	{
		$includePath = ini_get( "include_path" );
		$includePath .= ":" . $siteDir;
		ini_set( "include_path", $includePath );
	}
}

// site information
include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;



// index articles
// uncomment to index all articles in publish
/*
set_time_limit( 0 );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlecategory.php" );

$article = new eZArticle();

$articles =& $article->getAll();

foreach ( $articles as $article )
{
    print( "indexing article: " .  $article->name() . "<br>\n" );    
    $article->createIndex();
}
*/

// index all form messages
// uncomment this section to index all old forum messages
/*
set_time_limit( 0 );
include_once( "ezforum/classes/ezforummessage.php" );

$message = new eZForumMessage();

$messages =& $message->getAll();

foreach ( $messages as $message )
{
    print( "indexing message: " .  $message->topic() . "<br>\n" );    
    $message->createIndex();
}
*/

// do session cleanup
include( "ezsession/admin/cron.php" );

// Time publishing
include( "ezarticle/admin/cron.php" );

// fetch the latest newsheadlines.
// include_once( "ezmail/classes/ezmail.php" );

// syncronize local files
// include( "ezfilemanager/admin/cron.php" );

// add bug report mails to eZBug
// include( "ezbug/admin/cron.php" );

// uncomment the next line to fetch news by cron

// include( "eznewsfeed/admin/cron.php" );

// Include statistic.
include( "ezstats/admin/cron.php" );


?>
