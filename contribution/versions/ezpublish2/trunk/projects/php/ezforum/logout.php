<?
/*!
    $Id: logout.php,v 1.10 2000/09/07 15:44:44 bf-cvs Exp $

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
