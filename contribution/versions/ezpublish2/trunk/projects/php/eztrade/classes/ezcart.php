<?
// 
// $Id: ezcart.php,v 1.3 2000/09/28 13:15:45 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <25-Sep-2000 11:23:17 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
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
		                         SessionID='$this->SessionID',
		                         Type='$this->Type'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Cart SET
		                         SessionID='$this->SessionID',
		                         Type='$this->Type'
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
                $this->Type = $cart_array[0][ "Type" ];

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
    function getBySession( $session, $type )
    {
        $this->dbInit();

        $ret = false;
        if ( get_class( $session ) == "ezsession" )
        {        
            $sid = $session->id();
            $this->Database->array_query( $cart_array, "SELECT * FROM
                                                    eZTrade_Cart
                                                    WHERE SessionID='$sid' AND Type='$type'" );

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
      Returns the cart type.
    */
    function type()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Type;
    }

    /*!
      Sets the type of cart.

      Possible values are: Cart and WishList.
    */
    function setType( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Type = $value;
    }

    /*!
      Returns all the cart items in the cart.

      An array of eZCartItem objects are retunred if successful, false if not.
    */
    function items( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       
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
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZTradeMain" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $SessionID;
    var $Type;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
