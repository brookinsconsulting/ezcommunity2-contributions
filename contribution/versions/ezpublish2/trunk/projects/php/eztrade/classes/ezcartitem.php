<?php
// 
// $Id: ezcartitem.php,v 1.4 2000/10/25 19:21:41 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 15:19:05 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
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
include_once( "eztrade/classes/ezproduct.php" );

class eZCartItem
{
    /*!
      Constructs a new eZCart object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZCartItem( $id="", $fetch=true )
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
            $this->Count = 1;
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
            $this->Database->query( "INSERT INTO eZTrade_CartItem SET
		                         ProductID='$this->ProductID',
		                         CartID='$this->CartID',
		                         Count='$this->Count'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_CartItem SET
		                         ProductID='$this->ProductID',
		                         CartID='$this->CartID',
		                         Count='$this->Count'
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
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_CartItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][ "ID" ];
                $this->ProductID = $cart_array[0][ "ProductID" ];
                $this->CartID = $cart_array[0][ "CartID" ];
                $this->Count = $cart_array[0][ "Count" ];

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
      Deletes a eZCartItem object from the database.

    */
    function delete()
    {
        $this->dbInit();
            
        $this->Database->query( "DELETE FROM eZTrade_CartOptionValue WHERE CartItemID='$this->ID'" );

        $this->Database->query( "DELETE FROM eZTrade_CartItem WHERE ID='$this->ID'" );
            
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       return $this->Count;
    }

    /*!
      Sets the product.
    */
    function setProduct( $product )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Count = $count;
    }
    
    /*!
      Returns all the option values as an array of eZCartOptionValue objects.

      An empty array is returned if none exists.
    */
    function optionValues( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $return_array = array();
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT ID FROM eZTrade_CartOptionValue
                                     WHERE
                                     CartItemID='$this->ID'
                                   " );

       foreach ( $res_array as $item )
       {
           $return_array[] = new eZCartOptionValue( $item["ID"] );
       }
       return $return_array;
    }
    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $ProductID;
    var $CartID;
    var $Count;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
    
}

?>
