<?php
// 
// $Id: ezorderstatustype.php,v 1.7 2001/07/20 11:42:01 jakobn Exp $
//
// Definition of eZOrderStatus class
//
// Created on: <02-Oct-2000 15:06:32 bf>
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
    function eZOrderStatusType( $id="", $fetch=true )
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

				$this->ID = $this->Database->insertID();

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
            $this->Database->array_query( $status_type_array, "SELECT * FROM eZTrade_OrderStatusType WHERE ID='$id'" );
            if ( count( $status_type_array ) > 1 )
            {
                die( "Error: Status_type's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $status_type_array ) == 1 )
            {
                $this->ID =& $status_type_array[0][ "ID" ];
                $this->Name =& $status_type_array[0][ "Name" ];

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
      Fetches every status type and returns them as an array of eZOrderStatusType
      objects.
    */
    function getAll()
    {
        $this->dbInit();
        $ret = array();

        $this->Database->array_query( $status_type_array, "SELECT ID FROM eZTrade_OrderStatusType ORDER BY Name" );

        foreach ( $status_type_array as $status_type )
        {
            $ret[] = new eZOrderStatusType( $status_type["ID"] );
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
            $this->Database = eZDB::globalDatabase();
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
