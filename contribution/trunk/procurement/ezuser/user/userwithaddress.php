<?php
// 
// $Id: userwithaddress.php,v 1.75.2.6 2002/07/08 15:16:41 bf Exp $
//
// Created on: <10-ct-2000 12:52:42 bf>
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
require( "ezuser/user/usercheck.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "classes/eztexttool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$SelectCountry = $ini->read_var( "eZUserMain", "SelectCountry" );
$SelectRegion = $ini->read_var( "eZUserMain", "SelectRegion" );

$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );
$ForceSSL = $ini->read_var( "eZUserMain", "ForceSSL" );
$AutoCookieLogin = eZHTTPTool::getVar( "AutoCookieLogin" );
$UserPersonLink = $ini->read_var( "eZUserMain", "UserPersonLink" );

$session =& eZSession::globalSession();

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezcountry.php" );
include_once( "ezaddress/classes/ezregion.php" );
include_once( "ezmail/classes/ezmail.php" );

include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezperson.php" );

//include_once( "ezaddress/classes/ezphone.php" );
//include_once( "ezaddress/classes/ezonline.php" );


$user =& eZUser::currentUser();

// set SSL mode and redirect if not already in SSL mode.
if ( ( $ForceSSL == "enabled" ) )
{
    // force SSL if supposed to
    if ( $SERVER_PORT != '443' )
    {
   		$session->setVariable( "SSLMode", "enabled" );
        eZHTTPTool::header("Location: https://" . $HTTP_HOST . $REQUEST_URI );
 //       eZHTTPTool::header("Location: https://" . $HTTP_HOST . "/user/userwithaddress/edit/" . $user->id() );
//        print( "<font color=\"#333333\">Start: Location: https://" . $HTTP_HOST . $REQUEST_URI . "</font>" );
        exit();
    }
}
elseif ( $ForceSSL == "disabled" )
{
	if ( $SERVER_PORT == '443' )
    {
    	$session->setVariable( "SSLMode", "disabled" );
		eZHTTPTool::header("Location: http://" . $HTTP_HOST . "/user/userwithaddress/edit/" . $user->id() );
		exit();
	}
}

$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl/", $Language, "userwithaddress.php" );

$t->setAllStrings();

$t->set_file( "user_edit_tpl", "userwithaddress.tpl" );

$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );
$t->set_block( "user_edit_tpl", "missing_address_error_tpl", "missing_address_error" );
$t->set_block( "user_edit_tpl", "address_actions_tpl", "address_actions" );

$t->set_block( "user_edit_tpl", "address_tpl", "address" );
$t->set_block( "address_tpl", "main_address_tpl", "main_address" );
$t->set_block( "address_tpl", "delete_address_tpl", "delete_address" );
$t->set_block( "address_tpl", "country_tpl", "country" );
$t->set_block( "country_tpl", "country_option_tpl", "country_option" );

$t->set_block( "address_tpl", "region_tpl", "region" );

$t->set_block( "address_tpl", "region_line_tpl", "region_line" );
$t->set_block( "region_tpl", "region_option_tpl", "region_option" );

$t->set_block( "user_edit_tpl", "errors_item_tpl", "errors_item" );
$t->set_var( "errors_item", "" );

$t->set_block( "user_edit_tpl", "login_item_tpl", "login_item" );
$t->set_block( "user_edit_tpl", "disabled_login_item_tpl", "disabled_login_item" );

// Info templates
$t->set_block( "user_edit_tpl", "info_item_tpl", "info_item" );
$t->set_block( "info_item_tpl", "info_updated_tpl", "info_updated" );

// company style
$t->set_block( "user_edit_tpl", "companies_tpl", "companies" );
$t->set_block( "user_edit_tpl", "company_name_single_tpl", "company_name_single" );

// phone & address types
$t->set_block( "user_edit_tpl", "phone_table_item_tpl", "phone_table_item" );
$t->set_block( "phone_table_item_tpl", "phone_item_tpl", "phone_item" );
$t->set_block( "phone_item_tpl", "phone_item_select_tpl", "phone_item_select" );

$t->set_block( "user_edit_tpl", "online_table_item_tpl", "online_table_item" );
$t->set_block( "online_table_item_tpl", "online_item_tpl", "online_item" );
$t->set_block( "online_item_tpl", "online_item_select_tpl", "online_item_select" );

$t->set_block( "address_tpl", "address_item_select_tpl", "address_item_select" );

// Error templates
$t->set_block( "errors_item_tpl", "error_login_tpl", "error_login" );
$t->set_block( "errors_item_tpl", "error_login_exists_tpl", "error_login_exists" );
$t->set_block( "errors_item_tpl", "error_first_name_tpl", "error_first_name" );
$t->set_block( "errors_item_tpl", "error_last_name_tpl", "error_last_name" );

$t->set_block( "errors_item_tpl", "error_company_name_tpl", "error_company_name" );

$t->set_block( "errors_item_tpl", "error_email_tpl", "error_email" );
$t->set_block( "errors_item_tpl", "error_email_not_valid_tpl", "error_email_not_valid" );
$t->set_block( "errors_item_tpl", "error_password_match_tpl", "error_password_match" );
$t->set_block( "errors_item_tpl", "error_password_too_short_tpl", "error_password_too_short" );
$t->set_block( "errors_item_tpl", "error_password_not_entered_tpl", "error_password_not_entered" );

$t->set_block( "errors_item_tpl", "error_address_street1_tpl", "error_address_street1" );
$t->set_block( "errors_item_tpl", "error_address_street2_tpl", "error_address_street2" );
$t->set_block( "errors_item_tpl", "error_address_zip_tpl", "error_address_zip" );
$t->set_block( "errors_item_tpl", "error_address_place_tpl", "error_address_place" );

$t->set_block( "errors_item_tpl", "error_missing_address_tpl", "error_missing_address" );
$t->set_block( "errors_item_tpl", "error_missing_country_tpl", "error_missing_country" );

$t->set_block( "errors_item_tpl", "error_missing_region_tpl", "error_missing_region" );
$t->set_block( "errors_item_tpl", "error_missing_company_tpl", "error_missing_company" );
$t->set_block( "errors_item_tpl", "error_missing_phone_tpl", "error_missing_phone" );
$t->set_block( "errors_item_tpl", "error_missing_online_tpl", "error_missing_online" );


$t->set_block( "user_edit_tpl", "new_user_tpl", "new_user" );
$t->set_block( "user_edit_tpl", "edit_user_tpl", "edit_user" );
$t->set_block( "user_edit_tpl", "edit_user_info_tpl", "edit_user_info" );

$t->set_block( "user_edit_tpl", "ok_button_tpl", "ok_button" );
$t->set_block( "user_edit_tpl", "submit_button_tpl", "submit_button" );

$t->set_block( "companies_tpl", "company_select_tpl", "company_select" );

$t->set_var( "error_login", "" );
$t->set_var( "error_login_exists", "" );
$t->set_var( "error_first_name", "" );
$t->set_var( "error_last_name", "" );
$t->set_var( "error_company_name", "" );

$t->set_var( "error_email", "" );
$t->set_var( "error_email_not_valid", "" );
$t->set_var( "error_password_match", "" );
$t->set_var( "error_password_too_short", "" );
$t->set_var( "error_password_not_entered", "" ); 
$t->set_var( "error_missing_country", "" );
$t->set_var( "error_missing_region", "" );

$t->set_var( "error_address_place", "" );
$t->set_var( "error_address_zip", "" );
$t->set_var( "error_address_street1", "" );
$t->set_var( "error_address_street2", "" );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

if ( ( $UserPersonLink == "enabled" ) )
{
  $t->set_var( "company_name_value", "");
  $t->set_var( "company_name_single", "");
}else{
      $t->set_var( "companies", "" );
      $t->set_var( "company_name_value", $CompanyName );
      //      $t->parse( "company_name_single", "company_name_single_tpl");
}

$t->set_var( "login_value", $Login );
$t->set_var( "email_value", $Email );
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "address_actions", "" );

if ( $AutoCookieLogin == "on" )
{
    $t->set_var( "is_cookie_selected", "checked" );
}

if( $NoAddress == true )
{
    $t->set_var( "no_address", "no" );
}
else
{
    $t->set_var( "no_address", "" );
}

$user =& eZUser::currentUser();

$t->set_var( "ok_button", "" );
$t->set_var( "submit_button", "" );
$t->set_var( "new_user", "" );
$t->set_var( "edit_user", "" );
$t->set_var( "edit_user_info", "" );
if ( get_class( $user ) == "ezuser" )
{
    $t->parse( "submit_button", "submit_button_tpl" );
    $t->parse( "edit_user", "edit_user_tpl" );
    $t->parse( "edit_user_info", "edit_user_info_tpl" );
}
else
{
    $t->parse( "ok_button", "ok_button_tpl" );
    $t->parse( "new_user", "new_user_tpl" );
}

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
$addressCheck = true;
$companyCheck = true;
$phoneCheck = true;
$onlineCheck = true;


// If the user is trying to buy without having a address
if ( $MissingAddress == true )
{
    $t->parse( "error_missing_address", "error_missing_address_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
}
else
{
    $t->set_var( "error_missing_address", "" );
    $t->set_var( "action_value", "update" );
}

// If the user is trying to buy without having a address
if ( $MissingCountry == true )
{
    $t->parse( "error_missing_country", "error_missing_country_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
}
else
{
    $t->set_var( "error_missing_country", "" );
    $t->set_var( "action_value", "update" );
}

// If the user is trying to buy without having a address
if ( $MissingRegion == true )
{
    $t->parse( "error_missing_region", "error_missing_region_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
}
else
{
    $t->set_var( "error_missing_region", "" );
    $t->set_var( "action_value", "update" );
}

// Check for errors when inserting, updating and inserting a new address
if ( isSet( $OK ) or isSet( $OK_x ) )
{
    if ( $loginCheck )
    {
        if ( get_class( $user ) != "ezuser" and $Login == "" )
        {
            $t->parse( "error_login", "error_login_tpl" );
            $error = true;
        }
        else if ( get_class( $user ) != "ezuser" )
        {
            if ( eZUser::exists( $Login ) == true )
            {
                $t->parse( "error_login_exists", "error_login_exists_tpl" );
                $error = true;
            }
        }
    }

    if ($phoneCheck)
    {
      if ($Phone[0] == "" || $PhoneTypeID[0] == "-1")
      {
	$t->parse( "error_missing_phone", "error_missing_phone_tpl" );
	
	$error = true;
      }
      else
      {
	$t->set_var( "error_missing_phone", "");
      }
    }

    if ($onlineCheck)
    {
      if ($Online[0] == "" || $OnlineTypeID[0] == "-1")
      {
	$t->parse( "error_missing_online", "error_missing_online_tpl" );

	$error = true;
      }
      else
      {
	$t->set_var( "error_missing_online", "");
      }
    }

    if ( $firstNameCheck and $FirstName == "" )
    {
        $t->parse( "error_first_name", "error_first_name_tpl" );
        $error = true;
    }

    if ( $lastNameCheck and $LastName == "" )
    {
        $t->parse( "error_last_name", "error_last_name_tpl" );
        $error = true;
    }


    if ( $comanyNameCheck and $ComanyName == "" )
    {
        $t->parse( "error_comany_name", "error_comany_name_tpl" );
        $error = true;
    }

    if ($companyCheck)
    {
     if (empty($CompanyID) || (in_array(-1, $CompanyID) && sizeof($CompanyID)==1 ) )
     {
      $t->parse( "error_missing_company", "error_missing_company_tpl" );

      $error = true;
     }
     else
     {
      $t->set_var( "error_missing_company", "");
     }
    }

    if ( $emailCheck )
    {
        if( $Email == "" )
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
            if ( $ini->read_var( "eZUserMain", "RequireAddress" ) == "enabled" )
            {
                if ( count( $AddressID ) == 0 )
                {
                    $t->parse( "error_missing_address", "error_missing_address_tpl" );
                    $error = true;
                }
            }

            if ( $street1Check )
            {
                if ( $Street1[$i] == "" )
                {
                    $t->parse( "error_address_street1", "error_address_street1_tpl" );
                    $error = true;
                }
            }

            if ( $street2Check )
            {
                if ( $Street2[$i] == "" )
                {
                    $t->parse( "error_address_street2", "error_address_street2_tpl" );
                    $error = true;
                }
            }

            if ( $zipCheck )
            {
                if ( $Zip[$i] == "" )
                {
                    $t->parse( "error_address_zip", "error_address_zip_tpl" );
                    $error = true;
                }
            }

            if ( $placeCheck )
            {
                if ( $Place[$i] == "" )
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
    }
}

// Add a new address
if ( isSet( $NewAddress ) )
{
    if ( count( $AddressID ) > 0 )
        $AddressID[] = $AddressID[count( $AddressID ) - 1] + 1;
    else
        $AddressID = array( 1 );
    $Street1[] = "";
    $Street2[] = "";
    $Zip[] = "";
    $Place[] = "";
    $country_id = $ini->read_var( "eZUserMain", "DefaultCountry" );
    if ( count( $CountryID ) > 0 and is_numeric( $CountryID[count( $CountryID ) - 1] ) )
        $CountryID[] = $CountryID[count( $CountryID ) - 1];
    else
        $CountryID[] = $country_id;

    $region_id = $ini->read_var( "eZUserMain", "DefaultRegion" );

    if ( count( $RegionID ) > 0 and is_numeric( $RegionID[count($RegionID)-1] ) )
        $RegionID[] = $RegionID[count($RegionID)-1];
    else
        $RegionID[] = $region_id;

    if ( !$MainAddressID && count( $AddressID ) > 0 )
        $MainAddressID = $RealAddressID[0];


}

// Insert a user with address
if ( ( isSet( $OK ) or isSet( $OK_x ) ) and $error == false )
{
    $new_user = false;
    if ( get_class( $user ) != "ezuser" )
        $new_user = true;

    if ( $new_user )
    {
        $user_insert = new eZUser();
        $user_insert->setLogin( $Login );
    }
    else
    {
        $user_insert = $user;
    }

    if ( $new_user or $Password != "dummy" )
        $user_insert->setPassword( $Password );

    $user_insert->setEmail( $Email );
    $user_insert->setFirstName( $FirstName );
    $user_insert->setLastName( $LastName );

    // wont work under new users?
    /*
    $person = new eZPerson($PersonID);

    $person->removeCompanies();
    for ( $i = 0; $i < count( $CompanyID ); $i++ )
    {
         eZCompany::addPerson( $person->id(), $CompanyID[$i] );
    }
    */
    //    $user_insert->setCompanyName( $CompanyName );
    for ($i=0; $i<sizeof($CompanyID); $i++)
    {
    	if ($CompanyID[$i] == 0)
        {
         unset($CompanyID[$i]);
         break;
        }
    }
    $user_insert->setCompanies( $CompanyID );
    //    die($CompanyID[0]);
    $user_insert->setSignature( $Signature );


    // $user_insert->setPhones($PhoneID,$PhoneTypeID,$PhoneDelete);
    // $user_insert->setOnlines();


    if ( !isSet( $PhoneDelete ) )
    {
        $PhoneDelete = array();
    }
    if ( !isSet( $OnlineDelete ) )
    {
        $OnlineDelete = array();
    }

    //die($Phone[0]);
    

    $user_insert->removePhones();

    $count = max( count( $PhoneID ), count( $Phone ) );

    for ( $i = 0; $i < $count; $i++ )
    {


	if ( !in_array( $i + 1, $PhoneDelete ) && $Phone[$i] != "" )
	{
	    $phone = new eZPhone( false, true );
	    $phone->setNumber( $Phone[$i] );
	    $phone->setPhoneTypeID( $PhoneTypeID[$i] );
	    $phone->store();

	    $user_insert->addPhone( $phone );
	}
    }

    $user_insert->removeOnlines();
    $count = max( count( $OnlineID ), count( $Online ) );
    for ( $i = 0; $i < $count; $i++ )
    {
	if ( !in_array( $i + 1, $OnlineDelete ) && $Online[$i] != "" )
	{
	    $online = new eZOnline( false, true );
	    $online->setURL( $Online[$i] );
	    $online->setOnlineTypeID( $OnlineTypeID[$i] );
	    $online->store();

	    $user_insert->addOnline( $online );
	}
    }


    if ( $InfoSubscription == "on" )
        $user_insert->setInfoSubscription( true );
    else
        $user_insert->setInfoSubscription( false );
    
    if ( $InfoDisclaimer == "on" )
        $user_insert->setInfoDisclaimer( true );
    else
        $user_insert->setInfoDisclaimer( false );

    if ( $DeadlineReminder == "on" )
        $user_insert->setDeadlineReminders( true );
    else
        $user_insert->setDeadlineReminders( false );


    if ( $AutoCookieLogin == "on" )
        $user_insert->setCookieLogin( true );
    else
        $user_insert->setCookieLogin( false );

    $user_insert->store();

    // Does only need to add the user to the anonymous group if
    // it is a new user
    if ( $new_user )
    {
        setType( $AnonymousUserGroup, "integer" );
        $group = new eZUserGroup( $AnonymousUserGroup );
        $group->addUser( $user_insert );
        $user_insert->setGroupDefinition( $group );
    }
    
    if ( !$MainAddressID )
    {
        $mainAddress = eZAddress::mainAddress( $user );

        if ( $mainAddress )
            $MainAddressID = $mainAddress->id();
    }
            
    if ( !$MainAddressID && count( $AddressID ) > 0 )
        $MainAddressID = $AddressID[0];

//    if ( !$new_user )
//        $user_insert->removeAddresses();

    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
        $address_id = $AddressID[$i];
        $realAddressID = $RealAddressID[$i];

        $address = new eZAddress();
        if ( !$address->get( $realAddressID ) )
        {
            $address = new eZAddress();
        }
        
        $address->setStreet1( $Street1[$i] );
        $address->setStreet2( $Street2[$i] );
        $address->setZip( $Zip[$i] );
        $address->setPlace( $Place[$i] );


	$address->setAddressType( $AddressTypeID[$i] );


        if ( $SelectCountry == "enabled" and isSet( $CountryID[$i] ) )
        {
            $address->setCountry( $CountryID[$i] );
        }
        else
        {
            $CountryID = $ini->read_var( "eZUserMain", "DefaultCountry" );
            $address->setCountry( $CountryID );
        }

        if ( $SelectRegion == "enabled" and isSet( $RegionID[$i] ) )
        {
            $address->setRegion( $RegionID[$i] );
        }
        else
        {
            $RegionID = $ini->read_var( "eZUserMain", "DefaultRegion" );
            $address->setRegion( $RegionID );
        }

        $address->store();

        // set correct ID
        if ( !is_numeric( $realAddressID ) )
            $RealAddressID[$i] = $address->id();
        
        if ( $MainAddressID == $RealAddressID[$i] )
            $main_id = $MainAddressID;

        if ( count ( $AddressID ) == 1 )
            $main_id = $address->id();

        // add address if new
        if ( !is_numeric( $realAddressID ) )
            $user_insert->addAddress( $address );
    }

    if ( count( $AddressID ) > 0 )
        eZAddress::setMainAddress( $main_id, $user_insert );

    $user_insert->loginUser( $user_insert );

    //    $user_insert3 = $user_insert->loginUser( $user_insert );
    //die($user_insert3);
 
    if ( $user_insert->cookieLogin() == true )
    {
        $user_insert->setCookieValues();
    }

    if ( !$new_user )
        $Updated = true;

    if ( $new_user ) {
      // print("exit warning");
      eZHTTPTool::header( "Location: /user/confirmation/create" );
      exit();
    }


    /*
     more ezregion originals to replace the bellow?
     if( $RedirectURL == "" )
		 $RedirectURL = $REQUEST_URI;
    */

    // if ( $url_array[3] == "insert" )
    // include or redirect to url ?

    //            include( "ezuser/user/confirmation.php" );
    //        eZHTTPTool::header( "Location: /user/confirmation/create" );
    //        exit();
  
  
    if( $RedirectURL == "" )
    {
        $RedirectURL = $session->variable( "RedirectURL" );
    }
    if ( isSet( $RedirectURL )  && ( $RedirectURL != "" ) && !strstr( $RedirectURL, "1x1.gif" ) )
    {
        $session->setVariable( "RedirectURL", "$RedirectURL" );
        eZHTTPTool::header( "Location: $RedirectURL" );
        exit();
    }
    if ( get_class( $user ) != "ezuser" )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
}

$info_array = array();
$t->set_var( "info_item", "" );
if ( isSet( $Updated ) )
{
    $info_array[] = "info_updated";
}

if ( count( $info_array ) > 0 )
{
    foreach( $info_array as $info )
    {
        $t->parse( $info, $info . "_tpl" );
    }
    $t->parse( "info_item", "info_item_tpl" );
}

$t->set_var( "readonly", "" );
$cookieCheck = "";

if ( get_class( $user_insert ) == "ezuser" )
    $user = $user_insert;

// Fill in variables which are not set for current user,
// this is done the first the page loads
if ( get_class( $user ) == "ezuser" )
{
    if ( !isSet( $userID ) )
        $userID = $user->id();
    if ( !isSet( $Login ) )
        $Login = $user->Login();
    if ( !isSet( $Email ) )
        $Email = $user->Email();
    if ( !isSet( $FirstName ) )
        $FirstName = $user->FirstName();
    if ( !isSet( $LastName ) )
         $LastName = $user->LastName();

    // if ( !isSet( $CompanyName ) && $UserPersonLink != "enabled" )
    if ( !isSet( $CompanyName ) ) 
         $CompanyName = $user->CompanyName();

    if ( ( $UserPersonLink == "enabled" ) )
    {
      if( get_class( $user ) == "ezuser" ) {

    //######################################################
    if ( !isSet( $personID ) )
      $personID = $user->personID();

    $top_name = $t->get_var( "intl-top_category" );
    if ( !is_string( $top_name ) )
      $top_name = "";
    $companyTypeList = eZCompanyType::getTree( 0, 0, true, $top_name );
    $categoryList = array();
    $categoryList = eZPerson::companies( $personID, false );
    $category_values = array_values( $categoryList );
    $t->set_var( "is_top_selected", in_array( 0, $category_values ) ? "selected" : "" );
    foreach ( $companyTypeList as $companyTypeItem )
    {
	$t->set_var( "company_name", "[" . eZTextTool::htmlspecialchars( $companyTypeItem[0]->name() ) . "]" );
	$t->set_var( "company_id", "-1" );

	$level = $companyTypeItem[1] > 0 ? str_repeat( "&nbsp;", $companyTypeItem[1] ) : "";
	$t->set_var( "company_level", $level );
	$t->set_var( "is_selected", "" );
	$t->parse( "company_select", "company_select_tpl", true );

	$level = str_repeat( "&nbsp;", $companyTypeItem[1] + 1 );
	$t->set_var( "company_level", $level );

	$companies = eZCompany::getByCategory( $companyTypeItem[0]->id() );
	foreach ( $companies as $companyItem )
	{
	    $t->set_var( "company_name", eZTextTool::htmlspecialchars( $companyItem->name() ) );
	    $t->set_var( "company_id", $companyItem->id() );
	    $t->set_var( "is_selected", in_array( $companyItem->id(), $category_values ) ? "selected" : "" );
	    $t->parse( "company_select", "company_select_tpl", true );
	}
    }

    //######################################################

      $t->set_var( "company_name_single", "" );
      $t->parse( "companies", "companies_tpl");
  }
    }else{
      $t->set_var( "companies", "" );
      $t->set_var( "company_name_value", $CompanyName );
      $t->parse( "company_name_single", "company_name_single_tpl");
    }

    //######################################################

    $phones = $user->phones();
    $i = 1;
    foreach ( $phones as $phone )
    {
	$PhoneTypeID[$i - 1] = $phone->phoneTypeID();
	$PhoneID[$i - 1] = $i;
	$Phone[$i - 1] = $phone->number();
	$i++;
    }

    $onlines = $user->onlines();
    $i = 1;
    foreach ( $onlines as $online )
    {
	$OnlineTypeID[$i - 1] = $online->onlineTypeID();
	$OnlineID[$i - 1] = $i;
	$Online[$i - 1] = $online->url();
	$i++;
    }

    // ######################################################

    $phone_types =& eZPhoneType::getAll();
    $online_types =& eZOnlineType::getAll();
    $address_types =& eZAddressType::getAll();

    $PhoneMinimum = $ini->read_var( "eZContactMain", "PhoneMinimum" );
    $OnlineMinimum = $ini->read_var( "eZContactMain", "OnlineMinimum" );

    $PhoneWidth = $ini->read_var( "eZContactMain", "PhoneWidth" );
    $OnlineWidth = $ini->read_var( "eZContactMain", "OnlineWidth" );

    if ( !isSet( $PhoneDelete ) )
    {
	$PhoneDelete = array();
    }
    if ( !isSet( $OnlineDelete ) )
    {
	$OnlineDelete = array();
    }


    $t->set_var( "address_position", $i + 1 );
    $t->set_var( "address_item_select", "" );

    foreach ( $address_types as $address_type )
    {
	$t->set_var( "type_id", $address_type->id() );
	$t->set_var( "type_name", eZTextTool::htmlspecialchars( $address_type->name() ) );
	$t->set_var( "selected", "" );
	if ( $address_type->id() == $AddressTypeID[$i] )
	  $t->set_var( "selected", "selected" );
	$t->parse( "address_item_select", "address_item_select_tpl", true );
    }
    $t->parse( "address_item", "address_item_tpl", true );


    $personID = $user->personID();
    $item = new eZPerson($personID);

    $phones = $item->phones();
    $i = 1;
    foreach ( $phones as $phone )
    {
	$PhoneTypeID[$i - 1] = $phone->phoneTypeID();
	$PhoneID[$i - 1] = $i;
	$Phone[$i - 1] = $phone->number();
	$i++;
    }

    $onlines = $item->onlines();
    $i = 1;
    foreach ( $onlines as $online )
    {
	$OnlineTypeID[$i - 1] = $online->onlineTypeID();
	$OnlineID[$i - 1] = $i;
	$Online[$i - 1] = $online->url();
	$i++;
    }

    //######################################################
    if( get_class( $user ) == "ezuser" ) {

    if ( isSet( $NewPhone ) )
    {
	$PhoneTypeID[] = "";
	$PhoneID[] = count( $PhoneID ) > 0 ? $PhoneID[count( $PhoneID ) - 1] + 1 : 1;
	$Phone[] = "";
    }

    $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
    $item = 0;
    $last_id = 0;
    $PhoneDeleteValues =& array_values( $PhoneDelete );

    for ( $i = 0; $i < $count || $item < $PhoneMinimum; $i++ )
    {
	if ( ( $item % $PhoneWidth == 0 ) && $item > 0 )
	{
	    $t->parse( "phone_table_item", "phone_table_item_tpl", true );
	    $t->set_var( "phone_item" );
	}
	if ( !isSet( $PhoneID[$i] ) or !is_numeric( $PhoneID[$i] ) )
	  $PhoneID[$i] = ++$last_id;
	if ( !in_array( $PhoneID[$i], $PhoneDeleteValues ) )
	{
	    $last_id = $PhoneID[$i];
	    $t->set_var( "phone_number", eZTextTool::htmlspecialchars( $Phone[$i] ) );
	    $t->set_var( "phone_id", $PhoneID[$i] );
	    $t->set_var( "phone_index", $PhoneID[$i] );
	    $t->set_var( "phone_position", $i + 1 );

	    $t->set_var( "phone_item_select", "" );

	    foreach ( $phone_types as $phone_type )
	    {
		$t->set_var( "type_id", $phone_type->id() );
		$t->set_var( "type_name", eZTextTool::htmlspecialchars( $phone_type->name() ) );
		$t->set_var( "selected", "" );
		if ( $phone_type->id() == $PhoneTypeID[$i] )
		  $t->set_var( "selected", "selected" );
		$t->parse( "phone_item_select", "phone_item_select_tpl", true );
	    }

	    $t->parse( "phone_item", "phone_item_tpl", true );
	    $item++;
	}
	else
	  $PhoneDeleteValues = array_diff( $PhoneDeleteValues, array( $PhoneID[$i] ) );
    }
    $t->parse( "phone_table_item", "phone_table_item_tpl", true );
 
    //######################################################

    if ( isSet( $NewOnline ) )
    {
	$OnlineTypeID[] = "";
	$OnlineID[] = count( $OnlineID ) > 0 ? $OnlineID[count( $OnlineID ) - 1] + 1 : 1;
	$Online[] = "";
    }
    $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
    $item = 0;
    $last_id = 0;
    $OnlineDeleteValues =& array_values( $OnlineDelete );
    for ( $i = 0; $i < $count || $item < $OnlineMinimum; $i++ )
    {
	if ( ( $item % $OnlineWidth == 0 ) && $item > 0 )
	{
	    $t->parse( "online_table_item", "online_table_item_tpl", true );
	    $t->set_var( "online_item" );
	}
	if ( !isSet( $OnlineID[$i] ) or !is_numeric( $OnlineID[$i] ) )
	  $OnlineID[$i] = ++$last_id;
	if ( !in_array( $OnlineID[$i], $OnlineDeleteValues ) )
	{
	    $last_id = $OnlineID[$i];
	    $t->set_var( "online_value", eZTextTool::htmlspecialchars( $Online[$i] ) );
	    $t->set_var( "online_id", $OnlineID[$i] );
	    $t->set_var( "online_index", $OnlineID[$i] );
	    $t->set_var( "online_position", $i + 1 );

	    $t->set_var( "online_item_select", "" );

	    foreach ( $online_types as $online_type )
	    {
		$t->set_var( "type_id", $online_type->id() );
		$t->set_var( "type_name", eZTextTool::htmlspecialchars( $online_type->name() ) );
		$t->set_var( "selected", "" );
		if ( $online_type->id() == $OnlineTypeID[$i] )
		  $t->set_var( "selected", "selected" );
		$t->parse( "online_item_select", "online_item_select_tpl", true );
	    }

	    $t->parse( "online_item", "online_item_tpl", true );
	    $item++;
	}
	else
	  $OnlineDeleteValues = array_diff( $OnlineDeleteValues, array( $OnlineID[$i] ) );
    }
    $t->parse( "online_table_item", "online_table_item_tpl", true );
}

    //######################################################

    $cookieCheck = "";
    if ( $user->cookieLogin() == true )
    {
        $cookieCheck = "checked";
    }
    else
    {
    }
    
    if ( !isSet( $AddressID ) )
    {
        if ( !isSet( $AddressID ) )
            $AddressID = array();
        if ( !isSet( $Street1 ) )
            $Street1 = array();
        if ( !isSet( $Street2 ) )
            $Street2 = array();
        if ( !isSet( $Zip ) )
            $Zip = array();
        if ( !isSet( $Place ) )
            $Place = array();
        if ( !isSet( $CountryID ) )
            $CountryID = array();

        if ( !isset( $RegionID ) )
            $RegionID = array();

        $mainAddress = eZAddress::mainAddress( $user );

        $addressArray = $user->addresses();

        $i = 0;
        foreach ( $addressArray as $address )
        {
            if ( ( get_class( $mainAddress ) == "ezaddress" ) and ( $address->id() == $mainAddress->id()  ) and !isSet( $MainAddressID ) )
            {
//                $MainAddressID = $i + 1;
                $MainAddressID = $address->id();
            }
            if ( !isSet( $AddressID[$i] ) )
                $AddressID[$i] = $i + 1;
            if ( !isSet( $RealAddressID[$i] ) )
                $RealAddressID[$i] = $address->id();                
            if ( !isSet( $Street1[$i] ) )
                $Street1[$i] = $address->street1();
            if ( !isSet( $Street2[$i] ) )
                $Street2[$i] = $address->street2();
            if ( !isSet( $Zip[$i] ) )
                $Zip[$i] = $address->zip();
            if ( !isSet( $Place[$i] ) )
                $Place[$i] = $address->place();
	    if ( !isSet( $AddressTypeID[$i] ) )
	      $AddressTypeID[$i] = $address->addressTypeID();



            if ( !isSet( $CountryID[$i] ) )
            {
                $country = $address->country();
                if ( $country )
                {
                    $CountryID[$i] = $country->id();
                }
            }

            if ( !isSet( $RegionID[$i] ) )
            {
                $region = $address->region();
                if ( $region )
                {    // region object does not return region objects just id, feature addition, 
                     $RegionID[$i] = $region->id();
		     // $RegionID[$i] = $region;
                }
            }
            ++$i;
        }
    }
}
else
{
    if ( $ini->read_var( "eZUserMain", "RequireFirstAddress" ) == "enabled" )
    {
        if ( !isSet( $AddressID ) )
            $AddressID = array( 1 );
        if ( !isSet( $Street1 ) )
            $Street1 = array( "" );
        if ( !isSet( $Street2 ) )
            $Street2 = array( "" );
        if ( !isSet( $Zip ) )
            $Zip = array( "" );
        if ( !isSet( $Place ) )
            $Place = array( "" );
        if ( !isSet( $CountryID ) )
            $CountryID = array( $ini->read_var( "eZUserMain", "DefaultCountry" ) );

        if ( !isset( $RegionID ) )
            $RegionID = array( $ini->read_var( "eZUserMain", "DefaultRegion" ) );

        if ( !isSet( $MainAddressID ) )
        {
            $MainAddressID = 1;
        }
    }
    else
    {
        if ( !isSet( $AddressID ) )
            $AddressID = array();
    }
}

if ( !isSet( $DeleteAddressArrayID ) )
    $DeleteAddressArrayID = array();

$t->set_var( "login_value", $Login );
$t->set_var( "disabled_login_item", "" );
$t->set_var( "login_item", "" );
if ( get_class( $user ) == "ezuser" )
{
    $t->parse( "disabled_login_item", "disabled_login_item_tpl" );
}
else
{
    $t->parse( "login_item", "login_item_tpl" );
}

if ( get_class( $user ) == "ezuser" and $Password == "" )
    $Password = "dummy";

if ( get_class( $user ) == "ezuser" and $VerifyPassword == "" )
    $VerifyPassword = "dummy";

$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

if ( ( $UserPersonLink == "enabled" ) )
{
   if( !get_class( $user ) == "ezuser" ) {

//######################################################

if ( !isSet( $personID ) )
     $personID = ""; //$user->personID();

     $top_name = $t->get_var( "intl-top_category" );
     if ( !is_string( $top_name ) )
     $top_name = "";
     $companyTypeList = eZCompanyType::getTree( 0, 0, true, $top_name );
     $categoryList = array();
     $categoryList = eZPerson::companies( $personID, false );
     $category_values = array_values( $categoryList );
     $t->set_var( "is_top_selected", in_array( 0, $category_values ) ? "selected" : "" );

     foreach ( $companyTypeList as $companyTypeItem )
     {
     $t->set_var( "company_name", "[" . eZTextTool::htmlspecialchars( $companyTypeItem[0]->name() ) . "]" );
     $t->set_var( "company_id", "-1" );

     $level = $companyTypeItem[1] > 0 ? str_repeat( "&nbsp;", $companyTypeItem[1] ) : "";
     $t->set_var( "company_level", $level );
     $t->set_var( "is_selected", "" );
     $t->parse( "company_select", "company_select_tpl", true );

     $level = str_repeat( "&nbsp;", $companyTypeItem[1] + 1 );
     $t->set_var( "company_level", $level );

     $companies = eZCompany::getByCategory( $companyTypeItem[0]->id() );
      foreach ( $companies as $companyItem )
      {
       $t->set_var( "company_name", eZTextTool::htmlspecialchars( $companyItem->name() ) );
       $t->set_var( "company_id", $companyItem->id() );
       //$t->set_var( "is_selected", in_array( $companyItem->id(), $category_values ) ? "selected" : "" );
       $t->parse( "company_select", "company_select_tpl", true );
      }
     }

  //######################################################

  $t->set_var( "company_name_single", "" );
  $t->parse( "companies", "companies_tpl");
}
}else{
  $t->set_var( "companies", "" );
  $t->set_var( "company_name_value", $CompanyName );
  $t->parse( "company_name_single", "company_name_single_tpl");
}

if( !get_class( $user ) == "ezuser" ) {

$phone_types =& eZPhoneType::getAll();
$online_types =& eZOnlineType::getAll();

$address_types =& eZAddressType::getAll();

$PhoneMinimum = $ini->read_var( "eZContactMain", "PhoneMinimum" );
$OnlineMinimum = $ini->read_var( "eZContactMain", "OnlineMinimum" );

$PhoneWidth = $ini->read_var( "eZContactMain", "PhoneWidth" );
$OnlineWidth = $ini->read_var( "eZContactMain", "OnlineWidth" );


if ( !isSet( $PhoneDelete ) )
{
  $PhoneDelete = array();
}
if ( !isSet( $OnlineDelete ) )
{
  $OnlineDelete = array();
}

//######################################################

if ( isSet( $NewPhone ) )
{
  $PhoneTypeID[] = "";
  $PhoneID[] = count( $PhoneID ) > 0 ? $PhoneID[count( $PhoneID ) - 1] + 1 : 1;
  $Phone[] = "";
}

$count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
$item = 0;
$last_id = 0;
$PhoneDeleteValues =& array_values( $PhoneDelete );

for ( $i = 0; $i < $count || $item < $PhoneMinimum; $i++ )
{
  if ( ( $item % $PhoneWidth == 0 ) && $item > 0 )
    {
      $t->parse( "phone_table_item", "phone_table_item_tpl", true );
      $t->set_var( "phone_item" );
    }
  if ( !isSet( $PhoneID[$i] ) or !is_numeric( $PhoneID[$i] ) )
    $PhoneID[$i] = ++$last_id;
  if ( !in_array( $PhoneID[$i], $PhoneDeleteValues ) )
    {
      $last_id = $PhoneID[$i];
      $t->set_var( "phone_number", eZTextTool::htmlspecialchars( $Phone[$i] ) );
      $t->set_var( "phone_id", $PhoneID[$i] );
      $t->set_var( "phone_index", $PhoneID[$i] );
      $t->set_var( "phone_position", $i + 1 );

      $t->set_var( "phone_item_select", "" );

      foreach ( $phone_types as $phone_type )
	{
	  $t->set_var( "type_id", $phone_type->id() );
	  $t->set_var( "type_name", eZTextTool::htmlspecialchars( $phone_type->name() ) );
	  $t->set_var( "selected", "" );
	  if ( $phone_type->id() == $PhoneTypeID[$i] )
	    $t->set_var( "selected", "selected" );
	  $t->parse( "phone_item_select", "phone_item_select_tpl", true );
	}

      $t->parse( "phone_item", "phone_item_tpl", true );
      $item++;
    }
  else
    $PhoneDeleteValues = array_diff( $PhoneDeleteValues, array( $PhoneID[$i] ) );
}
$t->parse( "phone_table_item", "phone_table_item_tpl", true );


//######################################################

if ( isSet( $NewOnline ) )
{
  $OnlineTypeID[] = "";
  $OnlineID[] = count( $OnlineID ) > 0 ? $OnlineID[count( $OnlineID ) - 1] + 1 : 1;
  $Online[] = "";
}
$count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
$item = 0;
$last_id = 0;
$OnlineDeleteValues =& array_values( $OnlineDelete );
for ( $i = 0; $i < $count || $item < $OnlineMinimum; $i++ )
{
  if ( ( $item % $OnlineWidth == 0 ) && $item > 0 )
    {
      $t->parse( "online_table_item", "online_table_item_tpl", true );
      $t->set_var( "online_item" );
    }
  if ( !isSet( $OnlineID[$i] ) or !is_numeric( $OnlineID[$i] ) )
    $OnlineID[$i] = ++$last_id;
  if ( !in_array( $OnlineID[$i], $OnlineDeleteValues ) )
    {
      $last_id = $OnlineID[$i];
      $t->set_var( "online_value", eZTextTool::htmlspecialchars( $Online[$i] ) );
      $t->set_var( "online_id", $OnlineID[$i] );
      $t->set_var( "online_index", $OnlineID[$i] );
      $t->set_var( "online_position", $i + 1 );

      $t->set_var( "online_item_select", "" );

      foreach ( $online_types as $online_type )
	{
	  $t->set_var( "type_id", $online_type->id() );
	  $t->set_var( "type_name", eZTextTool::htmlspecialchars( $online_type->name() ) );
	  $t->set_var( "selected", "" );
	  if ( $online_type->id() == $OnlineTypeID[$i] )
	    $t->set_var( "selected", "selected" );
	  $t->parse( "online_item_select", "online_item_select_tpl", true );
	}

      $t->parse( "online_item", "online_item_tpl", true );
      $item++;
    }
  else
    $OnlineDeleteValues = array_diff( $OnlineDeleteValues, array( $OnlineID[$i] ) );
}
$t->parse( "online_table_item", "online_table_item_tpl", true );

}
//######################################################


$t->set_var( "is_cookie_selected", "$cookieCheck" );

if( ( get_class( $user ) == "ezuser" ) and $user->infoSubscription() == true )
    $InfoSubscription = "checked";
else
    $InfoSubscription = "";

$t->set_var( "info_subscription", $InfoSubscription );



if( ( get_class( $user ) == "ezuser" ) and $user->deadlineReminders() == true )
    $DeadlineReminder = "checked";
else
    $DeadlineReminder = "";

$t->set_var( "deadline_reminder", $DeadlineReminder );



if( ( get_class( $user ) == "ezuser" ) and $user->infoDisclaimer() == true )
    $InfoDisclaimer = "checked";
else
    $InfoDisclaimer = "";

$t->set_var( "info_disclaimer", $InfoDisclaimer );



if ( get_class( $user ) == "ezuser" )
    $t->set_var( "readonly", "disabled" );

$t->set_var( "address", "" );

if ( $SelectCountry == "enabled" )
    $countryList =& eZCountry::getAllArray();


/* 

Note After THis Idea Snipitt, that should replace
the below section to simplify the comlications of the list


scratch that just comment all this out , to mach ezregion

/*
if ( $SelectRegion == "enabled" and isset( $RegionID[$i] ) ) {


    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
	if ( $RegionID[$i] ) {
                //      $regionList =& eZRegion::get($i);
                // $regionList =& eZRegion::getAllArrayByCountry($country_id);
                $regionList =& eZRegion::getCountryArray($country_id);
	}else {	
                // $regionList =& eZRegion::getAllArrayByCountry($country_id);
		// $regionList =& eZRegion::getAllArray();
                $regionList =& eZRegion::getCountryArray($country_id);
                }
            }

// error with bob's version?
//        $regionList =& eZRegion::getAllByCountry($country_id);
$regionList =& eZRegion::getCountryArray($country_id);


}
*/


if ( !isSet($AddressID) ) {

    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
		foreach ( $countryList as $country )
                {
			if ( $country["ID"] == $CountryID[$i] ) {
        // $regionList =& eZRegion::getAllArrayByCountry($country["ID"]);
	   $regionList =& eZRegion::getCountryArray($country["ID"]);


			}else {
				$regionList =& eZRegion::getAllArray();
			}
    		}
    }
}else {

		$regionList =& eZRegion::getAllArray();
}

/* 
if ( $SelectRegion == "enabled" ) {
	if ( isSet( $OK ) or isSet( $OK_x ) )
	{
	    for ( $i = 0; $i < count( $AddressID ); ++$i )
	    {
		if ( $RegionID[$i] ) {
		//	$regionList =& eZRegion::get($i);
		 $regionList =& eZRegion::getAllArrayByCountry($country_id);
		}else {	
 		 $regionList =& eZRegion::getAllArrayByCountry($country_id);
//			$regionList =& eZRegion::getAllArray();
	}
    }
	$regionList =& eZRegion::getAllArrayByCountry($country_id);

	 }else {
		// not the right anserw here , just keeps the list, above still errors because i'm not doing a proper regionID is set question.
		// but default list should be based on default country like
		$regionList =& eZRegion::getAllArrayByCountry($country_id);
//		$regionList =& eZRegion::getAllArray();
	}
 $regionList =& eZRegion::getAllArrayByCountry($country_id);

}
*/

// Make sure the MainAddressID is set to something sensible
$deleted = false;
for ( $i = 0; $i < count( $AddressID ); ++$i )
{
    $address_id = $AddressID[$i];
    $variable = "DeleteAddressButton$address_id";
    if ( in_array( $AddressID[$i], $DeleteAddressArrayID ) or isSet( $$variable ) )
    {
        if ( $RealAddressID[$i] == $MainAddressID )
        {
            $deleted = true;
        }
    }
}

if ( $deleted )
{
    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
        $address_id = $AddressID[$i];
        $variable = "DeleteAddressButton$address_id";
        if ( !in_array( $AddressID[$i], $DeleteAddressArrayID ) and !isSet( $$variable ) )
        {
            $MainAddressID = $RealAddressID[$i];
            break;
        }
    }
}

// Check if we will add delete buttons
$checkArray = array_diff( $AddressID, $DeleteAddressArrayID );

if ( count( $DeleteAddressArrayID ) )
{
    // delete addresses
    foreach ( $DeleteAddressArrayID as $aid )
    {
        $delete_address = new eZAddress( $RealAddressID[$aid-1] );
        if ( $user )
            $user->removeAddress( $delete_address );
    }
}
        
// Render addresses
if ( $ini->read_var( "eZUserMain", "UserWithAddress" ) == "enabled" )
{
    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
        $address_id = $AddressID[$i];
        $variable = "DeleteAddressButton$address_id";
        if ( !in_array( $AddressID[$i], $DeleteAddressArrayID ) and !isSet( $$variable ) )
        {
            if ( is_numeric( $MainAddressID ) )
            {
                $t->set_var( "is_checked", $RealAddressID[$i] == $MainAddressID ? "checked" : "" );
            }

            if ( count ( $checkArray ) == 1 )
            {
                $t->set_var( "delete_address", "" );
                $t->set_var( "main_address", "" );
            }
            else
            {
                $t->set_var( "address_id", $RealAddressID[$i] );
                $t->parse( "main_address", "main_address_tpl" );
                $t->set_var( "address_id", $AddressID[$i] );
                $t->parse( "delete_address", "delete_address_tpl" );
            }
            $t->set_var( "address_id", $AddressID[$i] );

            $t->set_var( "real_address_id", $RealAddressID[$i] );
            
            $t->set_var( "street1_value", $Street1[$i] );
            $t->set_var( "street2_value", $Street2[$i] );
            

            $t->set_var( "zip_value", $Zip[$i] );
            $t->set_var( "place_value", $Place[$i] );
            
	    $t->set_var( "address_index", $AddressID[$i] );
	    $t->set_var( "address_position", $i + 1 );

	    $t->set_var( "address_item_select", "" );

	    foreach ( $address_types as $address_type )
	      {
		$t->set_var( "type_id", $address_type->id() );
		$t->set_var( "type_name", eZTextTool::htmlspecialchars( $address_type->name() ) );
		$t->set_var( "selected", "" );
		// debug : print( $address_type->id() ." --- ". $AddressTypeID[$i] ."<br />");
 
		if ( $address_type->id() == $AddressTypeID[$i] )
		  $t->set_var( "selected", "selected" );
		$t->parse( "address_item_select", "address_item_select_tpl", true );
	      }

            $t->set_var( "country", "" );
            if ( $SelectCountry == "enabled" )
            {
                $t->set_var( "country_option", "" );
                foreach ( $countryList as $country )
                {
                    $t->set_var( "is_selected", $country["ID"] == $CountryID[$i] ? "selected" : "" );
                    
                    $t->set_var( "country_id", $country["ID"] );
                    $t->set_var( "country_name", $country["Name"] );
                    $t->parse( "country_option", "country_option_tpl", true );
                }
                $t->parse( "country", "country_tpl" );
            }


            if ( $SelectRegion == "enabled" )
            {
				$regionList =& eZRegion::getCountryArray($CountryID[$i]);
//                              $regionList =& eZRegion::getAllArrayByCountry($CountryID[$i]);


// $regionList =& eZRegion::getAllArray();

		                $t->set_var( "region_option", "" );
				$t->set_var( "region", "" );
// online only if country has no regions, need lookup ?
if (count($regionList) >= 1) {

$t->set_var( "region_line", "" );
}
            $t->set_var( "specialFormId",$i);
				

// for new ish, bad deffault
                                $t->set_var( "region_name", "" );


	//			echo "<pre>";
	//			print_r ($regionList);
	//			echo "</pre>";
	//			exit();
				
		         foreach ( $regionList as $region )
				 if ( $region["ID"] != -1 )
				 {
	        	        	{
 	                   		$t->set_var( "is_selected", $region["ID"] == $RegionID[$i] ? "selected" : "" );
 	                     		$t->set_var( "region_id", $region["ID"] );
 	                   		$t->set_var( "region_name", $region["Name"] );
 	                   		$t->parse( "region_option", "region_option_tpl", true );
        	        		}
					$t->parse( "region", "region_tpl" );
					}
		







		
								
            }

// Old Code ... Pleast Test to Remove


/*
	$t->set_var( "region", "" );
        if ( $SelectRegion == "enabled" )
        {
            $t->set_var( "region_option", "" );
            $t->set_var( "specialFormId",$i);
//xxx

            foreach ( $regionList as $region )
            {
                $t->set_var( "is_selected", $region["ID"] == $RegionID[$i] ? "selected" : "" );
                $t->set_var( "region_id", $region["ID"] );
                $t->set_var( "region_name", $region["Name"] );
                $t->parse( "region_option", "region_option_tpl", true );
            }
            $t->parse( "region", "region_tpl" );
        }
*/
            $t->set_var( "address_number", $i + 1 );

            $t->parse( "address", "address_tpl", true );
        }
    }
    $t->parse( "address_actions", "address_actions_tpl" );
}


$t->set_var( "global_section_id", $GlobalSectionID );

$t->set_var( "user_id", $userID );
$t->set_var( "person_id", $personID );

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );

$t->pparse( "output", "user_edit_tpl" );

?>
