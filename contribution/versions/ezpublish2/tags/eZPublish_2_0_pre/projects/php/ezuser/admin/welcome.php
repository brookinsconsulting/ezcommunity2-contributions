<?
// 
// $Id: welcome.php,v 1.6 2001/03/01 14:06:26 jb Exp $
//
// Christoffer A. Elo <bf@ez.no>
// Created on: <13-Nov-2000 10:57:15 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl", $Language, "welcome.php" );
$t->setAllStrings();

$t->set_file( array(
    "welcome_tpl" => "welcome.tpl"
    ) );

$user = eZUser::currentUser();

if ( $user )
{
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
} 

$t->pparse( "output", "welcome_tpl" );

?>
