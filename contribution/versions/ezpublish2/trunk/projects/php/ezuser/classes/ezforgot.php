<?
// 
// $Id: ezforgot.php,v 1.2 2000/10/26 13:23:26 ce-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
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

include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezsession/classes/ezsession.php" );

class eZForgot
{
    /*!
      Constructs a new eZForgot object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZForgot( $id=-1, $fetch=true )
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
      Stores or updates a eZForgot object in the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Hash = md5( microTime() );
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZUser_Forgot SET
                                 UserID='$this->UserID',
                                 Hash='$this->Hash'" );
            $this->ID = mysql_insert_id();
        }
        return true;
    }

    /*!
      Deletes a eZForgot object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZUser_Forgot WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        $ret = false;
        if ( $id != "" )
        {
            $this->Database->array_query( $forgot_array, "SELECT * FROM eZUser_Forgot WHERE ID='$id'" );
            if ( count( $forgot_array ) > 1 )
            {
                die( "Error: User's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $forgot_array ) == 1 )
            {
                $this->ID = $forgot_array[0]["ID"];
                $this->Time = $forgot_array[0]["Time"];
                $this->UserID = $forgot_array[0]["UserID"];

                $this->State_ = "Coherent";  
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";

        }
        return $ret;
    }

    
    /*!
      Chech if hash is true or not.
      Returnes false if unsuccessful.
    */
    function check( $hash, $userID  )
    {
        $this->dbInit();
        $ret = false;
        
        $this->Database->array_query( $forgot_array, "SELECT ID FROM eZUser_Forgot WHERE Hash='$hash' and UserID='$userID'" );

        if ( count( $forgot_array ) == 1 )
        {
            $ret = $forgot_array[0]["ID"];
        }
        return $ret;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the users login.
    */
    function UserID( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->UserID;
    }

    /*!
      Sets the login.
    */
    function setUserID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->UserID = $value;
    }

    /*!
      Retuns the hash
    */
    function hash()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Hash;
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

    var $UserID;
    var $ID;
    var $Hash;
    

}
?>
