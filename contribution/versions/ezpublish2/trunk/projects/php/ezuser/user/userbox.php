<?
// 
// $Id: userbox.php,v 1.1 2000/10/25 07:59:56 ce-cvs Exp $
//
// Definition of eZUser class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
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
$DOC_ROOT = $ini->read_var( "eZUserMain", "DocumentRoot" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );

$user = eZUser::currentUser();
if ( !$user ) 
{
    $t = new eZTemplate( "ezuser/user/" .  $ini->read_var( "eZUserMain", "TemplateDir" ),
    "ezuser/user/intl", $Language, "login.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "login" => "login.tpl"
        ) );

    $t->set_var( "action_value", "login" );
    $t->pparse( "output", "login" );
    
}
else
{
    $t = new eZTemplate( "ezuser/user/" .  $ini->read_var( "eZUserMain", "TemplateDir" ),
    "ezuser/user/intl", $Language, "userbox.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "userbox" => "userbox.tpl"
        ) );

    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    $t->set_var( "user_id", $user->id() );
    $t->set_var( "style", $SiteStyle );

    $t->pparse( "output", "userbox" );
} 

?>
