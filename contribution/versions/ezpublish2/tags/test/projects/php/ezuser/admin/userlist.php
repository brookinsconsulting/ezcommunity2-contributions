<?
// 
// $Id: userlist.php,v 1.4 2000/10/08 13:07:11 bf-cvs Exp $
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

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZUserMain", "TemplateDir" ) . "/userlist/",
                     $DOC_ROOT . "/admin/" . "/intl", $Language, "userlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_list_page" => "userlist.tpl",
    "user_item" => "useritem.tpl"
    ) );

$user = new eZUser();

$userList = $user->getAll();

$i=0;
foreach( $userList as $userItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    
    $t->set_var( "first_name", $userItem->firstName() );
    $t->set_var( "last_name", $userItem->lastName() );
    $t->set_var( "login_name", $userItem->login() );
    $t->set_var( "user_id", $userItem->id() );

    $t->parse( "user_list", "user_item", true );
    $i++;
}

$t->pparse( "output", "user_list_page" );

?>
