<?
// 
// $Id: ezshippingtype.php,v 1.1 2001/02/22 14:57:42 bf Exp $
//
// Definition of eZShippingType class
//
// B�rd Farstad <bf@ez.no>
// Created on: <22-Feb-2001 11:48:05 bf>
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


//!! eZTrade
//! This class handles shipping types.
/*!
  Shipping types is different kinds of shipping methods,
  e.g. Fed. Ex. and DHL could be different shipping types.
  
  \sa eZProduct
*/

include_once( "classes/ezdb.php" );

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
		                 Created=now()" );
        
            $this->ID = mysql_insert_id();
        }
        else
        {
            $db->query( "UPDATE eZTrade_ShippingType SET
                        Name='$this->Name',
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
            $return_array[$i] = new eZShippingType( $shipping_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a Shipping Type from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZTrade_ShippingType WHERE ID='$this->ID'" );
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

    var $ID;
    var $Name;
}

?>
