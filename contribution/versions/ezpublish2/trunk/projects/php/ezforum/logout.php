<?
/*!
    $Id: logout.php,v 1.6 2000/08/28 14:00:22 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:45:59 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "ezphputils.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );

$user = new eZUser();
$user->delete( $AuthenticatedSession );

printRedirect( "/index.php?page=$DOC_ROOT/main.php" );
?>
