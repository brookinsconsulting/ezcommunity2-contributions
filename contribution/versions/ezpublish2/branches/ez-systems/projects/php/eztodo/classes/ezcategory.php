<?
// $Id: ezcategory.php,v 1.1 2000/09/07 07:12:25 ce-cvs Exp $
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
    //! eZCategory
    /*!
      eZCategory Constructor.
    */
    function eZCategory( $id=-1, $fetch=1 )
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

    //! delete
    /*!
      Deletes the category object in the database.
    */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZTodo_Category WHERE ID='$this->ID'" );
    }

    //! update
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

    //! get
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
            else if( count( $catgory_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Title = $category_array[0][ "Title" ];
                $this->Description = $category_array[0][ "Description" ];
            }
        }
    }

    //! getAll
    /*!
      Gets all the category informasjon from the database.
      Returns the array in $cateogry_array ordered by title.
    */
    function getAll()
    {
        $this->dbInit();
        $cateogry_array = 0;

        array_query( $cateogry_array, "SELECT * FROM eZTodo_Category ORDER BY Title" );
        return $cateogry_array;
    }


     //! title
    /*!
      Tilte of the category.
      Returns the title of the category as a string.
    */
    function title()
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        return $this->title();
    }

    //! setTitle
    /*!
      Sets the title of the category.
      The new title of the category is passed as a paramenter ( $value ).
     */
    function setTitle( $value )
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        $this->Title = $value;
    }

    //! description
    /*!
      Description of the category.
      Returns the description of the category as a string.
    */
    function description()
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        return $this->description();
    }

    //! setDescription
    /*!
      Sets the description of the category.
      The new description of the category is passed as a paramenter ( $value ).
     */
    function setDescription( $value )
    {
        if ( $this->IsCoherent == 0 )
            $this->get();
        $this->Description = $value;
    }
 
    //! id
    /*!
      Id of the priority.
      Returns the id of the category as a string.
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
    var $Description;
}    
    


    
    
