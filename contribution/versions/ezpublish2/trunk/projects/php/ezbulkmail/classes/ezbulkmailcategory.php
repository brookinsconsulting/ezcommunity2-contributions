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
                                 Description='$description'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Category SET
		                         Name='$name',
                                 Description='$description'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.
    */
    function delete( $id = -1 )
    {
        $db = eZDB::globalDatabase();
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
      Returns all the categories found in the database.

      The categories are returned as an array of eZBulkMailCategory objects.
    */
    function getAll()
    {
        $db = eZDB::globaldatabase();
        $return_array = array();
        $category_array = array();
        
        $db->array_query( $category_array, "SELECT ID FROM eZBulkMail_Category ORDER BY Name" );
        
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
      Adds a bug to the category.
    */
    function addMail( $value )
    {
       if ( get_class( $value ) == "ezbulkmail" )
       {
            $this->dbInit();

            $mailID = $value->id();

            $this->Database->query( "DELETE FROM eZBulkMail_MailCategoryLink WHERE MailID='$mailID'");
            $query = "INSERT INTO eZBulkMail_MailCategoryLink
                      SET CategoryID='$this->ID', MailID='$mailID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns every bug in a category as a array of eZBug objects.

    */
    function mail( $sortMode="time",
                       $offset=0,
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

       $this->Database->array_query( $bug_array, "
                SELECT eZBulkMail_Mail.ID AS MailID, 
                FROM eZBulkMail_Bug, eZBulkMail_Category, eZBulkMail_BugCategoryLink
                WHERE 
                eZBulkMail_MailCategoryLink.BugID = eZBulkMail_Mail.ID
                AND
                eZBulkMail_Category.ID = eZBulkMail_MailCategoryLink.CategoryID
                AND
                eZBulkMail_Category.ID='$this->ID'
                GROUP BY eZBulkMail_Mail.ID ORDER BY $OrderBy LIMIT $offset,$limit" );
 
       for( $i=0; $i<count($mail_array); $i++ )
       {
           $return_array[$i] = new eZBulkMail( $mail_array[$i]["MailID"] );
       }
       
       return $return_array;
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

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
