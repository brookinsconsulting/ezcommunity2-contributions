<?
/*!
    $Id: login.php,v 1.13 2000/10/17 14:16:49 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:37 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "../site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include( "ezphputils.php" );
include_once( "../classes/ezuser.php" );
include_once( "../classes/ezsession.php" );

$user = new eZUser( );

if ( $login )
{
    $tmp = $user->validateUser( $userid, $passwd);
    if ($tmp != 0)
    {
        $session = new eZSession();
        $session->setUserID( $tmp );
        $session->store();

        Header( "Location: ../index.php?page=$DOC_ROOT/main.php" );
//        printRedirect( "/index.php?page=$DOC_ROOT/main.php" );
    }
    else
    {
        Header( "Location: ../index.php?page=$DOC_ROOT/main.php&login=failed" );        
//        printRedirect( "/index.php?page=$DOC_ROOT/main.php&login=failed" );
    }
}
?>
