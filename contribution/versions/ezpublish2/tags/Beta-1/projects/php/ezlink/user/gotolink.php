<?
/*!
    $Id: gotolink.php,v 1.1 2000/10/19 09:32:09 ce-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: <14-Sep-2000 19:37:17 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

if ( $Action == "addhit" )
{
    $hit = new eZHit();
    $hit->setLink( $LinkID );
    $hit->setRemoteIP( $REMOTE_ADDR );
    $hit->store();
}

Header( "Location: http://" . $Url );

?>
