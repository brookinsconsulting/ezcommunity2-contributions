<?
// 
// $Id: success.php,v 1.5 2001/03/01 14:06:26 jb Exp $
//
// Definition of eZUser class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZUserMain", "DocumentRoot" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( $DOC_ROOT . $ini->read_var( "eZUserMain", "TemplateDir" ). "/login/",
                     $DOC_ROOT . "/intl", $Language, "success.php" );
$t->setAllStrings();

$t->set_file( array(
    "login" => "succsess.tpl"
    ) );

$user = eZUser::currentUser();
if ( !$user ) 
{
    print( "Du må logge inn" );
    eZHTTPTool::header( "Location: /user/login/" );
    exit();
}

eZHTTPTool::header( "Location: /" );

$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );

$t->set_var( "action_value", "login" );
$t->pparse( "output", "login" );

?>
