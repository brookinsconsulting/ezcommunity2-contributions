<?php
// 
// $Id: fetchimapattachment.php,v 1.1 2002/01/20 17:14:06 fh Exp $
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

//TODO: This needs to be tested with nvhost setup.
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezmail/classes/ezimapmail.php" );

$user =& eZUser::currentUser();

//$size = eZFile::filesize( "ezfilemanager/files/$filePath" );
//$AttachmentID = $url_array[3];
// first we need to disect the AttachmentID (account, mail, part,  folder )
$elements = explode( "-", $AttachmentID, 5 ); // max 1 split rest is foldername.
$accountID = $elements[0];
$mailID = $elements[1];
$partID = $elements[2];
$encoding = $elements[3];
$folderID = $elements[4];

$file = eZImapMail::fetchAttachment( $accountID, $mailID, $partID, $encoding ,$folderID );
$size = strlen( $file );

$nameParts = explode( ".", $FileName  );
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
    
//$fh = eZFile::fopen( "ezfilemanager/files/$filePath", "r" );
//fpassthru( $fh );
echo $file;
exit();

?>
