<?
// 
// $Id: ezorderitem.php,v 1.4 2000/10/03 09:45:18 bf-cvs Exp $
//
// Definition of eZOrderItem class
//
// Bård Farstad <bf@ez.no>
// Created on: <29-Sep-2000 10:27:55 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
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
    function eZOrderItem( $id="", $fetch=true )
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
      Stores a order to the database.
    */
    function store()
    {
        $this->dbInit();
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_OrderItem SET
		                         OrderID='$this->OrderID',
		                         Count='$this->Count',
		                         Price='$this->Price',
		                         ProductID='$this->ProductID'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_OrderItem SET
		                         OrderID='$this->OrderID',
		                         Count='$this->Count',
		                         Price='$this->Price',
		                         ProductID='$this->ProductID'
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
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_OrderItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID =& $cart_array[0][ "ID" ];
                $this->OrderID =& $cart_array[0][ "OrderID" ];
                $this->Count =& $cart_array[0][ "Count" ];
                $this->Price =& $cart_array[0][ "Price" ];
                $this->ProductID =& $cart_array[0][ "ProductID" ];

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Count;
    }

    /*!
      Returns the product.
    */
    function product( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = new eZProduct( $this->ProductID );
            
       return $ret;
    }

    /*!
      Returns all the option values.
     */
    function optionValues( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $return_array = array();
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT ID FROM eZTrade_OrderOptionValue
                                     WHERE
                                     OrderItemID='$this->ID'
                                   " );

       foreach ( $res_array as $item )
       {
           $return_array[] = new eZOrderOptionValue( $item["ID"] );
       }
       return $return_array;
       
    }

    /*!
      Sets the order.
    */
    function setOrder( $order )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Count = $value;
       setType( $this->Count, "integer" );
    }

    /*!
      Sets the price of one product.
    */
    function setPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Price = $value;
       setType( $this->Price, "double" );
    }
    
    /*!
      \private
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
    var $OrderID;
    var $Count;
    var $Price;
    var $ProductID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
