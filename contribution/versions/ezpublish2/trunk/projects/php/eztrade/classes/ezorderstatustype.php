<?
// 
// $Id: ezorderstatustype.php,v 1.1 2000/10/02 13:53:01 bf-cvs Exp $
//
// Definition of eZOrderStatus class
//
// Bård Farstad <bf@ez.no>
// Created on: <02-Oct-2000 15:06:32 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTrade
//! eZOrderStatusType handles order status types.
/*!

  \sa eZOrder eZOrderStatus
*/

/*!TODO
  Add documentation.
    
*/

include_once( "classes/ezdb.php" );

class eZOrderStatusType
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
      Stores a order status  to the database.
      \return true if successful, false if not
    */
    function store()
    {
        $this->dbInit();

        $ret = false;
        if ( $this->Name != "" )
        {
            if ( !isset( $this->ID ) )
            {
                $this->Database->query( "INSERT INTO eZTrade_OrderStatusType SET
		                         Name='$this->Name'
                                 " );

                $this->ID = mysql_insert_id();

                $this->State_ = "Coherent";
                $ret = true;
            }
            else
            {
                $this->Database->query( "UPDATE eZTrade_OrderStatusType SET
		                         Name='$this->Name'
                                 " );

                $this->State_ = "Coherent";
                $ret = true;                
            }
        }
        
        return $ret;
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
            $this->Database->array_query( $order_array, "SELECT * FROM eZTrade_OrderStatusType WHERE ID='$id'" );
            if ( count( $order_array ) > 1 )
            {
                die( "Error: Order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $order_array ) == 1 )
            {
                $this->ID =& $order_array[0][ "ID" ];
                $this->Name =& $order_array[0][ "Name" ];

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
      Returns the name of the status type.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       return $this->Name;
    }

    /*!
      Sets the status type name
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }
    

    /*!
      Returns the eZOrderStatusObject to the status matching name given as
      argument. false is returned if none is found.
    */
    function getByName( $name )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $ret = false;
       $this->dbInit();

       $this->Database->array_query( $value_array, "SELECT ID FROM eZTrade_OrderStatusType
                                                    WHERE Name='$name'" );       

       if ( count( $value_array ) == 1 )
       {
           $ret = new eZOrderStatusType( $value_array[0]["ID"] );
       }

       return $ret;
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
    var $Name;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
