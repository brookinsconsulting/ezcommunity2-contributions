<?
// $Id: eztodo.php,v 1.7 2000/11/20 13:21:17 ce-cvs Exp $
//
// Definition of eZTodo class
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
//! The eZTodo handles the todo informasjon. 
/*!
  Handles the todo informasjon stored in the database.
*/

include_once( "classes/ezdatetime.php" );

class eZTodo
{
    //! eZTodo
    /*!
      eZtodo Constructor.
    */
    function eZTodo( $id=-1, $fetch=true )
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
      Stores the todo object to the database.
      Returnes the ID to the eZCompany object if the store is a success.
    */
    function store()
    {
        $this->dbInit();
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTodo_Todo SET
                                     ID='$this->ID',
                                     Name='$this->Name',
                                     Text='$this->Text',
                                     Category='$this->Category',
                                     Priority='$this->Priority', 
                                     Due='$this->Due',
                                     UserID='$this->UserID',
                                     OwnerID='$this->OwnerID',
                                     Done='$this->Done',
                                     Date=now(),
                                     Permission='$this->Permission'" );
            $this->ID =  mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTodo_Todo SET
                                     Name='$this->Name',
                                     Text='$this->Text',
                                     Category='$this->Category',
                                     Priority='$this->Priority',
                                     Due='$this->Due',
                                     UserID='$this->UserID',
                                     OwnerID='$this->OwnerID',
                                     Done='$this->Done',
                                     Permission='$this->Permission',
                                     Date=Date
                                     WHERE ID='$this->ID' ");

            $this->State_ = "Coherent";
        }

        return true;
    }



    /*!
      Deletes the todo object in the database.
    */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZTodo_Todo WHERE ID='$this->ID'" );

        return true;
    }

    /*!
      Gets the todo object from the database, where ID == $id
    */
    function get( $id )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $todo_array, "SELECT * FROM eZTodo_Todo WHERE ID='$id'" );
            if ( count( $todo_array ) > 1 )
            {
                die( "Error: Todo's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $todo_array ) == 1 )
            {
                $this->ID = $todo_array[0][ "ID" ];
                $this->Name = $todo_array[0][ "Name" ];
                $this->Text = $todo_array[0][ "Text" ];
                $this->Category = $todo_array[0][ "Category" ];
                $this->Priority = $todo_array[0][ "Priority" ];
                $this->Due = $todo_array[0][ "Due" ];
                $this->Date = $todo_array[0][ "Date" ];
                $this->UserID = $todo_array[0][ "UserID" ];
                $this->OwnerID = $todo_array[0][ "OwnerID" ];
                $this->Done = $todo_array[0][ "Done" ];
                $this->Permission = $todo_array[0][ "Permission" ];

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
      Gets all the todo informasjon from the database.
      Returns the array in $todo_array ordered by name.
    */
    function getAll()
    {
        $this->dbInit();

        $return_array = array();
        $todo_array = array();
        
        $this->Database->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo ORDER BY Name" );
        
        for ( $i=0; $i<count($todo_array); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*! 
      Gets all the todo infomasjon from a user, where ID == $id.
      Return the array in $todo_array ordered by name.
      
    */
    function getByOwnerID( $id )
    {
        $this->dbInit();
        $todo_array = 0;

        $return_array = array();
        $todo_array = array();

        $this->Database->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo WHERE OwnerID='$id' ORDER BY Name");
       
        for ( $i=0; $i<count($todo_array); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i]["ID"], 0 );
        }
        return $return_array;        
    }

    /*! 
      Gets all the todo infomasjon from a owner, where ID == $id.
      Return the array in $todo_array ordered by name.
      
    */
    function getByUserID( $id )
    {
        $this->dbInit();
        $todo_array = 0;

        $return_array = array();
        $todo_array = array();

        $this->Database->array_query( $todo_array, "SELECT ID FROM eZTodo_Todo WHERE UserID='$id' ORDER BY Name");
       
        for ( $i=0; $i<count($todo_array); $i++ )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i]["ID"], 0 );
        }
        return $return_array;        
    }

    
    
    /*!
      Tilte of the todo.
      Returns the name of the todo as a string.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       return $this->Name;
    }


    /*!
      Sets the name of the todo.
      The new name of the todo is passed as a paramenter ( $value ).
     */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Name = $value;
    }

    /*!
      Text of the todo.
      Returns the text of the todo as a string.
    */
    function text()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Text;
    }

    /*!
      Sets the text of the todo.
      The new text of the todo is passed as a paramenter ( $value ).
     */
    function setText( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Text = $value;
    }

    /*!
      Tilte of the category.
      Returns the category of the todo as a string.
    */
    function categoryID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Category;
    }

    /*!
      Sets the category of the todo.
      The new category of the todo is passed as a paramenter ( $value ).
     */
    function setCategoryID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Category = $value;
    }

    /*!
      Priority of the todo.
      Returns the priority of the todo as a string.
    */
    function priorityID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Priority;
    }

    /*!
      Sets the priority of the todo.
      The new priority of the todo is passed as a paramenter ( $value ).
     */
    function setPriorityID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Priority = $value;
    }

    /*!
      Due of the todo.
      Returns the due of the todo as a string.
    */
    function due()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $dateTime = new eZDateTime();
        $dateTime->setMySQLDateTime( $this->Due );
        
        return $dateTime;
    }

    /*!
      Sets the due of the todo.
      The new due of the todo is passed as a paramenter ( $value ).
     */
    function setDue( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Due = $value;
    }

    /*!
      Date of the todo.
      Returns the due of the todo as a string.
    */
    function date()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $dateTime = new eZDateTime( );
        $dateTime->setMySQLTimeStamp( $this->Date );        
        
        return $dateTime;
    }

    /*!
      Sets the date of the todo.
      The new date of the todo is passed as a paramenter ( $value ).
     */
    function setDate( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Date = $value;
    }

    /*!
      UserID of the todo.
      Returns the priority of the todo as a string.
    */
    function userID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->UserID;
    }

    /*!
      Sets the user of the todo.
      The new user of the todo is passed as a paramenter ( $value ).
     */
    function setUserID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->UserID = $value;
    }

    /*!
      OwnerID of the todo.
      Returns the priority of the todo as a string.
    */
    function ownerID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->OwnerID;
    }

    /*!
      Sets the owner of the todo.
      The new owner of the todo is passed as a paramenter ( $value ).
     */
    function setOwnerID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->OwnerID = $value;
    }
    
    /*!
      Done of the todo.
      Returns the done of the todo as a string.
    */
    function done()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Done;
    }

    /*!
      Sets the done of the todo.
      The new done of the todo is passed as a paramenter ( $value ).
     */
    function setDone( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $value )
            $this->Done = "true";
        else
            $this->Done = "false";
       
    }


    /*!
      Permission of the todo.
      Returns the permission of the todo as a string.
    */
    function permission()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Permission;
    }

    /*!
      Sets the permission of the todo.
      The new permission of the todo is passed as a paramenter ( $value ).
     */
    function setPermission( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Permission = $value;
    }

    /*!
      Id of the todo.
      Returns the id of the todo as an int.
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

    var $OwnerID;
    var $UserID;
    var $Permission;
    var $Text;
    var $Due;
    var $Date;
    var $Name;
    var $CategoryID;
    var $PriorityID;
    var $ID;
    var $Done;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}
