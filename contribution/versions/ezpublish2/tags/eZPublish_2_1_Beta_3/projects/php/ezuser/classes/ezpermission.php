<?php
// 
// $Id: ezpermission.php,v 1.14 2001/05/04 16:37:27 descala Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 08:05:56 bf>
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
//! eZPermission haldes user group permissions.
/*!

  Example code:
  \code
  // First off, the fast version of permission checking.
  // the $group variable is a eZUserGroup object.
  if ( eZPermission::checkPermission( $group, "eZTrade", "AddProducts" ) )
  {
      print( "Access granted" );    
  }
  else
  {
      print( "Access denied" );
  }  
  
  // fetch a module from the database.
  $module->get( 1 );

  // create a new eZPermission object and set some values
  $permission = new eZPermission();
  $permission->setName( "AddProducts" );
  $permission->setModule( $module );

  // store the object and repport the success/failure
  if ( $permission->store() )
  {
      print( "Permission stored successfully<br>" );
  }
  else
  {
      print( "Error: could not store permission." );
  }

  // fetch a permission
  if ( $permission->get( 1 ) )
  {
      print( "Permission successfully fetched" );
  }

  // fetch a user group
  $group = new eZUserGroup();
  $group->get( 1 );
  
  // set a group permission
  $permission->setEnabled( $group, false );


  // check some permissions
  if ( $permission->isEnabled( $group ) )
  {
      print( "Access granted.<br>" );
  }
  else
  {
      print( "Access denied.<br>" );
  }

  \endcode
  \sa eZUser eZUserGroup eZModule eZForgot
*/

include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

class eZPermission
{
    /*!
      Constructs a new eZPermission object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZPermission( $id="", $fetch=true )
    {
        
        $this->IsConnected = false;
        if ( $id != "" )
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
      Stores or updates a eZPermission object in the database.
    */
    function store()
    {
        $this->dbInit();
        $ret = false;        
        $name = addslashes( $this->Name );
        
        if ( ( $this->ModuleID != "" ) && ( $this->ModuleID != 0 ) )
        {
            $this->Database->array_query( $value_array, "SELECT * FROM eZUser_Permission
                                                    WHERE Name='$name' AND ModuleID='$this->ModuleID'" );
            if ( count( $value_array ) == 0 )
            {            
                $ret = true;        
                if ( !isset( $this->ID ) )
                {
                    $this->Database->query( "INSERT INTO eZUser_Permission SET
		                         Name='$name',
                                 ModuleID='$this->ModuleID'
                                 " );
					$this->ID = $this->Database->insertID();
                }
                else
                {
                    $this->Database->query( "UPDATE eZUser_Permission SET
                                 Name='$name',
                                 ModuleID='$this->ModuleID'
                                 WHERE ID='$this->ID'" );
                }
            }
        }
        
        return $ret;
    }

    /*!
      Deletes a eZPermission object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZUser_GroupPermissionLink WHERE PermissionID='$this->ID'" );
           
            $this->Database->query( "DELETE FROM eZUser_Permission WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.

      Returns false if unsuccessful.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $permission_array, "SELECT * FROM eZUser_Permission WHERE ID='$id'" );
            if ( count( $permission_array ) > 1 )
            {
                die( "Error: Permission's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $permission_array ) == 1 )
            {
                $this->ID = $permission_array[0][ "ID" ];
                $this->Name = $permission_array[0][ "Name" ];
                $this->ModuleID = $permission_array[0][ "ModuleID" ];
                $ret = true;
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Fetches the id from the database where ModuleID == $id, and returns an array of eZPermission objects.
    */
    function getAllByModule( $module )
    {
        if ( get_class ( $module ) == "ezmodule" )
        {
        $this->dbInit();

        $moduleID = $module->id();

        $return_array = array();
        $permission_array = array();

            $this->Database->array_query( $permission_array, "SELECT ID FROM eZUser_Permission
                                                              WHERE ModuleID='$moduleID'
                                                              ORDER BY Name" );


            for ( $i=0; $i < count( $permission_array ); $i++ )
            {
                $return_array[$i] = new eZPermission( $permission_array[$i][ "ID" ], 0 );
            }

            return $return_array;
        }
    }

    /*!
      Fetches the id from the database, and returns an array of eZPermission objects.
    */
    function getAll( )
    {
        $this->dbInit();

        $return_array = array();
        $permission_array = array();

        $this->Database->array_query( $permission_array, "SELECT ID FROM eZUser_Permission" );

        for ( $i=0; $i < count( $permission_array ); $i++ )
        {
            $return_array[$i] = new eZPermission( $permission_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }
    


    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the permission name.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       return $this->Name;
    }

    /*!
      Returns the module as a eZModule object.

      False is returned if unsuccessful.
    */
    function module()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $module = new eZModule( );
       
       if ( $module->get( $this->ModuleID ) )
       {
           $ret = $module;
       }
            
       return $module;
    }


    /*!
      Sets the permission name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }

    /*!
      Sets the module.

      Returns false if unsuccessful, else true.
    */
    function setModule( $module )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( get_class( $module ) == "ezmodule" )
       {
           $this->ModuleID = $module->id();
           $ret = true;
       }
       return $ret;
    }

    /*!
      Sets the permission value.

      Takes a eZUserGroup object and a bool as arguments.

      Returned false if unsuccessful.
    */
    function setEnabled( $group, $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;

       if ( get_class( $group ) == "ezusergroup" )
       {
           $this->dbInit();
           $ret = true;

           $groupID = $group->id();

           if ( $value )
           {
               $isEnabled = "true";
           }
           else
           {
               $isEnabled = "false";
           }
           
           $this->Database->array_query( $value_array, "SELECT ID FROM eZUser_GroupPermissionLink
                                                    WHERE PermissionID='$this->ID' AND GroupID='$groupID'" );
           if ( count( $value_array ) == 1 )
           {
               $valueID = $value_array[0]["ID"];
               $this->Database->query( "UPDATE eZUser_GroupPermissionLink SET
		                         IsEnabled='$isEnabled' WHERE ID='$valueID'
                                 " );
           }
           else
           {
               $this->Database->query( "INSERT INTO eZUser_GroupPermissionLink SET
		                         PermissionID='$this->ID',
		                         GroupID='$groupID',
                                 IsEnabled='$isEnabled'
                                 " );
           }
       }
       return $ret;
    }

    /*!
      Returns true if the permission is enabled.

      False is returned if the permission does not exits or if the permission is disabled.
    */
    function isEnabled( $group )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( get_class( $group ) == "ezusergroup" )
       {
           $this->dbInit();

           $groupID = $group->id();


           $this->Database->array_query( $value_array, "SELECT * FROM eZUser_GroupPermissionLink
                                                    WHERE PermissionID='$this->ID' AND GroupID='$groupID'" );
           if ( count( $value_array ) == 1 )
           {
               if ( $value_array[0]["IsEnabled"] == "true" )
               {
                   $ret = true;
               }
           }           
       }
       return $ret;
    }

    /*!
      \static
      Static function for checking permissions.
      Note: If the user has root permissions this function allways returns true.
    */
    function checkPermission( $user, $moduleName, $permissionName )
    {
        $module = new eZModule();
        $module = $module->exists( $moduleName );

        if( get_class( $user ) != "ezuser" )
            return false;
        
        if ( $user->hasRootAccess() )
            return true;
        
        $ret = false;

        if ( $module )
        {
            // connect to the db
            if ( $this->IsConnected == false )
            {
                $this->Database =& eZDB::globalDatabase();
                $this->IsConnected = true;
            }

            $moduleID = $module->id();

            $test = "SELECT * FROM eZUser_Permission WHERE Name='$permissionName' AND ModuleID='$moduleID'";

            $this->Database->array_query( $value_array, "SELECT * FROM eZUser_Permission
                                                    WHERE Name='$permissionName' AND ModuleID='$moduleID'", true );


            if ( count( $value_array ) == 1 )
            {

                $permission = new eZPermission( );
                $permission->get( $value_array[0]["ID"] ); 

                if ( get_class( $user ) == "ezuser" )
                {

                    $group = new eZUserGroup();
                    $groupArray = $group->getByUser( $user );


                    foreach ( $groupArray as $group )
                    {
                        if ( $permission->isEnabled( $group ) )
                        {
                            $ret = true;
                            break;
                        }
                    }
                }
            }
        }
        return $ret;
    }

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $ModuleID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
 