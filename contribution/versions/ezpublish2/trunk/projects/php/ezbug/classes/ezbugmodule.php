<?
// 
// $Id: ezbugmodule.php,v 1.3 2000/12/09 18:59:02 bf Exp $
//
// Definition of eZBugModule class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Nov-2000 19:08:57 bf>
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
//! eZBugModule handles bug categories.
/*!
  Example code:
  \code
  // include the class
  include_once( "ezbug/classes/ezbugmodule.php" );

  // create a new class object
  $module = new eZBugModule();

  // Set some object values and store them to the database.
  $module->setName( "eZ trade" );
  $module->setDescription( "Bugs reported here are related to the eZ trade module." );
  $module->store();
  \endcode
  \sa eZBug eZBugCategory
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZBugModule
{
    /*!
      Constructs a new eZBugModule object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBugModule( $id=-1, $fetch=true )
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
      Stores a eZBugModule object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBug_Module SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBug_Module SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
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
            $this->Database->query( "DELETE FROM eZBug_BugModuleLink WHERE ModuleID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZBug_Module WHERE ID='$this->ID'" );            
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
            $this->Database->array_query( $module_array, "SELECT * FROM eZBug_Module WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Module's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][ "ID" ];
                $this->Name = $module_array[0][ "Name" ];
                $this->Description = $module_array[0][ "Description" ];
                $this->ParentID = $module_array[0][ "ParentID" ];
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

      The categories are returned as an array of eZBugModule objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $module_array = array();
        
        $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Module ORDER BY Name" );
        
        for ( $i=0; $i<count($module_array); $i++ )
        {
            $return_array[$i] = new eZBugModule( $module_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the module given as parameter as parent.

      The categories are returned as an array of eZBugModule objects.      
    */
    function getByParent( $parent, $sortby=name )
    {
        if ( get_class( $parent ) == "ezbugmodule" )
        {
            $this->dbInit();
        
            $return_array = array();
            $module_array = array();

            $parentID = $parent->id();

            $this->Database->array_query( $module_array, "SELECT ID, Name FROM eZBug_Module
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i<count($module_array); $i++ )
            {
                $return_array[$i] = new eZBugModule( $module_array[$i]["ID"], 0 );
            }

            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $moduleID=0 )
    {
        if ( $moduleID == 0 )
        {
            $moduleID = $this->ID;
        }
            
        $module = new eZBugModule( $moduleID );

        $path = array();

        $parent = $module->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $module->name() );
        }

        if ( $moduleID != 0 )
            array_push( $path, array( $module->id(), $module->name() ) );                                
        
        return $path;
    }
    
    /*!
      Returns the object ID to the module. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Returns the name of the module.
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
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->ParentID != 0 )
       {
           return new eZBugModule( $this->ParentID );
       }
       else
       {
           return 0;           
       }
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
      Sets the parent module.
    */
    function setParent( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $value ) == "ezbugmodule" )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
      Adds a bug to the module.
    */
    function addBug( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezbug" )
       {
            $this->dbInit();

            $bugID = $value->id();
            
            $query = "INSERT INTO
                           eZBug_BugModuleLink
                      SET
                           ModuleID='$this->ID',
                           BugID='$bugID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns every bug in a module as a array of eZBug objects.

      If $fetchUnhandled is set to true the bugs which is not yet handled are
      also returned.
    */
    function bugs( $sortMode="time",
                       $fetchUnhandled=true,
                       $offset=0,
                       $limit=50 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $OrderBy = "eZBug_Bug.Created DESC";
       switch( $sortMode )
       {
           case "alpha" :
           {
                 $OrderBy = "eZBug_Bug.Name ASC";
           }
           break;
       }
         
       $return_array = array();
       $bug_array = array();

       $unhandledSQL = "";
       if ( $fetchUnhandled == false )
       {
           $unhandledSQL = "AND IsHandled='true'";
       }


       $this->Database->array_query( $bug_array, "
                SELECT eZBug_Bug.ID AS BugID, eZBug_Bug.Name, eZBug_Module.ID, eZBug_Module.Name
                FROM eZBug_Bug, eZBug_Module, eZBug_BugModuleLink
                WHERE 
                eZBug_BugModuleLink.BugID = eZBug_Bug.ID
                AND
                eZBug_Module.ID = eZBug_BugModuleLink.ModuleID
                $unhandledSQL
                AND
                eZBug_Module.ID='$this->ID'
                GROUP BY eZBug_Bug.ID ORDER BY $OrderBy LIMIT $offset,$limit" );
 
       for ( $i=0; $i<count($bug_array); $i++ )
       {
           $return_array[$i] = new eZBug( $bug_array[$i]["BugID"], false );
       }
       
       return $return_array;
    }

    /*!
      Returns the bug count in the module.

      If $countUnhandled == true all bugs are counted if not only
      handled bugs are counted.

      If $excludeClosed == true the closed bugs does not get counted.
    */
    function countBugs( $countUnhandled=true, $excludeClosed=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $unhandledSQL = "";
       if ( $countUnhandled == false )
       {
           $unhandledSQL = "AND IsHandled='true'";
       }

       $openSQL = "";
       if ( $excludeClosed == true )
       {
           $openSQL = "AND IsClosed='false'";
       }
       
       $this->Database->array_query( $bug_array, "
                SELECT count( eZBug_Bug.ID ) AS Count
                FROM eZBug_Bug, eZBug_Module, eZBug_BugModuleLink
                WHERE 
                eZBug_BugModuleLink.BugID = eZBug_Bug.ID
                AND
                eZBug_Module.ID = eZBug_BugModuleLink.ModuleID
                $unhandledSQL
                $openSQL
                AND
                eZBug_Module.ID='$this->ID'
                " );
 
       $ret =  $bug_array[0]["Count"];       

       return $ret;
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
    var $ParentID;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
