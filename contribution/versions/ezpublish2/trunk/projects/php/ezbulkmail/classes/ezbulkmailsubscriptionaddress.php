<?
// 
// $Id: ezbulkmailsubscriptionaddress.php,v 1.2 2001/04/17 12:30:12 fh Exp $
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
    function eZBulkMail( $id=-1 )
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
        $this->dbInit();
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_SubscriptionAddress SET
                                 EMail='$this->EMail'
                                 " );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_SubscriptionAddress SET
                                 EMail='$this->EMail'
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
            $this->Database->array_query( $mail_array, "SELECT * FROM eZBulkMail_SubscriptionAddress WHERE ID='$id'" );
            if ( count( $mail_array ) > 1 )
            {
                die( "Error: Subscription addresses with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $mail_array ) == 1 )
            {
                $this->ID = $mail_array[0][ "ID" ];
                $this->UserID = $mail_array[0][ "Email" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
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
      Returns all the categories that this user is subscribed to as an array of eZBulkMailCategory objects. If you just want the ID's supply false as the first argument.
     */
    function subscriptions( $asObjects = true )
    {
        $result_array = array();
        $this->Database->array_query( $result_array, "SELECT CategoryID FROM eZBulkMail_SubscriptionLink WHERE AddressID='$this->ID'" );
        if( count( $result ) > 0 )
        {
            foreach( $result_array as $result )
                $result_array[] = $asObjects ? new eZBulkMailCategory( $result[ "CategoryID" ] ) : $result[ "CategoryID" ];
        }
    }

    /*!
      Subscribes this address to a category.
     */
    function subscribe( $category )
    {
        if( get_class( $category )
        {
            $categoryID = $category->id();
            $this->Datebase->query( "INSERT INTO eZBulkMail_SubscriptionLink SET AddressID='$this->ID', CategoryID='$categoryID" );
        }
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
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }


    var $EMail;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
