<?php
//
// $Id: useredit.php,v 1.38 2001/12/10 07:49:52 ce Exp $
//
// Created on: <20-Sep-2000 13:32:11 ce>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "ezmail/classes/ezmail.php" );
include_once( "classes/ezlog.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/eztitle.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezuseradditional.php" );

require( "ezuser/admin/admincheck.php" );

if ( isSet ( $DeleteUsers ) )
{
    $Action = "DeleteUsers";
}

if ( isSet( $Back ) )
{
    eZHTTPTool::header( "Location: /user/userlist/" );
    exit();
}

// do not allow editing users with root access while you do not.
$currentUser = eZUser::currentUser();
if( isset( $UserID ) )
{
    $editUser = new eZUser( $UserID );
    if( !$currentUser->hasRootAccess() && $editUser->hasRootAccess() )
    {
        $info = urlencode( "Can't edit a user with root priveliges." );
        eZHTTPTool::header( "Location: /error/403?Info=$info" );
        exit();
    }
}

if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserAdd" ) )
    {
        if ( $Login != "" &&
             $Email != "" &&
             $FirstName != "" &&
             $LastName != "" &&
             $SimultaneousLogins != "")
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
            {
                $user = new eZUser();
                $user->setLogin( $Login );
                if ( !$user->exists( $user->login() ) )
                {
                    $tmp[0] = $Email;
                    if ( eZMail::validate( $tmp[0] ) )
                    {
                        $user->setPassword( $Password );
                        $user->setEmail( $Email );
                        $user->setFirstName( $FirstName );
                        $user->setLastName( $LastName );
                        $user->setSignature( $Signature );
                        $user->setSimultaneousLogins( $SimultaneousLogins );

                        if ( $InfoSubscription == "on" )
                            $user->setInfoSubscription( true );
                        else
                            $user->setInfoSubscription( false );

                        if ( $DisabledAccount == "on" )
                            $user->setIsActive( false );
                        else
                            $user->setIsActive( true );

                        if ( $NoExpiryDate == "on" )
                            $user->setExpiryDate( 0 );
                        else
                            $user->setExpiryDate( new eZDateTime( $Year, $Month, $Day, 23, 59, 59 ) );

                        $user->store();

                        // Additional fields
                        if ( count ( $AdditionalArrayID ) > 0 )
                        {
                            $i=0;
                            sort( $AdditionalArrayID );
                            foreach( $AdditionalArrayID as $AdditionalID )
                            {
                                $additional = new eZUserAdditional( $AdditionalID );
                                $additional->addValue( $user, $AdditionalValue[$i] );
                                $i++;
                            }
                        }

                        // set title
                        $title = new eZTitle( );
                        if ( $title->get( $TitleID ) )
                        {
                            $user->setTitle( $title );
                        }

                        eZLog::writeNotice( "User created: $FirstName $LastName ($Login) $Email $SimultaneousLogins  from IP: $REMOTE_ADDR" );

                        // Add user to groups
                        $GroupArray = array_unique( array_merge( $GroupArray, $MainGroup ) );
                        $group = new eZUserGroup();
                        $user->get( $user->id() );
                        $user->removeGroups();
                        foreach ( $GroupArray as $GroupID )
                        {
                            $group = new eZUserGroup();
//                            $user->get( $user->id() );
//                            $user->removeGroups();
                            $group->get( $GroupID );
                            if ( ( $group->isRoot() && $currentUser->hasRootAccess() ) || !$group->isRoot() )
                            {
                                $group->adduser( $user );
                                $groupname = $group->name();
                                eZLog::writeNotice( "User added to group: $groupname from IP: $REMOTE_ADDR" );
                            }
                        }

                        $user->setGroupDefinition( $MainGroup );

                        eZHTTPTool::header( "Location: /user/userlist/" );
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
        $LastName != "" &&
        $SimultaneousLogins != "")
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
                        $user->setSignature( $Signature );

                        if ( $InfoSubscription == "on" )
                            $user->setInfoSubscription( true );
                        else
                            $user->setInfoSubscription( false );

                        if ( $DisabledAccount == "on" )
                            $user->setIsActive( false );
                        else
                            $user->setIsActive( true );

                        if ( $NoExpiryDate == "on" )
                            $user->setExpiryDate( 0 );
                        else
                            $user->setExpiryDate( new eZDateTime( $Year, $Month, $Day, 23, 59, 59 ) );

                        $user->setFirstName( $FirstName );
                        $user->setLastName( $LastName );

                        $user->setSimultaneousLogins( $SimultaneousLogins );

                        if ( strlen( $Password ) > 0 )
                        {
                            $user->setPassword( $Password );
                        }

                        $user->store();

                        // Additional fields
                        if ( count ( $AdditionalArrayID ) > 0 )
                        {
                            $i=0;
                            sort( $AdditionalArrayID );
                            foreach( $AdditionalArrayID as $AdditionalID )
                            {
                                $additional = new eZUserAdditional( $AdditionalID );
                                $additional->addValue( $user, $AdditionalValue[$i] );
                                $i++;
                            }
                        }

                        // set title
                        $title = new eZTitle( );
                        if ( $title->get( $TitleID ) )
                        {
                            $user->setTitle( $title );
                        }

                        eZLog::writeNotice( "User updated: $FirstName $LastName ($Login) $Email from IP: $REMOTE_ADDR" );

                        // Remove user from groups
                        $user->removeGroups();

                        // Add user to groups
                        $GroupArray = array_unique( array_merge( $GroupArray, $MainGroup ) );
                        $group = new eZUserGroup();
                        $user->get( $user->id() );
                        $user->removeGroups();
                        foreach ( $GroupArray as $GroupID )
                        {
                            $group = new eZUserGroup();
//                            $user->get( $user->id() );
//                            $user->removeGroups();
                            $group->get( $GroupID );
//                            if ( ( $group->isRoot() && $currentUser->hasRootAccess() ) || !$group->isRoot() )
                            {
                                $group->adduser( $user );
                                $groupname = $group->name();
                                eZLog::writeNotice( "User added to group: $groupname from IP: $REMOTE_ADDR" );
                            }
                        }

                        $user->setGroupDefinition( $MainGroup );
                        eZHTTPTool::header( "Location: /user/userlist/" );
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
        $simultaneousLogins = $user->simultaneousLogins();

        $user->delete();

        eZLog::writeNotice( "User deleted: $firstname $lastname ($login) $email $simultaneousLogins from IP: $REMOTE_ADDR" );
        eZHTTPTool::header( "Location: /user/userlist/" );
        exit();
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}
$currentUser = eZUser::currentUser();
if ( $Action == "DeleteUsers" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserDelete" ) )
    {
        if ( count ( $UserArrayID ) != 0 )
        {
            foreach( $UserArrayID as $UserID )
            {
                $user = new eZUser( $UserID );
                $login = $user->login();
                if( $user->hasRootAccess() && !$currentUser->hasRootAccess() )
                {
                    $currentLogin = $currentUser->login();
                    eZLog::writeNotice( "$currentLogin failed to delete user $login since he can't delete users with root privelidges." );
                }
                else
                {
                    $firstName = $user->firstName();
                    $lastName = $user->lastName();
                    $email = $user->email();
                    $login = $user->login();
                    $simultaneousLogins = $user->simultaneousLogins();

                    $user->delete();

                    eZLog::writeNotice( "User deleted: $firstname $lastname ($login) $email $simultaneousLogins from IP: $REMOTE_ADDR" );
                }
            }
        }
    }
    eZHTTPTool::header( "Location: /user/userlist/" );
    exit();
}

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ), "ezuser/admin/" . "/intl", $Language, "useredit.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_edit" => "useredit.tpl"
     ) );

$t->set_block( "user_edit", "title_item_tpl", "title_item" );
$t->set_block( "user_edit", "main_group_item_tpl", "main_group_item" );
$t->set_block( "user_edit", "group_item_tpl", "group_item" );
$t->set_block( "user_edit", "day_item_tpl", "day_item" );
$t->set_block( "user_edit", "month_item_tpl", "month_item" );

$t->set_block( "user_edit", "additional_text_item_tpl", "additional_text_item" );
$t->set_block( "user_edit", "additional_radio_item_tpl", "additional_radio_item" );
$t->set_block( "user_edit", "additional_item_tpl", "additional_item" );
$t->set_block( "additional_radio_item_tpl", "fixed_values_tpl", "fixed_values" );


if ( $Action == "new" )
{
    $FirstName = "";
    $Lastname = "";
    $Email = "";
    $Login = "";
    $SimultaneousLogins = $ini->read_var( "eZUserMain", "DefaultSimultaneousLogins" );
}

$ActionValue = "insert";

if ( $Action == "update" )
{
    $ActionValue = "update";
}

$headline = new INIFile( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

$group = new eZUserGroup();

$groupList = $group->getAll();


$user = 0;
$t->set_var( "read_only", "" );
if ( $Action == "edit" )
{
    $user = new eZUser();
    $user->get( $UserID );

    if ( $user->infoSubscription() == true )
        $InfoSubscription = "checked";
    else
        $InfoSubscription = "";

    $FirstName = $user->firstName();
    $LastName = $user->lastName();
    $Email = $user->email();
    $Login = $user->login();
    $Signature = $user->signature();
    $SimultaneousLogins = $user->simultaneousLogins();
    $currentTitleID = $user->title( false );
    if ( $user->isActive() )
        $disabledAccount = "";
    else
        $disabledAccount = "checked";

    $expiryDate = $user->expiryDate();
    if ( $expiryDate->timeStamp() == 0 )
    {
        $noExpiry = "checked";
        $expiryDate->setTimeStamp( eZDateTime::timeStamp( true ) );
    }
    else
    {
        $noExpiry = "";
    }

    $headline = new INIFile( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );

    $t->set_var( "read_only", "readonly=readonly" );

    $ActionValue = "update";
}
else // either new or failed edit... must put htmlspecialchars on stuff we got from form.
{
    $FirstName = htmlspecialchars( $FirstName );
    $LastName = htmlspecialchars( $LastName );
    $Login = htmlspecialchars( $Login );
    $Signature = htmlspecialchars( $Signature );
    $Email = htmlspecialchars( $Email );
    $user =& eZUser::currentUser();
    $expiryDate = new eZDateTime( $Year, $Month, $Day, 23, 59, 59 );

    if ( $NoExpiryDate == "on" )
        $noExpiry = "selected";
    else
        $noExpiry = "";

    if ( $DisabledAccount == "on" )
        $disabledAccount = "selected";
    else
        $disabledAccount = "";
}

// show titles
$title = new eZTitle();
$titleArray =& $title->getAll();

foreach ( $titleArray as $title )
{
    if ( $title->id() == $currentTitleID )
        $t->set_var( "title_checked", "checked" );
    else
        $t->set_var( "title_checked", "" );

    $t->set_var( "title_id", $title->id() );
    $t->set_var( "title_name", $title->name() );

    $t->parse( "title_item", "title_item_tpl", true );
}

// Show additional fields
$additionalList =& eZUserAdditional::getAll();
$i = 0;
if ( count ( $additionalList ) > 0 )
{
    $t->set_var( "additional_text_item", "" );
    $t->set_var( "additional_radio_item", "" );
    foreach( $additionalList as $additional )
    {
        $t->set_var( "additional_name", $additional->name() );
        $t->set_var( "additional_id", $additional->id() );
        $t->set_var( "additional_value", $additional->value( $user ) );

        $t->set_var( "i", $i );

        if ( $additional->type() == 1 )
        {
            $t->parse( "additional_item_tpl", "additional_text_item_tpl", true );
        }
        elseif ( $additional->type() == 2 )
        {
            $fixedValues =& $additional->fixedValues();
            foreach( $fixedValues as $value )
            {
                $t->set_var( "value_id", $value["ID"] );
                $t->set_var( "value", $value["Value"] );

                if ( $value["ID"] == $additional->value( $user ) )
                    $t->set_var( "radio_checked", "checked" );
                else
                    $t->set_var( "radio_checked", "" );

                $t->parse( "fixed_values", "fixed_values_tpl", true );
            }
            $t->parse( "additional_item_tpl", "additional_radio_item_tpl", true );
        }
        $i++;
    }
    $t->parse( "additional_item", "additional_item_tpl" );
}
else
{
    $t->set_var( "additional_radio_item", "" );
    $t->set_var( "additional_text_item", "" );
}

$mainGroup = $user->groupDefinition();
$groupArray = $user->groups();
foreach ( $groupList as $groupItem )
{
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );

    if ( $mainGroup == $groupItem->id() )
        $t->set_var( "main_selected", "selected" );
    else
        $t->set_var( "main_selected", "" );

    // add validation code here. $user->isValid();
    if ( $user )
    {
        $found = false;
        foreach ( $groupArray as $group )
        {
            if ( $group->id() == $groupItem->id() && $group->id() != $mainGroup )
            {
                $found = true;
            }
        }
        if ( $found == true )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->parse( "main_group_item", "main_group_item_tpl", true );
    $t->parse( "group_item", "group_item_tpl", true );
}

$t->set_var( "info_subscription", $InfoSubscription );
$t->set_var( "error", $error_msg );
$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );
$t->set_var( "email_value", $Email );
$t->set_var( "login_value", $Login );
$t->set_var( "signature", $Signature );
$t->set_var( "password_value", "" );
$t->set_var( "verify_password_value", "" );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "user_id", $UserID );
$t->set_var( "simultaneouslogins_value", $SimultaneousLogins );
$t->set_var( "no_expiry", $noExpiry );
$t->set_var( "disabled_account", $disabledAccount );

$locale = new eZLocale( $Language );

for ( $i = 1; $i <= 31; $i++ )
{
    $t->set_var( "day", $i );
    $t->set_var( "day_selected", $expiryDate->day() == $i ? "selected" : "" );
    $t->parse( "day_item", "day_item_tpl", true );
}

for ( $i = 1; $i <= 12; $i++ )
{
    $t->set_var( "month_name", $locale->monthName( $i, false ) );
    $t->set_var( "month_id", $i );
    $t->set_var( "month_selected", $expiryDate->month() == $i ? "selected" : "" );
    $t->parse( "month_item", "month_item_tpl", true );
}

$t->set_var( "year", $expiryDate->year() );

$t->pparse( "output", "user_edit" );

?>
