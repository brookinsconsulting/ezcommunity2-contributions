<?php
// 
// $Id: ezphone.php,v 1.7 2001/10/18 12:02:24 ce Exp $
//
// Definition of eZAddressType class
//
// Created on: <26-Jun-2001 13:40:19 ce>
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
//!
/*!

  Example code:
  \code
  // create a new phone type and set some variables.
  $phone = new eZPhone();
  $phone->setNumber( "35544435" );
  $phone->setPhoneType( $phoneType ); 
  $phone->store();
  \endcode
  \sa eZPhoneType eZCompany eZPerson eZAddress eZPhone eZAddress
*/

include_once( "classes/ezdb.php" );
include_once( "ezaddress/classes/ezphonetype.php" );

class eZPhone
{
    /*!
      Constructs a new eZPhone object.
    */
    function eZPhone( $id = "" )
    {
        if ( !empty( $id ) )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZPhone object to the database.
    */  
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $ret = false;
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAddress_Phone" );
			$this->ID = $db->nextID( "eZAddress_Phone", "ID" );
            $res[] = $db->query( "INSERT INTO eZAddress_Phone
                                  (ID, Number, PhoneTypeID)
                                  VALUES
                                  ('$this->ID',
                                   '$this->Number',
                                   '$this->PhoneTypeID')" );
            $db->unlock();
            $ret = true;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZAddress_Phone set Number='$this->Number', PhoneTypeID='$this->PhoneTypeID' WHERE ID='$this->ID' " );

            $ret = true;            
        }        
        eZDB::finish( $res, $db );
        return $ret;
    }

    /*!
      Deletes the an eZPhone object where id = $this->ID
    */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$id' " );
        eZDB::finish( $res, $db );
    }
    
    /*!
      Fetches an phone with object id==$id;
    */  
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $phone_array, "SELECT * FROM eZAddress_Phone WHERE ID='$id'" );
            if ( count( $phone_array ) > 1 )
            {
                die( "Error: Phones's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $phone_array ) == 1 )
            {
                $this->ID =& $phone_array[0][ $db->fieldName( "ID" ) ];
                $this->Number =& $phone_array[0][ $db->fieldName( "Number" ) ];
                $this->PhoneTypeID =& $phone_array[0][ $db->fieldName( "PhoneTypeID" ) ];
            }
        }
    }

    /*!
      Sets the number of the object.
    */
    function setNumber( &$value )
    {
        $this->Number = $value;
    }

    /*!
      Sets the PhoneTypeID of the object.
    */
    function setPhoneTypeID( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->PhoneTypeID = $value;
        }
        
        if( get_class( $value ) == "ezphonetype" )
        {
            $this->PhoneTypeID = $value->id();
        }
    }

    /*!
      Sets the PhoneType object of the object.
    */
    function setPhoneType( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->PhoneTypeID = $value;
        }
        
        if( get_class( $value ) == "ezphonetype" )
        {
            $this->PhoneTypeID = $value->id();
        }
    }

    /*!
      Sets the ID of the object.
    */
    function setID( $value )
    {
        $this->ID = $value;
    }

    /*!
      Returns the number of the object.
    */
    function &number( )
    {
        return $this->Number;
    }

    /*!
      Returns the phoneTypeID of the object.
    */
    function &phoneTypeID( )
    {
        return $this->PhoneTypeID;
    }

    /*!
      Returns the phoneType of the object.
    */
    function &phoneType( )
    {
        $phoneType = new eZPhoneType( $this->PhoneTypeID );
        return $phoneType;
    }

    /*!
      Returns the ID of the object.
    */
    function id( )
    {
        return $this->ID;
    }

    var $ID;
    var $Number;
    var $PhoneTypeID;
}

?>
