<?
// 
// $Id: ezquizquestion.php,v 1.5 2001/05/30 10:39:40 pkej Exp $
//
// eZQuizQuestion class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <22-May-2001 13:45:37 ce>
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

//!! eZQuizQuestion
//! eZQuizQuestion documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdate.php" );
include_once( "ezquiz/classes/ezquizalternative.php" );
include_once( "ezquiz/classes/ezquizgame.php" );
	      
class eZQuizQuestion
{

    /*!
      Constructs a new eZQuizQuestion object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZQuizQuestion( $id=-1, $fetch=true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a eZQuizQuestion object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name =& addslashes( $this->Name );
        $gameID = $this->Game->id();

        $db->query_single( $result, "SELECT MAX(Placement)+1 FROM eZQuiz_Question WHERE GameID='$gameID' " );

        $place = $result[0];
        if( $result[0] == NULL )
        {
            $place = 1;
        }

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZQuiz_Question SET
                                     Name='$name',
                                     Score='$this->Score',
                                     GameID='$gameID',
                                     Placement='$place'
                                     " );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZQuiz_Question SET
                                     Name='$name',
                                     Score='$this->Score',
                                     GameID='$gameID'
                                     WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZQuizQuestion object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();

        $alternatives =& $this->alternatives();
        if ( is_array ( $alternatives ) )
        {
            foreach ( $alternatives as $alternative )
            {
                $alternative->delete();
            }
        }

        $db->query( "DELETE FROM eZQuiz_Question WHERE ID='$this->ID'" );
        
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $questionArray, "SELECT * FROM eZQuiz_Question WHERE ID='$id'",
                              0, 1 );
            if( count( $questionArray ) == 1 )
            {
                $this->fill( &$questionArray[0] );
                $ret = true;
            }
            elseif( count( $questionArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$questionArray )
    {
        $this->ID =& $questionArray[ "ID" ];
        $this->Name =& $questionArray[ "Name" ];
        $this->Description =& $questionArray[ "Description" ];
        $this->Game = new eZQuizGame( $questionArray[ "GameID" ] );
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZQuizQuestion objects.
    */
    function getAll( $offset=0, $limit=20)
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $questionArray = array();
        
        $db->array_query( $questionArray, "SELECT ID
                                           FROM eZQuiz_Question
                                           ORDER BY StartDate DESC
                                           LIMIT $offset, $limit" );
        
        for ( $i=0; $i < count($questionArray); $i++ )
        {
            $returnArray[$i] = new eZQuizQuestion( $questionArray[$i]["ID"] );
        }
        
        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZQuiz_Question" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
      Returns the object ID to the game. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the game.
    */
    function name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the score of the game.
    */
    function score()
    {
        return $this->Score;
    }

    /*!
      Returns the name of the game.
    */
    function game()
    {
        return $this->Game;
    }

    /*!
      Sets the login.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the score.
    */
    function setScore( &$value )
    {
       $this->Score = $value;
    }

    /*!
      Returns the name of the game.
    */
    function setGame( &$game )
    {
        if ( get_class ( $game ) == "ezquizgame" )
            $this->Game = $game;
    }

    /*!
        Returns true if the submitted alternative is part of the questions alternatives
     */
    function isAlternative( &$alternative )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        if ( get_class ( $alternative ) == "ezquizalternative" )
        {
            $alternativeID = $alternative->id();
            $questionID = $this->ID;
            
            $db->query_single( $result, "SELECT ID 
                                     FROM eZQuiz_Alternative WHERE QuestionID='$questionID' AND ID='$alternativeID'" );
 
            if( is_numeric( $result["ID"] ) )
            {
                $ret = true;
            }
        }
        
        return $ret;
     }

    /*!
      Returns every alternative to this quiz question
      The alternatives is returned as an array of eZQuizAlternative objects.
    */
    function alternatives()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $questionArray, "SELECT ID FROM eZQuiz_Alternative WHERE QuestionID='$this->ID' ORDER BY ID" );

       for ( $i=0; $i < count($questionArray); $i++ )
       {
           $returnArray[$i] = new eZQuizAlternative( $questionArray[$i]["ID"], true );
       }
       return $returnArray;
    }
    
    var $ID;
    var $Name;
    var $Score;
    var $Game;
}

?>
