<?php
// 
// $Id: ezbidder.php,v 1.9.2.2 2002/08/15 14:20:29 gl Exp $
//
// Definition of eZBidder class
//
// Created on: <31-May-2001 13:52:30 bf>
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

//!! eZUser
//! eZBidder handles bidders/photographers.
/*!
  \sa eZUser eZUserGroup eZPermission eZModule eZForgot
*/

include_once( "classes/ezdb.php" );
include_once( "classes/eztexttool.php" );


// well basicly this object is a class based off user, 
// it lists based on a stuipid manual list table 
// doesnm't really pull information from a the actual user id it is based from, why not?
// basicly it's not pulling from any session / user id info , or putting it into the db.
// i need to add a session storage of the users, errum the list built from db user . . .?

// so this is prolly the wrong modula to base all this off of . . .. 
// re factor this moduel to use and hook to ez objects, it will be based of rfp content holders / bidders

class eZBidder
{
    /*!
      Constructs a new eZBidder object.
    */
    function eZBidder( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZBidder object in the database.
    */
    function store()
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );

        
        $name = $db->escapeString( $this->Name );
        $email = $db->escapeString( $this->EMail );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_Bidder" );
            $nextID = $db->nextID( "eZUser_Bidder", "ID" );

            $res = $db->query( "INSERT INTO eZUser_Bidder
                         ( ID, Name, EMail )
                         VALUES 
		                 ( '$nextID', '$name', '$email' )" );
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZUser_Bidder SET
		                 Name='$name',
                         EMail='$email'
                        WHERE ID='$this->ID'" );
            

        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
        {
            $db->commit();
            $ret = true;
        }

        return $ret;
    }

    /*!
      Deletes a eZBidder object from the database.
    */
    function delete( $id )
    {
        $db =& eZDB::globalDatabase();

        if ( is_numeric( $id ) )
        {
            $db->query( "DELETE FROM eZUser_Bidder WHERE ID='$id'" );
        }
        else if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_Bidder WHERE ID='$this->ID'" );
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
            $db->array_query( $bidder_array, "SELECT * FROM eZUser_Bidder WHERE ID='$id'" );
            if( count( $bidder_array ) == 1 )
            {                
                $this->ID =& $bidder_array[0][$db->fieldName("ID")];
                $this->Name =& $bidder_array[0][$db->fieldName("Name")];
                $this->EMail =& $bidder_array[0][$db->fieldName("EMail")];
                $ret = true;
            }
            elseif( count( $bidder_array ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }


    /*!
      Fetches the authour with the given name.

      True is retuned if successful, false (0) if not.
    */
    function getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        $db->array_query( $bidder_array, "SELECT * FROM eZUser_Bidder WHERE Name='$name'" );
        if( count( $bidder_array ) == 1 )
        {
            $this->ID =& $bidder_array[0][$db->fieldName("ID")];
            $this->Name =& $bidder_array[0][$db->fieldName("Name")];
            $this->EMail =& $bidder_array[0][$db->fieldName("EMail")];
            $ret = true;
        }
        return $ret;
    }

    /*!
      Fetches the user id from the database. And returns a array of eZBidder objects.
    */
    function &getAll(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $bidder_array = array();


        $db->array_query( $bidder_array, "SELECT ID,Name FROM eZUser_Bidder
                                        ORDER By Name" );

        foreach ( $bidder_array as $bidder )
        {
            $return_array[] = new eZBidder( $bidder[$db->fieldName("ID")] );
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
        if ( $html )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Name ) );
        return $this->Name;
    }

    /*!
      Returns the bidders e-mail address.
    */
    function email( )
    {
       return $this->EMail;
    }

    /*!
      Sets the name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the email address to the user.
    */
    function setEmail( $value )
    {
       $this->EMail = $value;
    }

    var $ID;
    var $Name;
    var $EMail;
}

?>
