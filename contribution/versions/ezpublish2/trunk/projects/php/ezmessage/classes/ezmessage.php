<?php
// 
// $Id: ezmessage.php,v 1.3 2001/06/06 08:30:46 bf Exp $
//
// Definition of eZMessage class
//
// Bård Farstad <bf@ez.no>
// Created on: <05-Jun-2001 13:46:48 bf>
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

//!! eZMessage
//! eZMessage handles messages to eZ publish users.
/*!
  
*/

include_once( "classes/ezdb.php" );


class eZMessage
{
    /*!
      Constructs a new eZMessage object.
    */
    function eZMessage( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZMessage object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $subject = addslashes( $this->Subject );
        $description = addslashes( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZMessage_Message SET
		                 Subject='$subject',
                         Created=now(),
                         Description='$description',
                         FromUserID='$this->FromUserID',
                         ToUserID='$this->ToUserID'
                       " );
			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZMessage_Message SET
		                 Subject='$subject',
                         Created=Created, 
                         Description='$description',
                         FromUserID='$this->FromUserID',
                         ToUserID='$this->ToUserID'
                         WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Deletes a eZMessage object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZMessage_Message WHERE ID='$this->ID'" );
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
            $db->array_query( $author_array, "SELECT * FROM eZMessage_Message WHERE ID='$id'" );
            if( count( $author_array ) == 1 )
            {
                $this->ID =& $author_array[0][ "ID" ];
                $this->Subject =& $author_array[0][ "Subject" ];
                $this->Description =& $author_array[0][ "Description" ];
                $this->Created =& $author_array[0][ "Created" ];
                $this->FromUserID =& $author_array[0][ "FromUserID" ];
                $this->ToUserID =& $author_array[0][ "ToUserID" ];
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
      Fetches the user id from the database. And returns a array of eZMessage objects.
    */
    function &getAll(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $message_array = array();


        $db->array_query( $message_array, "SELECT ID FROM eZMessage_Message
                                        ORDER By Created" );

        foreach ( $message_array as $message )
        {
            $return_array[] = new eZMessage( $message[0] );
        }
        return $return_array;
    }

    /*!
      Fetches the messages for a user.
    */
    function &messagesToUser( $user )
    {
        $userID = $user->id();
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $message_array = array();


        $db->array_query( $message_array, "SELECT ID FROM eZMessage_Message
                                        WHERE ToUserID='$userID'
                                        ORDER By Created" );

        foreach ( $message_array as $message )
        {
            $return_array[] = new eZMessage( $message[0] );
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
      Returns the subject.
    */
    function subject( $html = true )
    {
        if ( $html )
            return htmlspecialchars( $this->Subject );
        return $this->Subject;
    }

    /*!
      Sets the use which the message is from.
    */
    function setFromUser( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $this->FromUserID = $user->id();
        }
    }

    /*!
      Returns the from user as an eZUser object.
    */
    function fromUser()
    {
        return new eZUser( $this->FromUserID );
    }
     

    /*!
      Returns the to user as an eZUser object.
    */
    function toUser()
    {
        return new eZUser( $this->toUserID );
    }
     
    
    /*!
      Sets the use which the message is to.
    */
    function setToUser( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $this->ToUserID = $user->id();
        }
    }

    /*!
      Returns the message creation time as a eZDateTime object.
    */
    function &created()
    {
        $dateTime = new eZDateTime();    
        $dateTime->setMySQLTimeStamp( $this->Created );

        return $dateTime;
    }
    
    /*!
      Returns the description.
    */
    function description( )
    {
       return $this->Description;
    }

    /*!
      Sets the subject.
    */
    function setSubject( $value )
    {
       $this->Subject = $value;
    }
    
    /*!
      Sets the description.
    */
    function setDescription( $value )
    {
       $this->Description = $value;
    }
    
    var $ID;
    var $FromUserID;
    var $ToUserID;
    var $Created;
    var $Subject;
    var $Description;
}

?>
