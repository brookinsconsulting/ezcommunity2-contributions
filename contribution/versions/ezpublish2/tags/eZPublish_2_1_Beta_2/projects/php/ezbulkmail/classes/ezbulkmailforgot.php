<?
// 
// $Id
//
// Frederik Holljen <fh@ez.no>
// Created on: <20-Apr-2001 13:32:11 fh>
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

//!! eZBulkMailForgot
//! eZBulkMailForgot handles forgotten password. The user send a mail and get a hash returned, the user use the hash to get a new password.
/*!

  Example code:
  \code

  // Create a forgot object and store it in the database.
  $forgot = new eZBulkMailForgot( $userID );
  
  $forgot->setUserID( $userID );
  $forgot->store(); // The store function generate a hash.

  // Return the hash
  $hashVaraiable = $forgot->hash();

  // Check if the hash is legal.
  $forgot->check( $hashVariable );

  \endcode
  \sa eZUser eZUserGroup eZPermission eZBulkMailForgot
*/

include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezsession/classes/ezsession.php" );

class eZBulkMailForgot
{
    /*!
      Constructs a new eZBulkMailForgot object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBulkMailForgot( $id=-1, $fetch=true )
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
      Stores or updates a eZBulkMailForgot object in the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Hash = md5( microTime() );
        $password = addslashes( $this->Password );
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_Forgot SET
                                 Mail='$this->Mail',
                                 Password=PASSWORD( '$password' ),
                                 Hash='$this->Hash'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Forgot SET
                                 Mail='$this->Mail',
                                 Password=PASSWORD( '$password' ),
                                 Hash='$this->Hash'
                                 WHERE ID='$this->ID'" );
        }
    }

    /*!
      Deletes a eZBulkMailForgot object from the database.

    */
    function delete()
    {
        $this->dbInit();
        
        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZBulkMail_Forgot WHERE ID='$this->ID'" );
        }
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
            $this->Database->array_query( $forgot_array, "SELECT * FROM eZBulkMail_Forgot WHERE ID='$id'" );
            if ( count( $forgot_array ) > 1 )
            {
                die( "Error: User's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $forgot_array ) == 1 )
            {
                $this->ID = $forgot_array[0]["ID"];
                $this->Mail = $forgot_array[0]["Mail"];
                $this->Password = $forgot_array[0][ "Password" ];
                $this->Time = $forgot_array[0]["Time"];
                
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
      \static
      Returns object with the email if it exists. If it does't and the address is valid a new object is created and returned.
     */
    function getByEmail( $email )
    {
        $db = eZDB::globalDatabase();
        $email = addslashes( $email );
        $db->array_query( $forgot_array, "SELECT ID FROM eZBulkMail_Forgot WHERE Mail='$email'" );

        $return_value = false;
        if( count( $forgot_array ) > 1 )
        {
            die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
        }
        else if( count( $forgot_array ) == 1 )
        {
            $id = $forgot_array[0]["ID"];
            $return_value = new eZBulkMailForgot( $id );
        }
        else
        {
            $is_valid = new eZBulkMailForgot();
            $is_valid->setMail( $email );
            $return_value = $is_valid;
        }
        return $return_value;
    }

    
    /*!
      Chech if hash is true or not.
      Returnes false if unsuccessful.
    */
    function check( $hash )
    {
        $this->dbInit();
        $ret = false;
        
        $this->Database->array_query( $forgot_array, "SELECT ID FROM eZBulkMail_Forgot WHERE Hash='$hash'" );

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
      Returns the mail ID.
    */
    function mail( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Mail;
    }

    /*!
      Sets the login.
    */
    function setMail( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Mail = $value;
    }

    /*!
      Sets the password in cleartext.
     */
    function setPassword( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       $this->Password = $value;
    }

    /*!
      Returns the encryptet password.
     */
    function password()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Password;
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
      \private
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

    var $Mail;
    var $Password;
    var $ID;
    var $Hash;
    var $Time;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}
?>
