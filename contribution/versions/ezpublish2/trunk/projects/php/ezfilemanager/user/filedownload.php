<?php
// 
// $Id: filedownload.php,v 1.19 2001/09/03 16:05:31 bf Exp $
//
// Created on: <10-Dec-2000 16:39:10 bf>
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



include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$user =& eZUser::currentUser();

$file = new eZVirtualFile( $FileID );

//if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) == false )
//{
//    eZHTTPTool::header( "Location: /error/403/" );
//    exit();
//}

$fileName = $file->name();
$originalFileName = $file->originalFileName();
$filePath = $file->filePath( true );

include_once( "ezstats/classes/ezpageview.php" );

if ( get_class( $GlobalPageView ) != "ezpageview" )
{
    $GlobalPageView = new eZPageView();
    $GlobalPageView->store();
}

$originalFileName = str_replace( " ", "%20", $originalFileName );

// store the statistics
$file->addPageView( $GlobalPageView );
 
$filePath = preg_replace( "#.*/(.*)#", "\\1", $filePath );
 
//  print( $filePath );

//print( "Location: /filemanager/filedownload/$filePath/$originalFileName"  );
//exit();


// $host = $SERVER_NAME;
// $location = "Location: http://" . $SERVER_NAME . ":" . $SERVER_PORT . "/" . $wwwDir . $index . "filemanager/filedownload/$filePath/$originalFileName";


// print( $location );
// Header( $location );

// Rewrote to be compatible with virtualhost-less install
$size = eZFile::filesize( "ezfilemanager/files/$filePath" );

$nameParts = explode( ".", $originalFileName  );
$suffix = $nameParts[count( $nameParts ) - 1];

// clear what might be in the output buffer and stop the buffer.
ob_end_clean();

switch ( $suffix )
{
    case "doc" :
        header( "Content-Type: application/msword" );
        break;
    case "ppt" :
        header( "Content-Type: application/vnd.ms-powerpoint" );
        break;
    case "xls" :
        header( "Content-Type: application/vnd.ms-excel" );
        break;    
    case "pdf" :
        header( "Content-Type: application/pdf" );
        break;
    default :        
        header( "Content-Type: application/octet-stream" );
        break;
}

header( "Content-Length: $size" );
header( "Content-Disposition: attachment; filename=$originalFileName" );
header( "Content-Transfer-Encoding: binary" );

$fh = eZFile::fopen( "ezfilemanager/files/$filePath", "r" );
fpassthru( $fh );
exit();
?>
