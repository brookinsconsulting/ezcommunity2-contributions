<?
/*!
    $Id: login.php,v 1.7 2000/08/28 13:48:03 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:37 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include( "ezphputils.php" );
include_once( $DOC_ROOT . "/classes/ezdb.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );

$user = new eZUser( );

if ( $login )
{
    $tmp = $user->validateUser( $userid, $passwd);
    if ($tmp != 0)
    {
        $session = new eZSession();
        $session->setUserID( $tmp );
        $session->store();

        printRedirect( "/index.php?page=$DOC_ROOT/main.php" );
    }
    else
    {
        printRedirect( "/index.php?page=$DOC_ROOT/main.php&login=failed" );
    }
}
?>
