<?
// 
// $Id: useredit.php,v 1.3 2000/10/15 13:04:57 bf-cvs Exp $
//
// 
//
// B�rd Farstad <bf@ez.no>
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

print $RedirectURL;

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
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
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
                        
                    Header( "Location: $RedirectURL" );
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

$t = new eZTemplate( "ezuser/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "ezuser/intl/", $Language, "useredit.php" );

$t->setAllStrings();

$t->set_file( array(        
    "user_edit_tpl" => "useredit.tpl"
    ) );


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

$t->set_var( "login_value", $Login );
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

$t->set_var( "action_value", "insert" );
$t->set_var( "user_id", "" );

$t->set_var( "redirect_url", $RedirectURL );

$t->pparse( "output", "user_edit_tpl" );

?>
