<?
// 
// $Id: ezvirtualfolder.php,v 1.22 2001/06/29 11:21:24 bf Exp $
//
// Definition of eZVirtualFolder class
//
// B�rd Farstad <bf@ez.no>
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
//! eZVirtualFolder manages virtual folders.
/*!
  
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZVirtualFolder
{
    /*!
      Constructs a new eZVirtualFolder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZVirtualFolder( $id=-1 )
    {
        $this->IsConnected = false;
        $this->ExcludeFromSearch = "false";
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZVirtualFolder object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZFileManager_Folder" );
            $nextID = $db->nextID( "eZFileManager_Folder", "ID" );

            $result = $db->query( "INSERT INTO eZFileManager_Folder
                                  ( ID, Name, Description, UserID, ParentID )
                                  VALUES ( '$nextID',
                                           '$name',
                                           '$description',
                                           '$this->UserID',
                                           '$this->ParentID' )
                                  " );
			$this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZFileManager_Folder SET
		                         Name='$name',
                                 Description='$description',
                                 UserID='$this->UserID',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'", true );
        }

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes a eZVirtualFolder object from the database.

    */
    function delete( $catID=-1 )
    {
        $db =& eZDB::globalDatabase();
        if ( $catID == -1 )
            $catID = $this->ID;
        
        $category = new eZVirtualFolder( $catID );

        $categoryList = $category->getByParent( $category );

        foreach ( $categoryList as $category )
        {
            $this->delete( $category->id() );
        }

        foreach ( $this->files() as $file )
        {
            $file->delete();
        }

        $categoryID = $category->id();

        $results[] = $db->query( "DELETE FROM eZFileManager_Folder WHERE ID='$categoryID'" );
        $results[] = $db->query( "DELETE FROM eZFileManager_FileFolderLink WHERE FolderID='$categoryID'" );

        $results[] = $db->query( "DELETE FROM eZFileManager_FolderPermission WHERE ObjectID='$categoryID'" );

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
            $db->array_query( $category_array, "SELECT * FROM eZFileManager_Folder WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][$db->fieldName( "ID" )];
                $this->Name =& $category_array[0][$db->fieldName( "Name" )];
                $this->Description =& $category_array[0][$db->fieldName( "Description" )];
                $this->ParentID =& $category_array[0][$db->fieldName( "ParentID" )];
                $this->UserID =& $category_array[0][$db->fieldName( "UserID" )];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZVirtualFolder objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $category_array = array();
        
        $db->array_query( $category_array, "SELECT ID,Name FROM eZFileManager_Folder ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZVirtualFolder( $category_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZVirtualFolder objects.      
    */
    function &getByParent( $parent  )
    {
        if ( get_class( $parent ) == "ezvirtualfolder" )
        {
            $db =& eZDB::globalDatabase();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $db->array_query( $category_array, "SELECT ID, Name FROM eZFileManager_Folder
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i<count($category_array); $i++ )
            {
                $return_array[$i] = new eZVirtualFolder( $category_array[$i][$db->fieldName( "ID" )], 0 );
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
    function &path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
            
        $category = new eZVirtualFolder( $categoryID );

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

    function &getTree( $parentID=0, $level=0 )
    {
        $user = eZUser::currentUser();
        
        $category = new eZVirtualFolder( $parentID );

        $categoryList = $category->getByParent( $category );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
                array_push( $tree, array( $return_array[] = new eZVirtualFolder( $category->id() ), $level ) );

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
    function &name( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function &description()
    {
       if( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
    }
    
    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function &parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZVirtualFolder( $this->ParentID );
       }
       else
       {
           return 0;           
       }
    }

    /*!
      Returns a eZUser object.
    */
    function &user()
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
      $folder is the virtual folder ID
     */
    function isOwner( $user, $folderID )
    {
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZFileManager_Folder WHERE ID='$folderID'");
        $userID = $res[$db->fieldName( "UserID" )];
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
    function setParent( &$value )
    {
       if ( get_class( $value ) == "ezvirtualfolder" )
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
    function setUser( &$user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      Adds a file to the folder.
    */
    function addFile( &$value )
    {
       if ( get_class( $value ) == "ezvirtualfile" )
       {
           $db =& eZDB::globalDatabase();
           $db->begin();
           $db->lock( "eZFileManager_FileFolderLink" );
           $nextID = $db->nextID( "eZFileManager_FileFolderLink", "ID" );

           $fileID = $value->id();
            
           $query = "INSERT INTO eZFileManager_FileFolderLink
                      ( ID, FolderID, FileID )
                      VALUES ( '$nextID',
                               '$this->ID',
                               '$fileID'
                             )";
                      
           $result = $db->query( $query );

           $db->unlock();
           
           if ( $result == false )
               $db->rollback( );
           else
               $db->commit();
       }       
    }

    /*!
      Returns every files in a folder as a array of eZVirtualFile objects.

    */
    function &files( $sortMode="time",
                       $offset=0,
                       $limit=50 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $db =& eZDB::globalDatabase();

       $return_array = array();
       $article_array = array();

       $db->array_query( $file_array, "
                SELECT eZFileManager_File.ID AS FileID, eZFileManager_File.OriginalFileName
                FROM eZFileManager_File, eZFileManager_Folder, eZFileManager_FileFolderLink
                WHERE 
                eZFileManager_FileFolderLink.FileID = eZFileManager_File.ID
                AND
                eZFileManager_Folder.ID = eZFileManager_FileFolderLink.FolderID
                AND
                eZFileManager_Folder.ID='$this->ID'
                GROUP BY eZFileManager_File.ID, eZFileManager_File.OriginalFileName ORDER BY eZFileManager_File.OriginalFileName",
       array( "Limit" => $limit,
              "Offset" => $offset ) );
 
       for ( $i=0; $i<count($file_array); $i++ )
       {
           $return_array[$i] = new eZVirtualFile( $file_array[$i][$db->fieldName( "FileID" )], false );
       }
       
       return $return_array;
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
