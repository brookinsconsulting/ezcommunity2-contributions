<?
// 
// $Id: useredit.php,v 1.3 2000/10/26 09:56:08 ce-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Oct-2000 12:52:42 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezmail.php" );
include_once( "classes/ezlog.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );
$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

if ( $Action == "Insert" )
{
    print( "<br>trying<br>" );
    
    // check for valid data
    if ( $Login != "" &&
    $Email != "" &&
    $FirstName != "" &&
    $LastName != "" )
    {
        $user = new eZUser();

        if ( !$user->exists( $Login ) )
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 3 ) )
            {
                if ( eZMail::validate( $Email ) )
                {
                    $user->setLogin( $Login );
                    $user->setPassword( $Password );
                    $user->setEmail( $Email );
                    $user->setFirstName( $FirstName );
                    $user->setLastName( $LastName );

                    $user->store();

                    // add user to usergroup
                    setType( $AnonymousUserGroup, "integer" );
                
                    $group = new eZUserGroup( $AnonymousUserGroup );
                    $group->addUser( $user );
                

                    // log in the user
                    $user->loginUser( $user );

                    eZLog::writeNotice( "Anonyous user created: $FirstName $LastName ($Login) $Email from IP: $REMOTE_ADDR" );                    
                    eZLog::writeNotice( "User login: $Login from IP: $REMOTE_ADDR" );

                    if ( $RedirectURL )
                    {
                        Header( "Location: $RedirectURL" );
                    }
                    else
                    {
                        Header( "Location: /" );
                    }
                }
                else
                {
                    $EmailError = true;
                }                
            }
            else
            {
                $PasswordError = true;
            }
        }
        else
        {
            $UserExistsError = true;
        }
    }
    else
    {
        $Error = true;
    }
}

if ( $Action == "Update" )
{
    if ( eZMail::validate( $Email ) )
    {
        $user = new eZUser();
        $user->get( $UserID );
        $user->setEmail( $Email );
        $user->setFirstName( $FirstName );
        $user->setLastName( $LastName );
        if ( $Password )
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 3 ) )
            {
                if ( !$Password == "dummy" )
                    $user->setPassword( $Password );
            }
            else
            {
                $PasswordError = true;
            }
        }
        if ( $PasswordError == false )
        {
            $user->store();
        }
    }
    else
    {
        $EmailError = true;
    }
    if ( $EmailError == false )
    {
        Header( "Location: /" );
    }
}
        
$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "ezuser/user/intl/", $Language, "useredit.php" );

$t->setAllStrings();

$t->set_file( array(        
    "user_edit_tpl" => "useredit.tpl"
    ) );


$headline = new INIFIle( "ezuser/intl/" . $Language . "/useredit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

$actionValue = "insert";

if ( $Action == "Edit" )
{
    $user = new eZUser();
    $user->get( $UserID );

    $Login = $user->login();
    $Email = $user->email();
    $FirstName = $user->firstName();
    $LastName = $user->lastName();
    $Password = "dummy";
    $VerifyPassword = "dummy";
    $t->set_var( "read_only", "readonly=readonly" );
    $actionValue = "update";
    $headline = new INIFIle( "ezuser/intl/" . $Language . "/useredit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );
}


$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );
$t->set_block( "user_edit_tpl", "email_error_tpl", "email_error" );

if ( $Error == true )
{
    $t->parse( "required_fields_error", "required_fields_error_tpl" );
}
else
{
   $t->set_var( "required_fields_error", "" );
}

if ( $UserExistsError == true )
{
    $t->parse( "user_exists_error", "user_exists_error_tpl" );
}
else
{
   $t->set_var( "user_exists_error", "" );
}

if ( $PasswordError == true )
{
    $t->parse( "password_error", "password_error_tpl" );
}
else
{
   $t->set_var( "password_error", "" );
}

if ( $EmailError == true )
{
    $t->parse( "email_error", "email_error_tpl" );
}
else
{
   $t->set_var( "email_error", "" );
}

$t->set_var( "user_id", $UserID );
$t->set_var( "login_value", $Login );
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

$t->set_var( "action_value", $actionValue );

$t->set_var( "redirect_url", $RedirectURL );

$t->pparse( "output", "user_edit_tpl" );

?>
