<?php
// 
// $Id: ezmodule.php,v 1.6 2001/04/05 08:52:44 fh Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 08:00:43 bf>
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
//! eZModule handles projects/modules used for permission control.
/*!

  Example code:
  \code
  $module = new eZModule();

  $module->setName( "eZTrade" );

  if ( !$module->exists( $module->name() ) )
  {
      print( "Creating module<br>" );
      $module->store();
  }
  else
  {
      print( "Error: count not create module, a module with that name already exists." );
  }

  \endcode
  \sa eZUser eZUserGroup eZPermission eZForgot
*/

class eZModule
{
    /*!
      Constructs a new eZModule object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZModule( $id="", $fetch=true )
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
      Stores or updates a eZModule object in the database.

      Returns false if the storing did not succeed.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZUser_Module SET
                                     Name='$name'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZUser_Module SET
                                     Name='$name'
                                     WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZModule object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZUser_Module WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        $ret = false;
        if ( $id != "" )
        {
            $this->Database->array_query( $module_array, "SELECT * FROM eZUser_Module WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Module's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][ "ID" ];
                $this->Name = $module_array[0][ "Name" ];
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
      Fetches the user id from the database. And returns a array of eZModule objects.
    */
    function getAll()
    {
        $this->dbInit();

        $return_array = array();
        $module_array = array();

        $this->Database->array_query( $module_array, "SELECT ID FROM eZUser_Module ORDER By Name" );

        for ( $i=0; $i<count ( $module_array ); $i++ )
        {
            $return_array[$i] = new eZModule( $module_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the module with the given name as a eZModule object.

      False (0) is returned if unsuccessful.
    */
    function exists( $name )
    {
        $this->dbInit();
        $ret = false;
        
        $this->Database->array_query( $user_array, "SELECT * FROM eZUser_Module
                                                    WHERE Name='$name'" );

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0]["ID"] );
        }

        return $ret;        
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the module name.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Name;
       
    }

    /*!
      Sets the module name.
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
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
