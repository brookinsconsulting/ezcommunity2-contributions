<?
// $Id: ezpriority.php,v 1.1 2000/09/07 07:12:25 ce-cvs Exp $
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
    function eZPriority( $id=-1, $fetch=1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch != 1 )
            {
                $this->get();
                $this->IsCoherent = 1;                
            }
            else
            {
                $this->IsCoherent = 0;
            }
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

        array_query( $priority_array, "SELECT * FROM eZTodo_Priority ORDER BY Title" );
        return $priority_array;
    }


     //! title
    /*!
      Tilte of the priority.
      Returns the title of the priority as a string.
    */
    function title()
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        return $this->title();
    }

    //! setTitle
    /*!
      Sets the title of the priority.
      The new title of the priority is passed as a paramenter ( $value ).
     */
    function setTitle( $value )
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        $this->Title = $value;
    }

    //! id
    /*!
      Id of the priority.
      Returns the id of the priority as a string.
    */
    function id()
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        return $this->id();
    }

    //! dbInit
    /*!
      Private function.
      Open the database for read and write. Gets all the database informasjon from site.ini.
    */
    function dbInit()
    {
        include_once( "classes/class.INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Title;
    var $Priority;
}    
    

