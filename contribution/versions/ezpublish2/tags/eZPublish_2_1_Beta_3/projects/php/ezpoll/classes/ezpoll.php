<?
// 
// $Id: ezpoll.php,v 1.19 2001/05/05 11:16:04 bf Exp $
//
// Definition of eZPoll class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <19-Sep-2000 17:37:53 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
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
        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        if ( !isset( $this->ID ) )
        {
            
            $this->Database->query( "INSERT INTO eZPoll_Poll SET
                                 Name='$name',
                                 Description='$description',
                                 IsEnabled='$this->IsEnabled',
                                 ShowResult='$this->ShowResult',
                                 Anonymous='$this->Anonymous',
                                 IsClosed='$this->IsClosed' ");

			$this->ID = $this->Database->insertID();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZPoll_Poll SET
                                 Name='$name',
                                 Description='$description',
                                 IsEnabled='$this->IsEnabled',
                                 ShowResult='$this->ShowResult',
                                 Anonymous='$this->Anonymous',
                                 IsClosed='$this->IsClosed' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";    
        }
        return true;
    }

    /*!
      Deletes a eZPol object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZPoll_Vote WHERE PollID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZPoll_PollChoice WHERE PollID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZPoll_Poll WHERE ID='$this->ID'" );
        }
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
            $this->Database->array_query( $poll_array, "SELECT * FROM eZPoll_Poll WHERE ID='$id'" );

            if ( count( $poll_array ) > 1 )
            {
                die( "Error: Poll's with the same ID was found in the database." );
            }
            
            else if( count( $poll_array ) == 1 )
            {

                $this->ID = $poll_array[0][ "ID" ];
                $this->Name = $poll_array[0][ "Name" ];
                $this->Description = $poll_array[0][ "Description" ];

                if ( $poll_array[0][ "Anonymous" ] == "true" )
                    $this->Anonymous = true;
                else
                    $this->Anonymous = false;
                
                if ( $poll_array[0][ "IsEnabled" ] == "true" )
                    $this->IsEnabled = true;
                else
                    $this->IsEnabled = false;

                if ( $poll_array[0][ "IsClosed" ] == "true" )
                    $this->IsClosed = true;
                else
                    $this->IsClosed = false;

                if ( $poll_array[0][ "ShowResult" ] == "true" )
                    $this->ShowResult = true;
                else
                    $this->ShowResult = false;
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

        $this->Database->array_query( $poll_array, "SELECT ID FROM eZPoll_Poll ORDER BY Name" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPoll( $poll_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }

    /*!
      Fetches the poll id from the database where active=true. And returns a array of eZPoll objects. 
    */
    function getAllActive()
    {
        $this->dbInit();

        $return_array = array();
        $poll_array = array();

        $this->Database->array_query( $poll_array, "SELECT ID FROM eZPoll_Poll WHERE IsEnabled='true' ORDER BY Name" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPoll( $poll_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }

    
    /*!
      Returns the id of the poll.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }


    /*!
      Returns the name of the poll.
    */
    function name( $html = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the poll description.
    */
    function description( $html = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $html )
            return htmlspecialchars( $this->Description );
        else
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
      Returns the ShowResult.
    */
    function showResult()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ShowResult;
    }

    /*!
      Returns the Anonymous.
    */
    function anonymous()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Anonymous;
    }

    
    /*!
      Returns the main poll as a eZPoll object.

      False is returned if no poll is set as main poll.
    */
    function mainPoll(  )
    {
        $this->dbInit();
        
        // sets the current poll as main poll
        $this->Database->array_query( $poll_array, "SELECT PollID FROM eZPoll_MainPoll" );

        $ret = false;
        if ( count( $poll_array ) == 1 )
        {
            $ret = new eZPoll( $poll_array[0]["PollID"] );
        }
        return $ret;
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

        if ( $value  )
            $this->IsEnabled = "true";
        else
            $this->IsEnabled = "false";
    }
    
    /*!
      Sets the isclosed of the poll.
    */
    function setIsClosed( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $value  )
            $this->IsClosed = "true";
        else
            $this->IsClosed = "false";
    }

    /*!
      Sets the showresult of the poll.
    */
    function setShowResult( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $value )
            $this->ShowResult = "true";
        else
            $this->ShowResult = "false";
    }

    /*!
      Sets the anonymous of the poll.
    */
    function setAnonymous( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $value )
            $this->Anonymous = "true";
        else
            $this->Anonymous = "false";
    }
    
    /*!
      Fetches the total number of votes for the poll.
    */
    function totalVotes( )
    {
        $this->dbInit();
        $this->Database->array_query( $votecount, "SELECT COUNT(*) AS NUMBER FROM eZPoll_Vote WHERE PollID='$this->ID'" );
        
        return $votecount[0][ "NUMBER" ];
    }

    /*!
      Sets the active poll to the current poll.
    */
    function setMainPoll( $poll )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( get_class( $poll ) == "ezpoll" )
        {
            $this->dbInit();
            
            // delete old main poll
            $this->Database->query( "DELETE FROM eZPoll_MainPoll" );
            
            // sets the current poll as main poll
            $this->Database->query( "INSERT INTO eZPoll_MainPoll SET PollID='$this->ID'" );
        }
    }
    

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
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
    var $IsEnabled;
    var $IsClosed;
    var $ShowResult;
    var $Anonymous;

    var $Database;
    var $State_;
    var $IsConnected;
}

?>
