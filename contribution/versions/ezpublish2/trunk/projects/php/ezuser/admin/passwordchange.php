<?
// 
// $Id: passwordchange.php,v 1.2 2000/10/06 09:59:15 ce-cvs Exp $
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

require( "ezuser/admin/admincheck.php" );

// Template
$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZUserMain", "TemplateDir" ). "/passwordchange/",
                     $DOC_ROOT . "/admin/" . "/intl", $Language, "passwordchange.php" );
$t->setAllStrings();

$t->set_file( array(
    "change" => "change.tpl"
    ) );

$user = eZUser::currentUser();
if ( !$user ) 
{
    print( "Du må logge inn" );
    Header( "Location: /user/login/" );
    exit();
}

if ( $Action == "update" )
{
    $checkuser = $user->validateUser( $user->login(), $OldPassword );
    if ( !$checkuser )
    {
        print( "Gammelt passord var feil." );
        
    }
    else if ( $NewPassword != $VerifyPassword )
    {
        print( "Passordene machet ikke." );

    }
    else if ( $OldPassord == $NewPassord )
    {
        $user->setPassword( $NewPassword );
        $user->store();
        print( "Passordet ble oppgradert!" );

    }
    
}
if ( $Action == "change" )
{
    
    
}

$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );

$t->set_var( "action_value", "update" );
$t->pparse( "output", "change" );

?>
