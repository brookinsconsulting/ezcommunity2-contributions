<?
// 
// $Id: login.php,v 1.8 2000/10/25 19:03:06 bf-cvs Exp $
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
include_once( "classes/ezlog.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( "ezuser/admin/" .  $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/" . "/intl", $Language, "login.php" );
$t->setAllStrings();

$t->set_file( array(
    "login_tpl" => "login.tpl"
    ) );

$t->set_block( "login_tpl", "error_message_tpl", "error_message" );
    
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}
if ( $Action == "login" )
{
    $user = new eZUser();
    $user = $user->validateUser( $Username, $Password );

    if ( ( $user )  && eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ))
    {
        eZLog::writeNotice( "Admin login: $Username from IP: $REMOTE_ADDR" );

        eZUser::loginUser( $user );
        Header( "Location: /" );
    }
    else
    {
        eZLog::writeWarning( "Bad admin  login: $Username from IP: $REMOTE_ADDR" );
        
        $error = true;
    }
}

if ( $Action == "logout" )
{
    eZUser::logout();
    Header( "Location: /" );
    exit();
}

if ( $error )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}


$t->set_var( "action_value", "login" );
$t->pparse( "output", "login_tpl" );

?>
