<?php
// 
// $Id: ezorderoptionvalue.php,v 1.10 2001/07/20 11:42:01 jakobn Exp $
//
// Definition of eZOrderOptionValue class
//
// Created on: <28-Sep-2000 16:40:01 bf>
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
//! eZOrderOptionValue handles selected options for the order items.
/*!

  \sa eZOrder eZOrderItem 
*/

/*!TODO
    
*/

include_once( "classes/ezdb.php" );

class eZOrderOptionValue
{
    /*!
      Constructs a new eZOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZOrderOptionValue( $id="", $fetch=true )
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
            $this->Database->query( "INSERT INTO eZTrade_OrderOptionValue SET
		                         OrderItemID='$this->OrderItemID',
		                         OptionName='$this->OptionName',
		                         RemoteID='$this->RemoteID',
		                         ValueName='$this->ValueName'
                                 " );

			$this->ID = $this->Database->insertID();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Order SET
		                         OrderItemID='$this->OrderItemID',
		                         OptionName='$this->OptionName',
		                         RemoteID='$this->RemoteID',
		                         ValueName='$this->ValueName'
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
            $this->Database->array_query( $option_value_array, "SELECT * FROM eZTrade_OrderOptionValue WHERE ID='$id'" );
            if ( count( $option_value_array ) > 1 )
            {
                die( "Error: Option_value's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $option_value_array ) == 1 )
            {
                $this->ID =& $option_value_array[0][ "ID" ];
                $this->OrderItemID =& $option_value_array[0][ "OrderItemID" ];
                $this->OptionName =& $option_value_array[0][ "OptionName" ];
                $this->ValueName =& $option_value_array[0][ "ValueName" ];
                $this->RemoteID =& $option_value_array[0][ "RemoteID" ];

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
      Returns the option name.
    */
    function optionName( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->OptionName;
    }

    /*!
      Returns the value name.
    */
    function valueName( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ValueName;
    }

    /*!
      Returns the value name.
    */
    function remoteID( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->RemoteID;
    }
    
    /*!
      Sets the order item.
    */
    function setOrderItem( $orderItem )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $orderItem ) == "ezorderitem" )
       {
           $this->OrderItemID = $orderItem->id();
       }
    }

    /*!
      Sets the option name.
    */
    function setOptionName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->OptionName = $value;
    }
     
    /*!
      Sets the value name.
    */
    function setValueName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ValueName = $value;
    }

    /*!
      Sets the option name.
    */
    function setRemoteID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->RemoteID = $value;
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
    var $OrderItemID;
    var $OptionName;
    var $ValueName;
    var $RemoteID;    

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
