<?
// 
// $Id: 
//
// Definition of eZBulkMailCategory class
//
// Frederik Holljen <fh@ez.no>
// Created on: <17-Apr-2001 11:17:57 fh>
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

//!! eZBulkMail
//! eZBulkMailCategory
/*!
  Example code:
  \code
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "ezbulkmail/classes/ezbulkmail.php" );

class eZBulkMailCategory
{
    /*!
    */
    function eZBulkMailCategory( $id=-1 )
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
      Stores a eZBulkMailCategory object to the database.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_Category SET
		                         Name='$name',
                                 IsPublic='$this->IsPublic',
                                 Description='$description'" );
			$this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Category SET
		                         Name='$name',
                                 IsPublic='$this->IsPublic',
                                 Description='$description'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBulkMailCategory object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        if( $id == -1 )
            $id = $this->ID;
        
        // delete from BulkMailCategoryLink
        $db->query( "DELETE FROM eZBulkMail_MailCategoryLink WHERE CategoryID='$id'" );
        // delete actual group entry
        $db->query( "DELETE FROM eZBulkMail_Category WHERE ID='$id'" );
     }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZBulkMail_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Name = $category_array[0][ "Name" ];
                $this->IsPublic = $category_array[0][ "IsPublic" ];
                $this->Description = $category_array[0][ "Description" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Fetches the category with the given name. If not found false is returned
    */
    function getByName( $name )
    {
        $db = eZDB::globaldatabase();
        $category_array = array();

        $name = addslashes( $name );
        $db->array_query( $category_array, "SELECT ID FROM eZBulkMail_Category WHERE Name='$name'" );

        $return_value = false;
        if( count( $category_array ) == 1 )
            $return_value = new eZBulkMailCategory( $category_array[0]["ID"] );
        
        return $return_value;
    }
    
    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBulkMailCategory objects.
    */
    function getAll( $withPrivate = true )
    {
        $db = eZDB::globaldatabase();
        $return_array = array();
        $category_array = array();

        $privateSQL = "";
        if( $withPrivate == false )
            $privateSQL = "WHERE IsPublic='1'";
            
        
        $db->array_query( $category_array, "SELECT ID FROM eZBulkMail_Category $privateSQL ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZBulkMailCategory( $category_array[$i]["ID"] );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the category.
    */
    function name( $html = true )
    {
       if( $html )
           return  htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
    }
    
    /*!
      Sets the name of the category.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets if this category is public or not. Categories that are public show up on the user side and users can subscribe/unsubscribe themselves..
     */
    function setIsPublic( $value )
    {
        $this->IsPublic = $value;
    }
    
    /*!
      Returns true if this this is a public list
     */
    function isPublic( )
    {
        return $this->IsPublic;
    }
    
    /*!
      Subscribes a user group to a category
     */
    function addGroupSubscription( $groupID )
    {
        if( get_class( $groupID ) == "ezusergroup" )
            $groupID = $groupID->id();
        $this->Database->query( "INSERT INTO eZBulkMail_GroupCategoryLink SET CategoryID='$this->ID', GroupID='$groupID'" );
    }

    /*!
      Unsubscribes the given  user group from this category. If the supplied argument is true, the group is unsubscibed from all categories.
     */
    function removeGroupSubscription( $group )
    {
        if( get_class( $group ) == "ezusergroup" )
        {
            $groupID = $group->id();
            $this->Database->query( "DELETE FROM eZBulkMail_GroupCategoryLink WHERE CategoryID='$this->ID' AND GroupID='$groupID'" );
        }
        else if( $group == true )
        {
            $this->Database->query( "DELETE FROM eZBulkMail_GroupCategoryLink WHERE CategoryID='$this->ID'" );
        }
    }
    
    /*!
      Returns all the groups that are subscribed to this category.
     */
    function groupSubscriptions( $asObjects = true )
    {
        $final_result = array();
        $this->Database->array_query( $result_array, "SELECT GroupID FROM eZBulkMail_GroupCategoryLink WHERE CategoryID='$this->ID'" );
        if( count( $result_array ) > 0 )
        {
            foreach( $result_array as $result )
                $final_result[] = $asObjects ? new eZUserGroup( $result[ "GroupID" ] ) : $result[ "GroupID" ];
        }
        return $final_result;
    }

    /*!
      Returns every mail in a category as a array of eZBulkmail objects.

    */
    function mail( $offset=0,
                       $limit=50 )
    {
       $this->dbInit();

//         $OrderBy = "eZBug_Bug.Published DESC";
//         switch( $sortMode )
//         {
//             case "alpha" :
//             {
//                 $OrderBy = "eZBug_Bug.Name ASC";
//             }
//             break;
//         }

       $return_array = array();
       $mail_array = array();

       $this->Database->array_query( $mail_array, "
                SELECT eZBulkMail_Mail.ID AS MailID
                FROM eZBulkMail_Mail, eZBulkMail_Category, eZBulkMail_MailCategoryLink
                WHERE eZBulkMail_MailCategoryLink.MailID = eZBulkMail_Mail.ID
                AND eZBulkMail_Category.ID = eZBulkMail_MailCategoryLink.CategoryID
                AND eZBulkMail_Category.ID='$this->ID'
                GROUP BY eZBulkMail_Mail.ID LIMIT $offset,$limit" );
 
       for( $i=0; $i<count($mail_array); $i++ )
       {
           $return_array[$i] = new eZBulkMail( $mail_array[$i]["MailID"] );
       }
       
       return $return_array;
    }

    /*!
      Returns the number of mail in this category.
     */
    function mailCount()
    {
       $this->dbInit();

       $this->Database->query_single( $result, "
                SELECT Count( eZBulkMail_Mail.ID ) AS Count
                FROM eZBulkMail_Mail, eZBulkMail_MailCategoryLink
                WHERE eZBulkMail_MailCategoryLink.CategoryID='$this->ID' AND eZBulkMail_Mail.ID=eZBulkMail_MailCategoryLink.MailID" );
       
       return $result["Count"];
    }
    
    /*!
      Returns an array with all addresses that are subscribed to this category.
     */
    function subscribers()
    {
        $db = eZDB::globalDatabase();

        $subscribe_array = array();
        $return_array = array();
        $db->array_query( $subscribe_array, "SELECT EMail FROM eZBulkMail_SubscriptionAddress, eZBulkMail_SubscriptionLink
                                             WHERE eZBulkMail_SubscriptionAddress.ID=eZBulkMail_SubscriptionLink.AddressID
                                             AND eZBulkMail_SubscriptionLink.CategoryID='$this->ID'" );

        for( $i=0; $i<count($subscribe_array); $i++ )
        {
            $return_array[$i] = $subscribe_array[$i]["EMail"];
        }
        return $return_array;
    }

    /*!
      Returns the number of users subscribed to this list.
     */
    function subscriberCount()
    {
        $db = eZDB::globalDatabase();

        $db->query_single( $result, "SELECT count( EMail ) as Count FROM eZBulkMail_SubscriptionAddress, eZBulkMail_SubscriptionLink
                                             WHERE eZBulkMail_SubscriptionAddress.ID=eZBulkMail_SubscriptionLink.AddressID
                                             AND eZBulkMail_SubscriptionLink.CategoryID='$this->ID'" );

        return $result["Count"];
    }
    
    /*!
      \static
      Sets the category with the ID given to be the current single list. If the argument given is false there will be no single list selected.
     */
    function setSingleList( $value )
    {
        $db = eZDB::globalDatabase();
        $db->query( "UPDATE eZBulkMail_Category SET IsSingleCategory='0'" );

        if( $value != false )
        {
            if( get_class( $value ) == "ezbulkmailcategory" )
                $value = $value->id();

            $db->query( "UPDATE eZBulkMail_Category SET IsSingleCategory='1' WHERE ID='$value'" );
        }
    }

    /*!
      \static
      Returns the current selected single list. If none false is returned.
     */
    function singleList( $asObject = true )
    {
        $db = eZDB::globalDatabase();
        $return_value = false;
        $result_array = array();
        $db->array_query( $result_array, "SELECT ID from eZBulkMail_Category WHERE IsSingleCategory='1'" );

        if( count( $result_array ) > 0 )
            $return_value = ( $asObject == true ) ? new eZBulkMailCategory( $result_array[0][ "ID" ] ) : $result_array[0]["ID"];

        return $return_value;
    }

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globaldatabase();
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $Name;
    var $Description;
    var $IsPublic;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
 