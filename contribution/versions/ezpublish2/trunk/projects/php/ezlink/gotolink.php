<?
/*!
    $Id: gotolink.php,v 1.8 2000/09/14 18:04:47 bf-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: <14-Sep-2000 19:37:17 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*!
  Legger inn hits og redirecter til korrekt side.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "classes/template.inc" );

include_once( $DOC_ROOT . "classes/ezlinkgroup.php" );
include_once( $DOC_ROOT . "classes/ezlink.php" );
include_once( $DOC_ROOT . "classes/ezhit.php" );


//print( $HTTP_REFERER );

if ( $Action == "addhit" )
{
    $hit = new eZHit();
    $hit->setLink( $LID );
    $hit->setRemoteIP( $REMOTE_ADDR );
    $hit->store();
}

Header( "Location: http://" . $Url );

?>
