<?
// 
// $Id: eznewsitemtype.php,v 1.2 2000/09/28 08:27:15 pkej-cvs Exp $
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
    function eZNewsItemType( $id = -1, $fetch = true )
    {
        $this->IsConnected = false;

        if ( $id != -1 )
        {
            $this->ID = $id;

            if ( $fetch == true )
            {
                $this->get( $id );
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
      Fetches the object information from the database based on unique identifiers.
    */
    function get( $inID = -1 )
    {
        isset( $returnError );
        
        $this->dbInit();

        if( ( $this->State_ != "Altered" && $this->isAlteredCheck != true ) )
        {
            $query =
            "
                SELECT
                    *
                FROM
                    eZNews_ItemType
                WHERE
                    ID='%s'
            ";
            $query=sprintf( $query, $inID );
            $this->Database->array_query( $itemTypeArray, $query );
            $rowsFound = count( $itemTypeArray );

            switch ( $rowsFound )
            {
                case (0):
                    $this->State_ = "Don't Exist";
                    $returnError = "The row uniquely identified by $value in column $column does not exist";
                    break;
                case (1):
                    $this->ID       = $itemTypeArray[0][ "ID" ];
                    $this->Name     = $itemTypeArray[0][ "Name" ];
                    $this->eZTable  = $itemTypeArray[0][ "eZTable" ];
                    $this->eZClass  = $itemTypeArray[0][ "eZClass" ];
                    break;
                default:
                    die( "Error: News item's with the same  was found in the database. This shouldent happen.<br>" );
                    break;
            }
           
            $this->State_ = "Coherent";
        }
        else
        {
            if ( $this->State_ == "Altered" && $this->isAlteredCheck == true )
            {
                $returnError[] = "You have altered this object and not stored the changes. Turn off isAlteredCheck if you don't like this behaviour (default is off)";
            }
        }
        
        return $returnError;
    }


    function exists( $name )
    {
        $returnValue = false;
        
        $this->dbInit();
        
        $query =
        "
            SELECT
                *
            FROM
                eZNews_ItemType
            WHERE
                Name = '%s'
        ";

        $query=sprintf( $query, $name );
        $this->Database->array_query( $itemTypeArray, $query );
        $rowsFound = count( $itemTypeArray );

        switch( $rowsFound )
        {
            case (1):
                $this->get( $itemTypeArray[0][ "ID" ] );
                $returnValue = true;
                break;
            default:
                break;
        }
        return $returnValue;
    }

    function existsClass( $name )
    {
        $returnValue = false;
        
        $this->dbInit();
        
        $query =
        "
            SELECT
                *
            FROM
                eZNews_ItemType
            WHERE
                eZClass = '%s'
        ";

        $query=sprintf( $query, $name );
        $this->Database->array_query( $itemTypeArray, $query );
        $rowsFound = count( $itemTypeArray );

        switch( $rowsFound )
        {
            case (1):
                $this->get( $itemTypeArray[0][ "ID" ] );
                $returnValue = true;
                break;
            default:
                break;
        }
        return $returnValue;
    }

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZNewsMain" );
            $this->IsConnected = true;
        }
    }
    function ID()
    {
        return $this->ID;
    }
    
    function setName( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->Name = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }
    
    function name()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->Name;
    }
    function seteZClass( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->eZClass = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }
    
    function eZClass()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->eZClass;
    }
    function seteZTable( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->eZTable = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }
    
    function eZTable()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->eZTable;
    }

    
    var $ID;
    var $Name;
    var $eZClass;
    var $eZTable;
    
    /// Error variables
    
    var $InvariantError = array();
    
    /// SQL Queries and such.
    
    var $SQL = array(
        "insert_item" => "INSERT INTO eZNews_ItemType Name='%s', eZClass='eZ%s', eZTable='eZNews_%s'",
        "get_itemtype" => "SELECT * FROM eZNews_ItemType WHERE %s='%s'",
        "get_itemtypes" => "SELECT ID FROM eZNews_ItemType %s %s",
        );
    
    var $Columns = array(
        "id" => "ID",
        "name" => "Name",
        "class" => "eZClass",
        "table" => "eZTable"
        );
    
    var $OrderBy = array(
        "none" => "",
        "name" => "ORDER BY Name",
        "id" => "ORDER BY ID",
        "class" => "ORDER BY eZClass",
        "table" => "ORDER BY eZTable",
        "forward" => "ASC",
        "reverse" => "DESC",
        );
    
    /// Preferences
    
    /// Check altered flag before getting data. Default is false.
    var $isAlteredCheck = false;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has a database connection, false if not.
    var $IsConnected;

}

?>
