<?
/*!
    $Id: login.php,v 1.5 2000/07/25 08:23:34 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:37 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( $DOCROOT . "/classes/ezdb.php" );
include( $DOCROOT . "/classes/ezuser.php" );
include( $DOCROOT . "/classes/ezsession.php" );

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
