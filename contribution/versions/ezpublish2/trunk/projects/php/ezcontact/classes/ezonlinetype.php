<?
// 
// $Id: ezonlinetype.php,v 1.5 2001/01/11 21:41:40 jb Exp $
//
// Definition of eZOnline class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <09-Nov-2000 18:44:38 ce>
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
//!! eZOnlineType
//! eZOnlineType handles online types.
/*!

  Example code:
  \code
  $onlinetype = new eZOnlineType();
  $onlinetype->setURL( "/a/path/here" );
  $onlinetype->setURLType( http ) // http, httpd, ftp, news and mailto are currently supported.
  $onlinetype->setOnlineTypeID( 43 ); // What type of online, reads out from eZContact_OnlineType
  $onlinetype->store(); // Store or updates to the database.
  \code
  \sa eZOnlineType eZCompany eZPerson eZOnline eZPhone eZOnline
  
*/

class eZOnlineType
{
    /*!
      Constructs a new eZOnlineType object.
    */
    function eZOnlineType( $id="-1", $fetch=true )
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
      Stores a eZOnline object to the database.
    */
    function store()
    {
        $this->dbInit();

        $ret = false;
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_OnlineType set Name='$this->Name'" );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_OnlineType set
                                     Name='$this->Name'
                                     ListOrder='$this->ListOrder'
                                     WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }

    /*
      Deletes from the database where id = $this->ID
     */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZContact_OnlineType WHERE ID='$this->ID'" );
    }
    
  /*
    Fetches out a online type where id = $id
  */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $online_type_array, "SELECT * FROM eZContact_OnlineType WHERE ID='$id'" );
            if ( count( $online_type_array ) > 1 )
            {
                die( "Feil: Flere onlinetype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $online_type_array ) == 1 )
            {
                $this->ID = $online_type_array[ 0 ][ "ID" ];
                $this->Name = $online_type_array[ 0 ][ "Name" ];
                $this->ListOrder = $online_type_array[ 0 ][ "ListOrder" ];
            }
            else
            {
                $this->ID = "";
                $this->State_ = "New";
            }
        }
    }

    /*
    Fetches out all the online types that is stored in the database.
  */
    function getAll( )
    {
        $this->dbInit();    
        $online_type_array = 0;

        $online_type_array = array();
        $return_array = array();
    
        $this->Database->array_query( $online_type_array, "SELECT ID FROM eZContact_OnlineType ORDER BY ListOrder" );

        foreach ( $online_type_array as $onlineTypeItem )
        {
            $return_array[] = new eZOnlineType( $onlineTypeItem["ID"] );
        }
    
        return $return_array;
    }

  /*!
    Sets the name of the object.
  */
    function setName( $value )
    {
        $this->Name = $value;
    }

  /*!
    Returns the name of the object.
  */
    function name(  )
    {
        return $this->Name;
    }

    /*!
      Returns the id of the object.
    */
    function id(  )
    {
        return $this->ID;
    }
    
    /*!
      Returns the number of external items using this item.
    */

    function count()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $company_qry, "SELECT count( CompanyID ) as Count FROM eZContact_CompanyOnlineDict WHERE OnlineID='$this->ID'" );
        $db->query_single( $person_qry, "SELECT count( PersonID ) as Count FROM eZContact_PersonOnlineDict WHERE OnlineID='$this->ID'" );
        return $company_qry["Count"] + $person_qry["Count"];
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_OnlineType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_OnlineType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_OnlineType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_OnlineType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_OnlineType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_OnlineType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
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
    var $ListOrder;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
 
}

?>
