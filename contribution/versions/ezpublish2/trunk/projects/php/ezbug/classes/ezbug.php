<?
// 
// $Id: ezbug.php,v 1.1 2000/11/28 13:42:23 bf-cvs Exp $
//
// Definition of eZBug class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Nov-2000 19:43:24 bf>
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
  Example:
  \code
  // include the class
  include_once( "ezbug/classes/ezbug.php" );

  // create a new eZBug object.
  $bug = new eZBug();

  // set the object properties and save it to the database.
  $bug->setUser( eZUser::currentUser() );
  $bug->setName( "Empty search result" );
  $bug->setDescription( "The product search does not return anything." );
  $bug->setIsHandled( false );
  $bug->store();
  \endcode
  \sa eZBug eZBugCategory eZBugModule
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );

include_once( "ezuser/classes/ezuser.php" );

class eZBug
{
    /*!
      Constructs a new eZBug object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBug( $id=-1, $fetch=true )
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
      Stores a eZBug object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBug_Bug SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 IsHandled='$this->IsHandled',
                                 UserID='$this->UserID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBug_Bug SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 IsHandled='$this->IsHandled',
                                 Created='Created',
                                 UserID='$this->UserID'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBugGroup object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZBug_BugModuleLink WHERE BugID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZBug_BugCategoryLink WHERE BugID='$this->ID'" );

            $this->Database->query( "DELETE FROM eZBug_Bug WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $module_array, "SELECT * FROM eZBug_Bug WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Bugs with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][ "ID" ];
                $this->Name = $module_array[0][ "Name" ];
                $this->Description = $module_array[0][ "Description" ];
                $this->UserID = $module_array[0][ "UserID" ];
                $this->Created = $module_array[0][ "Created" ];
                $this->IsHandled = $module_array[0][ "IsHandled" ];
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
        
        $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Bug ORDER BY Name" );
        
        for ( $i=0; $i<count($module_array); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns all the unhandled bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function &getUnhandled()
    {
        $this->dbInit();
        
        $return_array = array();
        $module_array = array();
        
        $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Bug
                                                      WHERE IsHandled='false'
                                                      ORDER BY Created" );
        
        for ( $i=0; $i<count($module_array); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i]["ID"], 0 );
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
      Returns the name of the bug.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }
    
    /*!
      Returns the group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }
    
    /*!
      Returns the creation time of the article.

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
      Returns true if the article is handled false if not.
    */
    function isHandled()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsHandled == "true" )
       {
           $ret = true;
       }
       return $ret;
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
      Sets the name of the module.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
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
     Sets the article to handled or not. 
    */
    function setIsHandled( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsHandled = "true";
       }
       else
       {
           $this->IsHandled = "false";           
       }
    }

    /*!
      Sets the user of the article.
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
    var $Description;
    var $IsHandled;
    var $Created;
    var $UserID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
