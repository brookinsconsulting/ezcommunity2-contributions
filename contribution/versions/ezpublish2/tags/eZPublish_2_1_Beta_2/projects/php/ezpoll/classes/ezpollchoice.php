<?
// $Id: ezpollchoice.php,v 1.13 2001/04/04 16:10:48 fh Exp $
//
// Definition of eZPollChoice class
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
//! The eZPollChoice class handles the options for polls.
/*!
  The eZPollChoice class handles poll options. The class has function for storing and fetching from
  the database.

  Example code:
  \code
  
  
  \endcode
  \sa eZPoll eZVote
*/

include_once( "classes/ezdb.php" );

class eZPollChoice
{
        /*!
      Constructor a new eZPoll object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZPollChoice( $id=-1, $fetch=true )
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
        if ( !isset ( $this->ID ) )
        {
        
            $this->Database->query( "INSERT INTO eZPoll_PollChoice SET
                                 Name='$name',
                                 PollID='$this->PollID',
                                 Offset='$this->Offset' ");

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZPoll_PollChoice SET
                                 Name='$name',
                                 Offset='$this->Offset' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
        }
        return true;
    }

    /*!
      Deletes a eZPollChoice object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset ( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZPoll_Vote WHERE ChoiceID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZPoll_PollChoice WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $poll_array, "SELECT * FROM eZPoll_PollChoice WHERE ID='$id' ORDER By ID" );

            if ( count( $poll_array ) > 1 )
            {
                die( "Error: Poll's wit the same ID was found in the database." );
            }
            else if( count( $poll_array ) == 1 )
            {
                $this->ID = $poll_array[0][ "ID" ];
                $this->Name = $poll_array[0][ "Name" ];
                $this->PollID = $poll_array[0][ "PollID" ];
                $this->Offset = $poll_array[0][ "Offset" ];
            }

        }
    }

    /*!
      Fetches the poll id from the database. And returns a array of eZPoll objects.
    */
    function getAll( $ID )
    {
        $this->dbInit();

        $return_array = array();
        $poll_array = array();

        $this->Database->array_query( $poll_array, "SELECT ID FROM eZPoll_PollChoice WHERE PollID='$ID' ORDER By ID" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPollChoice( $poll_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the name of the pollchoice.
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
      Returns the name of the pollchoice.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }


    /*!
      Returns the name of the pollchoice.
    */
    function offset()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Offset;
    }


    
    /*!
      Returns the PollID of the Pollchoice.
    */
    function pollID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PollID;
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
      Sets the name of the poll.
    */
    function setOffset( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Offset = $value;
    }

    
    /*!
      Sets the name of the poll.
    */
    function setPollID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->PollID = $value;
    }

    /*!
      Fetches the number of votes for the current coice.
    */
    function voteCount( )
    {
        $this->dbInit();
        $this->Database->array_query( $votecount, "SELECT COUNT(*) AS NUMBER FROM eZPoll_Vote WHERE ChoiceID='$this->ID'" );
        
        return $votecount[0][ "NUMBER" ];
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
    var $PollID;
    var $Offset;

    var $Database;
    var $State_;
    var $IsConnected;
}
