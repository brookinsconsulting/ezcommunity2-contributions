<?
/*!
    $Id: login.php,v 1.6 2000/07/27 08:05:33 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:37 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include_once( $DOCROOT . "/classes/ezdb.php" );
include_once( $DOCROOT . "/classes/ezuser.php" );
include_once( $DOCROOT . "/classes/ezsession.php" );

$user = new eZUser( );

if ( $login )
{
    $tmp = $user->validateUser( $userid, $passwd);
    if ($tmp != 0)
    {
        $session = new eZSession();
        $session->setUserID( $tmp );
        $session->store();

        printRedirect( "/index.php?page=$DOCROOT/main.php" );
    }
    else
    {
        printRedirect( "/index.php?page=$DOCROOT/main.php&login=failed" );
    }
}
?>
