<?php
// 
// $Id: eztitle.php,v 1.1 2001/10/26 12:30:50 bf Exp $
//
// Definition of eZTitle class
//
// Created on: <26-Oct-2001 13:39:15 bf>
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
//! eZTitle handles user titles.
/*!
  \sa eZUser eZUserGroup eZPermission eZModule eZForgot
*/

include_once( "classes/ezdb.php" );


class eZTitle
{
    /*!
      Constructs a new eZTitle object.
    */
    function eZTitle( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZTitle object in the database.
    */
    function store()
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );

        
        $name = $db->escapeString( $this->Name );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_Title" );
            $nextID = $db->nextID( "eZUser_Title", "ID" );

            $res = $db->query( "INSERT INTO eZUser_Title
                         ( ID, Name )
                         VALUES 
		                 ( '$nextID', '$name' )" );
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZUser_Title SET
		                 Name='$name'
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
      Deletes a eZTitle object from the database.
    */
    function delete( $id )
    {
        $db =& eZDB::globalDatabase();

        if ( is_numeric( $id ) )
        {
            $db->query( "DELETE FROM eZUser_Title WHERE ID='$id'" );
        }
        else if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_Title WHERE ID='$this->ID'" );
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
            $db->array_query( $title_array, "SELECT * FROM eZUser_Title WHERE ID='$id'" );
            if( count( $title_array ) == 1 )
            {                
                $this->ID =& $title_array[0][$db->fieldName("ID")];
                $this->Name =& $title_array[0][$db->fieldName("Name")];
                $ret = true;
            }
            elseif( count( $title_array ) == 1 )
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
        $db->array_query( $title_array, "SELECT * FROM eZUser_Title WHERE Name='$name'" );
        if( count( $title_array ) == 1 )
        {
            $this->ID =& $title_array[0][$db->fieldName("ID")];
            $this->Name =& $title_array[0][$db->fieldName("Name")];
            $ret = true;
        }
        return $ret;
    }

    
    /*!
      Fetches the user id from the database. And returns a array of eZTitle objects.
    */
    function &getAll(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $title_array = array();


        $db->array_query( $title_array, "SELECT ID,Name FROM eZUser_Title
                                        ORDER By Name" );

        foreach ( $title_array as $title )
        {
            $return_array[] = new eZTitle( $title[$db->fieldName("ID")] );
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
            return htmlspecialchars( $this->Name );
        return $this->Name;
    }

    /*!
      Sets the name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }
    
    var $ID;
    var $Name;
}

?>
