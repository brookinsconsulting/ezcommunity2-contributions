<?
/*!
    $Id: logout.php,v 1.2 2000/07/14 13:31:20 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:45:59 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( "classes/ezsession.php" );

$session = new eZSession();
$session->delete( $AuthenticatedSession );
printRedirect( "index.php?page=$DOCROOT/main.php" );
?>
