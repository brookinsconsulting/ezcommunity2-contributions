<?php
// 
// $Id: eztopic.php,v 1.1 2001/06/01 13:29:49 bf Exp $
//
// Definition of eZTopic class
//
// Bård Farstad <bf@ez.no>
// Created on: <01-Jun-2001 12:03:53 bf>
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

//!! eZUser
//! eZTopic handles article topics
/*!
  
  \sa eZArticle eZArticeCategory
*/

include_once( "classes/ezdb.php" );


class eZTopic
{
    /*!
      Constructs a new eZTopic object.
    */
    function eZTopic( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZTopic object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZArticle_Topic SET
		                 Name='$name',
                         Description='$description'
                       " );
			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZArticle_Topic SET
		                 Name='$name',
                         Description='$description'
                        WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Deletes a eZTopic object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZArticle_Topic WHERE ID='$this->ID'" );
        }
        
        return true;
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
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Topic WHERE ID='$id'" );
            if( count( $author_array ) == 1 )
            {
                $this->ID =& $author_array[0][ "ID" ];
                $this->Name =& $author_array[0][ "Name" ];
                $this->Description =& $author_array[0][ "Description" ];
                $ret = true;
            }
            elseif( count( $author_array ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }


    /*!
      Fetches the user id from the database. And returns a array of eZTopic objects.
    */
    function &getAll(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $topic_array = array();


        $db->array_query( $topic_array, "SELECT ID FROM eZArticle_Topic
                                        ORDER By Name" );

        foreach ( $topic_array as $topic )
        {
            $return_array[] = new eZTopic( $topic[0] );
        }
        return $return_array;
    }
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the name.
    */
    function name( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Name );
        return $this->Name;
    }


    /*!
      Returns the description.
    */
    function description( )
    {
       return $this->Description;
    }

    /*!
      Sets the name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }
    
    /*!
      Sets the description.
    */
    function setDescription( $value )
    {
       $this->Description = $value;
    }
    
    var $ID;
    var $Name;
    var $Description;
}

?>
