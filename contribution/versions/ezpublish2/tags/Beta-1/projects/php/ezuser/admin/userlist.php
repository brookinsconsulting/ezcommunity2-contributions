<?
// 
// $Id: userlist.php,v 1.7 2000/10/16 12:42:56 ce-cvs Exp $
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
    "user_list_page" => "userlist.tpl"
      ) );


$t->set_block( "user_list_page", "user_item_tpl", "user_item" );

$t->set_block( "user_list_page", "group_item_tpl", "group_item" );

$user = new eZUser();

if ( $GroupID == 0 )
{
    $userList = $user->getAll();
}
else
{
    $usergroup = new eZUserGroup();
    $userList = $usergroup->users( $GroupID );
}


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
    $t->set_var( "email", $userItem->email() );
    $t->set_var( "user_id", $userItem->id() );

    $t->parse( "user_item", "user_item_tpl", true );
    $i++;
}

$group = new eZUserGroup();
$groupList = $group->getAll();

foreach( $groupList as $groupItem )
{

//  print( $GroupID . " " . $groupItem->id() . "<br>" );
   
    if ( $groupItem->id() == $GroupID )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );

    $t->parse( "group_item", "group_item_tpl", true );
}

$t->pparse( "output", "user_list_page" );

?>
