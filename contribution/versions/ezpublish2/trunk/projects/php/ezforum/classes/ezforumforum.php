<?
// 
// $Id: ezforumforum.php,v 1.24 2000/10/13 09:38:34 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZForum
//! The eZForumForum class handles forum's in the database.
/*!
  
  \sa eZForumMessage \eZForumCategory
*/

/*!TODO
  Rename SQL tables and Id->ID.

  CategoryId -> CategoryID
  
  Moderated='$this->Moderated',  Private='$this->Private' to use enum( 'true', 'false' )
  and use bool in the class. Rename the functions an variables to IsModerated and IsPrivate.  
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );
include_once( "ezforum/classes/ezforummessage.php" );


class eZForumForum
{
    /*!
      Constructs a new eZForumForum object.
    */
    function eZForumForum( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != "" )
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
      Stores a eZForumForum object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO ezforum_ForumTable SET
		                         CategoryId='$this->CategoryID',
		                         Name='$this->Name',
		                         Description='$this->Description',
		                         Moderated='$this->Moderated',
		                         Private='$this->Private'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE ezforum_ForumTable SET
		                         CategoryId='$this->CategoryID',
		                         Name='$this->Name',
		                         Description='$this->Description',
		                         Moderated='$this->Moderated',
		                         Private='$this->Private'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Deletes a eZForumCategory object from the database.
    */
    function delete()
    {
        $this->dbInit();

        $this->Database->query( "DELETE FROM ezforum_ForumTable WHERE ID='$this->ID'" );
        
        return true;
    }
    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $forum_array, "SELECT * FROM ezforum_ForumTable WHERE ID='$id'" );
            if ( count( $forum_array ) > 1 )
            {
                die( "Error: Forum's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $forum_array ) == 1 )
            {
                $this->ID = $forum_array[0][ "Id" ];
                $this->CategoryID = $forum_array[0][ "CategoryId" ];
                $this->Name = $forum_array[0][ "Name" ];
                $this->Description = $forum_array[0][ "Description" ];
                $this->Moderated = $forum_array[0][ "Moderated" ];
                $this->Private = $forum_array[0][ "Private" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }
    

    /*!
      Returns every forum.
    */
    function getAll( )
    {

    }

    /*!
      Returns the messages in a forum.
    */
    function messages( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       $this->dbInit();

       $this->Database->array_query( $message_array, "SELECT Id as ID FROM
                                                       ezforum_MessageTable
                                                       WHERE ForumId='$this->ID' ORDER BY PostingTime DESC" );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
       }
       
       return $ret;
    }

    /*!
      Returns the messages in every forum matching the query string.
    */
    function search( $query, $offset, $limit )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       $this->dbInit();

       $query = new eZQuery( array( "Topic", "Body" ), $query );
               
       $query_str = "SELECT Id as ID FROM ezforum_MessageTable WHERE (" .
             $query->buildQuery()  .
             ") ORDER BY PostingTime LIMIT $offset, $limit";       

       $this->Database->array_query( $message_array, $query_str );
       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
       }
       
       return $ret;
    }

    /*!
      Returns all the messages and submessages as a tree.

      Default limit is set to 30.
    */
    function &messageTree( $offset=0, $limit=30 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       $this->dbInit();

       $this->Database->array_query( $message_array, "SELECT Id as ID FROM
                                                       ezforum_MessageTable
                                                       WHERE ForumId='$this->ID' ORDER BY TreeID DESC LIMIT $offset,$limit" );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
       }
       
       return $ret;
    }

    /*!
      Returns all the messages and submessages of a thread as a tree.

      Default limit is set to 100
    */
    function &messageThreadTree( $threadID, $offset=0, $limit=100 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       $this->dbInit();

       $this->Database->array_query( $message_array, "SELECT Id as ID FROM
                                                       ezforum_MessageTable
                                                       WHERE ForumId='$this->ID' AND ThreadID='$threadID' ORDER BY TreeID DESC LIMIT $offset,$limit" );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
       }
       
       return $ret;
    }
    
        
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }
        
    /*!
      
    */
    function categoryId()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->CategoryID;
    }
        
    /*!
      
    */
    function setCategoryId( $newCategoryId )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->CategoryId = $newCategoryId;
    }
        
    /*!
      
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->Name;
    }
        
    /*!
      
    */
    function setName($newName)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Name = $newName;
    }
        
    /*!
      
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->Description;
    }
        
    /*!
      
    */
    function setDescription($newDescription)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Description = $newDescription;
    }
        
    /*!
      
    */
    function moderated()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->Moderated;
    }
        
    /*!
      
    */
    function setModerated($newModerated)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Moderated = $newModerated;
    }
        
    /*!
      
    */
    function private()
    {
        return $this->Private;
    }
        
    /*!
      
    */
    function setPrivate($newPrivate)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Private = $newPrivate;
    }

    /*!
      Returns the number of threads in the forum.
    */
    function threadCount()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->array_query( $message_array, "SELECT Id as ID FROM
                                                       ezforum_MessageTable
                                                       WHERE ForumId='$this->ID' GROUP BY ThreadID" );

       $ret = count( $message_array );

       return $ret;
    }

    /*!
      Returns the number of messages in the forum.
    */
    function messageCount()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->array_query( $message_array, "SELECT Id as ID FROM
                                                       ezforum_MessageTable
                                                       WHERE ForumId='$this->ID'" );

       $ret = count( $message_array );

       return $ret;
    }
    
    /*!
      \private
      Opens the database for read and write.
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
    var $CategoryID;
    var $Name;
    var $Description;
    var $Moderated;
    var $Private;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
