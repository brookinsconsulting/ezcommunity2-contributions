<?
// 
// $Id: login.php,v 1.3 2000/10/03 10:11:07 ce-cvs Exp $
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


// Template
$t = new eZTemplate( $DOC_ROOT . "/admin/" .  $ini->read_var( "eZUserMain", "TemplateDir" ). "/login/",
                     $DOC_ROOT . "/admin/" . "/intl", $Language, "login.php" );
$t->setAllStrings();

$t->set_file( array(
    "login" => "login.tpl"
    ) );

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

    if ( !eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
    {
        print( "Har ikke adminlogin!" );
    }
    if ( !$user )
    {
        print( "Bruker finnes ikke!" );
    }
    if ( $user )
    {
        eZUser::loginUser( $user );
        Header( "Location: /user/success/" );
    }
}

if ( $Action == "logout" )
{
    eZUser::logout();
    Header( "Location: /" );
    exit();
}

$t->set_var( "action_value", "login" );
$t->pparse( "output", "login" );

?>
