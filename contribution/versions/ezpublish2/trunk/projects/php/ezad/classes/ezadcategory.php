<?
// 
// $Id: ezadcategory.php,v 1.9 2001/01/23 13:43:54 bf Exp $
//
// Definition of eZAdCategory class
//
// Bård Farstad <bf@ez.no>
// Created on: <22-Nov-2000 20:32:30 bf>
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

//!! eZAd
//! eZAdCategory handles banner ad categories.
/*!

  \sa eZAd
*/

/*!TODO

 */

include_once( "classes/ezdb.php" );

class eZAdCategory
{
    /*!
      Constructs a new eZAdCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZAdCategory( $id=-1, $fetch=true )
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
      Stores a eZAdCategory object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZAd_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZAd_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZAdCategory object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZAd_AdCategoryLink
                                     WHERE CategoryID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZAd_Category WHERE ID='$this->ID'" );            
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
            $this->Database->array_query( $category_array, "SELECT * FROM eZAd_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Name = $category_array[0][ "Name" ];
                $this->Description = $category_array[0][ "Description" ];
                $this->ParentID = $category_array[0][ "ParentID" ];
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

      The categories are returned as an array of eZAdCategory objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZAd_Category ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZAdCategory( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZAdCategory objects.
    */
    function getByParent( $parent  )
    {
        if ( get_class( $parent ) == "ezadcategory" )
        {
            $this->dbInit();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZAd_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i<count($category_array); $i++ )
            {
                $return_array[$i] = new eZAdCategory( $category_array[$i]["ID"], 0 );
            }

            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
            
        $category = new eZAdCategory( $categoryID );

        $path = array();

        $parent = $category->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $category->name() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );                                
        
        return $path;
    }

    function getTree( $parentID=0, $level=0 )
    {
        $category = new eZAdCategory( $parentID );

        $categoryList = $category->getByParent( $category );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $returnObj = new eZAdCategory( $category->id() ), $level ) );

            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category->id(), $level ) );
            }

        }

        return $tree;
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
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }
    
    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->ParentID != 0 )
       {
           return new eZAdCategory( $this->ParentID );
       }
       else
       {
           return 0;           
       }
    }


    /*!
      Returns true if the category is to be excluded
      from search, false if not.
    */
    function excludeFromSearch( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->ExcludeFromSearch  == "true" )
       {
           $ret = true;
       }

       return $ret;
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
      Sets the parent category.
    */
    function setParent( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $value ) == "ezadcategory" )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
     Sets the exclude from search bit.
     The argumen can be true or false.
    */
    function setExcludeFromSearch( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->ExcludeFromSearch = "true";
       }
       else
       {
           $this->ExcludeFromSearch = "false";           
       }
    }

    /*!
      Adds a ad to the category.
    */
    function addAd( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezad" )
       {
            $this->dbInit();

            $adID = $value->id();
            
            $query = "INSERT INTO
                           eZAd_AdCategoryLink
                      SET
                           CategoryID='$this->ID',
                           AdID='$adID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns every ad in a category as a array of eZAd objects.

      If does not return un active ads unless $fetchUnActive is set to true.
    */
    function &ads( $sortMode="name",
                   $fetchUnActive=false,
                   $offset=0,
                   $limit=50 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $return_array = array();
       $ad_array = array();

       
       $fetchActiveSQL = "";
       if ( $fetchUnActive == false )
       {
           $fetchActiveSQL = "AND eZAd_Ad.IsActive = 'true'";
       }

       $orderBySQL = "eZAd_Ad.Name ASC";
//         $orderBySQL = "Count DESC";
           
       $this->Database->array_query( $ad_array, "
                SELECT eZAd_Ad.ID AS AdID, eZAd_Ad.Name, eZAd_Category.ID, eZAd_Category.Name, Count(*) AS Count
                FROM eZAd_Ad, eZAd_Category, eZAd_AdCategoryLink, eZAd_View
                WHERE 
                eZAd_AdCategoryLink.AdID = eZAd_Ad.ID
                AND
                eZAd_Category.ID = eZAd_AdCategoryLink.CategoryID
                $fetchActiveSQL
                AND 
                eZAd_Category.ID='$this->ID'
                GROUP BY eZAd_Ad.ID ORDER BY $orderBySQL LIMIT $offset,$limit" );



       for ( $i=0; $i<count($ad_array); $i++ )
       {
           print( $ad_array[$i]["Count"] . "<br>" );
           $return_array[$i] = new eZAd( $ad_array[$i]["AdID"], false );
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
    var $ParentID;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

