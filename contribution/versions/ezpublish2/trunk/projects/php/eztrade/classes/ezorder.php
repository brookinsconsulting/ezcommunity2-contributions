<?
// 
// $Id: ezorder.php,v 1.1 2000/09/30 10:17:32 bf-cvs Exp $
//
// Definition of eZOrder class
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Sep-2000 16:40:01 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTrade
//! eZOrder handles orders.
/*!

  \sa eZOrderItem eZOrderOptionValue
*/

/*!TODO
  Check implementation
    
*/

include_once( "classes/ezdb.php" );

include_once( "ezuser/classes/ezuser.php" );

class eZOrder
{

    /*!
      Constructs a new eZOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZOrder( $id="", $fetch=true )
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
            $this->Database->query( "INSERT INTO eZTrade_Order SET
		                         UserID='$this->UserID',
		                         AddressID='$this->AddressID',
		                         ShippingCharge='$this->ShippingCharge'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Order SET
		                         UserID='$this->UserID',
		                         AddressID='$this->AddressID',
		                         ShippingCharge='$this->ShippingCharge'
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
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_Order WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][ "ID" ];
                $this->UserID = $cart_array[0][ "UserID" ];
                $this->AddressID = $cart_array[0][ "AddressID" ];
                $this->ShippingCharge = $cart_array[0][ "ShippingCharge" ];

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
      Returns the user as a eZUser object.

      True is retuned if successful, false (0) if not.
    */
    function user()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       
       $user = new eZUser( );
       if ( $user->get( $this->UserID ) )
           $ret = true;

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
    var $UserID;
    var $AddressID;
    var $ShippingCharge;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
