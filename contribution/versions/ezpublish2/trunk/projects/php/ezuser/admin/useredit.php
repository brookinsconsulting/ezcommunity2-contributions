<?
// 
// $Id: useredit.php,v 1.4 2000/10/08 13:07:11 bf-cvs Exp $
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

if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserAdd" ) )
    {
        if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
        {
            $user = new eZUser();

            if ( !$user->exists( $user->login() ) )
            {
                $user->setLogin( $Login );
                $user->setPassword( $Password );
                $user->setEmail( $Email );
                $user->setFirstName( $FirstName );
                $user->setLastName( $LastName );

                $user->store();
                
                // Add user to groups
                if ( isset( $GroupArray ) )
                {
                    $group = new eZUserGroup();
                    $user->get( $user->id() );
                    foreach ( $GroupArray as $GroupID )
                    {
                        $group->get( $GroupID );
                        $group->adduser( $user );
                    }
                }
                Header( "Location: /user/userlist/" );
                exit();
            }
            print( "Bruker finnes i databasen" );
        }
        print( "Passordene machet ikke, eller var mindre enn 3 bokstaver" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}



if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserModify" ) )
    {
        $user = new eZUser();
        $user->get( $UserID );

        $user->setLogin( $Login );

        if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
        {
            $user->setPassword( $Password );
        }
            
        $user->setEmail( $Email );
        $user->setFirstName( $FirstName );
        $user->setLastName( $LastName );

        $user->store();

        // remove user from groups
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
            }
        }            

        Header( "Location: /user/userlist/" );
        exit();
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserDelete" ) )
    {
        $user = new eZUser();
        $user->get( $UserID );

        $user->delete();
        Header( "Location: /user/userlist/" );
        exit();
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZUserMain", "TemplateDir" ). "/useredit/",
$DOC_ROOT . "/admin/" . "/intl", $Language, "useredit.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_edit" => "useredit.tpl",
    "group_list" => "groupitem.tpl"
    ) );

$FirstName = "";
$Lastname = "";
$Email = "";
$Login = "";
$ActionValue = "insert";

$headline = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

$group = new eZUserGroup();

$groupList = $group->getAll();


$user = 0;
if ( $Action == "edit" )
{
    $user = new eZUser();
    $user->get( $UserID );

    $FirstName = $user->firstName();
    $LastName = $user->lastName();
    $Email = $user->email();
    $Login = $user->login();

    $headline = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );

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

    $t->parse( "group_item", "group_list", true );
}

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
