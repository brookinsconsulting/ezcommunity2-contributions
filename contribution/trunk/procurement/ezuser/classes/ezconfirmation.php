<?php
// 
// $Id: ezconfirmation.php,v 1.12.2.1 2003/05/16 13:15:58 br Exp $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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
//! eZConfirmation handles confirmationten password. The user send a mail and get a hash returned, the user use the hash to get a new password.
/*!

  Example code:
  \code

  // Create a confirmation object and store it in the database.
  $confirmation = new eZConfirmation( $userID );
  
  $confirmation->setUserID( $userID );
  $confirmation->store(); // The store function generate a hash.

  // Return the hash
  $hashVaraiable = $confirmation->hash();

  // Check if the hash is legal.
  $confirmation->check( $hashVariable );

  \endcode
  \sa eZUser eZUserGroup eZPermission eZConfirmation
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezsession/classes/ezsession.php" );

class eZConfirmation
{
    /*!
      Constructs a new eZConfirmation object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZConfirmation( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZConfirmation object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $this->Hash = md5( microTime() );

        $dbError = false;
        $db->begin( );        
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_Confirmation" );

            $nextID = $db->nextID( "eZUser_Confirmation", "ID" );

            $timeStamp = eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZUser_Confirmation
                                 ( ID, UserID, Hash, Time )
                                 VALUES ( '$nextID',
                                          '$this->UserID',
                                          '$this->Hash',
                                          '$timeStamp' )" );
            
			$this->ID = $nextID;
        }

        $db->unlock();
        
        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZConfirmation object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_Confirmation WHERE ID='$this->ID'" );
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
        if ( $id != -1 )
        {
            $db->array_query( $confirmation_array, "SELECT * FROM eZUser_Confirmation WHERE ID='$id'" );

            if ( count( $confirmation_array ) > 1 )
            {
                die( "Error: User's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $confirmation_array ) == 1 )
            {
                $this->ID = $confirmation_array[0][$db->fieldName("ID")];
                $this->Time = $confirmation_array[0][$db->fieldName("Time")];
                $this->UserID = $confirmation_array[0][$db->fieldName("UserID")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Chech if hash is true or not.
      Returnes false if unsuccessful.
    */
    function check( $hash )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $hash = $db->escapeString( $hash );
        
        $db->array_query( $confirmation_array, "SELECT ID FROM eZUser_Confirmation WHERE Hash='$hash'" );

        if ( count( $confirmation_array ) == 1 )
        {
            $ret = $confirmation_array[0][$db->fieldName("ID")];
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
    function userID( )
    {
       return $this->UserID;
    }

    /*!
      Sets the login.
    */
    function setUserID( $value )
    {
       $this->UserID = $value;
    }

    /*!
      Retuns the hash
    */
    function hash()
    {
       return $this->Hash;
    }

    var $UserID;
    var $ID;
    var $Hash;
}
?>
