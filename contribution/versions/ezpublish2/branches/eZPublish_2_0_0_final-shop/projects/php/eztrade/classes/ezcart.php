<?
// 
// $Id: ezcart.php,v 1.14.2.2 2001/03/21 12:35:36 pkej Exp $
//
// Definition of eZCart class
//
// Bård Farstad <bf@ez.no>
// Created on: <25-Sep-2000 11:23:17 bf>
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
//! eZCart handles a shopping cart
/*!

  Example:
  \code

  // Create a new cart
  $cart = new eZCart();
  $cart->setSession( $session );

  // Store the cart to the database
  $cart->store();
  
  // Fetch all cart items
  $items = $cart->items();
  
  // print contents of the cart if it exists
  if  ($items )
  {
      foreach ( $items as $item )
      {
          $product = $item->product();
          print( $product->name() . "<br>");
      }
  }

  \endcode
  \sa eZCartItem eZProductCategory eZOption
*/

include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezcartitem.php" );

class eZCart
{
    /*!
      Constructs a new eZCart object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZCart( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
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
      Stores a cart to the database.
    */
    function store()
    {
        $this->dbInit();

        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_Cart SET
		                         SessionID='$this->SessionID'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Cart SET
		                         SessionID='$this->SessionID'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_Cart WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][ "ID" ];
                $this->SessionID = $cart_array[0][ "SessionID" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }


    /*!
      Returns a eZCart object. 
    */
    function getBySession( $session  )
    {
        $this->dbInit();

        $ret = false;
        if ( get_class( $session ) == "ezsession" )
        {        
            $sid = $session->id();
            $this->Database->array_query( $cart_array, "SELECT * FROM
                                                    eZTrade_Cart
                                                    WHERE SessionID='$sid'" );

            if ( count( $cart_array ) == 1 )
            {
                $ret = new eZCart( $cart_array[0]["ID"] );
            }

        }
        return $ret;
    }

    /*!
      Deletes a eZCart object from the database.

    */
    function delete()
    {
        $this->dbInit();

        $items = $this->items();

        if  ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }
            
        $this->Database->query( "DELETE FROM eZTrade_Cart WHERE ID='$this->ID'" );
            
        return true;
    }

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }

    /*!
      Sets the session the cart belongs to.

      Return false if the applied argument is not and eZSession object.
    */
    function setSession( $session )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $session ) == "ezsession" )
        {
            $this->SessionID = $session->id();
        }
    }

    /*!
      Returns all the cart items in the cart.

      An array of eZCartItem objects are retunred if successful, an empty array.
    */
    function items( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = array();
       
       $this->dbInit();

       $this->Database->array_query( $cart_array, "SELECT * FROM
                                                    eZTrade_CartItem
                                                    WHERE CartID='$this->ID'" );

       if ( count( $cart_array ) > 0 )
       {
           $return_array = array();
           foreach ( $cart_array as $item )
           {
               $return_array[] = new eZCartItem( $item["ID"] );               
           }
           $ret = $return_array;
       }

       return $ret;       
    }

    /*!
      Calculates the shipping cost with the given
      shippint type.

      The argument must be a eZShippingType object.
    */
    function shippingCost( $shippingType )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $items =& $this->items( );
       $ShippingCostValues = array();
       
       foreach ( $items as $item )
       {
           $product =& $item->product();
           $shippingGroup =& $product->shippingGroup();
           if ( $shippingGroup )
           {
               $values =& $shippingGroup->startAddValue( $shippingType );
           
               for ( $i=0; $i<$item->count(); $i++  )
               {
                   $ShippingCostValues[] = $values;
                   
               }
           }           
       }

       
       // find the max start value
       $max = 0;
       $maxIndex = -1;
       $i=0;
       foreach ( $ShippingCostValues as $value )
       {
           if ( $value["StartValue"] > $max )
           {
               $maxIndex = $i;
               $max = $value["StartValue"];
           }
           
           $i++;
       }

       // calculate the shipping cost.
       $cost = 0;
       $i=0;
       foreach ( $ShippingCostValues as $value )
       {
           if ( $i == $maxIndex )
           {
               $cost += $max;
           }
           else
           {
               $cost += $value["AddValue"];
           }
        
           $i++;
       }

       return $cost;
    }

    /*!
      Returns the shipping VAT. That is the VAT value
      of the shipping cost.

      The argument must be a eZShippingType object.
    */
    function shippingVAT( $shippingType )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $shippingType ) == "ezshippingtype" )
       {
           $vatType =& $shippingType->vatType();

           $shippingCost = $this->shippingCost( $shippingType );
       
           $shippingVAT = 0;
           if ( $vatType )
           {
               $value =& $vatType->value();
               $shippingVAT = ( $shippingCost / ( $value + 100  ) ) * $value;
           }
       }

       return $shippingVAT;
    }

    /*!
      Empties out the cart.
    */
    function clear()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $items = $this->items();

       // delete the option values and cart items
       foreach ( $items as $item )
       {
           $itemID = $item->id();
           $this->Database->query( "DELETE FROM
                                eZTrade_CartOptionValue
                                WHERE CartItemID='$itemID'" );

           $this->Database->query( "DELETE FROM
                                eZTrade_CartItem
                                WHERE ID='$itemID'" );
       }

       $this->delete();       
    }

  
    /*!
      \private
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $SessionID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
