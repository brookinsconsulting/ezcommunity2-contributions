<?
/*!
    $Id: gotolink.php,v 1.6 2000/09/01 13:29:00 ce-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*!
  Legger inn hits og redirecter til korrekt side.
*/


include_once( "classes/class.INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "classes/template.inc" );
include_once( "common/ezphputils.php" );

require $DOC_ROOT . "classes/ezlinkgroup.php";
require $DOC_ROOT . "classes/ezlink.php";
require $DOC_ROOT . "classes/ezhit.php";


//print( $HTTP_REFERER );

if ( $Action == "addhit" )
{
    $hit = new eZHit();
    $hit->setLink( $LID );
    $hit->setRemoteIP( $REMOTE_ADDR );
    $hit->store();
}

printRedirect( "http://" . $Url );



?>
