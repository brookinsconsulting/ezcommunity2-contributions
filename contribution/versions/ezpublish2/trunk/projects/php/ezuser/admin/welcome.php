<?
// 
// $Id: welcome.php,v 1.1 2000/11/13 12:03:41 bf-cvs Exp $
//
// Christoffer A. Elo <bf@ez.no>
// Created on: <13-Nov-2000 10:57:15 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/admin/intl", $Language, "welcome.php" );
$t->setAllStrings();

$t->set_file( array(
    "welcome_tpl" => "welcome.tpl"
    ) );

$user = eZUser::currentUser();

$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );

$t->pparse( "output", "welcome_tpl" );

?>
