<?
// $Id: ezcategory.php,v 1.3 2000/09/12 07:54:39 bf-cvs Exp $
//
// Definition of eZCategory class
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
//! The eZCategory handles the category informasjon.
/*!
  Handles the category informasjon stored in the database. All the todo's are grouped in to categorys.
*/

class eZCategory
{
    /*!
      eZCategory Constructor.
    */
    function eZCategory( $id=-1, $fetch=true )
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

    /*!
      Stores the category object to the database.
      Returnes the ID to the eZCategory object if the store is a success.
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZTodo_Category SET
                ID='$this->ID',
                Title='$this->Title',
                Description='$this->Description'," );
        return mysql_insert_id();
    }

    /*!
      Deletes the category object in the database.
    */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZTodo_Category WHERE ID='$this->ID'" );
    }

    /*!
      Update the category object in the database.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZTodo_Category SET
                ID='$this->ID',
                Title='$this->Title',
                Description='$this->Description'
                WHERE ID='$this->ID' ");
    }

    /*!
      Gets a category object from the database, where ID == $id
    */
    function get( $id )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            array_query( $category_array, "SELECT * FROM eZTodo_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Title = $category_array[0][ "Title" ];
                $this->Description = $category_array[0][ "Description" ];
            }
                 
            $this->State_ = "Coherent";
        }
    }

    /*!
      Gets all the category informasjon from the database.
      Returns the array in $cateogry_array ordered by title.
    */
    function getAll()
    {
        $this->dbInit();

        $category_array = 0;

        $return_array = array();
        $category_array = array();

        array_query( $category_array, "SELECT ID FROM eZTodo_Category ORDER by Title" );

        for ( $i=0; $i<count( $category_array ); $i++ )
        {
            $return_array[$i] = new eZCategory( $category_array[$i]["ID"], 0 );
        }
        return $return_array;
    }


    /*!
      Tilte of the category.
      Returns the title of the category as a string.
    */
    function title()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Title;
    }

    /*!
      Sets the title of the category.
      The new title of the category is passed as a paramenter ( $value ).
     */
    function setTitle( $value )
    {        
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Title = $value;
    }

    /*!
      Description of the category.
      Returns the description of the category as a string.
    */
    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Description;
    }

    /*!
      Sets the description of the category.
      The new description of the category is passed as a paramenter ( $value ).
     */
    function setDescription( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Description = $value;
    }
 
    /*!
      Id of the priority.
      Returns the id of the category as a string.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->ID;
    }

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
    var $Description;
    var $State_;
}    
    


    
    
