<?php
// 
// $Id: eztodolog.php,v 1.6 2001/07/20 11:36:07 jakobn Exp $
//
// eZTodoLog class
//
// Created on: <30-Apr-2001 14:19:19 ce>
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

//!! eZTodo
//! eZTodoLog handles logs.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdatetime.php" );

class eZTodoLog
{

    /*!
      constructor
    */
    function eZTodoLog( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZTodoLog object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $GLOBALS["DEBUG"] = true;

        $log = $db->escapeString( $this->Log );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZTodo_Log" );
			$this->ID = $db->nextID( "eZTodo_Log", "ID" );
            $res[] = $db->query( "INSERT INTO eZTodo_Log
		                          (ID, Log)
                                  VALUES
                                  ('$this->ID','$log')" );
            $db->unlock();
            $this->get( $this->ID );
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTodo_Log SET
  		                          Log='$log'
                                  WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZTodoLog object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        if ( isset( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZTodoLog WHERE UserID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $todoLogArray, "SELECT * FROM eZTodo_Log WHERE ID='$id'",
                              0, 1 );
            if ( count( $todoLogArray ) == 1 )
            {
                $this->fill( $todoLogArray[0] );
                $ret = true;
            }
            else if( count( $todoLogArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$user_array )
    {
        $this->ID =& $user_array[ $db->fieldName( "ID" ) ];
        $this->Log =& $user_array[ $db->fieldName( "Log" ) ];
        $this->Created =& $user_array[ $db->fieldName( "Created" ) ];
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the log
    */
    function log()
    {
        return $this->Log;
    }

    /*!
      Sets the log.
    */
    function setLog( $value )
    {
       $this->Log = $value;
    }

    /*!
      Returns the creation time of the log.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
       $dateTime = new eZDateTime();

       $dateTime->setTimeStamp( $this->Created );
       
       return $dateTime;
    }

    var $ID;
    var $Log;
    var $Created;
}

?>
