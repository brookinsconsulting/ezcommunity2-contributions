<?php
// 
// $Id: ezarticlepoll.php,v 1.1.2.1 2002/06/03 15:06:18 pkej Exp $
//
// ezarticlepoll class
//
// Created on: <22-May-2002 15:42:23 pkej>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZArticle
//! ezarticlepoll documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezarticle/classes/ezarticle.php" );

class eZArticlePoll
{

    /*!
      Constructs a new eZArticlePoll object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZArticlePoll( $id=-1, $fetch=true )
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
      Stores a eZArticlePoll object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        
        if( get_class( $this->Poll ) == "ezpoll" )
        {
            $PollID = $this->Poll->id();
        }

        if( get_class( $this->Article ) == "ezarticle" )
        {
            $ArticleID = $this->Article->id();
        }
        
        $setValues = "
            PollID='$PollID',
            ArticleID='$ArticleID'
        ";
        
        if ( empty( $this->ID ) )
        {
            $db->query( "INSERT INTO eZArticle_ArticlePollDict SET $setValues" );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZArticle_ArticlePollDict SET $setValues WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZArticlePoll object from the database.
    */
    function delete( $pollID=-1 )
    {
        if ( $pollID == -1 )
            $pollID = $this->ID;

        $db =& eZDB::globalDatabase();
        
        $db->query( "DELETE FROM eZArticle_ArticlePollDict WHERE ID=$pollID" );
    }

    /*!
      Fetches the object inpollation from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $pollArray, "SELECT * FROM eZArticle_ArticlePollDict WHERE ID='$id'",
                              0, 1 );
            if( count( $pollArray ) == 1 )
            {
                $this->fill( &$pollArray[0] );
                $ret = true;
            }
            elseif( count( $pollArray ) != 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in inpollation to the object taken from the array.
    */
    function fill( &$pollArray )
    {
        $this->ID =& $pollArray[ "ID" ];
        
        $this->Article =& new eZArticle( $pollArray[ "ArticleID" ] );
        $this->Poll =& new eZPoll( $pollArray[ "PollID" ] );
    }

    /*!
      \static
      Returns all the objects found in the database.

      The objects are returned as an array of eZArticlePoll objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $pollArray = array();

        if ( $limit == false )
        {
            $db->array_query( $pollArray, "SELECT ID
                                           FROM eZArticle_ArticlePollDict
                                           " );

        }
        else
        {
            $db->array_query( $pollArray, "SELECT ID
                                           FROM eZArticle_ArticlePollDict
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($pollArray); $i++ )
        {
            $returnArray[$i] = new eZArticlePoll( $pollArray[$i]["ID"] );
        }

        return $returnArray;
    }

    /*!
      \static
      Returns the total count of objects in the database.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZArticle_ArticlePollDict" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
      Returns the object ID. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the poll of the object.
    */
    function &poll()
    {
        return $this->Poll;
    }

    /*!
      Returns the article of the object.
    */
    function &receiver()
    {
        return $this->Article;
    }

   /*!
      Sets the article of the object.
    */
    function setArticle( &$object )
    {
        if( get_class( $object ) == "ezarticle" )
        {
            $this->Article = $object;
        }
    }

   /*!
      Sets the poll of the object.
    */
    function setPoll( &$object )
    {
        if( get_class( $object ) == "ezpoll" )
        {
            $this->Poll = $object;
        }
    }

    /*!
        \static
        Returns the poll if the article has a poll.
        
        The article is sent in as an eZArticle object.
        The poll is returned as an eZPoll object.
    */
    function &articleHasPoll( &$object )
    {
        $returnArray = array();
        $pollArray = array();
        
        if( get_class( $object ) == "ezarticle" )
        {
            $ArticleID = $object->id();
        }
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT PollID FROM eZArticle_ArticlePollDict WHERE ArticleID='$ArticleID'" );

        $ret =& new eZPoll( $qry["PollID"] );

        return $ret;
    }

    /*!
        \static
        Returns the article if the poll has an article.
        
        The poll is sent in as an eZPoll object.
        The article is returned as an eZArticle object.
    */
    function &pollHasArticle( &$object )
    {
        $returnArray = array();
        $pollArray = array();
        
        if( get_class( $object ) == "ezpoll" )
        {
            $PollID = $object->id();
        }
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ArticleID FROM eZArticle_ArticlePollDict WHERE PollID='$PollID'" );

        $ret =& eZArticle( $qry["ArticleID"] );

        return $ret;
    }

    var $ID;
    var $Poll;
    var $Article;
}

?>
