<?php
// 
// $Id: ezshippingtype.php,v 1.7 2001/07/20 11:42:01 jakobn Exp $
//
// Definition of eZShippingType class
//
// Created on: <22-Feb-2001 11:48:05 bf>
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


//!! eZTrade
//! This class handles shipping types.
/*!
  Shipping types is different kinds of shipping methods,
  e.g. Fed. Ex. and DHL could be different shipping types.
  
  \sa eZProduct
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezvattype.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );

class eZShippingType
{
    /*!
      Constructs a new object.
    */
    function eZShippingType( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores/updates the Shipping type in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZTrade_ShippingType SET
		                 Name='$this->Name',
		                 VATTypeID='$this->VATTypeID',
		                 IsDefault='$this->IsDefault',
		                 Created=now()" );
        
			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZTrade_ShippingType SET
                        Name='$this->Name',
  	                    VATTypeID='$this->VATTypeID',
  	                    IsDefault='$this->IsDefault',
  	                    Created=Created
                        WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Fetches the Shipping Type from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1  )
        {
            $db->array_query( $shipping_array, "SELECT * FROM eZTrade_ShippingType WHERE ID='$id'" );
            
            if ( count( $shipping_array ) > 1 )
            {
                die( "Error: Shipping Types's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $shipping_array ) == 1 )
            {
                $this->ID =& $shipping_array[0][ "ID" ];
                $this->Name =& $shipping_array[0][ "Name" ];
                $this->VATTypeID =& $shipping_array[0][ "VATTypeID" ];
                $this->IsDefault =& $shipping_array[0][ "IsDefault" ];
            }
        }
    }

    /*!
      Retrieves all the VAT types from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $shipping_array = array();
        
        $db->array_query( $shipping_array, "SELECT ID FROM eZTrade_ShippingType ORDER BY Created" );
        
        for ( $i=0; $i<count($shipping_array); $i++ )
        {
            $return_array[$i] = new eZShippingType( $shipping_array[$i]["ID"] );
        }
        
        return $return_array;
    }

    /*!
      Deletes a Shipping Type from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZTrade_ShippingValue WHERE ShippingTypeID='$this->ID'" );

        $db->query( "DELETE FROM eZTrade_ShippingType WHERE ID='$this->ID'" );
    }

    /*!
      Sets this shipping type to be the default shipping type.
    */
    function setAsDefault()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "UPDATE eZTrade_ShippingType SET IsDefault='0', Created=Created" );
        $db->query( "UPDATE eZTrade_ShippingType SET IsDefault='1', Created=Created WHERE ID='$this->ID'" );
    }

    /*!
      Returns true if this is the default type.
    */
    function isDefault()
    {
        if ( $this->IsDefault == 0 )
            return false;
        else
            return true;
    }

    /*!
      Returns the default shipping type.
    */
    function &defaultType()
    {
        $db =& eZDB::globalDatabase();
        
        $shipping_array = array();
        
        $db->array_query( $shipping_array, "SELECT ID FROM eZTrade_ShippingType WHERE IsDefault='1'" );

        $ret = false;
        if ( count( $shipping_array ) == 1 )
        {
            $ret = new eZShippingType( $shipping_array[0]["ID"] );
        }
        
        return $ret;
    }
    

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the vat type.
    */
    function name()
    {
        return $this->Name;
    }


    /*!
      Sets the name of the vat type.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the VAT type.
    */
    function setVATType( $type )
    {
        if ( get_class( $type ) == "ezvattype" )
        {
           $this->VATTypeID = $type->id();
        }
    }


    /*!
      Returns the VAT type.

      False if no type is assigned.
    */
    function &vatType( )
    {
        $ret = false;
        if ( is_numeric( $this->VATTypeID ) and ( $this->VATTypeID > 0 ) )
        {
            $ret = new eZVATType( $this->VATTypeID );
        }
        
        return $ret;
    }
    
    var $ID;
    var $Name;
    var $VATTypeID;
    var $IsDefault;
}

?>
