<?
// $Id: eztodo.php,v 1.1 2000/09/07 07:12:25 ce-cvs Exp $
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
  Handles the todo informasjon stoered in the database.
*/
class eZTodo
{
    //! eZTodo
    /*!
      eZtodo Constructor.
    */
    function eZTodo( $id=-1, $fetch=1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch != 1 )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        $this->State_ = "New";
    }

    //! store
    /*!
      Stores the todo object to the database.
      Returnes the ID to the eZCompany object if the store is a success.
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZTodo_Todo SET
                ID='$this->ID',
                Title='$this->Title',
                Text='$this->Text',
                Category='$this->Category',
                Priority='$this->Priority',
                Due='$this->Due',
                User='$this->User',
                Owner='$this->Owner',
                Status='$this->Status',
                Permission='$this->Permission'" );
        return mysql_insert_id();
    }

    //! delete
    /*!
      Deletes the todo object in the database.
    */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZTodo_Todo WHERE ID='$this->ID'" );
    }

    //! update
    /*!
      Update the todo object in the database.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZTodo_Todo SET
                Title='$this->Title',
                Text='$this->Text',
                Category='$this->Category',
                Priority='$this->Priority',
                Due='$this->Due',
                User='$this->User',
                Owner='$this->Owner',
                Status='$this->Status',
                Permission='$this->Permission'
                WHERE ID='$this->ID' ");
                
    }

    //! get
    /*!
      Gets the todo object from the database, where ID == $id
    */
    function get( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $todo_array, "SELECT * FROM eZTodo_Todo WHERE ID='$id'" );
            if ( count( $todo_array ) > 1 )
            {
                die( "Error: Todo's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $todo_array ) == 1 )
            {
                $this->ID = $todo_array[0][ "ID" ];
                $this->Title = $todo_array[0][ "Title" ];
                $this->Text = $todo_array[0][ "Text" ];
                $this->Category = $todo_array[0][ "Category" ];
                $this->Priority = $todo_array[0][ "Priority" ];
                $this->Due = $todo_array[0][ "Due" ];
                $this->User = $todo_array[0][ "User" ];
                $this->Owner = $todo_array[0][ "Owner" ];
                $this->Status = $todo_array[0][ "Status" ];
                $this->Permission = $todo_array[0][ "Permission" ];
            }
            $this->State_ = "Coherent";
        }
    }

    //! getAll
    /*!
      Gets all the todo informasjon from the database.
      Returns the array in $todo_array ordered by title.
    */
    function getAll()
    {
        $this->dbInit();
        $todo_array = 0;

        $return_array = array();
        $todo_array = array();
        
        array_query( $todo_array, "SELECT ID FROM eZTodo_Todo ORDER BY Title" );
        
        for ( $i=0; $i<count($todo_array); $i )
        {
            $return_array[$i] = new eZTodo( $todo_array[$i]["ID"], 0 );
        }
        
        return $todo_array;
    }

    //! getByUser
    /*!
      Gets all the todo infomasjon from a user, where ID == $id.
      Return the array in $todo_array ordered by title.
      
    */
    function getByUser( $id )
    {
        $this->dbInit();
        $todo_array = 0;
        array_query( $todo_array, "SELECT * FROM eZTodo_Todo WHERE User='$id' ORDER BY Title");
        return $todo_array;
    }


    //! title
    /*!
      Tilte of the todo.
      Returns the title of the todo as a string.
    */
    function title()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Title;
    }

    //! setTitle
    /*!
      Sets the title of the todo.
      The new title of the todo is passed as a paramenter ( $value ).
     */
    function setTitle( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Title = $value;
    }

    //! text
    /*!
      Text of the todo.
      Returns the text of the todo as a string.
    */
    function text()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Text;
    }

    //! setText
    /*!
      Sets the text of the todo.
      The new text of the todo is passed as a paramenter ( $value ).
     */
    function setText( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Text = $value;
    }

    //! category
    /*!
      Tilte of the category.
      Returns the category of the todo as a string.
    */
    function category()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Category;
    }

    //! setCategory
    /*!
      Sets the category of the todo.
      The new category of the todo is passed as a paramenter ( $value ).
     */
    function setCategory( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Category = $value;
    }

    //! priority
    /*!
      Priority of the todo.
      Returns the priority of the todo as a string.
    */
    function priority()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Priority;
    }

    //! setPriority
    /*!
      Sets the priority of the todo.
      The new priority of the todo is passed as a paramenter ( $value ).
     */
    function setPriority( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Priority = $value;
    }

    //! due
    /*!
      Due of the todo.
      Returns the due of the todo as a string.
    */
    function due()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Due;
    }

    //! setDue
    /*!
      Sets the due of the todo.
      The new due of the todo is passed as a paramenter ( $value ).
     */
    function setDue( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Due = $value;
    }

    //! user
    /*!
      User of the todo.
      Returns the priority of the todo as a string.
    */
    function user()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->User;
    }

    //! setUser
    /*!
      Sets the user of the todo.
      The new user of the todo is passed as a paramenter ( $value ).
     */
    function setUser( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->User = $value;
    }

    //! owner
    /*!
      Owner of the todo.
      Returns the priority of the todo as a string.
    */
    function owner()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Owner;
    }

    //! setOwner
    /*!
      Sets the owner of the todo.
      The new owner of the todo is passed as a paramenter ( $value ).
     */
    function setOwner( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Owner = $value;
    }
    
    //! status
    /*!
      Status of the todo.
      Returns the status of the todo as a string.
    */
    function status()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Status;
    }

    //! setStatus
    /*!
      Sets the status of the todo.
      The new status of the todo is passed as a paramenter ( $value ).
     */
    function setStatus( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Status = $value;
    }


    //! permission
    /*!
      Permission of the todo.
      Returns the permission of the todo as a string.
    */
    function permission()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->Permission;
    }

    //! setPermission
    /*!
      Sets the permission of the todo.
      The new permission of the todo is passed as a paramenter ( $value ).
     */
    function setPermission( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        $this->Permission = $value;
    }

    //! id
    /*!
      Id of the todo.
      Returns the id of the todo as an int.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get();
        return $this->ID;
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

    var $UserID;
    var $Permission;
    var $Text;
    var $Due;
    var $Title;
    var $CategoryID;
    var $PriorityID;
    var $ID;
    var $Status;
    var $State_;
}
