<?
/*!
    $Id: logout.php,v 1.12 2000/10/17 14:16:49 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:45:59 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "common/ezphputils.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );

$session = new eZSession();
$session->delete( $AuthenticatedSession );

printRedirect( "/index.php?page=$DOC_ROOT/main.php" );
?>
