<?
/*!
    $Id: gotolink.php,v 1.5 2000/08/14 09:52:33 bf-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*!
  Legger inn hits og redirecter til korrekt side.
*/


include_once( "class.INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "template.inc" );
include_once( "ezphputils.php" );

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
