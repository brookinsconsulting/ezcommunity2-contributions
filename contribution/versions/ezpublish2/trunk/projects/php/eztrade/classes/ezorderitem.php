<?php
// 
// $Id: ezorderitem.php,v 1.14 2001/07/30 07:11:55 br Exp $
//
// Definition of eZOrderItem class
//
// Created on: <29-Sep-2000 10:27:55 bf>
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
//! eZOrderItem handles order items.
/*!

  \sa eZOrder eZOrderOptionValue
*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezorderoptionvalue.php" );

class eZOrderItem
{
    /*!
      Constructs a new eZOrderItem object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZOrderItem( $id="" )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a order to the database.
    */
    function store()
    {
        $db = eZBD::globalDatabase();
        $db->begin();
        if ( !isset( $this->ID ) )
        {
            
            $db->lock( "eZTrade_OrderItem" );
            $nextID = $db->nextID( "eZTrade_OrderItem", "ID");
            $ret[] = $db->query( "INSERT INTO eZTrade_OrderItem
                               ( ID,
		                         OrderID,
		                         Count,
		                         Price,
		                         ProductID )
                               VALUES
                               ( '$nextID',
		                         '$this->OrderID',
		                         '$this->Count',
		                         '$this->Price',
		                         '$this->ProductID' )" );

			$this->ID = $nextID;

        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_OrderItem SET
		                         OrderID='$this->OrderID',
		                         Count='$this->Count',
		                         Price='$this->Price',
		                         ProductID='$this->ProductID'
                                 WHERE ID='$this->ID'
                                 " );

        }
        eZDB::finish( $ret, $db );
        return true;
    }    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db = eZBD::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_OrderItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID =& $cart_array[0][$db->fieldName("ID")];
                $this->OrderID =& $cart_array[0][$db->fieldName("OrderID")];
                $this->Count =& $cart_array[0][$db->fieldName("Count")];
                $this->Price =& $cart_array[0][$db->fieldName("Price")];
                $this->ProductID =& $cart_array[0][$db->fieldName("ProductID")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZOrderItem object from the database.
    */
    function delete()
    {
        $db = eZBD::globalDatabase();
        $db->begin();

        $ret[] = $db->query( "DELETE FROM eZTrade_OrderOptionValue WHERE OrderItemID='$this->ID'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_OrderItem WHERE ID='$this->ID'" );

        eZDB::finish( $ret, $db );
        return true;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the count.
    */
    function count( )
    {
       return $this->Count;
    }

    /*!
      Returns the price of the order item.
    */
    function price( )
    {
       return $this->Price;
    }
    
    /*!
      Returns the product.
    */
    function product( )
    {
       $ret = new eZProduct( $this->ProductID );
            
       return $ret;
    }

    /*!
      Returns all the option values.
     */
    function optionValues( )
    {
        $db = eZBD::globalDatabase();

        $return_array = array();
       
        $db->array_query( $res_array, "SELECT ID FROM eZTrade_OrderOptionValue
                                     WHERE
                                     OrderItemID='$this->ID'
                                   " );

        foreach ( $res_array as $item )
        {
            $return_array[] = new eZOrderOptionValue( $item[$db->fieldName("ID")] );
        }
        
        return $return_array;       
    }

    /*!
      Sets the order.
    */
    function setOrder( $order )
    {
       if ( get_class( $order ) == "ezorder" )
       {
           $this->OrderID = $order->id();
       }       
    }

    /*!
      Sets the product.
    */
    function setProduct( $product )
    {
       if ( get_class( $product ) == "ezproduct" )
       {
           $this->ProductID = $product->id();
       }
    }

    /*!
      Sets the number of products.
    */
    function setCount( $value )
    {
       $this->Count = $value;
       setType( $this->Count, "integer" );
    }

    /*!
      Sets the price of one product.
    */
    function setPrice( $value )
    {
       $this->Price = $value;
       setType( $this->Price, "double" );
    }
    
    var $ID;
    var $OrderID;
    var $Count;
    var $Price;
    var $ProductID;

}

?>
