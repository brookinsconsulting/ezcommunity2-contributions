<?
// 
// $Id: ezimagecategory.php,v 1.13 2001/05/04 16:37:25 descala Exp $
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

class eZImageCategory
{
    /*!
      Constructs a new eZImageCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZImageCategory( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        $this->ExcludeFromSearch = "false";
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
      Stores a eZImageCategory object to the database.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZImageCatalogue_Category SET
		                         Name='$name',
                                 Description='$description',
                                 UserID='$this->UserID',
                                 ParentID='$this->ParentID'" );
			$this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZImageCatalogue_Category SET
		                         Name='$name',
                                 Description='$description',
                                 UserID='$this->UserID',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZImageCategory object from the database.
    */
    function delete( $catID=-1 )
    {
        $this->dbInit();
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
        $this->Database->query( "DELETE FROM eZImageCatalogue_Category WHERE ID='$categoryID'" );
        $this->Database->query( "DELETE FROM eZImageCatalogue_CategoryPermission WHERE ObjectID='$this->ID'" );
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZImageCatalogue_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][ "ID" ];
                $this->Name =& $category_array[0][ "Name" ];
                $this->Description =& $category_array[0][ "Description" ];
                $this->ParentID =& $category_array[0][ "ParentID" ];
                $this->UserID =& $category_array[0][ "UserID" ];
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

      The categories are returned as an array of eZImageCategory objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZImageCatalogue_Category ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZImageCategory( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZImageCategory objects.      
    */
    function getByParent( $parent  )
    {
        if ( get_class( $parent ) == "ezimagecategory" )
        {
            $this->dbInit();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZImageCatalogue_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i<count($category_array); $i++ )
            {
                $return_array[$i] = new eZImageCategory( $category_array[$i]["ID"], 0 );
            }

            return $return_array;
        }
        else
        {
            return array();
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT UserID from eZImageCatalogue_Category WHERE ID='$imagecategory'");
        $userID = $res[ "UserID" ];
        if(  $userID == $user->id() )
            return true;

        return false;
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

       if ( get_class( $value ) == "ezimagecategory" )
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
      Sets the user of the file.
    */
    function setUser( $user )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      Adds a file to the folder.
    */
    function addImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezimage" )
       {
            $this->dbInit();

            $imageID = $value->id();

            $this->Database->query( "DELETE FROM eZImageCatalogue_ImageCategoryLink WHERE ImageID='$imageID'" );
            
            $query = "INSERT INTO eZImageCatalogue_ImageCategoryLink SET CategoryID='$this->ID', ImageID='$imageID'";

            $this->Database->query( $query );
       }
    }

    /*!
      Returns every images in a category as a array of eZImage objects.

    */
    function images( $sortMode="time",
                       $offset=0,
                       $limit=50 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $return_array = array();
       $article_array = array();

       $this->Database->array_query( $file_array, "
                SELECT eZImageCatalogue_Image.ID AS ImageID, eZImageCatalogue_Image.Name, eZImageCatalogue_Category.ID, eZImageCatalogue_Category.Name
                FROM eZImageCatalogue_Image, eZImageCatalogue_Category, eZImageCatalogue_ImageCategoryLink
                WHERE 
                eZImageCatalogue_ImageCategoryLink.ImageID = eZImageCatalogue_Image.ID
                AND
                eZImageCatalogue_Category.ID = eZImageCatalogue_ImageCategoryLink.CategoryID
                AND
                eZImageCatalogue_Category.ID='$this->ID'
                GROUP BY eZImageCatalogue_Image.ID ORDER BY eZImageCatalogue_Image.OriginalFileName LIMIT $offset,$limit" );
 
       for ( $i=0; $i<count($file_array); $i++ )
       {
           $return_array[$i] = new eZImage( $file_array[$i]["ImageID"], false );
       }
       
       return $return_array;
    }

    /*!
      Adds read permission to the user.
    */
    function addReadPermission( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();
       
       $query = "INSERT INTO eZImageCatalogue_CategoryReadGroupLink SET CategoryID='$this->ID', GroupID='$value'";
            
       $this->Database->query( $query );
    }

    /*!
      Adds write permission to the user.
    */
    function addWritePermission( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();
       
       $query = "INSERT INTO eZImageCatalogue_CategoryWriteGroupLink SET CategoryID='$this->ID', GroupID='$value'";
            
       $this->Database->query( $query );
    }

    /*!
      Check if the user have read permissions. Returns true if the user have permissions. False if not.
    */
    function hasReadPermissions( $user=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->array_query( $userArrayID, "SELECT UserID FROM eZImageCatalogue_Category WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0]["UserID"] == $user->id() )
           {
               return true;
           }
           
           $groups = $user->groups();
       }

       $this->Database->array_query( $readPermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryReadGroupLink WHERE CategoryID='$this->ID'" );

       for ( $i=0; $i < count ( $readPermissions ); $i++ )
       {
           if ( $readPermissions[$i]["GroupID"] == 0 )
           {
               return true;
           }
           else
           {
               if ( count ( $groups ) > 0 )
               {
                   
                   foreach ( $groups as $group )
                   {
                       if ( $group->id() == $readPermissions[$i]["GroupID"] )
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->array_query( $userArrayID, "SELECT UserID FROM eZImageCatalogue_Category WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0]["UserID"] == $user->id() )
           {
               return true;
           }
           
           $groups = $user->groups();
       }

       $this->Database->array_query( $writePermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryWriteGroupLink WHERE CategoryID='$this->ID'" );

       for ( $i=0; $i < count ( $writePermissions ); $i++ )
       {
           if ( $writePermissions[$i]["GroupID"] == 0 )
           {
               return true;
           }
           else
           {
               if ( count ( $groups ) > 0 )
               {
                   
                   foreach ( $groups as $group )
                   {
                       if ( $group->id() == $writePermissions[$i]["GroupID"] )
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $readPermissions = array();
       $ret = false;

       $this->Database->array_query( $readPermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryReadGroupLink WHERE CategoryID='$this->ID'" );
      
       for ( $i=0; $i < count ( $readPermissions ); $i++ )
       {
           if ( $readPermissions[$i]["GroupID"] == 0 )
           {
               $ret[] = "Everybody";
           }
          
           $ret[] = new eZUserGroup( $readPermissions[$i]["GroupID"] );
       }

       return $ret;
    }

    /*!
      Returns all the write permission for this object.

    */
    function writePermissions( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $writePermissions = array();
       $ret = false;

       $this->Database->array_query( $writePermissions, "SELECT GroupID FROM eZImageCatalogue_CategoryWriteGroupLink WHERE CategoryID='$this->ID'" );
      
       for ( $i=0; $i < count ( $writePermissions ); $i++ )
       {
           if ( $writePermissions[$i]["GroupID"] == 0 )
           {
               $ret[] = "Everybody";
           }
          
           $ret[] = new eZUserGroup( $writePermissions[$i]["GroupID"] );
       }

       return $ret;
    }

    /*!
      Remove the read permissions from this eZVirtualFolder object.

    */
    function removeReadPermissions()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZImageCatalogue_CategoryWriteGroupLink WHERE FolderID='$this->ID'" );
    }


    /*!
      Remove the write permissions from this eZVirtualFolder object.

    */
    function removeWritePermissions()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZImageCatalogue_CategoryWriteGroupLink WHERE FolderID='$this->ID'" );
    }


    /*!
      Private function.
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
    
    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $UserID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

 