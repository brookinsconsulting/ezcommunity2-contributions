<?
/*!
    $Id: gotolink.php,v 1.9 2000/10/10 07:01:09 ce-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: <14-Sep-2000 19:37:17 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( $DOC_ROOT . "classes/ezlinkgroup.php" );
include_once( $DOC_ROOT . "classes/ezlink.php" );
include_once( $DOC_ROOT . "classes/ezhit.php" );

if ( $Action == "addhit" )
{
    $hit = new eZHit();
    $hit->setLink( $LID );
    $hit->setRemoteIP( $REMOTE_ADDR );
    $hit->store();
}

Header( "Location: http://" . $Url );

?>
