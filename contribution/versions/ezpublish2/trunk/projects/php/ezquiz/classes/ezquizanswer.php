<?
// 
// $Id: ezquizanswer.php,v 1.3 2001/05/30 14:19:37 pkej Exp $
//
// eZQuizAnswer class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <29-May-2001 14:01:35 pkej>
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

//!! eZQuizAnswer
//! eZQuizAnswer documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezquiz/classes/ezquizalternative.php" );
	      
class eZQuizAnswer
{

    /*!
      Constructs a new eZQuizAnswer object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZQuizAnswer( $id=-1, $fetch=true )
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
      Stores a eZQuizAnswer object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name =& addslashes( $this->Name );
        $userID = $this->User->id();
        $alternativeID = $this->Alternative->id();

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZQuiz_Answer SET
                                     UserID='$userID',
                                     AlternativeID='$alternativeID'
                                     " );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZQuiz_Answer SET
                                     UserID='$userID',
                                     AlternativeID='$alternativeID'
                                     WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZQuizAnswer object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();


        $db->query( "DELETE FROM eZQuiz_Answer WHERE ID='$catID'" );
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
            $db->array_query( $answerArray, "SELECT * FROM eZQuiz_Answer WHERE ID='$id'",
                              0, 1 );

            if( count( $answerArray ) == 1 )
            {
                $this->fill( &$answerArray[0] );
                $ret = true;
            }
            elseif( count( $answerArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$answerArray )
    {
        $this->ID =& $answerArray[ "ID" ];
        $this->User =& new eZUser( $answerArray[ "UserID" ] );
        $this->Alternative = new eZQuizAlternative( $answerArray[ "QuestionID" ] );
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZQuizAnswer objects.
    */
    function getAll( $offset=0, $limit=20)
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $answerArray = array();
        
        $db->array_query( $answerArray, "SELECT ID
                                           FROM eZQuiz_Answer
                                           " );
        
        for ( $i=0; $i < count($answerArray); $i++ )
        {
            $returnArray[$i] = new eZQuizAnswer( $answerArray[$i]["ID"] );
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
                                     FROM eZQuiz_Answer" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
        This function returns true if the user has answered the same question earlier.
    */
    function hasAnswered()
    {
        $return = false;
        
        if( get_class( $this->Alternative ) == "ezquizalternative" )
        {
            $QuestionID = $this->Alternative->QuestionID();
        }
        
        if( get_class( $this->User ) == "ezuser" )
        {
            $UserID = $this->User->id();
        }
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $result, "SELECT * FROM eZQuiz_Answer, eZQuiz_Alternative
                            WHERE eZQuiz_Answer.AlternativeID = eZQuiz_Alternative.ID
                            AND eZQuiz_Alternative.QuestionID = '$QuestionID' AND eZQuiz_Answer.UserID = '$UserID'" );

        if( count( $result ) >= 1 )
        {
            $return = true;
        }
        
        return $return;
    }


    /*!
      Returns the object ID to the answer. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the alternative of the answer.
    */
    function alternative()
    {
        return $this->Alternative;
    }

    /*!
      Returns the user of the answer.
    */
    function user()
    {
        return $this->User;
    }

    /*!
      Sets the user
    */
    function setUser( &$user )
    {
        if ( get_class ( $user ) == "ezuser" )
            $this->User = $user;
    }

    /*!
      Sets the alternative
    */
    function setAlternative( &$alternative )
    {
        if ( get_class ( $alternative ) == "ezquizalternative" )
            $this->Alternative = $alternative;
    }
    

    var $ID;
    var $User;
    var $Alternative;
}

?>
