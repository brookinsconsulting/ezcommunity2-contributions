<?php
// 
// $Id: newsarchive.php,v 1.3 2000/11/16 11:04:46 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Nov-2000 16:56:48 bf>
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
include_once( "eznewsfeed/classes/eznewsimporter.php" );

include_once( "classes/ezdatetime.php" );

$news = new eZNews( );

$news->setName( "News item" );
$news->setIntro( "more text" );
$news->setIsPublished( true );

$news->setKeywords( "one two" );
$news->setOrigin( "from here" );
$news->setURL( "http://ez.no" );
$dateTime = new eZDateTime( 2000, 11, 13, 14, 0, 15 );
$news->setOriginalPublishingDate( $dateTime );

//  $news->store();

$newsList = $news->newsList();

foreach ( $newsList as $newsItem )
{
    $intro = $newsItem->intro();
    
    print( "<h2>". $newsItem->name() . "</h2><p>" . $newsItem->intro() . "</p>" );
}

$newsImporter = new eZNewsImporter( "nyheter.no" );
$newsImporter->importNews();

//  $newsImporter = new eZNewsImporter( "freshmeat.net" );
//  $newsImporter->importNews();

?>
