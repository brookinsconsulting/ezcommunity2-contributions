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
        $this->IsPublic = 0;
        $this->IsSingleCategory = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBulkMailCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBulkMail_Category" );
            $nextID = $db->nextID( "eZBulkMail_Category", "ID" );

            $result = $db->query( "INSERT INTO eZBulkMail_Category
                                ( ID, Name, IsPublic, Description )
                                VALUES
                                ( '$nextID',
                                  '$name',
                                  '$this->IsPublic',
                                  '$description'
                                ) " );
			$this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZBulkMail_Category SET
		                         Name='$name',
                                 IsPublic='$this->IsPublic',
                                 Description='$description'
                                 WHERE ID='$this->ID'" );
        }

        $db->unlock();
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes a eZBulkMailCategory object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if( $id == -1 )
            $id = $this->ID;
        
        // delete from BulkMailCategoryLink
        $results[] = $db->query( "DELETE FROM eZBulkMail_MailCategoryLink WHERE CategoryID='$id'" );
        // delete actual group entry
        $results[] = $db->query( "DELETE FROM eZBulkMail_Category WHERE ID='$id'" );

        $commit = true;
        foreach(  $results as $result )
        {
            if ( $result == false )
                $commit = false;
        }
        if ( $commit == false )
            $db->rollback( );
        else
            $db->commit();
     }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZBulkMail_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName( "ID" )];
                $this->Name = $category_array[0][$db->fieldName( "Name" )];
                $this->IsPublic = $category_array[0][$db->fieldName( "IsPublic" )];
                $this->Description = $category_array[0][$db->fieldName( "Description" )];
            }
        }
    }

    /*!
      Fetches the category with the given name. If not found false is returned
    */
    function getByName( $name )
    {
        $db = eZDB::globaldatabase();
        $category_array = array();

        $name =& $db->escapeString( $name );
        $db->array_query( $category_array, "SELECT ID FROM eZBulkMail_Category WHERE Name='$name'" );

        $return_value = false;
        if( count( $category_array ) == 1 )
            $return_value = new eZBulkMailCategory( $category_array[0][$db->fieldName( "ID" )] );
        
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
        
        $db->array_query( $category_array, "SELECT ID, Name FROM eZBulkMail_Category $privateSQL ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZBulkMailCategory( $category_array[$i][$db->fieldName("ID" )] );
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
        $db =& eZDB::globalDatabase();
        $db->begin();

        if( get_class( $groupID ) == "ezusergroup" )
            $groupID = $groupID->id();

        $db->lock( "eZBulkMail_GroupCategoryLink" );
        $result = $db->query( "INSERT INTO eZBulkMail_GroupCategoryLink
                  ( CategoryID, GroupID )
                  VALUES
                  ( '$this->ID',
                    '$groupID'
                  ) " );

        $db->unlock();
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Unsubscribes the given  user group from this category. If the supplied argument is true, the group is unsubscibed from all categories.
     */
    function removeGroupSubscription( $group )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if( get_class( $group ) == "ezusergroup" )
        {
            $groupID = $group->id();
            $result = $db->query( "DELETE FROM eZBulkMail_GroupCategoryLink WHERE CategoryID='$this->ID' AND GroupID='$groupID'" );
        }
        else if( $group == true )
        {
            $result = $db->query( "DELETE FROM eZBulkMail_GroupCategoryLink WHERE CategoryID='$this->ID'" );
        }
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }
    
    /*!
      Returns all the groups that are subscribed to this category.
     */
    function groupSubscriptions( $asObjects = true )
    {
        $db =& eZDB::globalDatabase();
        $final_result = array();
        $db->array_query( $result_array, "SELECT GroupID FROM eZBulkMail_GroupCategoryLink WHERE CategoryID='$this->ID'" );
        if( count( $result_array ) > 0 )
        {
            foreach( $result_array as $result )
                $final_result[] = $asObjects ? new eZUserGroup( $result[$db->fieldName( "GroupID" )] ) : $result[$db->fieldName( "GroupID" )];
        }
        return $final_result;
    }

    /*!
      Returns every mail in a category as a array of eZBulkmail objects.

    */
    function mail( $offset=0,
                       $limit=50 )
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $mail_array = array();

        $db->array_query( $mail_array, "
                SELECT eZBulkMail_Mail.ID AS MailID
                FROM eZBulkMail_Mail, eZBulkMail_Category, eZBulkMail_MailCategoryLink
                WHERE eZBulkMail_MailCategoryLink.MailID = eZBulkMail_Mail.ID
                AND eZBulkMail_Category.ID = eZBulkMail_MailCategoryLink.CategoryID
                AND eZBulkMail_Category.ID='$this->ID'
                GROUP BY eZBulkMail_Mail.ID",
                array( "Limit" => $limit,
                       "Offset" => $offset ) );
 
        for( $i=0; $i<count($mail_array); $i++ )
        {
            $return_array[$i] = new eZBulkMail( $mail_array[$i][$db->fieldName( "MailID" )] );
        }
       
        return $return_array;
    }

    /*!
      Returns the number of mail in this category.
     */
    function mailCount()
    {
        $db = eZDB::globalDatabase();

        $db->query_single( $result, "
                SELECT COUNT( eZBulkMail_Mail.ID ) AS Count
                FROM eZBulkMail_Mail, eZBulkMail_MailCategoryLink
                WHERE eZBulkMail_MailCategoryLink.CategoryID='$this->ID' AND eZBulkMail_Mail.ID=eZBulkMail_MailCategoryLink.MailID" );
       
       return $result[$db->fieldName( "Count" )];
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
            $return_array[$i] = $subscribe_array[$i][$db->fieldName( "EMail" )];
        }
        return $return_array;
    }

    /*!
      Returns the number of users subscribed to this list.
     */
    function subscriberCount()
    {
        $db = eZDB::globalDatabase();

        $db->query_single( $result, "SELECT COUNT( EMail ) as Count FROM eZBulkMail_SubscriptionAddress, eZBulkMail_SubscriptionLink
                                             WHERE eZBulkMail_SubscriptionAddress.ID=eZBulkMail_SubscriptionLink.AddressID
                                             AND eZBulkMail_SubscriptionLink.CategoryID='$this->ID'" );

        return $result[$db->fieldName( "Count" )];
    }
    
    /*!
      \static
      Sets the category with the ID given to be the current single list. If the argument given is false there will be no single list selected.
     */
    function setSingleList( $value )
    {
        $db = eZDB::globalDatabase();

        $db->begin();
        $result = $db->query( "UPDATE eZBulkMail_Category SET IsSingleCategory='0'" );
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();

        if( $value != false )
        {
            if( get_class( $value ) == "ezbulkmailcategory" )
                $value = $value->id();

            $db->begin();
            $result = $db->query( "UPDATE eZBulkMail_Category SET IsSingleCategory='1' WHERE ID='$value'" );
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
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
            $return_value = ( $asObject == true ) ? new eZBulkMailCategory( $result_array[0][$db->fieldName( "ID" )] ) : $result_array[0][$db->fieldName( "ID" )];

        return $return_value;
    }

    var $ID;
    var $Name;
    var $Description;
    var $IsPublic;
    
}

?>
