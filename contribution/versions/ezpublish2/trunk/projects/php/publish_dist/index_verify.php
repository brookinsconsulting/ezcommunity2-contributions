<?php
// 
// $Id: index_verify.php,v 1.3 2001/07/29 23:30:57 kaid Exp $
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
if ( isset( $siteDir ) and $siteDir != "" )
{
    $includePath = ini_get( "include_path" );
    $includePath .= ":" . $siteDir;
    ini_set( "include_path", $includePath );
}
	
ob_end_clean();
// script to check if the site is alive
// this script will return 42 if the server is alive
// it will return 13 if not

include_once( "classes/ezdb.php" );

$db =& eZDB::globalDatabase();
$db->query_single( $session_array, "SELECT count( ID ) AS Count FROM eZSession_Session" );
$db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User" );
if ( $user_array["Count"] > 0 )
    print( "42" );
else
    print( "13" );

exit();
?>
