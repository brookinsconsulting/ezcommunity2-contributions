<?
// $Id: ezstatus.php,v 1.2 2001/04/10 20:07:55 wojciechp Exp $
//
// Definition of eZStatus class
//
// Created on: <28-Mar-2001 21:00:00 ce> by: Wojciech Potaczek <Wojciech@Potaczek.pl>
// Based on ezcategory.php
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTodo
//! The eZStatus handles the ToDo status information. (Done, not done)
/*!
  Handles the to do status information stored in the database. All the todo's have this status.
*/

class eZStatus
{
    /*!
      eZStatus Constructor.
    */
    function eZStatus( $id=-1, $fetch=true )
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
      Stores the status object to the database.
      Returnes the ID to the eZStatus object if the store is a success.
    */
    function store()
    {
        $this->dbInit();
	$name = addslashes( $this->Name );

        if ( !isSet( $this->ID ) )
        {

            $this->Database->query( "INSERT INTO eZTodo_Status SET
                                     ID='$this->ID',
                                     Name='$this->Name' ");
            $this->ID =  mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTodo_Status SET
                                     ID='$this->ID',
                                     Name='$this->Name'
                                     WHERE ID='$this->ID' ");
            $this->State_ = "Coherent";
        }
        return true;
    }
        

    /*!
      Deletes the status object in the database.
    */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZTodo_Status WHERE ID='$this->ID'" );

        return true;
    }

    /*!
      Gets a status object from the database, where ID == $id
    */
    function get( $id )
    {
        $this->dbInit();
        $ret = false;
        
        
        if ( $id != "" )
        {
            $this->Database->array_query( $status_array, "SELECT * FROM eZTodo_Status WHERE ID='$id'" );
            if ( count( $status_array ) > 1 )
            {
                die( "Error: More then one status with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $status_array ) == 1 )
            {
                $this->ID = $status_array[0][ "ID" ];
                $this->Name = $status_array[0][ "Name" ];
                $this->Description = $status_array[0][ "Description" ];
                $ret = true;
            }
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        
        return $ret;
    }

    /*!
      Gets all the status informasjon from the database.
      Returns the array in $status_array ordered by name.
    */
    function getAll()
    {
        $this->dbInit();

        $status_array = 0;

        $return_array = array();
        $status_array = array();

        $this->Database->array_query( $status_array, "SELECT ID FROM eZTodo_Status ORDER by Name" );

        for ( $i=0; $i<count( $status_array ); $i++ )
        {
            $return_array[$i] = new eZStatus( $status_array[$i]["ID"], 0 );
        }
        return $return_array;
    }


    /*!
      Tilte of the status.
      Returns the name of the status as a string.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return htmlspecialchars( $this->Name );
    }

    /*!
      Sets the name of the status.
      The new name of the status is passed as a paramenter ( $value ).
     */
    function setName( $value )
    {        
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Name = $value;
    }

    /*!
      Description of the status.
      Returns the description of the status as a string.
    */
    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the description of the status.
      The new description of the status is passed as a paramenter ( $value ).
     */
    function setDescription( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Description = $value;
    }
 
    /*!
      Id of the priority.
      Returns the id of the status as a string.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->ID;
    }

    /*!
      \private
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}    
    


    
    
