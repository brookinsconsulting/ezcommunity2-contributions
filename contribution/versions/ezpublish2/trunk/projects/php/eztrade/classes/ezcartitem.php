<?php
// 
// $Id: ezcartitem.php,v 1.14 2001/07/19 12:44:26 ce Exp $
//
// Definition of eZCartItem class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 15:19:05 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
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
//! eZCartItem handles a shopping cart
/*!
  Example code:
  \code
  $product = new eZProduct( 3 );

  $cartItem = new eZCartItem();
  $cartItem->setProduct( $product );
  $cartItem->setCart( $cart );

  // Store to the database
  $cartItem->store();

  \endcode
  \sa eZCart
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );

include_once( "eztrade/classes/ezwishlistitem.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

class eZCartItem
{
    /*!
      Constructs a new eZCart object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZCartItem( $id="" )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->Count = 1;
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
            $db->lock( "eZTrade_CartItem" );
            $nextID = $db->nextID( "eZTrade_CartItem", "ID" );            
            
            $res = $db->query( "INSERT INTO eZTrade_CartItem
                      ( ID, ProductID, CartID, Count, WishListItemID )
                      VALUES
                      ( '$nextID',
                        '$this->ProductID',
                        '$this->CartID',
                        '$this->Count',
                        '$this->WishListItemID'
                      " );

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_CartItem SET
		                         ProductID='$this->ProductID',
		                         CartID='$this->CartID',
		                         Count='$this->Count',
		                         WishListItemID='$this->WishListItemID'
                                 WHERE ID='$this->ID'
                                 " );
        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
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
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_CartItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName( "ID" )];
                $this->ProductID = $cart_array[0][$db->fieldName( "ProductID" )];
                $this->CartID = $cart_array[0][$db->fieldName( "CartID" )];
                $this->Count = $cart_array[0][$db->fieldName( "Count" )];
                $this->WishListItemID = $cart_array[0][$db->fieldName( "WishListItemID" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZCartItem object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
            
        $res[] = $db->query( "DELETE FROM eZTrade_CartOptionValue WHERE CartItemID='$this->ID'" );
        
        $res[] = $db->query( "DELETE FROM eZTrade_CartItem WHERE ID='$this->ID'" );
        
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();            

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
      Returns the product to the cart item as an eZProduct object.

    */
    function product()
    {
       $ret = false;

       $prod = new eZProduct( );
       if ( $prod->get( $this->ProductID ) )
       {
           $ret = $prod;
       }

       return $ret;       
    }

    /*!
      Returns the cart.
    */
    function cart()
    {
       $ret = false;

       $cart = new eZCart( );
       if ( $cart->get( $this->CartID ) )
       {
           $ret = $cart;
       }

       return $ret;       
    }

    /*!
      Returns the product count.
    */
    function count( )
    {
        return $this->Count;
    }

    /*!
      Returns the price of the cart item.

      Options and count is calculated if not disabled.
    */
    function price( $calcCount=true )
    {
        $optionValues =& $this->optionValues();
        $product =& $this->product();

        $optionPrice = 0.0;
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();            

            // the pricegroup is set in the datasupplier
            
            $PriceGroup = $GLOBALS["PriceGroup"];

            // get the value price if exists
            $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup,
            $option->id(), $value->id() );
        
            $found_price = false;

            if ( $price )
            {
                $found_price = true;
            }
            
            // if not fetch the standard price
            if ( !$found_price )
            {
                $price = $value->price();
            }
            
            $optionPrice += $price;
        }

        if ( $calcCount == true )
        {
            $price = ( $product->price() + $optionPrice )  * $this->count();
        }
        else
        {
            $price = ( $product->price() + $optionPrice );
        }            

        return $price;        
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
      Sets the cart.
    */
    function setCart( $cart )
    {
       if ( get_class( $cart ) == "ezcart" )
       {
           $this->CartID = $cart->id();
       }        
    }

    /*!
      Sets the number of products.
    */
    function setCount( $count )
    {
       $this->Count = $count;
    }

    /*!
      Sets the wishlist item.
    */
    function setWishListItem( $wishlist )
    {
       if ( get_class( $wishlist ) == "ezwishlistitem" )
       {
           $this->WishListItemID = $wishlist->id();
       }
    }

    /*!
      Returns the wishlist item as a eZWishListItem object. 0 if the 
    */
    function &wishListItem()
    {
       $ret = false;
       
       if ( ( $this->WishListItemID != 0 ) && is_numeric( $this->WishListItemID ) )
       {
           $ret = new eZWishListItem( $this->WishListItemID );
       }

       return $ret;
    }
    
    
    /*!
      Returns all the option values as an array of eZCartOptionValue objects.

      An empty array is returned if none exists.
    */
    function &optionValues( )
    {
       $return_array = array();
       $db =& eZDB::globalDatabase();
       
       $db->array_query( $res_array, "SELECT ID FROM eZTrade_CartOptionValue
                                     WHERE
                                     CartItemID='$this->ID'
                                   " );

       foreach ( $res_array as $item )
       {
           $return_array[] = new eZCartOptionValue( $item[$db->fieldName( "ID" )] );
       }
       return $return_array;
    }
    
    var $ID;
    var $ProductID;
    var $CartID;
    var $Count;

    /// ID to a wishlist item. Indicates which wishlistitem the cart item comes from. 0 if added from product.
    var $WishListItemID;
}

?>
