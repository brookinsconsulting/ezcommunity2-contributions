<?
// 
// $Id: eznewschangetype.php,v 1.3 2000/09/28 08:27:14 pkej-cvs Exp $
//
// Definition of eZNewsChangeType class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <14-Sep-2000 11:40:37 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsChangeType handles change types for the eZNews log.
/*!
    The eZNewsChangeType class is used to identify what kind of
    changes one can do to a news item. The change type info can
    be used to find certain types of changes (for example who
    deleted what) to a news item.
    
    The change type is stored in a change ticket.
    
    Example of usage:
    \code
    \endcode
    
    \sa eZNewsItem eZNewsChangeTicket
*/

include_once( "classes/ezdb.php" );

class eZNewsChangeType
{
    /*!
      Constructs a new eZNewsChangeType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZNewsChangeType( $id=-1, $fetch=true )
    {
        $this->dbInit();
        
        $IsConnected = false;
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
      Stores a eZNewsChangeType object to the database.

      Returns the ID to the stored type.
    */
    function store( $reason="created")
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZNews_ChangeType SET
                                 Name='$this->Name'");
        $this->ID = mysql_insert_id();

        return $this->ID;
    }



    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "-1" )
        {
            $changetype_array = array();
            
            $query="
            SELECT * FROM eZNews_ChangeType
            WHERE ID='$id'
            ";
            
            $this->Database->array_query( $changetype_array, $query );
            
            if ( count( $changetype_array ) > 1 )
            {
                die( "Error: Change type's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $changetype_array ) == 1 )
            {
                $this->ID = $changetype_array[0][ "ID" ];
                $this->Name = $changetype_array[0][ "Name" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }



    /*!
      Returns all the change types found in the database.

      The change types are returned as an array of eZNewsChangeType objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $changetype_array = array();
        
        $query="
        SELECT ID FROM eZNews_ChangeType ORDER BY Name
        ";
        
        $this->Database->array_query( $changetype_array, $query );
        
        for ( $i=0; $i<count($changetype_array); $i++ )
        {
            $return_array[$i] = new eZNewsChangeType( $changetype_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Returns the object ID to the change type. This is the unique ID stored in the database.
    */
    function id()
    {
        $returnValue = 0;
        if ( $this->State_ != "New" )
        {
            $returnValue=$this->ID;
        }
       
        return $returnValue;
    }



    /*!
      Returns the name of the change type.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }



    /*!
      Sets the name of the change type.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }



    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZNewsMain" );
            $IsConnected = true;
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
