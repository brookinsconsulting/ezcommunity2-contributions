<?
// $Id: ezpriority.php,v 1.2 2000/09/08 14:00:19 ce-cvs Exp $
//
// Definition of eZPriority class
//
// <real-name> <<mail-name>>
// Created on: <04-Sep-2000 16:53:15 ce>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
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
        query( "INSERT INTO eZTodo_Priority SET
                ID='$this->ID',
                Title='$this->Title'" );
        return mysql_insert_id();
    }

    //! delete
    /*!
      Deletes the priority object in the database.
    */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZTodo_Priority WHERE ID='$this->ID'" );
    }

    //! update
    /*!
      Update the priority object in the database.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZTodo_Priority SET
                ID='$this->ID',
                Title='$this->Title'
                WHERE ID='$this->ID' ");
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
            array_query( $priority_array, "SELECT * FROM eZTodo_Priority WHERE ID='$id'" );
            if ( count( $priority_array ) > 1 )
            {
                die( "Error: Priority's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $priority_array ) == 1 )
            {
                $this->ID = $priority_array[0][ "ID" ];
                $this->Title = $priority_array[0][ "Title" ];
            }
            $this->State_ = "Coherent";
        }
    }

    //! getAll
    /*!
      Gets all the priority informasjon from the database.
      Returns the array in $priority_array ordered by title.
    */
    function getAll()
    {
        
        $this->dbInit();
        $priority_array = 0;

        $return_array = array();
        $priority_array = array();

        array_query( $priority_array, "SELECT ID FROM eZTodo_Priority ORDER BY Title" );
        
        for ( $i=0; $i<count( $priority_array ); $i++ )
        {
            $return_array[$i] = new eZPriority( $priority_array[$i]["ID"], 0 );
        }
        return $return_array;
    }


    //! title
    /*!
      Tilte of the priority.
      Returns the title of the priority as a string.
    */
    function title()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Title;
    }

    //! setTitle
    /*!
      Sets the title of the priority.
      The new title of the priority is passed as a paramenter ( $value ).
    */
    function setTitle( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Title = $value;
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

    //! dbInit
    /*!
      Private function.
      Open the database for read and write. Gets all the database informasjon from site.ini.
    */
    function dbInit()
    {
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "eZTodoMain", "Server" );
        $DATABASE = $ini->read_var( "eZTodoMain", "Database" );
        $USER = $ini->read_var( "eZTodoMain", "User" );
        $PWD = $ini->read_var( "eZTodoMain", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Title;
    var $Priority;
    var $State_;
}    
    

