<?
// 
// $Id: storestats.php,v 1.1 2001/04/26 09:03:53 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Apr-2001 10:39:18 bf>
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
ob_start( );

// get right path 
chdir( "../../" );

$REQUEST_URI = preg_replace( "#/stats/store(.*?)1x1.gif$#", "\\1", $REQUEST_URI );

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

// create a global page view object for statistics
// and store the stats
$GlobalPageView = new eZPageView();
$GlobalPageView->store();

ob_end_clean();

//  # the file may be a local file with full path.
$filePath = "images/1x1.gif";
$fileSize = filesize( $filePath );
$fp = fopen( $filePath, "r" );
$content =& fread( $fp, $fileSize );

$originalFileName = "1x1.gif";

Header("Content-type: image/gif"); 
Header("Content-length: $fileSize"); 
Header("Content-disposition: attachment; filename=\"$originalFileName\"");

echo($content);
exit();
?>
