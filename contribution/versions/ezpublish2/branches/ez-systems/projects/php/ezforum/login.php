 <?OC
/*!
    $Id: login.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:37 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include( "ezphputils.php" );
include( "ezforum/dbsettings.php" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );
include( "$DOCROOT/classes/ezsession.php" );

$user = new eZUser( );

if ( $login )
{
    $tmp = $user->validateUser( $userid, $passwd);
    if ($tmp != 0)
    {
        $session = new eZSession();
        $session->setUserID( $tmp );
        $session->store();

        printRedirect( $DOCROOT . "/index.php?page=main.php" );
    }
    else
    {
        //error message
        printRedirect( $DOCROOT . "/index.php?page=loginfailed.php" );
    }
}
?>
