<?
/*!
    $Id: logout.php,v 1.11 2000/10/17 13:44:44 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:45:59 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "$DOCROOT/classes/ezsession.php" );

eZSession::delete( $AuthenticatedSession );

printRedirect( "/index.php?page=$DOCROOT/main.php" );
?>
