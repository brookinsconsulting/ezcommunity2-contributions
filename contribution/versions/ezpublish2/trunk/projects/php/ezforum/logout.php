<?
/*!
    $Id: logout.php,v 1.4 2000/07/24 14:24:14 lw Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:45:59 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( "$DOCROOT/classes/ezsession.php" );

$session = new eZSession();
$session->delete( $AuthenticatedSession );
printRedirect( "/index.php?page=$DOCROOT/main.php" );
?>
