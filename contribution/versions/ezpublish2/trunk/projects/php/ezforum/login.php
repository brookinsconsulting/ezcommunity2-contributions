<?
/*!
    $Id: login.php,v 1.3 2000/07/14 13:28:24 lw Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:37 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include( "ezphputils.php" );
include( "ezforum/dbsettings.php" );
include( "classes/ezdb.php" );
include( "classes/ezuser.php" );
include( "classes/ezsession.php" );

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
        //error message
        printRedirect( "/index.php?page=$DOCROOT/loginfailed.php" );
    }
}
?>
