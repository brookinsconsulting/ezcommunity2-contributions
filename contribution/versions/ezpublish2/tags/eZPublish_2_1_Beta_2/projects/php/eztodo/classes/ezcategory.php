<?
// $Id: ezcategory.php,v 1.9 2001/04/04 16:28:31 fh Exp $
//
// Definition of eZCategory class
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
      Stores the category object to the database.
      Returnes the ID to the eZCategory object if the store is a success.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );

        if ( !isSet( $this->ID ) )
        {

            $this->Database->query( "INSERT INTO eZTodo_Category SET
                                     ID='$this->ID',
                                     Name='$name' ");
            $this->ID =  mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTodo_Category SET
                                     ID='$this->ID',
                                     Name='$name'
                                     WHERE ID='$this->ID' ");
            $this->State_ = "Coherent";
        }
        return true;
    }
        

    /*!
      Deletes the category object in the database.
    */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZTodo_Category WHERE ID='$this->ID'" );

        return true;
    }

    /*!
      Gets a category object from the database, where ID == $id
    */
    function get( $id )
    {
        $this->dbInit();
        $ret = false;
        
        
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZTodo_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Name = $category_array[0][ "Name" ];
                $this->Description = $category_array[0][ "Description" ];
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
      Gets all the category informasjon from the database.
      Returns the array in $cateogry_array ordered by name.
    */
    function getAll()
    {
        $this->dbInit();

        $category_array = 0;

        $return_array = array();
        $category_array = array();

        $this->Database->array_query( $category_array, "SELECT ID FROM eZTodo_Category ORDER by Name" );

        for ( $i=0; $i<count( $category_array ); $i++ )
        {
            $return_array[$i] = new eZCategory( $category_array[$i]["ID"], 0 );
        }
        return $return_array;
    }


    /*!
      Tilte of the category.
      Returns the name of the category as a string.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return htmlspecialchars( $this->Name );
    }

    /*!
      Sets the name of the category.
      The new name of the category is passed as a paramenter ( $value ).
     */
    function setName( $value )
    {        
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Name = $value;
    }

    /*!
      Description of the category.
      Returns the description of the category as a string.
    */
    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return htmlspecialchars( $this->Description );
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
    


    
    
