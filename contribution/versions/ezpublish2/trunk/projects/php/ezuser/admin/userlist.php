<?
// 
// $Id: userlist.php,v 1.18 2001/01/22 14:43:02 jb Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZUserMain", "Language" );
$errorIni = new INIFIle( "ezuser/admin/intl/" . $Language . "/userlist.php.ini", false );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/" . "/intl", $Language, "userlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_list_page" => "userlist.tpl"
      ) );


$t->set_block( "user_list_page", "user_item_tpl", "user_item" );

$t->set_block( "user_list_page", "group_item_tpl", "group_item" );

$user = new eZUser();

if ( $GroupID == 0 )
{
    $userList = $user->getAll( $OrderBy );
}
else
{
    $usergroup = new eZUserGroup();
    $userList = $usergroup->users( $GroupID );
}

$t->set_var( "user_count", count( $userList ) );

if ( count ( $userList ) == 0 )
{
    $error = $errorIni->read_var( "strings", "no_users" );
    $t->set_var( "user_item", $error );
}
else
{
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
        
//      if ( $userItem->infoSubscription( ) == true )
//      {
//          print( $userItem->email() . "<br>" );
//      }
        
        $t->parse( "user_item", "user_item_tpl", true );
        $i++;
    }
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

$t->set_var( "current_group_id", $GroupID );
$t->set_var( "sort_order", $OrderBy );

$t->pparse( "output", "user_list_page" );

?>
