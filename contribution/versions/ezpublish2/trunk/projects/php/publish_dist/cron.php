<?
// 
// $Id: cron.php,v 1.9 2001/07/25 12:19:15 bf Exp $
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

// do session cleanup
include( "ezsession/admin/cron.php" );

// Time publishing
include( "ezarticle/admin/cron.php" );

// fetch the latest newsheadlines.
include_once( "ezmail/classes/ezmail.php" );

// uncomment the next line to fetch news by cron

// include( "eznewsfeed/admin/cron.php" );


?>
