<?
// 
// $Id: ezbulkmailsubscriptionaddress.php,v 1.9 2001/05/15 09:24:46 ce Exp $
//
// eZBulkMailSubscriptionAddress class
//
// Frederik Holljen <fh@ez.no>
// Created on: <17-Apr-2001 13:21:53 fh>
//
// Copyright (C) .  All rights reserved.
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

//!! eZBulkMailSubscriptionAddress
//! eZBulkMailSubscriptionAddress documentation.
/*!

  Example code:
  \code
  \endcode

*/
	      
class eZBulkMailSubscriptionAddress
{
    /*!
    */
    function eZBulkMailSubscriptionAddress( $id=-1 )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZBulkMail object to the database.
    */
    function store()
    {
        $password = addslashes( $this->Password );
        $this->dbInit();
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_SubscriptionAddress SET
                                 EMail='$this->EMail',
                                 Password='$password'
                                 " );
			$this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_SubscriptionAddress SET
                                 EMail='$this->EMail',
                                 Password='$password'
                                 WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            // delete from BulkMailCategoryLink
            $this->Database->query( "DELETE FROM eZBulkMail_SubscriptionLink WHERE AddressID='$this->ID'" );
            // delete actual group entry
            $this->Database->query( "DELETE FROM eZBulkMail_SubscriptionAddress WHERE ID='$this->ID'" );            
        }
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "-1" )
        {
            $this->Database->array_query( $address_array, "SELECT * FROM eZBulkMail_SubscriptionAddress WHERE ID='$id'" );
            if( count( $address_array ) > 1 )
            {
                die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $address_array ) == 1 )
            {
                $this->ID = $address_array[0][ "ID" ];
                $this->EMail = $address_array[0][ "EMail" ];
                $this->Password = $address_array[0][ "Password" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      \static
      Returns object with the email if it exists. If it doesn't and the address is valid a new object is created and returned.
     */
    function getByEmail( $email )
    {
        $db = eZDB::globalDatabase();
        $email = addslashes( $email );
        $db->array_query( $address_array, "SELECT ID FROM eZBulkMail_SubscriptionAddress WHERE EMail='$email'" );

        $return_value = false;
        if( count( $address_array ) > 1 )
        {
            die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
        }
        else if( count( $address_array ) == 1 )
        {
            $id = $address_array[0]["ID"];
            $return_value = new eZBulkMailSubscriptionAddress( $id );
        }
        else
        {
            $is_valid = new eZBulkMailSubscriptionAddress();
            if( $is_valid->setEMail( $email ) )
            {
//                $is_valid->store();
                $return_value = $is_valid;
            }
        }
        return $return_value;
    }

    /*!
      Returns true if the address exists. False if not.
     */
    function addressExists( $email )
    {
        $db = eZDB::globalDatabase();
        $email = addslashes( $email );
        $db->array_query( $address_array, "SELECT ID FROM eZBulkMail_SubscriptionAddress WHERE EMail='$email'" );

        $return_value = false;
        if( count( $address_array ) > 1 )
        {
            die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
        }
        else if( count( $address_array ) == 1 )
        {
            $return_value = true;
        }
        else
        {
            $return_value = false;
        }
        return $return_value;
    }
    
    /*!
      Returns the email address of this user.
    */
    function eMail()
    {
        return $this->EMail;
    }

    /*!
      Sets the email address.
      Returns true if the address is set and valid, false othervise.
     */
    function setEMail( $value )
    {
        $pos = ( ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $value) );
        if( $pos == true )
        {
            $this->EMail = $value;
            return true;
        }
        return false;
    }

    /*!
      Sets an allready encryptet password.
     */
    function setEncryptetPassword( $value )
    {
        $this->Password = $value;
    }
    
    /*!
      Returns all the categories that this user is subscribed to as an array of eZBulkMailCategory objects. If you just want the ID's supply false as the first argument.
     */
    function subscriptions( $asObjects = true )
    {
        $final_result = array();
        $this->Database->array_query( $result_array, "SELECT CategoryID FROM eZBulkMail_SubscriptionLink WHERE AddressID='$this->ID'" );
        if( count( $result_array ) > 0 )
        {
            foreach( $result_array as $result )
                $final_result[] = $asObjects ? new eZBulkMailCategory( $result[ "CategoryID" ] ) : $result[ "CategoryID" ];
        }
        return $final_result;
    }

    /*!
      Subscribes this address to a category.
     */
    function subscribe( $categoryID )
    {
        if( get_class( $categoryID ) == "ezbulkmailcategory" )
            $categoryID = $categoryID->id();

        $db = eZDB::globalDatabase();
        $db->array_query( $check, "SELECT AddressID
                                               FROM eZBulkMail_SubscriptionLink
                                               WHERE CategoryID='$categoryID'
                                               AND AddressID='$this->ID'
                                               " );
        if ( count ( $check ) == 0 )
        {
            $db->query( "INSERT INTO eZBulkMail_SubscriptionLink SET AddressID='$this->ID', CategoryID='$categoryID'" );
            return true;
        }
        else
            return false;
    }

    /*!
      Unsubscribes this user from the given category. If the supplied argument is true, the user is unsubscibed from all categories.
     */
    function unsubscribe( $category )
    {
        if( get_class( $category ) == "ezbulkmailcategory" )
        {
            $categoryID = $category->id();
            $this->Database->query( "DELETE FROM eZBulkMail_SubscriptionLink WHERE AddressID='$categoryID'" );
        }
        else if( $category == true )
        {
            $this->Database->query( "DELETE FROM eZBulkMail_SubscriptionLink WHERE AddressID='$this->ID'" );
        }
    }

    /*!
      Checks if the email and passwords have a match. Returns true if yes, and false if not.
    */
    function validate( $email, $password )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        $db->array_query( $subscription_array, "SELECT * FROM eZBulkMail_SubscriptionAddress
                                                    WHERE EMail='$email'
                                                    AND Password=PASSWORD('$password')" );
        if ( count( $subscription_array ) == 1 )
            $ret = true;
        
        return $ret;
    }
    
    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $EMail;
    
    ///  Variable for keeping the database connection.
    var $Database;
    var $Password;
    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
