<?
// 
// $Id: ezonline.php,v 1.4 2000/11/16 18:55:36 pkej-cvs Exp $
//
// Definition of eZOnline class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <09-Nov-2000 18:05:07 ce>
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
//!! eZOnline
//! eZOnline handles onlinees.
/*!

  Example code:
  \code
  $online = new eZOnline();
  $online->setURL( "domain.com/a/path" );
  $online->setURLType( http ) // http, httpd, ftp, news and mailto are currently supported.
  $online->setOnlineTypeID( 43 ); // What type of online, reads out from eZContact_OnlineType
  $online->store(); // Store or updates to the database.
  \code
  \sa eZOnlineType eZCompany eZPerson eZAddress eZPhone eZAddress
  
*/

include_once( "classes/ezdb.php" );

class eZOnline
{
    /*!
      Constructs a new eZOnline object.
    */
    function eZOnline( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( !empty( $id ) )
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
      Stores a eZOnline
    */  
    function store()
    {
        $this->dbInit();

        $ret = false;
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_Online SET
                    URL='$this->URL',
                    URLType='$this->URLType',
                    OnlineTypeID='$this->OnlineTypeID'" );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_Online SET
                    URL='$this->URL',
                    URLType='$this->URLType',
                    OnlineTypeID='$this->OnlineTypeID'
                    WHERE ID='$this->ID'" );            

            $this->State_ = "Coherent";
            $ret = true;            
        }        

        
        return $ret;
    }

    /*!
      Deletes the online where id = $this->ID
     */
    function delete()
    {
        $this->dbInit();

        $this->Database->query( "DELETE FROM eZContact_Online WHERE ID='$this->ID'" );
    }    


    /*!
      Fetches an online with object id==$id;
    */  
    function get( $id=-1 )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $online_array, "SELECT * FROM eZContact_Online WHERE ID='$id'" );
            if ( count( $online_array ) > 1 )
            {
                die( "Feil: Flere onlineer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $online_array ) == 1 )
            {
                $this->ID =& $online_array[ 0 ][ "ID" ];
                $this->URL =& $online_array[ 0 ][ "URL" ];
                $this->URLType =& $online_array[ 0 ][ "URLType" ];
                $this->OnlineTypeID =& $online_array[ 0 ][ "OnlineTypeID" ];
            }
        }
    }

    /*!
      Fetches out all the onlines thats stored in the database.
    */
    function getAll( )
    {
        $this->dbInit();    
        $online_array = 0;

        $online_array = array();
        $return_array = array();
    
        $this->Database->array_query( $online_array, "SELECT ID FROM eZContact_Online" );

        foreach ( $online_array as $addresItem )
        {
            $return_array[] = new eZOnline( $onlineItem["ID"] );
        }
    
        return $online_array;
    }

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }

    /*!
      Returns the URL of the object.
    */
    function url( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->URL;
    }

    /*!
      Returns the URLType of the object.
    */
    function urlType( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->URLType;
    }

    /*!
      Returns the OnlineTypeID of the object.
    */
    function onlineTypeID( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->OnlineTypeID;
    }

    /*!
      Sets the URL of the object.
    */
    function setURL( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->URL= $value;
    }
    
    /*!
      Sets the URLType of the object.
    */
    function setURLType( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->URLType= $value;
    }

    /*!
      Sets the OnlineTypeID of the object.
    */
    function setOnlineTypeID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->OnlineTypeID= $value;
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
    var $URL;
    var $URLTypeID;
    var $OnlineTypeID;

    /// Relation to an eZOnlineType
    var $OnlineTypeID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
