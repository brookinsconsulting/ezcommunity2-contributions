<?
// 
// $Id: userwithaddress.php,v 1.38 2001/02/09 12:31:07 ce Exp $
//
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <10-Oct-2000 12:52:42 bf>
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

require( "ezuser/user/usercheck.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );
$SelectCountry = $ini->read_var( "eZUserMain", "SelectCountry" );
$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezcountry.php" );
include_once( "classes/ezmail.php" );

$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl/", $Language, "userwithaddress.php" );

$t->setAllStrings();

$t->set_file( array(        
    "user_edit_tpl" => "userwithaddress.tpl"
    ) );

$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );
$t->set_block( "user_edit_tpl", "missing_address_error_tpl", "missing_address_error" );

$t->set_block( "user_edit_tpl", "address_tpl", "address" );
$t->set_block( "address_tpl", "delete_address_tpl", "delete_address" );
$t->set_block( "address_tpl", "country_tpl", "country" );
$t->set_block( "country_tpl", "country_option_tpl", "country_option" );

$t->set_block( "user_edit_tpl", "errors_item_tpl", "errors_item" );
$t->set_var( "errors_item", "&nbsp;" );

// Error templates
$t->set_block( "errors_item_tpl", "error_login_tpl", "error_login" );
$t->set_block( "errors_item_tpl", "error_login_exists_tpl", "error_login_exists" );
$t->set_block( "errors_item_tpl", "error_first_name_tpl", "error_first_name" );
$t->set_block( "errors_item_tpl", "error_last_name_tpl", "error_last_name" );
$t->set_block( "errors_item_tpl", "error_email_tpl", "error_email" );
$t->set_block( "errors_item_tpl", "error_email_not_valid_tpl", "error_email_not_valid" );
$t->set_block( "errors_item_tpl", "error_password_match_tpl", "error_password_match" );
$t->set_block( "errors_item_tpl", "error_password_too_short_tpl", "error_password_too_short" );

$t->set_block( "errors_item_tpl", "error_address_street1_tpl", "error_address_street1" );
$t->set_block( "errors_item_tpl", "error_address_street2_tpl", "error_address_street2" );
$t->set_block( "errors_item_tpl", "error_address_zip_tpl", "error_address_zip" );
$t->set_block( "errors_item_tpl", "error_address_place_tpl", "error_address_place" );

$t->set_block( "errors_item_tpl", "error_missing_address_tpl", "error_missing_address" );

$t->set_var( "error_login", "" );
$t->set_var( "error_login_exists", "" );
$t->set_var( "error_first_name", "" );
$t->set_var( "error_last_name", "" );
$t->set_var( "error_email", "" );
$t->set_var( "error_email_not_valid", "" );
$t->set_var( "error_password_match", "" );
$t->set_var( "error_password_too_short", "" );

$t->set_var( "error_address_place", "" );
$t->set_var( "error_address_zip", "" );
$t->set_var( "error_address_street1", "" );
$t->set_var( "error_address_street2", "" );

$t->set_var( "first_name_value", "$FirstName" );
$t->set_var( "last_name_value", "$LastName" );
$t->set_var( "login_value", "$Login" );
$t->set_var( "email_value", "$Email" );
$t->set_var( "password_value", "$Password" );
$t->set_var( "verify_password_value", "$VerifyPassword" );


// Check if there are addresses
if ( count ( $AddressID ) != 0 )
{
    $address_number = 1;
    for ( $i=0; $i < count ( $AddressID ); $i++ )
    {
        $t->set_var( "address_number", "$i" );
        $t->set_var( "address_id", "$AddressID[$i]" );
        $t->set_var( "street1_value", "$Street1[$i]" );
        $t->set_var( "street2_value", "$Street2[$i]" );
        $t->set_var( "zip_value", "$Zip[$i]" );
        $t->set_var( "place_value", "$Place[$i]" );

        if ( $SelectCountry == "enabled" )
        {
            $countryList = "";

            $ezcountry = new eZCountry();
            $countryList =& $ezcountry->getAllArray();

            $t->set_var( "country_option", "" );
            foreach ( $countryList as $country )
                {
                    if ( $country["ID"] == $CountryID[$i] )
                    {
                        $t->set_var( "is_selected", "selected" );
                    }
                    else
                        $t->set_var( "is_selected", "" );

                    $t->set_var( "country_id", $country["ID"] );
                    $t->set_var( "country_name", $country["Name"] );
                    $t->parse( "country_option", "country_option_tpl", true );
                }
            $t->parse( "country", "country_tpl" );
        }
        else
        {
            $t->set_var( "country", "" );
        }

        $t->set_var( "address_number", $address_number );
        $address_number++;
        $t->parse( "address", "address_tpl", true );
    }

    $addressCheck = true;
}


$user = eZUser::currentUser();

// Set error checking.
$error = false;
$nameCheck = true;
$emailCheck = true;
$firstNameCheck = true;
$lastNameCheck = true;
$loginCheck = true;
$passwordCheck = true;
$street1Check = true;
$street2Check = false;
$zipCheck = true;
$placeCheck = true;

// If the user is trying to buy without having a address
if ( $MissingAddress == true )
{
    $t->parse( "error_missing_address", "error_missing_address_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
    $Action = "Edit";
}
else
{
    $t->set_var( "error_missing_address", "" );
}

// Check for errors when inserting, updating and inserting a new address
if ( ( $Action == "Insert" || $Action == "Update" || isSet ( $NewAddress ) ) && $DeleteAddress == false )
{

    if ( $loginCheck && $Action == "Insert" )
    {
        if ( empty ( $Login ) )
        {
            $t->parse( "error_login", "error_login_tpl" );
            $error = true;

        }
        else
        {
            $user = new eZUser();
            if ( $user->exists( $Login ) == true )
            {
                $t->parse( "error_login_exists", "error_login_exists_tpl" );
                $error = true;
            }
        }
    }

    if ( $firstNameCheck )
    {
        if ( empty ( $FirstName ) )
        {
            $t->parse( "error_first_name", "error_first_name_tpl" );
            $error = true;
        }
    }

    if ( $lastNameCheck )
    {
        if ( empty ( $LastName ) )
        {
            $t->parse( "error_last_name", "error_last_name_tpl" );
            $error = true;
        }
    }

    if ( $emailCheck )
    {
        if( empty( $Email ) )
        {
            $t->parse( "error_email", "error_email_tpl" );
            $error = true;
        }
        else
        {
            if( eZMail::validate( $Email ) == false )
            {
                $t->parse( "error_email_not_valid", "error_email_not_valid_tpl" );
                $error = true;
            }
        }        
    }

    if ( $passwordCheck )
    {
        if ( $Password != $VerifyPassword )
        {
            $t->parse( "error_password_match", "error_password_match_tpl" );
            $error = true;
            
        }
        if ( strlen( $VerifyPassword ) < 2 )
        {
            $t->parse( "error_password_too_short", "error_password_too_short_tpl" );
            $error = true;
        }
    }

    if ( $addressCheck )
    {
        for( $i=0; $i < count ( $AddressID ); $i++ )
        {
            if ( $street1Check )
            {
                if ( empty ( $Street1[$i] ) )
                {
                    $t->parse( "error_address_street1", "error_address_street1_tpl" );
                    $error = true;
                }
            }

            if ( $street2Check )
            {
                if ( empty ( $Street2[$i] ) )
                {
                    $t->parse( "error_address_street2", "error_address_street2_tpl" );
                    $error = true;
                }
            }

            if ( $zipCheck )
            {
                if ( empty ( $Zip[$i] ) )
                {
                    $t->parse( "error_address_zip", "error_address_zip_tpl" );
                    $error = true;
                }
            }

            if ( $placeCheck )
            {
                if ( empty ( $Place[$i] ) )
                {
                    $t->parse( "error_address_place", "error_address_place_tpl" );
                    $error = true;
                }
            }
        }
    }
    
    if( $error == true )
    {
        $t->parse( "errors_item", "errors_item_tpl" );

        if ( $user )
        {
            $t->set_var( "action_value", "update" );
            $Action = "";
        }
        else
        {
            $Action = "New";
        }
    }
}

// Add a new address
if ( isset( $NewAddress ) && $error == false )
{
    $newAddress = new eZAddress();
    $country = new eZCountry( $CountryID );    
    $newAddress->setCountry( 0 );    
    $newAddress->store();
                
    // add the address to the user.
    if ( get_class ( $user ) == "ezuser" )
        $user->addAddress( $newAddress );

    if ( $Action == "Insert" )
    {
        $Action = "Insert";
    }
    if ( $Action == "Update" )
    {
        $Action = "Update";
    }
}
elseif ( isset( $NewAddress ) && $error == true )
{
    $Action = "Edit";
}

// Delete address
if ( isset( $DeleteAddress ) )
{            
    if ( count ( $DeleteAddressArrayID ) != 0 )
    {
        foreach( $DeleteAddressArrayID as $ID )
        {
            
            $address = new eZAddress( $ID );
            $user->removeAddress( $address );
        }
    }

    if ( $Action == "Insert" )
    {
        $Action = "Insert";
    }
    if ( $Action == "Update" )
    {
        $Action = "Update";
    }
}

// Insert a user with address
if ( $Action == "Insert" && $error == false )
{
    $user = new eZUser();
    $user->setLogin( $Login );
    $user->setPassword( $Password );
    $user->setEmail( $Email );
    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->setSignature( $Signature );

    $user->store();

    // add user to usergroup
    setType( $AnonymousUserGroup, "integer" );
    
    $group = new eZUserGroup( $AnonymousUserGroup );
    $group->addUser( $user );
    
    $address = new eZAddress();
    $address->setStreet1( $Street1[0] );
    $address->setStreet2( $Street2[0] );
    $address->setZip( $Zip[0] );
    $address->setPlace( $Place[0] );

    if ( isset( $CountryID ) )
    {
        $country = new eZCountry( $CountryID[0] );
        $address->setCountry( $country );
    }
    else
    {
        $CountryID = $ini->read_var( "eZUserMain", "DefaultCountry" );
        $country = new eZCountry( $CountryID );
        $address->setCountry( $country );
    }
    
    $address->store();

    // add the address to the user.
    $user->addAddress( $address );

    $user->loginUser( $user );

    // Sets the main address
    $mainAddress = new eZAddress( $MainAddressID );
    $address->setMainAddress( $address, $user );

    if ( isSet ( $NewAddress ) )
    {
        if ( get_class ( $newAddress ) == "ezaddress" )
            $user->addAddress( $newAddress );
        eZHTTPTool::header( "Location: /user/userwithaddress/edit/" );
        exit();
    }
    
    if ( isSet( $RedirectURL )  && ( $RedirectURL != "" ) )
    {
        eZHTTPTool::header( "Location: $RedirectURL" );
        exit();
    }
    eZHTTPTool::header( "Location: /" );
    exit();
}

// Update a user with address
if ( $Action == "Update" )
{
    $user = eZUser::currentUser();

    if ( $Password != "dummy" )
        $user->setPassword( $Password );
    
    $user->setEmail( $Email );
    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->setSignature( $Signature );
    
    for ( $i=0; $i<count($AddressID); $i++ )
    {
        if ( $addressID == -1 )
        {
            $address = new eZAddress();
        }
        else
        {
            $address = new eZAddress( $AddressID[$i] );
        }

        $address->setStreet1( $Street1[$i] );
        $address->setStreet2( $Street2[$i] );
        $address->setZip( $Zip[$i] );
        $address->setPlace( $Place[$i] );
        
        if ( isset( $CountryID[$i] ) )
        {
            $country = new eZCountry( $CountryID[$i] );
            $address->setCountry( $country );
        }
        else
        {
            $CountryID = $ini->read_var( "eZUserMain", "DefaultCountry" );
            $country = new eZCountry( $CountryID );
            $address->setCountry( $country );
        }
        
        $address->store();
    }

    $user->store();

    $mainAddress = new eZAddress( $MainAddressID );

    if ( get_class( $address ) == "ezaddress" )
        $address->setMainAddress( $mainAddress, $user );

    if ( isSet( $RedirectURL )  && ( $RedirectURL != "" ) )
    {
        eZHTTPTool::header( "Location: $RedirectURL" );
        exit();
    }

    if ( isSet ( $NewAddress ) )
    {
        eZHTTPTool::header( "Location: /user/userwithaddress/edit/" );
        exit();
    }
    if ( isSet ( $DeleteAddress ) )
    {
        eZHTTPTool::header( "Location: /user/userwithaddress/edit/" );
        exit();
    }
    
    eZHTTPTool::header( "Location: /" );
    exit();
}

$t->set_var( "readonly", "" );

if ( $Action == "Update" )
    $action_value = "update";

// Set up the default values when creating a new user
if ( $Action == "New" )
{
    if  ( ( $error == false ) || ( ( $error == true ) && ( isset ( $NewAddress ) ) ) )
    {
        $t->set_var( "address_number", 1 );
        $t->set_var( "address_id", "-1" );
        $t->set_var( "street1_value", "" );
        $t->set_var( "street2_value", "" );
        $t->set_var( "zip_value", "" );
        $t->set_var( "place_value", "" );

        $t->set_var( "delete_address", "" );
        $t->set_var( "is_checked", "checked" );

        $t->parse( "address", "address_tpl" );
    }

    if ( $SelectCountry == "enabled" )
    {
        $countryList = "";

        $ezcountry = new eZCountry();
        $countryList =& $ezcountry->getAllArray();

        $t->set_var( "country_option", "" );
        foreach ( $countryList as $country )
        {
            if ( $Action == "New" )
            {
                $countryID = $ini->read_var( "eZUserMain", "DefaultCountry" );
                if ( $country["ID"] == $countryID )
                {
                    $t->set_var( "is_selected", "selected" );
                }
                else
                    $t->set_var( "is_selected", "" );
            }
                        
        $t->set_var( "country_id", $country["ID"] );
        $t->set_var( "country_name", $country["Name"] );
        $t->parse( "country_option", "country_option_tpl", true );
        }
        $t->parse( "country", "country_tpl" );
    }
    else
    {
        $t->set_var( "country", "" );
    }
    $t->set_var( "action_value", "insert" );
}

// Set the values to the user when editing
if ( $Action == "Edit" )
{
    $user = eZUser::currentUser();
    if ( !$user )
        eZHTTPTool::header( "Location: /" );
    
    $UserID = $user->id();
    $user->get( $user->id() );
    
    $Login = $user->Login( );
    $Email = $user->Email(  );
    $FirstName = $user->FirstName(  );
    $LastName = $user->LastName(  );

    $t->parse( "delete_address", "delete_address_tpl" );

    $t->set_var( "login_value", $Login );
    if ( $Password == "" )
        $t->set_var( "password_value", "dummy" );
    else
        $t->set_var( "password_value", $Password );

    if ( $VerifyPassword == "" )
        $t->set_var( "verify_password_value", "dummy" );
    else
        $t->set_var( "verify_password_value", $VerifyPassword );
    
    $t->set_var( "email_value", $Email );

    $t->set_var( "first_name_value", $FirstName );
    $t->set_var( "last_name_value", $LastName );
    
    $t->set_var( "readonly", "readonly" );

    $t->set_var( "address", "" );

    $addressArray = "";
    $addressArray = $user->addresses();

    $i = 0;
    $address_number = 1;
    foreach ( $addressArray as $address )
    {
        $Street1 =  $address->street1();
        $Street2 = $address->street2();
        $Zip = $address->zip();
        $Place = $address->place();

        $t->set_var( "address_id", $address->id() );
            
        $t->set_var( "street1_value", $Street1 );
        $t->set_var( "street2_value", $Street2 );

        $mainAddress = $address->mainAddress( $user );

        if ( $mainAddress )
        {
            $mainAddressID = $mainAddress->id();
            
            if ( $address->id() == $mainAddressID )
            {
                $t->set_var( "is_checked", "checked" );
            }
            else
            {
                $t->set_var( "is_checked", "" );
            }
        }
        
        $t->set_var( "zip_value", $Zip );
            
        $t->set_var( "place_value", $Place );
            
        if ( $SelectCountry == "enabled" )
        {
            $countryList = "";

            $ezcountry = new eZCountry();
            $countryList =& $ezcountry->getAllArray();

            $t->set_var( "country_option", "" );
            foreach ( $countryList as $country )
                {
                    if ( $Action == "Edit" )
                    {
                        if ( $address )
                        {
                            $countryID = $address->country();
                
                            if ( $country["ID"] == $countryID->id() )
                            {
                                $t->set_var( "is_selected", "selected" );
                            }
                            else
                                $t->set_var( "is_selected", "" );
                        }
                    }
                        
                    $t->set_var( "country_id", $country["ID"] );
                    $t->set_var( "country_name", $country["Name"] );
                    $t->parse( "country_option", "country_option_tpl", true );
                }
            $t->parse( "country", "country_tpl" );
        }
        else
        {
            $t->set_var( "country", "" );
        }

        $t->set_var( "address_number", $address_number );
        
        $i++;
        $address_number++;
        $t->parse( "address", "address_tpl", true );
    }

    $t->set_var( "action_value", "update" );
}

$t->set_var( "user_id", $UserID );

$t->set_var( "redirect_url", $RedirectURL );

$t->pparse( "output", "user_edit_tpl" );

?>
