<?
// 
// $Id: ezvirtualfile.php,v 1.27 2001/05/04 16:37:24 descala Exp $
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
    function eZVirtualfile( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != "" )
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
      Stores a eZVirtualFile object to the database.
    */
    function store()
    {
        $this->dbInit();

        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        $filename = addslashes( $this->FileName );
        $originalfilename = addslashes( $this->OriginalFileName );
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZFileManager_File SET
                                 Name='$name',
                                 Description='$description',
                                 FileName='$filename',
                                 OriginalFileName='$originalfilename',
                                 UserID='$this->UserID'
                                 " );
			$this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZFileManager_File SET
                                 Name='$name',
                                 Description='$description',
                                 FileName='$filename',
                                 OriginalFileName='$originalfilename'
                                 WHERE ID='$this->ID'
                                 " );
        }
        
        $this->State_ = "Coherent";
    }

    /*!
      Delete the eZVirtualFile object from the database and the filesystem.
    */
    function delete()
    {
        // Delete from the database
        $this->dbInit();
        
        if ( isset( $this->ID ) )
        {
            $this->removeWritePermissions();
            $this->removeReadPermissions();

            $this->Database->query( "DELETE FROM eZFileManager_File WHERE ID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZFileManager_FilePermission WHERE ObjectID='$this->ID'" );
        }

        // Delete from the filesystem
        if ( file_exists ( $this->filePath( true ) ) )
        {
            unlink( $this->filePath( true ) );
        }
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();

        $ret = false;
        if ( $id != "" )
        {
            $this->Database->array_query( $virtualfile_array, "SELECT * FROM eZFileManager_File WHERE ID='$id'" );

            if ( count( $virtualfile_array ) > 1 )
            {
                die( "Error: VirtualFile's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $virtualfile_array ) == 1 )
            {
                $this->ID =& $virtualfile_array[0][ "ID" ];
                $this->Name =& $virtualfile_array[0][ "Name" ];
                $this->Description =& $virtualfile_array[0][ "Description" ];
                $this->FileName =& $virtualfile_array[0][ "FileName" ];
                $this->OriginalFileName =& $virtualfile_array[0][ "OriginalFileName" ];
                $this->UserID =& $virtualfile_array[0][ "UserID" ];

                $this->State_ = "Coherent";
                $ret = true;

            }
            else if( count( $virtualfile_array ) < 1 )
            {
                $this->State_ = "Dirty";
            }
            if( count( $virtualfile_array ) == 0 )
            {
                $this->ID = 0;
                $this->State_ = "New";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }

        return $ret;
    }


    /*!
      Returns all the files found in the database.

      The files are returned as an array of eZVirtualFile objects.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZFileManager_File ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZVirtualFile( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }


    
    /*!
      Returns the id of the virtual file.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->ID;
    }

    
    
    /*!
      Returns the name of the virtual file.
    */
    function &name( $html = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->FileName;
    }

    /*!
      Returns the original file name of the virtual file.
    */
    function &originalFileName()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->OriginalFileName;
    }


    /*!
      Returns a eZUser object.
    */
    function &user()
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
      $file is the ID of the file.
     */
    function isOwner( $user, $file )
    {
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT UserID from eZFileManager_File WHERE ID='$file'");
        $userID = $res[ "UserID" ];
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the virtual file description.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the original virtual filename.
    */
    function setOriginalFileName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->OriginalFileName = $value;
    }

    /*!
      Sets the user of the file.
    */
    function setUser( &$user )
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
      Makes a copy of the file and stores the file in the file manager.
      
    */
    function setfile( &$file )
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );
        
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
        Checks if the object is in the coherent state. This check can be applied
        after a get to check if the object data really exists.
        
    */

    function isCoherent()
    {
        $value = false;
        
        if ( $this->State_ == "Coherent" )
        {
            $value = true;
        }
        
        return $value;
    }

    /*!
      Retuns the folder for this eZVirtualFile object.
    */
    function &folder()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       $result = array();

       $query = ( "SELECT FolderID FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
       $this->Database->array_query( $result, $query );

       foreach ( $result as $folder )
       {
           return new eZVirtualFolder( $folder["FolderID"] );
       }
    }


    /*!
      Removes the file from every folders.
    */
    function removeFolders()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $query = ( "DELETE FROM eZFileManager_FileFolderLink WHERE FileID='$this->ID'" );
       $this->Database->query( $query );
    }

    /*!
      Adds a pagview to the file.
    */
    function addPageView( $pageView )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $pageView ) == "ezpageview" )
       {
           $this->dbInit();

           $pageViewID = $pageView->id();

           $this->Database->query( "LOCK TABLES eZFileManager_FilePageViewLink WRITE" );
           
           $query = ( "INSERT INTO eZFileManager_FilePageViewLink
           SET PageViewID='$pageViewID', FileID='$this->ID' " );
           
           $this->Database->query( $query );

           $this->Database->query( "UNLOCK TABLES" );
       }
    }

        /*!
      Adds read permission to the user.
    */
    function addReadPermission( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();
       
       $query = "INSERT INTO eZFileManager_FileReadGroupLink SET FileID='$this->ID', GroupID='$value'";
            
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
       
       $query = "INSERT INTO eZFileManager_FileWriteGroupLink SET FileID='$this->ID', GroupID='$value'";
            
       $this->Database->query( $query );
    }

    /*!
      Check if the user have read permissions.

    */
    function hasReadPermissions( $user=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->array_query( $userArrayID, "SELECT UserID FROM eZFileManager_File WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0]["UserID"] == $user->id() )
           {
               return true;
           }
           $groups = $user->groups();
       }

       $this->Database->array_query( $readPermissions, "SELECT GroupID FROM eZFileManager_FileReadGroupLink WHERE FileID='$this->ID'" );

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
      Check if the user have read permissions.

    */
    function hasWritePermissions( $user=false )
    {

       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->array_query( $userArrayID, "SELECT UserID FROM eZFileManager_File WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0]["UserID"] == $user->id() )
           {
               return true;
           }
           $groups = $user->groups();
       }
       
       $this->Database->array_query( $writePermissions, "SELECT GroupID FROM eZFileManager_FileWriteGroupLink WHERE FileID='$this->ID'" );

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
      Remove the read permissions from this eZVirtualFile object.

    */
    function removeReadPermissions()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZFileManager_FileReadGroupLink WHERE FileID='$this->ID'" );
    }


    /*!
      Remove the write permissions from this eZVirtualFile object.

    */
    function removeWritePermissions()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZFileManager_FileWriteGroupLink WHERE FileID='$this->ID'" );
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

       $this->Database->array_query( $readPermissions, "SELECT GroupID FROM eZFileManager_FileReadGroupLink WHERE FileID='$this->ID'" );

      
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

       $this->Database->array_query( $writePermissions, "SELECT GroupID FROM eZFileManager_FileWriteGroupLink WHERE FileID='$this->ID'" );

      
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
 