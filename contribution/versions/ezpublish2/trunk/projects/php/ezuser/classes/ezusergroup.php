<?
// 
// $Id: ezusergroup.php,v 1.6 2000/11/19 14:42:35 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Sep-2000 18:45:40 bf>
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

//!! eZUser
//! eZUserGroup handles user groups.
/*!
  
  Example code:
  \code
  // create a new eZUsergroup object and set some values.
  $group = new eZUserGroup();
  $group->setName( "Administrator" );
  $group->setDescription( "Has root access" );

  // store it to the database.
  $group->store();

  // fetch user group with object id == 1
  $group->get( 1 );

  // fetch a user with object id == 42
  $user = new eZUser();
  $user->get( 42 );

  // add a user to the user group.
  if ( $group->adduser( $user ) )
  {
      print( "User added to group" );    
  }
  else
  {
      print( "Error: count not add user." );
  }  
  \endcode
  \sa eZUser eZPermission eZModule eZForgot
*/

/*!TODO
*/
class eZUserGroup
{

    /*!
      Constructs a new eZUser object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZUserGroup( $id=-1, $fetch=true )
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
      Stores or updates a eZUserGroup object in the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZUser_Group SET
                                 Name='$this->Name',
                                 Description='$this->Description',
                                 SessionTimeout='$this->SessionTimeout'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZUser_Group SET
                                 Name='$this->Name',
                                 Description='$this->Description',
                                 SessionTimeout='$this->SessionTimeout'
                                 WHERE ID='$this->ID'" );            
        }
        
        return true;
    }

    /*!
      Deletes a eZUserGroup object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZUser_UserGroupLink WHERE GroupID='$this->ID'" );

            $this->Database->query( "DELETE FROM eZUser_GroupPermissionLink WHERE GroupID='$this->ID'" );

            $this->Database->query( "DELETE FROM eZUser_Group WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $user_group_array, "SELECT * FROM eZUser_Group WHERE ID='$id'" );
            if ( count( $user_group_array ) > 1 )
            {
                die( "Error: User groups with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $user_group_array ) == 1 )
            {
                $this->ID = $user_group_array[0][ "ID" ];
                $this->Name = $user_group_array[0][ "Name" ];
                $this->Description = $user_group_array[0][ "Description" ];
                $this->SessionTimeout = $user_group_array[0][ "SessionTimeout" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns every user group from the database. The result is returned as an
      array of eZUserGroup objects.
    */
    function getAll()
    {
        $this->dbInit();

        $return_array = array();
        $group_array = array();

        $this->Database->array_query( $group_array, "SELECT ID FROM eZUser_Group ORDER By Name" );

        for ( $i=0; $i<count ( $group_array ); $i++ )
        {
            $return_array[$i] = new eZUserGroup( $group_array[$i][ "ID" ], 0 );
        }

        return $return_array;
        
    }

    /*!
      Fetches every group the user is a member of.

      The result is returned as an array of eZUserGroup object. An empty array
      is returned if there are none.
    */
    function getByUser( $user )
    {
        $return_array = array();
        
        if ( get_class( $user ) == "ezuser" )
        {
            $this->dbInit();
        
            $group_array = array();

            $userID = $user->id();

            $this->Database->array_query( $group_array, "SELECT GroupID FROM eZUser_UserGroupLink WHERE UserID='$userID'" );

            for ( $i=0; $i<count ( $group_array ); $i++ )
            {
                $return_array[$i] = new eZUserGroup( $group_array[$i][ "GroupID" ], 0 );
            }
        }
        return $return_array;        
    }
    
    /*!
      Returns the object ID to the user group.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the users who is a member of the eZUserGroup object.
    */
    function users( $GroupID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = array();
        $this->dbInit();

        $this->Database->array_query( $user_array, "SELECT * FROM eZUser_UserGroupLink
                                                   WHERE GroupID='$GroupID'" );
        foreach ( $user_array as $user )
        {
            $ret[] = new eZUser( $user["UserID"] );
        }
        
        return $ret;
    }

    /*!
      Returns the user group name.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the user group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }

    /*!
      Returns the session timeout value in minutes.
    */
    function sessionTimeout()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->SessionTimeout;
    }
    
    /*!
      Sets the name of the user group.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }

    /*!
      Sets the description of the user group.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Description = $value;
    }

    /*!
      Sets the session timeout value, the value is in minutes.
    */
    function setSessionTimeout( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->SessionTimeout = $value;
       
       setType( $this->SessionTimeout, "integer" );
    }
    
    /*!
      Adds a user to the current user group.

      Returns true if successful, false if not.
    */
    function addUser( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $ret = false;

       if ( get_class( $user ) == "ezuser" )
       {
           $this->dbInit();

           $userID = $user->id();

//             if ( $this->ID > 1 )
           {
               $this->Database->query( "INSERT INTO eZUser_UserGroupLink
                                    SET
                                    UserID='$userID',
                                    GroupID='$this->ID'" );
               $ret = true;
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
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Description;
    var $SessionTimeout;
        
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
