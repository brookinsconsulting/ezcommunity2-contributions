<?
// 
// $Id: ezbugpriority.php,v 1.4 2001/04/04 15:21:44 fh Exp $
//
// Definition of eZBugPriority class
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Nov-2000 20:30:36 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZBug
//! eZBugPriority handles bug categories.
/*!
  Example code:
  \code
  // include the class
  include_once( "ezbug/classes/ezbugpriority.php" );

  // create a new class object
  $priority = new eZBugPriority();

  // Set some object values and store them to the database.
  $priority->setName( "Urgent" );
  $priority->store();
  \endcode
  \sa eZBug eZBugModule eZBugCategory
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZBugPriority
{
    /*!
      Constructs a new eZBugPriority object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBugPriority( $id=-1, $fetch=true )
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
      Stores a eZBugPriority object to the database.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBug_Priority SET
		                         Name='$name'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBug_Priority SET
		                         Name='$name'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBugPriority object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            // remove all bugs from the database that have this priority.
            $this->Database->query( "DELETE FROM eZBug_Bug WHERE PriorityID='$this->ID'" );
            // remove the priority itself. 
            $this->Database->query( "DELETE FROM eZBug_Priority WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $priority_array, "SELECT * FROM eZBug_Priority WHERE ID='$id'" );
            if ( count( $priority_array ) > 1 )
            {
                die( "Error: Priority's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $priority_array ) == 1 )
            {
                $this->ID = $priority_array[0][ "ID" ];
                $this->Name = $priority_array[0][ "Name" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBugPriority objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $priority_array = array();
        
        $this->Database->array_query( $priority_array, "SELECT ID FROM eZBug_Priority ORDER BY Name" );
        
        for ( $i=0; $i<count($priority_array); $i++ )
        {
            $return_array[$i] = new eZBugPriority( $priority_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the object ID to the priority. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Returns the name of the priority.
    */
    function name( $html = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       if( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Sets the name of the priority.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Private function.      
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
    
    var $ID;
    var $Name;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
