<?
// 
// $Id: ezpreorder.php,v 1.1 2001/03/15 17:18:16 bf Exp $
//
// Definition of eZPreOrder class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Mar-2001 18:11:55 bf>
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
//! eZOrder handles pre-orders.
/*!
  Pre orders is a handler for unique ID's for checkouts. This is needed
  because the orders are not created until the payment is done and
  we need a unique ID for the checkout (VISA clearing etc). This is to prevent
  double charging of the clients.

  \sa eZOrderItem
*/

include_once( "classes/ezdb.php" );


class eZPreOrder
{
    /*!
      Constructs a new eZPreOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZPreOrder( $id="" )
    {
        $this->IsConnected = false;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
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
            $this->Database->query( "INSERT INTO eZTrade_PreOrder SET
		                         Created=now()
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_PreOrder SET
		                         Created=Created
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Deletes a eZOrder object from the database.
    */
    function delete()
    {
        $this->dbInit();
        
        $this->Database->query( "DELETE FROM eZTrade_PreOrder WHERE ID='$this->ID'" );
            
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
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_PreOrder WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Pre order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][ "ID" ];
                $this->Created = $cart_array[0][ "Created" ];
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
      Returns the order date as a eZDateTime object.
    */
    function date(   )
    {
       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->Date );
       
       return $dateTime;
    }    
    
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      \private
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
    var $Created;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
