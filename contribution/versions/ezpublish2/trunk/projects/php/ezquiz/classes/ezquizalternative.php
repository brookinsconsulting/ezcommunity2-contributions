<?
// 
// $Id: ezquizalternative.php,v 1.3 2001/05/29 09:07:05 ce Exp $
//
// eZQuizAlternative class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <22-May-2001 16:22:08 ce>
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

//!! eZQuizAlternative
//! eZQuizAlternative documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdate.php" );
	      
class eZQuizAlternative
{

    /*!
      Constructs a new eZQuizAlternative object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZQuizAlternative( $id=-1, $fetch=true )
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
      Stores a eZQuizAlternative object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name =& addslashes( $this->Name );
        $questionID = $this->Question->id();

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZQuiz_Alternative SET
                                     Name='$name',
                                     QuestionID='$questionID',
                                     IsCorrect='$this->IsCorrect'
                                     " );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZQuiz_Alternative SET
                                     Name='$name',
                                     QuestionID='$questionID',
                                     IsCorrect='$this->IsCorrect'
                                     WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZQuizAlternative object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();

//        $answers =& $this->answers();

        if ( is_array ( $answers ) )
        {
            foreach( $answers as $answer )
            {
                $answer->delete();
            }
        }

        $db->query( "DELETE FROM eZQuiz_Alternative WHERE ID='$catID'" );
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
            $GLOBALS["DEBUG"] = true;
            $db->array_query( $alternativeArray, "SELECT * FROM eZQuiz_Alternative WHERE ID='$id'",
                              0, 1 );

            if( count( $alternativeArray ) == 1 )
            {
                $this->fill( &$alternativeArray[0] );
                $ret = true;
            }
            elseif( count( $alternativeArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$alternativeArray )
    {
        $this->ID =& $alternativeArray[ "ID" ];
        $this->Name =& $alternativeArray[ "Name" ];
        $this->IsCorrect =& $alternativeArray[ "IsCorrect" ];
        $this->Question = new eZQuizQuestion( $alternativeArray[ "QuestionID" ] );
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZQuizAlternative objects.
    */
    function getAll( $offset=0, $limit=20)
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $alternativeArray = array();
        
        $db->array_query( $alternativeArray, "SELECT ID
                                           FROM eZQuiz_Alternative
                                           " );
        
        for ( $i=0; $i < count($alternativeArray); $i++ )
        {
            $returnArray[$i] = new eZQuizAlternative( $alternativeArray[$i]["ID"] );
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
                                     FROM eZQuiz_Alternative" );
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
    function isCorrect()
    {
        if ( $this->IsCorrect == 1 )
            return true;
        elseif ( $this->IsCorrect == 0 )
            return false;
    }

    /*!
      Returns the name of the game.
    */
    function question()
    {
        return $this->Question;
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
    function setIsCorrect( $value )
    {
        if ( $value == true )
            $this->IsCorrect = 1;
        else
            $this->IsCorrect = 0;
    }

    /*!
      Returns the name of the game.
    */
    function setQuestion( &$question )
    {
        if ( get_class ( $question ) == "ezquizquestion" )
            $this->Question = $question;
    }

    /*!
      Returns every answers to this quiz alternative
      The alternatives is returned as an array of eZQuizAnswer objects.
    */
    function answers()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $questionArray, "SELECT ID FROM eZQuiz_Answer WHERE AlternativeID='$this->ID'" );

       for ( $i=0; $i < count($questionArray); $i++ )
       {
           $returnArray[$i] = new eZQuizAnswer( $questionArray[$i]["ID"], true );
       }
       return $returnArray;
    }

    var $ID;
    var $Name;
    var $IsCorrect;
    var $Question;
}

?>
