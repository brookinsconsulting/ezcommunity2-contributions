<?
// 
// $Id: ezbuglog.php,v 1.3 2001/04/04 15:21:44 fh Exp $
//
// Definition of eZBugLog class
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Nov-2000 21:44:41 bf>
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
//! eZBug handles bug repports.
/*!
  \sa eZBug eZBugCategory eZBugModule eZBugPriority
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );

include_once( "ezuser/classes/ezuser.php" );

class eZBugLog
{
    /*!
      Constructs a new eZBugLog object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBuglog( $id=-1, $fetch=true )
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
      Stores a eZBuglog object to the database.
    */
    function store()
    {
        $this->dbInit();
        $description = addslashes( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBug_Log SET
                                 Description='$description',
                                 BugID='$this->BugID',
                                 UserID='$this->UserID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBug_Log SET
                                 Description='$description',
                                 BugID='$this->BugID',
                                 Created='Created',
                                 UserID='$this->UserID'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBugLog object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZBug_Log WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $module_array, "SELECT * FROM eZBug_Log WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: BugLogs with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][ "ID" ];
                $this->Description = $module_array[0][ "Description" ];
                $this->UserID = $module_array[0][ "UserID" ];
                $this->BugID = $module_array[0][ "BugID" ];
                $this->Created = $module_array[0][ "Created" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $module_array = array();
        
        $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Log ORDER BY Created" );
        
        for ( $i=0; $i<count($module_array); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns all the bugs found in the database which is assigned to
      the bug given as argument.

      The bugs are returned as an array of eZBug objects.
    */
    function getByBug( $bug )
    {
        $this->dbInit();
        
        $return_array = array();
        $module_array = array();
        if ( get_class( $bug ) == "ezbug" )
        {
            $bugID = $bug->id();
            $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Log
                                                          WHERE BugID='$bugID' 
                                                          ORDER BY Created" );
        
            for ( $i=0; $i<count($module_array); $i++ )
            {
                $return_array[$i] = new eZBugLog( $module_array[$i]["ID"], 0 );
            }
        }
        
        return $return_array;
    }
    
    /*!
      Returns the object id.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->ID;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
           
    }
    
    /*!
      Returns the creation time of the bug log message.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->Created );
       
       return $dateTime;
    }

    /*!
      Returns the user as a eZUser object.
    */
    function &user()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $user = new eZUser( $this->UserID );
       return $user;
    }

    /*!
      Returns the bug as a eZBug object.
    */
    function &bug()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $bug = new eZBug( $this->BugID );
       return $bug;
    }
    
    /*!
      Sets the description of the module.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the user who made the change.
    */
    function setUser( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $user ) == "ezuser" )
       {
           $this->UserID = $user->id();
       }
    }

    /*!
      Sets the bug the change belongs to.
    */
    function setBug( $bug )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $bug ) == "ezbug" )
       {
           $this->BugID = $bug->id();
       }
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
    var $Description;
    var $IsHandled;
    var $Created;
    var $UserID;
    var $BugID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
