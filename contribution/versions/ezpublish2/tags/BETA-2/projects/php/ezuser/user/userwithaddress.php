<?
// 
// $Id: userwithaddress.php,v 1.1 2000/10/25 07:59:56 ce-cvs Exp $
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );
$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezaddress.php" );

if ( $Action == "Insert" )
{
    // check for valid data
    if ( $Login != "" &&
    $Email != "" &&
    $FirstName != "" &&
    $LastName != "" &&
    $Street1 != "" &&
    $Zip != "" &&
    $Place != "" )
    {
        $user = new eZUser();

        if ( !$user->exists( $Login ) )
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
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
                

                $address = new eZAddress();
                $address->setStreet1( $Street1 );
                $address->setStreet2( $Street2 );
                $address->setZip( $Zip );
                $address->setPlace( $Place );

                $address->store();

                // add the address to the user.
                $user->addAddress( $address );

                $user->loginUser( $user );
                
                Header( "Location: $RedirectURL" );
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

$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "ezuser/user/intl/", $Language, "userwithaddress.php" );

$t->setAllStrings();

$t->set_file( array(        
    "user_edit_tpl" => "userwithaddress.tpl"
    ) );


$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );

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

$t->set_var( "login_value", $Login );
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

$t->set_var( "street1_value", $Street1 );
$t->set_var( "street2_value", $Street2 );

$t->set_var( "zip_value", $Zip );

$t->set_var( "place_value", $Place );

$t->set_var( "action_value", "insert" );
$t->set_var( "user_id", "" );

$t->set_var( "redirect_url", $RedirectURL );

$t->pparse( "output", "user_edit_tpl" );

?>
