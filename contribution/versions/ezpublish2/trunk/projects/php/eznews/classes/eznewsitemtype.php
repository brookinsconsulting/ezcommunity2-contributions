<?
// 
// $Id: eznewsitemtype.php,v 1.1 2000/09/14 10:36:20 pkej-cvs Exp $
//
// Definition of eZNewsItemType class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <14-Sep-2000 11:24:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsItemType handles typing of news items.
/*!
    The eZNewsItemType identifies what different kind of news items that
    can be used in a eZNews system. In addition to the typing info it
    stores information about which tables and classes are used to handle
    that type.
    
    Example of usage:
    \code
    \endcode
    
    \sa
*/

include_once( "classes/ezdb.php" );

class eZNewsItemType
{
    /*!
      Constructs a new eZNewsItemType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZNewsItemType( $id=-1, $fetch=true )
    {
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
      Stores a eZNewsItemType object to the database.

      Returns the ID to the stored type.
    */
    function store( $reason="created")
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZNews_ItemType SET
                                 Name='$this->Name',
                                 eZClass='$this->eZClass',
                                 eZTable='$this->eZTable');
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
            $itemtype_array = array();
            
            $query="
            SELECT * FROM eZNews_ItemType
            WHERE ID='$id'
            ";
            
            $this->Database->array_query( $itemtype_array, $query );
            
            if ( count( $itemtype_array ) > 1 )
            {
                die( "Error: Item type's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $itemtype_array ) == 1 )
            {
                $this->ID = $itemtype_array[0][ "ID" ];
                $this->Name = $itemtype_array[0][ "Name" ];
                $this->eZClass = $itemtype_array[0][ "eZClass" ];
                $this->eZTable = $itemtype_array[0][ "eZTable" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }






    /*!
      Returns all the item types found in the database.

      The item types are returned as an array of eZNewsItemType objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $itemtype_array = array();
        
        $query="
        SELECT ID FROM eZNews_ItemType ORDER BY Name
        ";
        
        $this->Database->array_query( $itemtype_array, $query );
        
        for ( $i=0; $i<count($itemtype_array); $i++ )
        {
            $return_array[$i] = new eZNewsItemType( $itemtype_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Returns the object ID to the item type. This is the unique ID stored in the database.
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
      Returns the name of the item type.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }



    /*!
      Returns the Class of the item type.
    */
    function class()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->eZClass;
    }



    /*!
      Returns the Table of the item type.
    */
    function table()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->eZTable;
    }



    /*!
      Sets the name of the item type.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }



    /*!
      Sets the class of the item type.
    */
    function setClass( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->eZClass = $value;
    }



    /*!
      Sets the table of the item type.
    */
    function setTable( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->eZTable = $value;
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
    var $eZClass;
    var $eZTable;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
