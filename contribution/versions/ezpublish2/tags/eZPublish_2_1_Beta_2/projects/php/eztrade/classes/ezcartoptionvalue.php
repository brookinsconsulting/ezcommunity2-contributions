<?php
// 
// $Id: ezcartoptionvalue.php,v 1.7 2001/03/27 13:49:42 ce Exp $
//
// Definition of eZCartOptionValue class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 15:19:13 bf>
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
            $this->get( $this->ID );
        }
        else
        {
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
		                         RemoteID='$this->RemoteID',
		                         OptionValueID='$this->OptionValueID'
                                 " );

            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_CartOptionValue SET
		                         CartItemID='$this->CartItemID',
		                         OptionID='$this->OptionID',
		                         OptionValueID='$this->OptionValueID'
                                 WHERE ID='$this->ID'
                                 " );
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
                $this->RemoteID =& $cart_array[0][ "RemoteID" ];
                $this->OptionValueID =& $cart_array[0][ "OptionValueID" ];

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
      Returns the object remoteID.
    */
    function remoteID( )
    {
        return $this->RemoteID;        
    }

    /*!
      Returns the cart item object.
    */
    function &cartItem()
    {
       return new eZCartItem( $this->CartItemID );
    }

    /*!
      Returns the option object.
    */
    function &option()
    {
       return new eZOption( $this->OptionID );
    }

    /*!
      Returns the option value object.
    */
    function &optionValue()
    {
       return new eZOptionValue( $this->OptionValueID );
    }
    
    /*!
      Sets the cart item object id.
    */
    function setCartItem( &$cartItem )
    {
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
       if ( get_class( $option ) == "ezoption" )
       {
           $this->OptionID = $option->id();
       }
    }

    /*!
      Sets the option object id.
    */
    function setRemoteID( $value )
    {
        $this->RemoteID = $value;
    }

    /*!
      Sets the option value object id.
    */
    function setOptionValue( &$optionValue )
    {
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
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $CartItemID;
    var $OptionID;
    var $OptionValueID;
    var $RemoteID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
