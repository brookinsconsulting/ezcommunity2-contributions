<?
// 
// $Id: ezimagecategory.php,v 1.22 2001/06/27 14:17:37 pkej Exp $
//
// Definition of eZImageCategory class
//
// Bård Farstad <bf@ez.no>
// Created on: <11-Dec-2000 15:24:35 bf>
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

//!! eZFileManager
//! eZImageCategory manages virtual folders.
/*!
  
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );


class eZImageCategory
{
    /*!
      Constructs a new eZImageCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZImageCategory( $id=-1 )
    {
        $this->ExcludeFromSearch = "false";
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZImageCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );
        
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZImageCatalogue_Category" );

            $this->ID = $db->nextID( "eZImageCatalogue_Category", "ID" );
            
            $db->query( "INSERT INTO eZImageCatalogue_Category
                                     ( ID, Name, Description, UserID, ParentID ) VALUES
                                     ( '$this->ID', '$name', '$description', '$this->UserID', '$this->ParentID' )" );
        }
        else
        {
            $db->query( "UPDATE eZImageCatalogue_Category SET
                                     Name='$name',
                                     Description='$description',
                                     UserID='$this->UserID',
                                     ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();

        
        return true;
    }

    /*!
      Deletes a eZImageCategory object from the database.
    */
    function delete( $catID=-1 )
    {
       
        if ( $catID == -1 )
            $catID = $this->ID;
        
        $category = new eZImageCategory( $catID );

        $categoryList = $category->getByParent( $category );

        foreach ( $categoryList as $category )
        {
            $this->delete( $category->id() );
        }

        foreach ( $this->images() as $image )
        {
            $image->delete();
        }

        $categoryID = $category->id();

        $db =& eZDB::globalDatabase();
        
        $db->begin( );
        
        $res1 = $db->query( "DELETE FROM eZImageCatalogue_Category WHERE ID='$categoryID'" );
        $res2 = $db->query( "DELETE FROM eZImageCatalogue_CategoryPermission WHERE ObjectID='$this->ID'" );

        if ( ( $res1 == false)  || ( $res2 == false ) )
            $db->rollback( );
        else
            $db->commit();
        
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZImageCatalogue_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][$db->fieldName("ID")];
                $this->Name =& $category_array[0][$db->fieldName("Name")];
                $this->Description =& $category_array[0][$db->fieldName("Description")];
                $this->ParentID =& $category_array[0][$db->fieldName("ParentID")];
                $this->UserID =& $category_array[0][$db->fieldName("UserID")];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZImageCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $category_array = array();
        
        $db->array_query( $category_array, "SELECT ID, Name FROM eZImageCatalogue_Category ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZImageCategory( $category_array[$i][$db->fieldName("ID")] );
        }
        
        return $return_array;
    }

    /*!
      \Static
      Returns all images in a category
    */
    function &getImages( $user, $category = false )
    {
        if ( !$category )
            $category = $this->ID;

        $db =& eZDB::globalDatabase();

        $return_array = array();
        $image_array = array();

        $db->array_query( $image_array, "SELECT ImageID, CategoryID FROM eZImageCatalogue_ImageCategoryLink WHERE CategoryID='$category' ORDER BY ImageID" );

        for ( $i = 0; $i < count( $image_array ); $i++ )
        {
            $image = new eZImage( $image_array[$i][$db->fieldName( "ImageID" )] );
            if ( $image->hasReadPermissions( $user ) )
            {
                array_push( $return_array, $image );
            }
        }
        return $return_array;
    }
    
    /*! 
      Returns the number of categories in the the category given as parameter as parent.
    */  
    function countByParent( $parent  )
    { 
        if ( get_class( $parent ) == "ezimagecategory" ) 
        { 
            $db =& eZDB::globalDatabase();
        
            $parentID = $parent->id(); 

            $db->query_single( $count, "SELECT count( ID ) AS Count FROM eZImageCatalogue_Category
                                        WHERE ParentID='$parentID'", "Count" );

            return $count;
        } 
        else 
        { 
            return 0;
        } 
    } 

    /*! 
      Returns the categories with the category given as parameter as parent. 
      
      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.
      
      The categories are returned as an array of eZImageCategory objects.      
    */  
    function getByParent( $parent, $offset = 0, $max = -1 )
    { 
        if ( get_class( $parent ) == "ezimagecategory" ) 
        { 
            $db =& eZDB::globalDatabase();
        
            $return_array = array(); 
            $category_array = array();
 
            $parentID = $parent->id(); 

            $db->array_query( $category_array, "SELECT ID, Name FROM eZImageCatalogue_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name", array( "Limit" => $max,
                                                                 "Offset" => $offset ) );

            for ( $i=0; $i<count($category_array); $i++ ) 
            { 
                $return_array[$i] = new eZImageCategory( $category_array[$i][$db->fieldName("ID")], 0 ); 
            } 

            return $return_array; 
        } 
        else 
        { 
            return array(); 
        } 
    } 

    /*!
        \static
        Returns the one, and only if one exists, category with the name
        
        Returns an object of eZImageCategory.
     */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();
        
        $topic =& new eZArticleCategory();
        
        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT ID, Name FROM eZArticle_Category WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic =& new eZImageCategory( $author_array[0][$db->fieldName("ID")] );
            }
        }
        
        return $topic;
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
            
        $category = new eZImageCategory( $categoryID ); 

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

    /*!
      Recursive function that returns an array containing an int (tree position) and an array ( all items on that level )
     */
    function getTree( $parentID=0, $level=0 )
    {
        $category = new eZImageCategory( $parentID );

        $categoryList = $category->getByParent( $category );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
            {
                array_push( $tree, array( $return_array[] = new eZImageCategory( $category->id() ), $level ) );

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
    function name( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Name );
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
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZImageCategory( $this->ParentID );
       }
       else
       {
           return 0;           
       }
    }


    /*!
      Returns a eZUser object.
    */
    function user()
    {
        if ( $this->UserID != 0 )
        {
            $ret = new eZUser( $this->UserID );
        }
        
        return $ret;
    }

    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $imagecategory is the ID of the image category.
     */
    function isOwner( $user, $imagecategory )
    {
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZImageCatalogue_Category WHERE ID='$imagecategory'");
        $userID = $res[$db->fieldName("UserID")];
        if(  $userID == $user->id() )
            return true;

        return false;
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
      Sets the parent category.
    */
    function setParent( $value )
    {
       if ( get_class( $value ) == "ezimagecategory" )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
      Sets the user of the file.
    */
    function setUser( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      \static
      Adds a file to the category.
      Can be used as a static function if $categoryid is supplied
    */
    function addImage( $value, $categoryid = false )
    {        
       if ( get_class( $value ) == "ezimage" )
           $imageID = $value->id();
       else if ( is_numeric( $value ) )
           $imageID = $value;
       else
           return false;

       if ( !$categoryid )
           $categoryid = $this->ID;
           
       $db =& eZDB::globalDatabase();

       $imageID = $value->id();

       $db->begin( );
       $db->lock( "eZImageCatalogue_ImageCategoryLink" );

       $query = "DELETE FROM eZImageCatalogue_ImageCategoryLink WHERE
                 CategoryID='$categoryid' AND ImageID='$imageid'";

       $db->query( $query );
       
       $nextID = $db->nextID( "eZImageCatalogue_ImageCategoryLink", "ID" );
       
       $query = "INSERT INTO eZImageCatalogue_ImageCategoryLink ( ID, CategoryID, ImageID )
                 VALUES ( '$nextID', '$categoryid', '$imageID' )";
       
       $res = $db->query( $query );

       $db->unlock();
       
       if ( $res == false )
           $db->rollback( );
       else
           $db->commit();        
    }

    /*!
      \static
      Removes an image from the category.
      Can be used as a static function if $categoryid is supplied
    */
    function removeImage( $value, $categoryid = false )
    {
        if ( get_class( $value ) == "ezimage" )
            $imageID = $value->id();
        else if ( is_numeric( $value ) )
            $imageID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "DELETE FROM eZImageCatalogue_ImageCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        ImageID='$imageID'";

        $db->begin( );
        
        $res = $db->query( $query );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    
    /*!
      Returns every images in a category as a array of eZImage objects.
    */
    function imageCount()
    {
        if ( $limit == 0 )
        {
            $ini =& INIFile::globalINI();
            $limit = $ini->read_var( "eZImageCatalogueMain", "ListImagesPerPage" );
        }

        $db =& eZDB::globalDatabase();

        $db->query_single( $count, "
                SELECT count( DISTINCT eZImageCatalogue_Image.ID ) AS Count
                FROM eZImageCatalogue_Image, eZImageCatalogue_Category, eZImageCatalogue_ImageCategoryLink
                WHERE 
                eZImageCatalogue_ImageCategoryLink.ImageID = eZImageCatalogue_Image.ID
                AND
                eZImageCatalogue_Category.ID = eZImageCatalogue_ImageCategoryLink.CategoryID
                AND
                eZImageCatalogue_Category.ID='$this->ID'", "Count" );

        return $count;
    } 

    /*!
      Returns every images in a category as a array of eZImage objects.
    */
    function images( $sortMode="time", $offset=0, $limit=0 )
    {
       if ( $limit == 0 )
       {
           $ini =& INIFile::globalINI();
           $limit = $ini->read_var( "eZImageCatalogueMain", "ListImagesPerPage" );
       }

       $db =& eZDB::globalDatabase();

       $return_array = array();
       $article_array = array();

       $db->array_query( $file_array, "
                SELECT eZImageCatalogue_Image.ID AS ImageID
                FROM eZImageCatalogue_Image, eZImageCatalogue_Category, eZImageCatalogue_ImageCategoryLink
                WHERE 
                eZImageCatalogue_ImageCategoryLink.ImageID = eZImageCatalogue_Image.ID
                AND
                eZImageCatalogue_Category.ID = eZImageCatalogue_ImageCategoryLink.CategoryID
                AND
                eZImageCatalogue_Category.ID='$this->ID'
                GROUP BY eZImageCatalogue_Image.ID, eZImageCatalogue_Image.OriginalFileName ORDER BY eZImageCatalogue_Image.OriginalFileName",
       array( "Limit" => $limit,
              "Offset" => $offset ) );
 
       for ( $i=0; $i<count($file_array); $i++ )
       {
           $return_array[$i] = new eZImage( $file_array[$i][$db->fieldName("ImageID")], false );
       }
       
       return $return_array;
    } 

    /*!
      Adds read permission to the user.
    */
    function addReadPermission( $value )
    {
       $db =& eZDB::globalDatabase();

       $db->begin( );
       $db->lock( "eZImageCatalogue_CategoryReadGroupLink" );
       $nextID = $db->nextID( "eZImageCatalogue_CategoryReadGroupLink", "ID" );
       
       $query = "INSERT INTO eZImageCatalogue_CategoryReadGroupLink ( ID, CategoryID, GroupID )
                 VALUES ( '$nextID', '$this->ID', '$value' )";
       
       $res = $db->query( $query );

       $db->unlock();
    
       if ( $res == false )
           $db->rollback( );
       else
           $db->commit();        
    }

    /*!
      Adds write permission to the user.
    */
    function addWritePermission( $value )
    {
       $db =& eZDB::globalDatabase();
       
       $db->begin( );
       $db->lock( "eZImageCatalogue_CategoryReadGroupLink" );
       $nextID = $db->nextID( "eZImageCatalogue_CategoryReadGroupLink", "ID" );
       
       $query = "INSERT INTO eZImageCatalogue_CategoryReadGroupLink ( ID, CategoryID, GroupID )
                 VALUES ( '$nextID', '$this->ID', '$value' )";
       
       $res = $db->query( $query );

       $db->unlock();
    
       if ( $res == false )
           $db->rollback( );
       else
           $db->commit();       
    }

    /*!
      Check if the user have read permissions. Returns true if the user have permissions. False if not.
    */
    function hasReadPermissions( $user=false )
    {
       $db =& eZDB::globalDatabase();

       $db->array_query( $userArrayID, "SELECT UserID FROM eZImageCatalogue_Category WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0][$db->fieldName("UserID")] == $user->id() )
           {
               return true;
           }
           
           $groups = $user->groups();
       }

       $db->array_query( $readPermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryReadGroupLink WHERE CategoryID='$this->ID'" );

       for ( $i=0; $i < count ( $readPermissions ); $i++ )
       {
           if ( $readPermissions[$i][$db->fieldName("GroupID")] == 0 )
           {
               return true;
           }
           else
           {
               if ( count ( $groups ) > 0 )
               {
                   
                   foreach ( $groups as $group )
                   {
                       if ( $group->id() == $readPermissions[$i][$db->fieldName("GroupID")] )
                       {
                           return true;
                       }
                   }
               }
           }
       }
       
       return false;
    }

    /*!
      Check if the user have write permissions. Returns true if the user have permissions. False if not.
    */
    function hasWritePermissions( $user=false )
    {
       $db =& eZDB::globalDatabase();

       $db->array_query( $userArrayID, "SELECT UserID FROM eZImageCatalogue_Category WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0][$db->fieldName("UserID")] == $user->id() )
           {
               return true;
           }
           
           $groups = $user->groups();
       }

       $db->array_query( $writePermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryWriteGroupLink WHERE CategoryID='$this->ID'" );

       for ( $i=0; $i < count ( $writePermissions ); $i++ )
       {
           if ( $writePermissions[$i][$db->fieldName("GroupID")] == 0 )
           {
               return true;
           }
           else
           {
               if ( count ( $groups ) > 0 )
               {                   
                   foreach ( $groups as $group )
                   {
                       if ( $group->id() == $writePermissions[$i][$db->fieldName("GroupID")] )
                       {
                           return true;
                       }
                   }
               }
           }
       }
       
       return false;
    }
    
    /*!
      Returns all the read permission for this object.

    */
    function readPermissions( )
    {
       $db =& eZDB::globalDatabase();

       $readPermissions = array();
       $ret = false;

       $db->array_query( $readPermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryReadGroupLink WHERE CategoryID='$this->ID'" );
      
       for ( $i=0; $i < count ( $readPermissions ); $i++ )
       {
           if ( $readPermissions[$i][$db->fieldName("GroupID")] == 0 )
           {
               $ret[] = "Everybody";
           }
          
           $ret[] = new eZUserGroup( $readPermissions[$i][$db->fieldName("GroupID")] );
       }

       return $ret;
    }

    /*!
      Returns all the write permission for this object.

    */
    function writePermissions( )
    {
       $db =& eZDB::globalDatabase();

       $writePermissions = array();
       $ret = false;

       $db->array_query( $writePermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryWriteGroupLink WHERE CategoryID='$this->ID'" );
      
       for ( $i=0; $i < count ( $writePermissions ); $i++ )
       {
           if ( $writePermissions[$i][$db->fieldName("GroupID")] == 0 )
           {
               $ret[] = "Everybody";
           }
          
           $ret[] = new eZUserGroup( $writePermissions[$i][$db->fieldName("GroupID")] );
       }

       return $ret;
    }

    /*!
      Remove the read permissions from this eZVirtualFolder object.

    */
    function removeReadPermissions()
    {
       $db =& eZDB::globalDatabase();

       $db->query( "DELETE FROM eZImageCatalogue_CategoryWriteGroupLink WHERE FolderID='$this->ID'" );
    }


    /*!
      Remove the write permissions from this eZVirtualFolder object.

    */
    function removeWritePermissions()
    {
       $db =& eZDB::globalDatabase();

       $this->Database->query( "DELETE FROM eZImageCatalogue_CategoryWriteGroupLink WHERE FolderID='$this->ID'" );
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $UserID;

}

?>
