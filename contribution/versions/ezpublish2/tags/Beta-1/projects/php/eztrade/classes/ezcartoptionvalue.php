<?php
// 
// $Id: ezcartoptionvalue.php,v 1.3 2000/10/06 09:39:42 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 15:19:13 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTrade
//! eZCartOptionValue handles option values.
/*!
  

*/

include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );

class eZCartOptionValue
{
    /*!
      Constructs a new eZCartOptionValue object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZCartOptionValue( $id="", $fetch=true )
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
      Stores a cart option value to the database.
    */
    function store()
    {
        $this->dbInit();
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_CartOptionValue SET
		                         CartItemID='$this->CartItemID',
		                         OptionID='$this->OptionID',
		                         OptionValueID='$this->OptionValueID'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_CartOptionValue SET
		                         CartItemID='$this->CartItemID',
		                         OptionID='$this->OptionID',
		                         OptionValueID='$this->OptionValueID'
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
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_CartOptionValue WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID =& $cart_array[0][ "ID" ];
                $this->CartItemID =& $cart_array[0][ "CartItemID" ];
                $this->OptionID =& $cart_array[0][ "OptionID" ];
                $this->OptionValueID =& $cart_array[0][ "OptionValueID" ];

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
    function id( )
    {
        return $this->ID;        
    }

    /*!
      Returns the cart item object.
    */
    function &cartItem()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZCartItem( $this->CartItemID );
    }

    /*!
      Returns the option object.
    */
    function &option()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZOption( $this->OptionID );
    }

    /*!
      Returns the option value object.
    */
    function &optionValue()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZOptionValue( $this->OptionValueID );
    }
    
    /*!
      Sets the cart item object id.
    */
    function setCartItem( &$cartItem )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $cartItem ) == "ezcartitem" )
       {
           $this->CartItemID = $cartItem->id();
       }
    }

    /*!
      Sets the option object id.
    */
    function setOption( &$option )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $option ) == "ezoption" )
       {
           $this->OptionID = $option->id();
       }
    }

    /*!
      Sets the option value object id.
    */
    function setOptionValue( &$optionValue )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $optionValue ) == "ezoptionvalue" )
       {
           $this->OptionValueID = $optionValue->id();
       }
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
    var $CartItemID;
    var $OptionID;
    var $OptionValueID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
