<?
// 
// $Id: useredit.php,v 1.14 2000/12/19 13:52:05 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "classes/ezmail.php" );
include_once( "classes/ezlog.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

require( "ezuser/admin/admincheck.php" );

if ( isSet( $Back ) )
{
    Header( "Location: /user/userlist/" );
    exit();
}

if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserAdd" ) )
    {
        if ( $Login != "" &&
        $Email != "" &&
        $FirstName != "" &&
        $LastName != "" )
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
            {
                $user = new eZUser();
                $user->setLogin( $Login );
                if ( !$user->exists( $user->login() ) )
                {
                    if ( eZMail::validate( $Email ) )
                    {
                        $user->setPassword( $Password );
                        $user->setEmail( $Email );
                        $user->setFirstName( $FirstName );
                        $user->setLastName( $LastName );

                        if ( $InfoSubscription == "on" )
                            $user->setInfoSubscription( true );
                        else
                            $user->setInfoSubscription( false );
                        
                        $user->store();
                        eZLog::writeNotice( "User created: $FirstName $LastName ($Login) $Email from IP: $REMOTE_ADDR" );
                        
                        // Add user to groups
                        if ( isset( $GroupArray ) )
                        {
                            $group = new eZUserGroup();
                            $user->get( $user->id() );
                            foreach ( $GroupArray as $GroupID )
                                {
                                    $group->get( $GroupID );
                                    $group->adduser( $user );
                                    $groupname = $group->name();
                                    eZLog::writeNotice( "User added to group: $groupname from IP: $REMOTE_ADDR" );     
                                }
                        }
                        Header( "Location: /user/userlist/" );
                        exit();
                    }
                    else
                    {
                        $error_msg = $error->read_var( "strings", "error_email" );
                    }
                }
                else
                {
                    $error_msg = $error->read_var( "strings", "error_user_exists" );
                }
                
            }
            else
            {
                $error_msg = $error->read_var( "strings", "error_password" );
            }
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}

if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserModify" ) )
    {
        if ( $Login != "" &&
        $Email != "" &&
        $FirstName != "" &&
        $LastName != "" )
        {
            if (  ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) ) ||
                  ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) == 0 ) ) )
            {
                $user->setLogin( $Login );
                {
                    if ( eZMail::validate( $Email ) )
                    {
                        $user = new eZUser();
                        $user->get( $UserID );
                        
                        $user->setEmail( $Email );

                        if ( $InfoSubscription == "on" )
                            $user->setInfoSubscription( true );
                        else
                            $user->setInfoSubscription( false );

                        $user->setFirstName( $FirstName );
                        $user->setLastName( $LastName );
                        
                        if ( strlen( $Password ) > 0 )
                        {
                            $user->setPassword( $Password );
                        }
                            
                        $user->store();
                        eZLog::writeNotice( "User updated: $FirstName $LastName ($Login) $Email from IP: $REMOTE_ADDR" );

                        // Remove user from groups
                        $user->removeGroups();
                        
                        // Add user to groups
                        if ( isset( $GroupArray ) )
                        {
                            $group = new eZUserGroup();
                            $user->get( $user->id() );
                            foreach ( $GroupArray as $GroupID )
                            {
                                $group->get( $GroupID );
                                $group->adduser( $user );
                                eZLog::writeNotice( "User added to group: $groupname from IP: $REMOTE_ADDR" );
                            }
                        }
                        Header( "Location: /user/userlist/" );
                        exit();
                    }
                    else
                    {
                        $error_msg = $error->read_var( "strings", "error_email" );
                    }
                }
                
            }
            else
            {
                $error_msg = $error->read_var( "strings", "error_password" );
            }
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
    $ActionValue = "update";
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserDelete" ) )
    {
        $user = new eZUser();
        $user->get( $UserID );
        $firstName = $user->firstName();
        $lastName = $user->lastName();
        $email = $user->email();
        $login = $user->login();
        $user->delete();
        
        eZLog::writeNotice( "User deleted: $firstname $lastname ($login) $email from IP: $REMOTE_ADDR" );
        Header( "Location: /user/userlist/" );
        exit();
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
 "ezuser/admin/" . "/intl", $Language, "useredit.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_edit" => "useredit.tpl"
     ) );

$t->set_block( "user_edit", "group_item_tpl", "group_item" );

if ( $Action == "new" )
{
    $FirstName = "";
    $Lastname = "";
    $Email = "";
    $Login = "";
}
$ActionValue = "insert";
if ( $Action == "update" )
{
    $ActionValue = "update";
}

$headline = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

$group = new eZUserGroup();

$groupList = $group->getAll();


$user = 0;
$t->set_var( "read_only", "" );
if ( $Action == "edit" )
{
    $user = new eZUser();
    $user->get( $UserID );

    if( $user->infoSubscription() == true )
        $InfoSubscription = "checked";
    else
        $InfoSubscription = "";
    
    $FirstName = $user->firstName();
    $LastName = $user->lastName();
    $Email = $user->email();
    $Login = $user->login();

    $headline = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );

    $t->set_var( "read_only", "readonly=readonly" );

    $ActionValue = "update";
}


foreach( $groupList as $groupItem )
{
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );

    // add validation code here. $user->isValid();
    if ( $user )
    {
        $groupArray = $user->groups();
        $found = false;
        foreach ( $groupArray as $group )
        {
            if ( $group->id() == $groupItem->id() )
            {
                $found = true;
            }
        }
        if ( $found  == true )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->parse( "group_item", "group_item_tpl", true );
}

$t->set_var( "info_subscription", $InfoSubscription );
$t->set_var( "error", $error_msg );
$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );
$t->set_var( "email_value", $Email );
$t->set_var( "login_value", $Login );
$t->set_var( "password_value", "" );
$t->set_var( "verify_password_value", "" );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "user_id", $UserID );

$t->pparse( "output", "user_edit" );

?>
