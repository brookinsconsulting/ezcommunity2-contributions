<?
// 
// $Id: ezperson.php,v 1.28 2000/11/23 14:00:49 pkej-cvs Exp $
//
// Definition of eZPerson class
//
// <real-name><<email-name>>
// Created on: <09-Nov-2000 14:52:40 ce>
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

//!! eZPerson
//! eZPerson handles a persons belonging in contacts information.

//!! eZPerson
//!
/*!
  Denne klassen håndterer personer i eZ contact. Disse lagres og hentes ut fra databasen.
*/

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezdb.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezonline.php" );

class eZPerson
{
    /*!
      Constructs a new eZPerson object.
      
      If $id is set, the object's values are fetched from the
      database.
    */
    function eZPerson( $id="", $fetch=true  )
    {
        $this->IsConnected = false;
        if( !empty( $id ) )
        {
            $this->ID = $id;
            if( $fetch == true )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }
  
    /*!
      Stores a person to the database. 
    */  
    function store()
    {
        $this->dbInit();
        if( !isSet( $this->ID ) )
        {
        
            $this->Database->query( "INSERT INTO eZContact_Person set
                                                    FirstName='$this->FirstName',
                                                    LastName='$this->LastName',
	                                                Comment='$this->Comment',
	                                                PersonNo='$this->PersonNo',
	                                                BirthDate='$this->BirthDate',
                                                    ContactTypeID='$this->ContactType',
	                                                CreatorID='$this->Creator'" );
            $this->ID = mysql_insert_id();            
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_Person set
                                                    FirstName='$this->FirstName',
                                                    LastName='$this->LastName',
	                                                Comment='$this->Comment',
	                                                PersonNo='$this->PersonNo',
	                                                BirthDate='$this->BirthDate',
                                                    ContactTypeID='$this->ContactType',
                                               	    CreatorID='$this->Creator' WHERE ID='$this->ID'" );
            $this->State_ = "Coherent";
        }
    }


    /*!
      Deletes an eZPerson from the database.
    */
    function delete()
    {
        $this->dbInit();

        if( isSet( $this->ID ) )
        {
            // Delete real world addresses

            $this->Database->array_query( $address_array, "SELECT eZContact_Address.ID AS 'AID', eZContact_PersonAddressDict.ID AS 'DID'
                                               FROM eZContact_Address, eZContact_PersonAddressDict
                                               WHERE eZContact_Address.ID=eZContact_PersonAddressDict.AddressID AND eZContact_PersonAddressDict.PersonID='$this->ID' " );

            foreach( $address_array as $addressItem )
            {
                $addressID = $addressItem["AID"];
                $addressDictID = $addressItem["DID"];
                $this->Database->query( "DELETE FROM eZContact_Address WHERE ID='$addressID'" );
                $this->Database->query( "DELETE FROM eZContact_PersonAddressDict WHERE ID='$addressDictID'" );
            }
           
            // Delete phone numbers.

            $this->Database->array_query( $phone_array, "SELECT eZContact_Phone.ID AS 'PID', eZContact_PersonPhoneDict.ID AS 'DID'
                                     FROM eZContact_Phone, eZContact_PersonPhoneDict
                                     WHERE eZContact_Phone.ID=eZContact_PersonPhoneDict.PhoneID AND eZContact_PersonPhoneDict.PersonID='$this->ID' " );

            foreach( $phone_array as $phoneItem )
            {
                $phoneID = $phoneItem["PID"];
                $phoneDictID = $phoneItem["DID"];
                $this->Database->query( "DELETE FROM eZContact_Phone WHERE ID='$phoneID'" );
                $this->Database->query( "DELETE FROM eZContact_PersonPhoneDict WHERE ID='$phoneDictID'" );
            }
            
            // Delete online address.

            $this->Database->array_query( $online_array, "SELECT eZContact_Online.ID AS 'OID', eZContact_PersonOnlineDict.ID AS 'DID'
                                     FROM eZContact_Online, eZContact_PersonOnlineDict
                                     WHERE eZContact_Online.ID=eZContact_PersonOnlineDict.OnlineID AND eZContact_PersonOnlineDict.PersonID='$this->ID' " );

            foreach( $online_array as $onlineItem )
            {
                $onlineID = $onlineItem["OID"];
                $onlineDictID = $onlineItem["DID"];
                $this->Database->query( "DELETE FROM eZContact_Online WHERE ID='$onlineDictID'" );
                $this->Database->query( "DELETE FROM eZContact_PersonOnlineDict WHERE ID='$onlineDictID'" );
            }
            
            $this->Database->query( "DELETE FROM eZContact_Person WHERE ID='$this->ID'" );
        }
        return true;

    }


    /*
      Henter ut person med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if( $id != "" )
        {
            $this->Database->array_query( $person_array, "SELECT * FROM eZContact_Person WHERE ID='$id'" );
            if( count( $person_array ) > 1 )
            {
                die( "Feil: Flere personer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if( count( $person_array ) == 1 )
            {
                $this->ID = $person_array[ 0 ][ "ID" ];
                $this->FirstName = $person_array[ 0 ][ "FirstName" ];
                $this->LastName = $person_array[ 0 ][ "LastName" ];
                $this->Creator = $person_array[ 0 ][ "CreatorID" ];
                $this->ContactType = $person_array[ 0 ][ "ContactTypeID" ];
                $this->BirthDate = $person_array[ 0 ][ "BirthDate" ];
                $this->Comment = $person_array[ 0 ][ "Comment" ];
                $this->PersonNo = $person_array[ 0 ][ "PersonNo" ];
            }
        }
    }
    
    /*
        Fetches the person with the USER ID == $id
     */
    function getByUserID( $id )
    {
        $this->dbInit();
        
        $query = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID='$id'";

        $this->Database->array_query( $person_array, $query );
        foreach( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem["PersonID"], false );
        }
        
        return $return_array;
    }
    
    /*!
      Fetches all persons in the database.
    */
    function getAll( )
    {
        $this->dbInit();
        $person_array = 0;
    
        $this->Database->array_query( $person_array, "SELECT ID FROM eZContact_Person ORDER BY LastName" );

        foreach( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem["ID"] );
        }
        return $return_array;
    }

    /*!
      Fetches all persons whith first name or last name equal to the query string.
    */
    function search( $query )
    {
        $this->dbInit();    
        $person_array = 0;
    
        $this->Database->array_query( $person_array, "SELECT * FROM eZContact_Person WHERE FirstName LIKE '%$query%' OR LastName LIKE '%$query%' ORDER BY LastName" );
    
        foreach( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem["ID"] );
        }
        return $return_array;
    }
    
    /*!
      Returns the address that belong to this eZPerson object.
    */
    function addresses( $personID )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $address_array, "SELECT AddressID
                                                 FROM eZContact_PersonAddressDict
                                                 WHERE PersonID='$personID'" );

        foreach( $address_array as $addressItem )
        {
            $return_array[] = new eZAddress( $addressItem["AddressID"] );
        }

        return $return_array;
    }

    /*!
      Adds an address to the current Person.
    */
    function addAddress( $address )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $address ) == "ezaddress" )
        {
            $addressID = $address->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonAddressDict WHERE AddressID='$addressID'";
            
            $this->Database->array_query( $address_array, $checkQuery );

            $count = count( $address_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZContact_PersonAddressDict
                                SET PersonID='$this->ID', AddressID='$addressID'" );
            }
            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the phones that belong to this eZPerson object.
    */
    function phones( $personID )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $phone_array, "SELECT PhoneID
                                                 FROM eZContact_PersonPhoneDict
                                                 WHERE PersonID='$personID'" );

        foreach( $phone_array as $phoneItem )
            {
                $return_array[] = new eZPhone( $phoneItem["PhoneID"] );
            }

        return $return_array;
    }

    /*!
      Adds an phone to the current Person.
    */
    function addPhone( $phone )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $phone ) == "ezphone" )
        {
            $phoneID = $phone->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonPhoneDict WHERE PhoneID='$phoneID'";

            $this->Database->array_query( $phone_array, $checkQuery );

            $count = count( $phone_array );
            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZContact_PersonPhoneDict
                                SET PersonID='$this->ID', PhoneID='$phoneID'" );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the onlines that belong to this eZPerson object.
    */
    function onlines()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $online_array, "SELECT OnlineID
                                                 FROM eZContact_PersonOnlineDict
                                                 WHERE PersonID='$personID'" );

        foreach( $online_array as $onlineItem )
        {
            $return_array[] = new eZOnline( $onlineItem["OnlineID"] );
        }

        return $return_array;
    }

    /*!
      Adds an online to the current Person.
    */
    function addOnline( $online )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();

        if( get_class( $online ) == "ezonline" )
        {
            $onlineID = $online->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonOnlineDict WHERE OnlineID='$onlineID'";
            
            $this->Database->array_query( $online_array, $checkQuery );

            $count = count( $online_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZContact_PersonOnlineDict
                                SET PersonID='$this->ID', OnlineID='$onlineID'" );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the user that belong to this eZPerson object.
    */
    function user()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $user_array, "SELECT UserID
                                                 FROM eZContact_UserPersonDict
                                                 WHERE PersonID='$this->ID'" );

        foreach( $user_array as $userItem )
        {
            $return_array[] = new eZUser( $userItem["UserID"] );
        }

        return $return_array;
    }

    /*!
      Adds a user to the current Person.
    */
    function addUser( $user )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
        
        $this->dbInit();

        if( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();
            
            $checkQuery = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID=$userID";
            $this->Database->array_query( $user_array, $checkQuery );
            
            $count = count( $user_array );
            
            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZContact_UserPersonDict
                                SET PersonID='$this->ID', UserID='$userID'" );
            }
            $ret = true;
        }
        return $ret;
    }

    /*!
        Set the first name of this object to $value.
     */
    function setFirstName( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->FirstName = $value;
    }

    /*!
        Set the last name of this object to $value.
     */
    function setLastName( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->LastName = $value;
    }

    /*!
        Set the comment of this object to $value.
     */
    function setComment( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Comment = $value;
    }

    /*!
        Set the contact type of this object to $value.
     */
    function setContactType( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ContactType = $value;
    }

    /*!
        Set the creator of this object to $value. $value is a user id.
     */
    function setCreator( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Creator = $value;
    }
  
  
    /*!
        Set the birth day of this object to $value.
     */
    function setBirthDay( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->BirthDate = $value;
    }
  
  
    /*!
        Set the person number of this object to $value. This number is different
        from country to country and also called different things.
        
        It is the equivalent of the US social security number, in other words
        the unique number the government is using to identify a person by.
     */
    function setPersonNo( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->PersonNo = $value;
    }
  
    /*!
      Returns the ID of the person.
    */
    function id()
    {
        return $this->ID;
    }
  
    /*!
      Returns the first name of the person.
    */
    function firstName()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->FirstName;
    }

    /*!
      Returns the last name of the person.
    */
    function lastName()
    {    
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->LastName;
    }

    /*!
      Returns the person number of the person.
    */
    function personNo()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PersonNo;
    }

    /*!
      Returns the comment for this person.
    */
    function comment( )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Comment;
    }

    /*!
      Returns the contact type of this person.
    */
    function contactType( )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ContactType;
    }

    /*!
      Returns the creator (user id) of this person.
    */
    function creator( )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Creator;
    }
  
    /*!
      Returns the birthday of this person.
    */
    function birthDate( )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->BirthDate;
    }
  
    /*!
      \private
      Used by this class to connect to the database.
    */
    function dbInit()
    {
        if( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $FirstName;
    var $LastName;
    var $BirthDate;  
    var $Creator;
    var $PersonNo;
    var $ContactType;
    var $Comment;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
};

?>
