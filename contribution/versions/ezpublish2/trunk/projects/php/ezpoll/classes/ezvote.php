<?
// 
// $Id: ezvote.php,v 1.10 2000/10/09 14:15:08 ce-cvs Exp $
//
// Definition of eZVote class
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
//! The eZVote class handles the options for votes.
/*!
  The eZVote class handles vote options. The class has function for storing and fetching from
  the database.

  Example code:
  \code
  
  
  \endcode
  \sa eZVoteChoice eZVote
*/

include_once( "classes/ezdb.php" );

class eZVote
{
    /*!
      Constructor a new eZVote object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZVote( $id=-1, $fetch=true )
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
      Stores a eZVote object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZPoll_Vote SET
                                 VotingIP='$this->IP',
                                 PollID='$this->PollID',
                                 ChoiceID='$this->ChoiceID',
                                 UserID='$this->UserID'
                                 ");
        $this->ID = mysql_insert_id();

        return true;
    }

    /*!
      Fetches the vote object from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        if ( $id != -1 )
        {
            $this->Database->array_query( $vote_array, "SELECT * FROM eZPoll_Vote" );

            if ( count( $vote_array ) > 1 )
            {
                die( "Error: Vote's with the same ID was found in the database." );
            }
            else if( count( $vote_array ) == 1 )
            {
                $this->ID = $vote_array[0][ "ID" ];
                $this->IP = $vote_array[0][ "VotingIP" ];
                $this->PollID = $vote_array[0][ "PollID" ];
                $this->ChoiceID = $vote_array[0][ "ChoiceID" ];
                $this->UserID = $vote_array[0][ "UserID" ];
            }

        }
    }

    /*!
      Fetches the vote id from the database. And returns a array of eZVote objects.
    */
    function getAll( $id )
    {
        $this->dbInit();

        $return_array = array();
        $vote_array = array();

        $this->Database->array_query( $vote_array, "SELECT ID FROM eZPoll_Vote WHERE PollID='$id'" );

        for ( $i=0; $i<count( $vote_array ); $i++ )
        {
            $return_array[$i] = new eZVote( $vote_array[$i][ "ID" ], 0 );
        }
    }
    
    /*!
      Returns the pollid of the vote.
    */
    function pollID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PollID;
    }

    /*!
      Returns the pollid of the vote.
    */
    function choiceID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->choiceID;
    }

       
    /*!
      Returns the IP of the vote.
    */
    function votingIP()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IP;
    }

    /*!
      Returns the ID of the vote.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }

    /*!
      Returns the ID of the vote.
    */
    function userID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->userID;
    }

    
    /*!
      Sets the IP of the vote.
    */
    function setVotingIP( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->IP = $value;
    }

    /*!
      Sets the Pollid of the vote.
    */
    function setPollID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->PollID = $value;
    }

    /*!
      Sets the Pollid of the vote.
    */
    function setChoiceID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->ChoiceID = $value;
    }
    
    /*!
      Sets the ChoiceID of the vote.
    */
    function setUserID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->UserID = $value;
    }

    /*!
      \static
      Check if the user already have voted. If voted, return true.
    */
    function oneVoteCheck( $userID, $pollID )
    { 
        $ret = true;

        $vote_array = array();

        $this->dbInit();

        $this->Database->array_query( $vote_array, "SELECT * FROM eZPoll_Vote
                                                    WHERE UserID='$userID' AND PollID='$pollID'" );
        if ( count( $vote_array ) == 0 )
        {
            $ret = false;
        }

        return $ret;
    }

    /*!
      \private
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

    var $IP;
    var $PollID;
    var $ChoiceIP;
    var $UserID;
}

?>
