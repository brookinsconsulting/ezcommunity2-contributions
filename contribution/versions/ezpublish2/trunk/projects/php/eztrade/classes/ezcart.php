<?php
// 
// $Id: ezcart.php,v 1.23 2001/08/21 11:21:41 ce Exp $
//
// Definition of eZCart class
//
// Created on: <25-Sep-2000 11:23:17 bf>
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
  if  ( $items )
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
    function eZCart( $id = "" )
    {
        $PersonID = 0;
        $CompanyID = 0;
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a cart to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_Cart" );
            $nextID = $db->nextID( "eZTrade_Cart", "ID" );            

            $res = $db->query( "INSERT INTO eZTrade_Cart
                                ( ID,
                                  SessionID,
                                  PersonID,
                                  CompanyID )
                                VALUES
                                ( '$nextID',
                                  '$this->SessionID',
                                  '$this->PersonID',
                                  '$this->CompanyID' )
                               " );
            $db->unlock();

            $this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_Cart SET
		                         SessionID='$this->SessionID',
                                 PersonID='$this->PersonID',
                                 CompanyID='$this->CompanyID',
                                 WHERE ID='$this->ID'
                                 " );
        }
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }    

    /*!
      Returns the person we are shopping for
    */
    function personID()
    {
        return $this->PersonID;
    }

    /*!
      Returns the company we are shopping for
    */
    function companyID()
    {
        return $this->CompanyID;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_Cart WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName( "ID" )];
                $this->SessionID = $cart_array[0][$db->fieldName( "SessionID" )];
                $this->PersonID = $cart_array[0][$db->fieldName( "PersonID" )];
                $this->CompanyID = $cart_array[0][$db->fieldName( "CompanyID" )];
                $ret = true;
            }
        }
        return $ret;
    }


    /*!
      Returns a eZCart object. 
    */
    function getBySession( $session  )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( get_class( $session ) == "ezsession" )
        {
            $sid = $session->id();

            $db->array_query( $cart_array, "SELECT * FROM eZTrade_Cart WHERE SessionID='$sid'" );

            if ( count( $cart_array ) == 1 )
            {
                $ret = new eZCart( $cart_array[0][$db->fieldName( "ID" )] );
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZCart object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $items = $this->items();

        if ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }
            
        $res = $db->query( "DELETE FROM eZTrade_Cart WHERE ID='$this->ID'" );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
            
        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Sets the session the cart belongs to.

      Return false if the applied argument is not and eZSession object.
    */
    function setSession( $session )
    {
        if ( get_class( $session ) == "ezsession" )
        {
            $this->SessionID = $session->id();
        }
    }

    /*!
      Sets the person we are shopping for
    */
    function setPersonID( $userID )
    {
        if ( get_class( $userID ) == "ezuser" )
            $id = $userID->ID();
        else
            $id = $userID;
        if ( is_numeric( $id ) )
        {
            $this->PersonID = $id;
        }
    }
            
    /*!
      Sets the company we are shopping for
    */
    function setCompanyID( $id )
    {
        if ( is_numeric( $id ) )
        {
            $this->CompanyID = $id;
        }
    }            

    /*!
      Returns all the cart items in the cart.

      An array of eZCartItem objects are retunred if successful, an empty array.
    */
    function items( )
    {
       $ret = array();
       
       $db =& eZDB::globalDatabase();

       $db->array_query( $cart_array, "SELECT * FROM
                                       eZTrade_CartItem
                                       WHERE CartID='$this->ID'" );

       if ( count( $cart_array ) > 0 )
       {
           foreach ( $cart_array as $item )
           {
               $ret[] = new eZCartItem( $item[$db->fieldName( "ID" )] );               
           }
       }
       if ( $ret )
           return $ret;
       else
           return array();
    }

    /*!
      Calculates the shipping cost with the given
      shippint type.

      The argument must be a eZShippingType object.
    */
    function shippingCost( $shippingType )
    {
       $items =& $this->items( );
       $ShippingCostValues = array();

       foreach ( $items as $item )
       {
           $product =& $item->product();
           $shippingGroup =& $product->shippingGroup();
           if ( $shippingGroup )
           {
               $values =& $shippingGroup->startAddValue( $shippingType );

               $shipid = $shippingGroup->id();
               $count = $item->count() + $ShippingCostValues[$shipid]["Count"];
               $ShippingCostValues[$shipid]["Count"] = $count;
               $ShippingCostValues[$shipid]["ID"] = $shipid;
               $ShippingCostValues[$shipid]["Values"] = $values;
           }
       }
       $cost = 0;

       $max = 0;
       // Find largest start sum first
       foreach( $ShippingCostValues as $value )
       {
           $val = $value["Values"]["StartValue"];
           if ( $val > $max )
           {
               $max = $val;
               $max_id = $value["ID"];
           }
       }
       $cost += $max;
//         if ( isset( $max_id ) )
//         {
//             print( $max );
//         }

       foreach ( $ShippingCostValues as $value )
       {
           $count = $value["Count"];
           if ( $value["ID"] == $max_id )
               --$count;
           // Add additional values if any
           $cost += $value["Values"]["AddValue"]*$count;
//             print( "+ " . $value["Values"]["AddValue"]*$count ."(".$value["Values"]["AddValue"]."*".$count.") " );
       }
//         print( "= $cost" );
//         print( "<pre>" );
//         print_r( $ShippingCostValues );
//         print( "</pre>" );
//         exit();

       return $cost;
    }

    /*!
      Returns the shipping VAT. That is the VAT value
      of the shipping cost.

      The argument must be a eZShippingType object.
    */
    function shippingVAT( $shippingType )
    {
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
       $db =& eZDB::globalDatabase();
       $db->begin();
       
       $items = $this->items();

       // delete the option values and cart items
       foreach ( $items as $item )
       {
           $itemID = $item->id();
           $res[] = $db->query( "DELETE FROM
                                eZTrade_CartOptionValue
                                WHERE CartItemID='$itemID'" );

           $res[] = $db->query( "DELETE FROM
                                eZTrade_CartItem
                                WHERE ID='$itemID'" );
       }

       if ( in_array( false, $res ) )
           $db->rollback( );
       else
           $db->commit();            

       $this->delete();       
    }

    var $ID;
    var $SessionID;
    var $PersonID;
    var $CompanyID;
}

?>
