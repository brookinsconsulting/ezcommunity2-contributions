<?
// $Id: ezpriority.php,v 1.7 2001/04/04 16:28:31 fh Exp $
//
// Definition of eZPriority class
//
// <real-name> <<mail-name>>
// Created on: <04-Sep-2000 16:53:15 ce>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTodo
//! The eZPriority handles the priority informasjon.
/*!
  Handles the priority informasjon stored in the database. All the todo's have a priority status.
*/
class eZPriority
{
    //! eZPriority
    /*!
      eZPriority Constructor.
    */
    function eZPriority( $id=-1, $fetch=true )
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

    //! store
    /*!
      Stores the priority object to the database.
      Returnes the ID to the eZPriority object if the store is a success.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        if ( !isSet ( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTodo_Priority SET
                                     ID='$this->ID',
                                     Name='$name'" );
            return mysql_insert_id();
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTodo_Priority SET
                                     ID='$this->ID',
                                     Name='$name'
                                     WHERE ID='$this->ID' ");
            $this->State_ = "Coherent";
        }

        return true;
    }

    //! delete
    /*!
      Deletes the priority object in the database.
    */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZTodo_Priority WHERE ID='$this->ID'" );
    }

    //! get
    /*!
      Gets a priority object from the database, where ID == $id
    */
    function get( $id )
    {
        
        $this->dbInit();
        if ( $id != "" )
        {
            $this->Database->array_query( $priority_array, "SELECT * FROM eZTodo_Priority WHERE ID='$id'" );
            if ( count( $priority_array ) > 1 )
            {
                die( "Error: Priority's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $priority_array ) == 1 )
            {
                $this->ID = $priority_array[0][ "ID" ];
                $this->Name = $priority_array[0][ "Name" ];

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

    //! getAll
    /*!
      Gets all the priority informasjon from the database.
      Returns the array in $priority_array ordered by name.
    */
    function getAll()
    {
        
        $this->dbInit();
        $priority_array = 0;

        $return_array = array();
        $priority_array = array();

        $this->Database->array_query( $priority_array, "SELECT ID FROM eZTodo_Priority ORDER BY Name" );
        
        for ( $i=0; $i<count( $priority_array ); $i++ )
        {
            $return_array[$i] = new eZPriority( $priority_array[$i]["ID"], 0 );
        }
        return $return_array;
    }


    //! name
    /*!
      Tilte of the priority.
      Returns the name of the priority as a string.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return htmlspecialchars( $this->Name );
    }

    //! setName
    /*!
      Sets the name of the priority.
      The new name of the priority is passed as a paramenter ( $value ).
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Name = $value;
    }

    //! id
    /*!
      Id of the priority.
      Returns the id of the priority as a string.
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
    var $Priority;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}    
    

