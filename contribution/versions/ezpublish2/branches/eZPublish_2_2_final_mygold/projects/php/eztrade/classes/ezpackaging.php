<?php
//
// $Id: ezpackaging.php,v 1.1.2.1 2002/06/10 08:48:21 ce Exp $
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
include_once( "classes/ezdatetime.php" );
include_once( "eztrade/classes/ezvattype.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );

class eZPackingtype
{
    /*!
      Constructs a new object.
    */
    function eZPackingType( $id=-1 )
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
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        $this->Description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $timeStamp =& eZDateTime::timeStamp( true );
            $db->lock( "eZTrade_PackingType" );
            $nextID = $db->nextID( "eZTrade_PackingType", "ID" );
            $res[] = $db->query( "INSERT INTO eZTrade_PackingType
                       ( ID,
		                 Name,
                         Description,
                         ImageID,
		                 Price,
                         VATTypeID
		                  )
                       VALUES
		               ( '$nextID',
                         '$this->Name',
		                 '$this->Description',
		                 '$this->ImageID',
		                 '$this->Price',
		                 '$this->VATypeID'
		                  )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_PackingType SET
                        Name='$this->Name',
	                    Description='$this->Description',
	                    VATTypeID='$this->VATTypeID',
	                    ImageID='$this->ImageID',
	                    Price='$this->Price'
                        WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
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
            $db->array_query( $shipping_array, "SELECT * FROM eZTrade_PackingType WHERE ID='$id'" );

            if ( count( $shipping_array ) > 1 )
            {
                die( "Error: Shipping Types's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $shipping_array ) == 1 )
            {
                $this->ID =& $shipping_array[0][$db->fieldName( "ID" )];
                $this->Name =& $shipping_array[0][$db->fieldName( "Name" )];
                $this->Description =& $shipping_array[0][$db->fieldName( "Description" )];
                $this->VATTypeID =& $shipping_array[0][$db->fieldName( "VATTypeID" )];
                $this->Price =& $shipping_array[0][$db->fieldName( "Price" )];
                $this->ImageID =& $shipping_array[0][$db->fieldName( "ImageID" )];
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

        $db->array_query( $shipping_array, "SELECT ID FROM eZTrade_PackingType" );

        for ( $i=0; $i<count($shipping_array); $i++ )
        {
            $return_array[$i] = new eZPackingType( $shipping_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      Deletes a Shipping Type from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZTrade_PackingType WHERE ID='$this->ID'" );
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
      Returns the description of the vat type.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Sets the description of the vat type.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Returns the name of the vat type.
    */
    function price()
    {
        return $this->Price;
    }

    /*!
      Sets the name of the vat type.
    */
    function setPrice( $value )
    {
        $this->Price = $value;
        setType( $this->Price, "double" );
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
      Returns the VAT type id of the packing.
    */
    function vatTypeID()
    {
        print( "faen" );
        return $this->VATTypeID;
    }


    /*!
      Returns the VAT type.

      False if no type is assigned.
    */
    function &vatType( )
    {
        $user =& eZUser::currentUser();
        $ret = new eZVATType();

        $ini =& INIFile::globalINI();
        if ( $ini->read_var( "eZTradeMain", "NoUserShowVAT" ) == "enabled" )
            $useVAT = false;
        else
            $useVAT = true;

        if ( get_class ( $user ) == "ezuser" )
        {
            $mainAddress = $user->mainAddress();
            if ( get_class ( $mainAddress ) == "ezaddress" )
            {
                $country = $mainAddress->country();
                if ( ( get_class ( $country ) == "ezcountry" ) and ( !$country->hasVAT() ) )
                    $useVAT = false;
                else
                    $useVAT = true;
            }
        }

        if ( ( $useVAT ) and ( is_numeric( $this->VATTypeID ) ) and ( $this->VATTypeID > 0 ) )
        {
            $ret = new eZVATType( $this->VATTypeID );
        }

        return $ret;
    }

    var $ID;
    var $Name;
    var $Description;
    var $Price;
    var $VATTypeID;
}

?>
