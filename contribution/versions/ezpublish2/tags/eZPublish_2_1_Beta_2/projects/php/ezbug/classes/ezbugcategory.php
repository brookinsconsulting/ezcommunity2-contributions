<?
// 
// $Id: ezbugcategory.php,v 1.6 2001/04/04 15:21:44 fh Exp $
//
// Definition of eZBugCategory class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Nov-2000 19:08:57 bf>
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

//!! eZBug
//! eZBugCategory handles bug categories.
/*!
  Example code:
  \code
  // include the class
  include_once( "ezbug/classes/ezbugcategory.php" );

  // create a new class object
  $category = new eZBugCategory();

  // Set some object values and store them to the database.
  $category->setName( "GUI related" );
  $category->setDescription( "This is bugs reported which are related to GUI issues." );
  $category->store();
  \endcode
  \sa eZBug eZBugModule
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZBugCategory
{
    /*!
      Constructs a new eZBugCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBugCategory( $id=-1, $fetch=true )
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
      Stores a eZBugCategory object to the database.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBug_Category SET
		                         Name='$name',
                                 Description='$description'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBug_Category SET
		                         Name='$name',
                                 Description='$description'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBugGroup object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            // delete from BugCategoryLink
            $this->Database->query( "DELETE FROM eZBug_BugCategoryLink WHERE CategoryID='$this->ID'" );
            // delete actual group entry
            $this->Database->query( "DELETE FROM eZBug_Category WHERE ID='$this->ID'" );            
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
            $this->Database->array_query( $category_array, "SELECT * FROM eZBug_Category WHERE ID='$id'" );
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

      The categories are returned as an array of eZBugCategory objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZBug_Category ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZBugCategory( $category_array[$i]["ID"], 0 );
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Adds a bug to the category.
    */
    function addBug( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezbug" )
       {
            $this->dbInit();

            $bugID = $value->id();
            
            $query = "INSERT INTO
                           eZBug_BugCategoryLink
                      SET
                           CategoryID='$this->ID',
                           BugID='$bugID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns every bug in a category as a array of eZBug objects.

      If $fetchUnhandled is set to true the bugs which is not yet handled are
      also returned.
    */
    function bugs( $sortMode="time",
                       $fetchUnhandled=true,
                       $offset=0,
                       $limit=50 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       $bug_array = array();

       if ( $fetchUnhandled == false )
       {
           $excludedCode = " AND eZBug_Category.ExcludeFromSearch = 'false' ";
       }
       else
       {
           $excludedCode = "";           
       }
       

       $this->Database->array_query( $bug_array, "
                SELECT eZBug_Bug.ID AS BugID, eZBug_Bug.Name, eZBug_Category.ID, eZBug_Category.Name
                FROM eZBug_Bug, eZBug_Category, eZBug_BugCategoryLink
                WHERE 
                eZBug_BugCategoryLink.BugID = eZBug_Bug.ID
                AND
                eZBug_Category.ID = eZBug_BugCategoryLink.CategoryID
                AND
                eZBug_Category.ID='$this->ID'
                $excludedCode  
                GROUP BY eZBug_Bug.ID ORDER BY $OrderBy LIMIT $offset,$limit" );
 
       for ( $i=0; $i<count($bug_array); $i++ )
       {
           $return_array[$i] = new eZBug( $bug_array[$i]["BugID"], false );
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
            $this->Database = new eZDB( "site.ini", "site" );
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
