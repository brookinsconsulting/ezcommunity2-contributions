<?
// 
// $Id: ezpoll.php,v 1.1 2000/09/20 09:32:06 ce-cvs Exp $
//
// Definition of eZPoll class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <19-Sep-2000 17:37:53 ce>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZPoll
//! The eZPoll class handles the options for polls.
/*!
  The eZPoll class handles poll options. The class has function for storing and fetching from
  the database.

  Example code:
  \code
  
  // Create a new poll object.
  $poll = new eZPoll();

  $poll->setName( "Policy" );
  $poll->setDescription(  "What do you think about Carl I. Hagen" );

  // Stores the poll information into the database.
  $poll->store();

  // Fetch all the poll information from the database.
  $poll->getAll();

  // Print out all the polls
  foreach( $pollArray as pollItem )
  {
      print( "Poll: " . $pollItem->name() . "<br>" );
  }
  
  \endcode
  \sa eZPollChoice eZVote
*/

include_once( "classes/ezdb.php" );

class eZPoll
{
    /*!
      Constructor a new eZPoll object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZPoll( $id=-1, $fetch=true )
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
      Stores a eZPoll object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZPoll_Poll SET
                                 Name='$this->Name',
                                 Description='$this->Description',
                                 IsEnabled='$this->IsEnabled',
                                 IsClosed='$this->IsClosed' ");

        $this->ID = mysql_insert_id();

        return true;
    }

    /*!
      Fetches the poll object from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        if ( $id != -1 )
        {
            $this->Database->array_query( $poll_array, "SELECT * FROM eZPoll_Poll" );

            if ( count( $poll_array ) > 1 )
            {
                die( "Error: Poll's with the same ID was found in the database." );
            }
            else if( count( $poll_array ) == 1 )
            {
                $this->ID = $poll_array[0][ "ID" ];
                $this->
                     }

        }
    }

    /*!
      Fetches the poll id from the database. And returns a array of eZPoll objects.
    */
    function getAll()
    {
        $this->dbInit();

        $return_array = array();
        $poll_array = array();

        $this->Database->array_query( $poll_array, "SELECT ID FROM ezPoll" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPoll( $poll_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }


    /*!
      Returns the name of the poll.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Name;
    }

    /*!
      Returns the poll description.
    */
    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Description;
    }

    /*!
      Returns the isEnabled.
    */

    function isEnabled()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IsEnabled;
    }

    /*!
      Returns the isClosed.
    */

    function isClosed()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IsClosed;
    }

    /*!
      Sets the name of the poll.
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the poll.
    */
    function setDescription( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the isenabled of the poll.
    */
    function setIsEnabled( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->IsEnabled = $value;
    }
    
    /*!
      Sets the isclosed of the poll.
    */
    function setIsClosed( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->IsClosed = $value;
    }

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZPollMain" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Description;
    var $IsEnabled;
    var $IsClosed;
}
