<?
// 
// $Id: ezsex.php,v 1.1 2000/12/21 18:14:02 pkej Exp $
//
// Definition of eZSex class
//
// <Paul K Egell-Johnsen><pkej@ez.no>
// Created on: <12-Des-2000 18:00:00 pkej>
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
//!! eZCV
//! eZSex handles the sex as used in eZCV.
/*!
*/

class eZSex
{
    /*!
        Constructor.
    */
    function eZSex( $id="-1", $fetch=true)
    {
        $this->IsConnected = false;

        if ( $id != -1 )
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
        Saves this item to the database.      
    */
    function store()
    {
        $this->dbInit();

        $ret = false;
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZCV_Sex set Name='$this->Name'" );
            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZCV_Sex set Name='$this->Name' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }

    /*!
        Deletes this item from the database.
     */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZCV_Sex WHERE ID='$this->ID'" );
    }
    
    /*!
        Gets the info stored in the database.
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $address_type_array, "SELECT * FROM eZCV_Sex WHERE ID='$id'" );
            if ( count( $address_type_array ) > 1 )
            {
                die( "Feil: Flere addresstype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $address_type_array ) == 1 )
            {
                $this->ID = $address_type_array[ 0 ][ "ID" ];
                $this->Name = $address_type_array[ 0 ][ "Name" ];
            }
            else
            {
                $this->ID = "";
                $this->State_ = "New";
            }
        }
    }

    /*!
        Gets all items of this type saved in the database.
    */
    function getAll( )
    {
        $this->dbInit();    
        $online_type_array = 0;

        $address_type_array = array();
        $return_array = array();
    
        $this->Database->array_query( $address_type_array, "SELECT ID FROM eZCV_Sex" );

        foreach( $address_type_array as $addressTypeItem )
        {
            $return_array[] = new eZSex( $addressTypeItem["ID"] );
        }
    
        return $return_array;
    }

    /*!
        Sets the name of this item.
    */
    function setName( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Name = $value;
    }

    /*!
        Returns the name of this item.
    */
    function name(  )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Name;
    }

    /*!
      Returns the id of this item.
    */
    function id(  )
    {
        return $this->ID;
    }
    
    /*!
      \private
      Open the database.
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
    var $Name;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
