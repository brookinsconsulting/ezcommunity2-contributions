<?
// 
// $Id: ezvirtualfile.php,v 1.32 2001/06/28 14:52:36 jb Exp $
//
// Definition of eZVirtualFile class
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Dec-2000 15:36:36 bf>
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
//! The eZVirtualFile represents a file in the virtual file manager.
/*!
  
*/

/*!TODO
 */

include_once( "classes/ezdb.php" );
include_once( "classes/ezfile.php" );

class eZVirtualfile
{
    /*!
      Constructs a new eZVirtualfile object.
    */
    function eZVirtualfile( $id="" )
    {
        $this->IsConnected = false;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZVirtualFile object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $filename = $db->escapeString( $this->FileName );
        $originalfilename = $db->escapeString( $this->OriginalFileName );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZFileManager_File" );
            $nextID = $db->nextID( "eZFileManager_File", "ID" );
            
            $result = $db->query( "INSERT INTO eZFileManager_File
                                  ( ID, Name, Description, FileName, OriginalFileName, UserID )
                                  VALUES ( '$nextID',
                                           '$name',
                                           '$description',
                                           '$filename',
                                           '$originalfilename',
                                           '$this->UserID' )
                                  " );
			$this->ID = $nextID;

        }
        else
        {
            $result = $db->query( "UPDATE eZFileManager_File SET
                                 Name='$name',
                                 Description='$description',
                                 FileName='$filename',
                                 OriginalFileName='$originalfilename'
                                 WHERE ID='$this->ID'
                                 " );
        }

        $db->unlock();

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Delete the eZVirtualFile object from the database and the filesystem.
    */
    function delete()
    {
        // Delete from the database
        $db =& eZDB::globalDatabase();
        
        if ( isset( $this->ID ) )
        {
            $this->removeWritePermissions();
            $this->removeReadPermissions();
            
            $db->begin();

            $results[] = $db->query( "DELETE FROM eZFileManager_File WHERE ID='$this->ID'" );
            $results[] = $db->query( "DELETE FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
            
            $results[] = $db->query( "DELETE FROM eZFileManager_FilePermission WHERE ObjectID='$this->ID'" );

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
        
        // Delete from the filesystem
        if ( ( file_exists ( $this->filePath( true ) ) ) && $commit )
        {
            unlink( $this->filePath( true ) );
        }
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $virtualfile_array, "SELECT * FROM eZFileManager_File WHERE ID='$id'" );

            if ( count( $virtualfile_array ) > 1 )
            {
                die( "Error: VirtualFile's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $virtualfile_array ) == 1 )
            {
                $this->ID =& $virtualfile_array[0][$db->fieldName( "ID" )];
                $this->Name =& $virtualfile_array[0][$db->fieldName( "Name" )];
                $this->Description =& $virtualfile_array[0][$db->fieldName( "Description" )];
                $this->FileName =& $virtualfile_array[0][$db->fieldName( "FileName" )];
                $this->OriginalFileName =& $virtualfile_array[0][$db->fieldName( "OriginalFileName" )];
                $this->UserID =& $virtualfile_array[0][$db->fieldName( "UserID" )];
                $ret = true;
            }
        }
        return $ret;
    }


    /*!
      Returns all the files found in the database.

      The files are returned as an array of eZVirtualFile objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $category_array = array();
        
        $db->array_query( $category_array, "SELECT ID FROM eZFileManager_File ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZVirtualFile( $category_array[$i][$db->fieldName("ID")], 0 );
        }
        
        return $return_array;
    }

    /*!
      Does a search in the filemanager.

      Default limit is set to 30.
     */
    function &search( &$queryText, $offset=0, $limit=30 )
    {
        $db =& eZDB::globalDatabase();
        $returnArray = array();

        $query = new eZQuery( array( "Name", "Description", "OriginalFileName" ), $queryText );

        $queryString = ( "SELECT ID FROM eZFileManager_File
                        WHERE (" . $query->buildQuery() . ")
                        ORDER By Name" );

        $limit = array( "Limit" => $limit,
                        "Offset" => $offset );


        $db->array_query( $fileArray, $queryString, $limit );

        foreach ( $fileArray as $file )
        {
            $returnArray[] = new eZVirtualFile( $file[$db->fieldName( "ID" )] );
        }
        return $returnArray;
    }

    /*!
      Returns the total count of a query.
     */
    function searchCount( &$queryText )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $query = new eZQuery( array( "Name", "Description", "OriginalFileName" ), $queryText );

        $queryString = ( "SELECT COUNT(ID) as Count
                        FROM eZFileManager_File
                        WHERE (" . $query->buildQuery() . ")" );

        $db->query_single( $result, $queryString );
        $ret = $result[$db->fieldName("Count")];
        return $ret;
    }

    /*!
      Get all the files that is not assigned to a category.

      The images are returned as an array of eZVirtualFile objects.
     */
    function &getUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $fileArray, "SELECT File.ID, Link.FileID
                                        FROM eZFileManager_File AS File
                                        LEFT JOIN  eZFileManager_FileFolderLink AS Link
                                        ON File.ID=Link.FileID
                                        WHERE FileID IS NULL" );

        foreach( $fileArray as $file )
        {
            $returnArray[] = new eZVirtualFile( $file[$db->fieldName( "ID" )] );
        }
        return $returnArray;
    }

    
    /*!
      Returns the id of the virtual file.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the name of the virtual file.
    */
    function &name( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the description of the virtual file.
    */
    function &description( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Description );
        else
            return $this->Description;
    }    

    /*!
      Returns the filename of the virtual file.
    */
    function &fileName()
    {
        return $this->FileName;
    }

    /*!
      Returns the original file name of the virtual file.
    */
    function &originalFileName()
    {
        return $this->OriginalFileName;
    }


    /*!
      Returns a eZUser object.
    */
    function &user( $as_object = true )
    {
        if ( $this->UserID != 0 )
        {
            $ret = $as_object ? new eZUser( $this->UserID ) : $this->UserID;
        }
        
        return $ret;
    }

    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $file is the ID of the file.
     */
    function isOwner( $user, $file )
    {
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $db =& eZDB::globalDatabase();
        $dadb->query_single( $res, "SELECT UserID from eZFileManager_File WHERE ID='$file'");
        $userID = $res[$db->fieldName( "UserID" )];
        if(  $userID == $user->id() )
            return true;

        return false;
    }

    
    /*!
      Returns the path and filename to the original virtualfile.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function &filePath( $relative=false )
    {
        if ( $relative == true )
        {
            $path = "ezfilemanager/files/" . $this->FileName;
        }
        else
        {
            $path = "/ezfilemanager/files/" . $this->FileName;
        }
       
        return $path;
    }

    /*!
      Returns the size of the file.
    */

    function &fileSize()
    {
        $filepath =& $this->filePath( true );
        $size = filesize( $filepath );

        print( "path: " . $filepath . "<br>");
        print( "direkte: " . filesize( "ezfilemanager/files/phphjhrHe" ) . "<br>");
        print( "fra var: ". $filepath . filesize( $filepath ) . "<br>");

//        print( filesize ( "ezfilemanager/files/phphjhrHe" ) );

        return $size;
    }

    /*!
      Returns the size of the file in a shortened form useful for printing to the user,
      the returned value is an array with the filesize, the size as a shortened string
      and the unit. The keys used for fetching the various items in the array are:
      "size" - The full file size
      "size-string" - The shortened file size as a string
      "unit" - The unit for the shortened size, either B, KB, MB or GB
    */

    function &siFileSize()
    {
        $size = $this->fileSize();
        return eZFile::siFileSize( $size );
    }

    /*!
      Sets the virtual file name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the virtual file description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the original virtual filename.
    */
    function setOriginalFileName( $value )
    {
        $this->OriginalFileName = $value;
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
      Makes a copy of the file and stores the file in the file manager.
      
    */
    function setFile( &$file )
    {
        if ( get_class( $file ) == "ezfile" )
        {
            print( "storing virtualfile" );

            $this->OriginalFileName = $file->name();

            $suffix = "";
            if ( ereg( "\\.([a-z]+)$", $this->OriginalFileName, $regs ) )
            {
                // We got a suffix, make it lowercase and store it
                $suffix = strtolower( $regs[1] );
            }

            // the path to the catalogue

            // Copy the file since we support it directly
            $file->copy( "ezfilemanager/files/" . basename( $file->tmpName() ) . $postfix );

            $this->FileName = basename( $file->tmpName() ) . $postfix;

            $name = $file->name();
           
            $this->OriginalFileName =& $name;
        }
    }
    
    /*!
      Retuns the folder for this eZVirtualFile object.
    */
    function &folder()
    {
        $db =& eZDB::globalDatabase();
        $result = array();

        $query = ( "SELECT FolderID FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
        $db->array_query( $result, $query );
        
        foreach ( $result as $folder )
        {
            return new eZVirtualFolder( $folder[$db->fieldName( "FolderID" )] );
        }
    }
    

    /*!
      Removes the file from every folders.
    */
    function removeFolders()
    {
        $db =& eZDB::globalDatabase();

        $query = ( "DELETE FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
        $db->query( $query );
    }

    /*!
      Adds a pagview to the file.
    */
    function addPageView( $pageView )
    {
        if ( get_class( $pageView ) == "ezpageview" )
        {
            $db =& eZDB::globalDatabase();

            $pageViewID = $pageView->id();

            $db->lock( "eZFileManager_FilePageViewLink" );
            $nextID = $db->nextID( "eZFileManager_FilePageViewLink", "ID" );

            $query = ( "INSERT INTO eZFileManager_FilePageViewLink
                       ( ID, PageViewID, FileID )
                       VALUES ( '$nextID', '$this->ID', '$pageViewID' ) " );

            $result = $db->query( $query );

            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Adds read permission to the user.
    */
    function addReadPermission( $value )
    {
        $db =& eZDB::globalDatabase();
        
        $db->lock( "eZFileManager_FileReadGroupLink" );
        $nextID = $db->nextID( "eZFileManager_FileReadGroupLink", "ID" );
        
        $query = "INSERT INTO eZFileManager_FileReadGroupLink
                 ( ID, FileID, GroupID )
                 VALUES ( '$nextID', '$this->ID', '$value' )";
        
        $result = $db->query( $query );
        
        if ( $result == false )
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
       
        $db->lock( "eZFileManager_FileWriteGroupLink" );
        $nextID = $db->nextID( "eZFileManager_FileWriteGroupLink", "ID" );
        
        $query = "INSERT INTO eZFileManager_FileWriteGroupLink
                 ( ID, FileID, GroupID )
                 VALUES ( '$nextID', '$this->ID', '$value' )";
        
        $result = $db->query( $query );
        
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Check if the user have read permissions.

    */
    function hasReadPermissions( $user=false )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $userArrayID, "SELECT UserID FROM eZFileManager_File WHERE ID='$this->ID'" );

        if ( $user )
        {
            if ( $userArrayID[0][$db->fieldName( "UserID" )] == $user->id() )
            {
                return true;
            }
            $groups = $user->groups();
        }

        $db->array_query( $readPermissions, "SELECT GroupID FROM eZFileManager_FileReadGroupLink WHERE FileID='$this->ID'" );

        for ( $i=0; $i < count ( $readPermissions ); $i++ )
        {
            if ( $readPermissions[$i][$db->fieldName( "GroupID" )] == 0 )
            {
                return true;
            }
            else
            {
                if ( count ( $groups ) > 0 )
                {
                    foreach ( $groups as $group )
                    {
                        if ( $group->id() == $readPermissions[$i][$db->fieldName( "GroupID" )] )
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
      Check if the user have read permissions.

    */
    function hasWritePermissions( $user=false )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $userArrayID, "SELECT UserID FROM eZFileManager_File WHERE ID='$this->ID'" );

        if ( $user )
        {
            if ( $userArrayID[0][$db->fieldName( "UserID" )] == $user->id() )
            {
                return true;
            }
            $groups = $user->groups();
        }
       
        $db->array_query( $writePermissions, "SELECT GroupID FROM eZFileManager_FileWriteGroupLink WHERE FileID='$this->ID'" );

        for ( $i=0; $i < count ( $writePermissions ); $i++ )
        {
            if ( $writePermissions[$i][$db->fieldName( "GroupID" )] == 0 )
            {
                return true;
            }
            else
            {
                if ( count ( $groups ) > 0 )
                {
                    foreach ( $groups as $group )
                    {
                        if ( $group->id() == $writePermissions[$i][$db->fieldName( "GroupID" )] )
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
      Remove the read permissions from this eZVirtualFile object.

    */
    function removeReadPermissions()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();
        $result = $db->query( "DELETE FROM eZFileManager_FileReadGroupLink WHERE FileID='$this->ID'" );
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }


    /*!
      Remove the write permissions from this eZVirtualFile object.

    */
    function removeWritePermissions()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();
        $result = $db->query( "DELETE FROM eZFileManager_FileWriteGroupLink WHERE FileID='$this->ID'" );
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Returns all the read permission for this object.

    */
    function readPermissions( )
    {
        $db =& eZDB::globalDatabase();

        $readPermissions = array();
        $ret = false;

        $db->array_query( $readPermissions, "SELECT GroupID FROM eZFileManager_FileReadGroupLink WHERE FileID='$this->ID'" );

      
        for ( $i=0; $i < count ( $readPermissions ); $i++ )
        {
            if ( $readPermissions[$i][$db->fieldName( "GroupID" )] == 0 )
            {
                $ret[] = "Everybody";
            }
          
            $ret[] = new eZUserGroup( $readPermissions[$i][$db->fieldName( "GroupID" )] );
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

        $db->array_query( $writePermissions, "SELECT GroupID FROM eZFileManager_FileWriteGroupLink WHERE FileID='$this->ID'" );

      
        for ( $i=0; $i < count ( $writePermissions ); $i++ )
        {
            if ( $writePermissions[$i][$db->fieldName( "GroupID" )] == 0 )
            {
                $ret[] = "Everybody";
            }
          
            $ret[] = new eZUserGroup( $writePermissions[$i][$db->fieldName( "GroupID" )] );
        }

        return $ret;
    }
    
    var $ID;
    var $Name;
    var $Caption;
    var $Description;
    var $FileName;
    var $OriginalFileName;
    var $UserID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
