<?php
//
// $Id: ezaddress.php,v 1.20 2001/10/18 12:02:24 ce Exp $
//
// Definition of eZAddress class
//
// Created on: <07-Oct-2000 12:34:13 bf>
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

//!! eZAddress
//! eZAddress handles addresses.
/*!
  
  Example code:
  \code
  $address = new eZAddress(); // Create a new object.
  $address->setStreet1( "Street 1" );
  $address->setStreet2( "Street 2" );
  $address->setZip( "2348" );
  $address->setPlace( "Skien" );
  $address->setPlace( "Skien" );
  $address->setCountry( $country );
  $address->setAddressType( $addressType );
  $address->setMainAddress( $user );
  $address->store(); // Stores the object to the database
  \endcode
  \sa eZAddressType eZCompany eZPerson eZAddress eZPhone eZOnline
*/

include_once( "classes/ezdb.php" );
include_once( "ezaddress/classes/ezcountry.php" );
include_once( "ezaddress/classes/ezaddresstype.php" );

class eZAddress
{
    /*!
      Constructs a new eZAddress object.
    */
    function eZAddress( $id = "" )
    {
        if ( $id != "" )
        {

            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZAddress
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $ret = false;
        if ( $this->CountryID <= 0 )
            $country_id = "NULL";
        else
            $country_id = "$this->CountryID";

        $street1 = $db->escapeString( $this->Street1 );
        $street2 = $db->escapeString( $this->Street2 );
        $name = $db->escapeString( $this->Name );
        $place = $db->escapeString( $this->Place );
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZAddress_Address" );
			$this->ID = $db->nextID( "eZAddress_Address", "ID" );
            $res[] = $db->query( "INSERT INTO eZAddress_Address
                                  (ID, Street1, Street2, Zip, Place, CountryID, AddressTypeID, Name)
                                  VALUES
                                  ('$this->ID',
                                   '$street1',
                                   '$street2',
                                   '$this->Zip',
                                   '$place',
                                   '$country_id',
                                   '$this->AddressTypeID',
                                   '$name')" );
            $db->unlock();
            $ret = true;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZAddress_Address
                                  SET Street1='$street1',
                                  Street2='$street2',
                                  Zip='$this->Zip',
                                  Place='$place',
                                  AddressTypeID='$this->AddressTypeID',
                                  Name='$name',
                                  CountryID=$country_id
                                  WHERE ID='$this->ID'" );            
            $ret = true;            
        }

        eZDB::finish( $res, $db );
        return $ret;
    }

    /*!
      Fetches an address with object id==$id;
    */  
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $address_array, "SELECT * FROM eZAddress_Address WHERE ID='$id'" );
            if ( count( $address_array ) > 1 )
            {
                die( "Error: addresses with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $address_array ) == 1 )
            {
                $this->ID =& $address_array[ 0 ][ $db->fieldName( "ID" ) ];
                $this->Street1 =& $address_array[ 0 ][ $db->fieldName( "Street1" ) ];
                $this->Street2 =& $address_array[ 0 ][ $db->fieldName( "Street2" ) ];
                $this->Zip =& $address_array[ 0 ][ $db->fieldName( "Zip" ) ];
                $this->Place =& $address_array[ 0 ][ $db->fieldName( "Place" ) ];
                $this->CountryID =& $address_array[ 0 ][ $db->fieldName( "CountryID" ) ];
                $this->AddressTypeID =& $address_array[ 0 ][ $db->fieldName( "AddressTypeID" ) ];
                $this->Name =& $address_array[ 0 ][ $db->fieldName( "Name" ) ];
            }
            if ( $this->CountryID == "NULL" )
                $this->CountryID = -1;
        }
    }

    /*!
      Returns all the adddresses found in the database.

      The categories are returned as an array of eZAddress objects.

    */
    function getAll( )
    {
        $db =& eZDB::globalDatabase();
        $address_array = 0;
    
        $db->array_query( $address_array, "SELECT * FROM eZAddress_Address" );
    
        return $address_array;
    }

    /*!
      Delete this object from the database.
     */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZAddress_Address WHERE ID='$id'" );
        eZDB::finish( $res, $db );
    }    
    

    /*!
      Copy this object.
    */
    function &copy( )
    {
        $new = $this;
        $new->unsetID();
        $new->store();

        return $new;
    }

    /*!
      Empty this ID.
    */
    function unsetID(  )
    {
        unset( $this->ID );
    }
    
    /*!
      Sets street1 of this eZAddress object.
    */
    function setStreet1( $value )
    {
        $this->Street1 = $value;
    }

    /*!
      Sets street2 of this eZAddress object.
    */
    function setStreet2( $value )
    {
        $this->Street2 = $value;
    }

    /*!
      Sets the name of this eZAddress object.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the zip code for this eZAddress object.
    */
    function setZip( $value )
    {
        $this->Zip = $value;
    }

    /*!
      Sets the address type for this eZAddress object.
      The parameter can be the ID for the eZAddressType object a the eZAddressType object.
    */
    function setAddressType( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->AddressTypeID = $value;
        }
        
        if( get_class( $value ) == "ezaddresstype" )
        {
            $this->AddressTypeID = $value->id();
        }
    }

    /*!
      Sets the main address
      The paramenter can be the ID for the eZAddress object or a eZAddress object.
      If the eZUser object already has a eZAddress object as a main address, the address will be updated.
    */
    function setMainAddress( $mainAddress, $user )
    {
        if ( get_class( $mainAddress ) == "ezaddress" )
            $addressID = $mainAddress->id();
        else
            $addressID = $mainAddress;
        if ( get_class ( $user ) == "ezuser" )
            $userID = $user->id();
        else
            $userID = $user;

        $db =& eZDB::globalDatabase();

        $db->array_query( $checkForAddress, "SELECT UserID FROM eZAddress_AddressDefinition
                                     WHERE UserID='$userID'" );

        if ( count ( $checkForAddress ) != 0 )
        {
            $res[] = $db->query( "UPDATE eZAddress_AddressDefinition SET
                                         AddressID='$addressID',
                                         UserID='$userID'
                                         WHERE UserID='$userID'" );
        }
        else
        {
            $db->begin();
            $res[] = $db->query( "INSERT INTO eZAddress_AddressDefinition
                                  (AddressID, UserID)
                                  VALUES
                                  ('$addressID', '$userID')" );
            $db->unlock();
        }
        
        eZDB::finish( $res, $db );
    }
    
    /*!
      Returns the main address as an eZAddress object.
      The paramenter can be the ID for the eZUser object or a eZUser object.
    */
    function mainAddress( $user )
    {
        if ( get_class ( $user ) == "ezuser" )
            $userID = $user->id();
        else
            $userID = $user;

        $db =& eZDB::globalDatabase();

        $db->array_query( $addressArray, "SELECT AddressID FROM eZAddress_AddressDefinition
                                     WHERE UserID='$userID'", 0, 1 );

        if ( count( $addressArray ) == 1 )
        {
            return new eZAddress( $addressArray[0][$db->fieldName( "AddressID" )] );
        }
        else
        {
            return false;
        }
    }

    /*!
      Returns the ID eZAddress object.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns street1 for this eZAddress object.
    */
    function street1( )
    {
        return $this->Street1;
    }

    /*!
      Returns street2 for this eZAddress object.
    */
    function street2( )
    {
        return $this->Street2;
    }

    /*!
      Returns name for this eZAddress object.
    */
    function name( )
    {
        return $this->Name;
    }

    /*!
      Returns zip for this eZAddress object.
    */
    function zip( )
    {
        return $this->Zip;
    }

    /*!
      Returns addressTypeID for this eZAddress object.
    */
    function addressTypeID( $asObject=false )
    {
        if ( $asObject )
            return new eZAddress( $this->AddressTypeID );
        else
            return $this->AddressTypeID;
    }

    /*!
      Returns address type as an eZAddressType object.
    */
    function addressType()
    {
        $addressType = new eZAddressType( $this->AddressTypeID );
        return $addressType;
    }

    /*!
      Returns place for this eZAddress object.
    */
    function setPlace( $value )
    {
       $this->Place = $value;
    }

    /*!
      Sets the country, takes an eZCountry object as argument.
    */
    function setCountry( $country )
    {
       if ( get_class( $country ) == "ezcountry" )
       {
           $this->CountryID = $country->id();
       }
       else if ( is_numeric( $country ) )
       {
           $this->CountryID = $country;
       }
    }

    /*!
      Returns place for this eZAddress object.
    */
    function place()
    {
       return $this->Place;
    }

    /*!
      Returns the country as an eZCountry object.
    */
    function country()
    {
        if ( is_numeric( $this->CountryID ) and $this->CountryID > 0 )
            return new eZCountry( $this->CountryID );
        else
            return false;
    }

    
    var $ID;
    var $Street1;
    var $Street2;
    var $Zip;
    var $Place;
    var $CountryID;
    var $Name;
    
    /// Relation to an eZAddressTypeID
    var $AddressTypeID;
}

?>
