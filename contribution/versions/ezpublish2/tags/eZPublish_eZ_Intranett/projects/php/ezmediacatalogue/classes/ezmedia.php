<?php
// 
// $Id: ezmedia.php,v 1.3 2001/07/29 23:31:08 kaid Exp $
//
// Definition of eZMedia class
//
// Created on: <21-Sep-2000 11:22:21 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
//!! eZMediaCatalogue
//! The eZMedia class hadles medias in the media catalogue.
/*!
  Example code:
  \code
  // Fetch an uploaded file and store it in the mediacatalogue.
    $file = new eZMediaFile();

    // userfile is the name of the input in the html form
    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $media = new eZMedia();
        $media->setName( $Name );
        $media->setCaption( $Caption );

        $media->setMedia( $file );
        
        $media->store();
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // Get an media from the database and request a variation from it

    // gets an media from the eZProduct class
    // can also use code like $mainMedia = new eZMedia( 2 );
    // where 2 is the id of the media in the catalogue.
    $mainMedia = $product->mainMedia();
    
    if ( $mainMedia )
    {
        $variation = $mainMedia->requestMediaVariation( 250, 250 );

        // set some template variables
        $t->set_var( "main_media_uri", "/" . $variation->mediaPath() );
        $t->set_var( "main_media_width", $variation->width() );
        $t->set_var( "main_media_height", $variation->height() );
        $t->set_var( "main_media_caption", $mainMedia->caption() );
    }
    else
    {
        $t->set_var( "main_media", "" );    
    }
    
    \endcode
  \sa eZMediaVariation eZMediaVariationGroup eZMediaFile
*/
/*!TODO
    $t in the example just pops out of nowhere, giving us no indication
    of where it was created or what connection it has with this class
 */
include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

include_once( "ezmediacatalogue/classes/ezmediatype.php" );

class eZMedia
{
    /*!
      Constructs a new eZMedia object.
    */
    function eZMedia( $id="" )
    {
        $this->PhotographerID = 0;
        $this->NewMedia = false;
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZMedia object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );
        
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $caption = $db->escapeString( $this->Caption );
        $filename = $db->escapeString( $this->FileName );
        $originalfilename = $db->fieldName( $this->OriginalFileName );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZMediaCatalogue_Media" );

            $timeStamp =& eZDateTime::timeStamp( true );

            $this->ID = $db->nextID( "eZMediaCatalogue_Media", "ID" );
            $res = $db->query( "INSERT INTO eZMediaCatalogue_Media
                                           ( ID,
                                             Name,
                                             Caption,
                                             Description,
                                             FileName,
                                             UserID,
                                             WritePermission,
                                             ReadPermission,
                                             OriginalFileName,
                                             PhotographerID,
                                             Created )
                                    VALUES ( '$this->ID',
                                             '$name',
                                             '$caption',
                                             '$description',
                                             '$filename',
                                             '$this->UserID',
                                             '$this->WritePermission',
                                             '$this->ReadPermission',
                                             '$originalfilename',
                                             '$this->PhotographerID',
                                             '$timeStamp' )");
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZMediaCatalogue_Media SET
                                 Name='$name',
                                 Caption='$caption',
                                 Description='$description',
                                 FileName='$filename',
                                 UserID='$this->UserID',
                                 WritePermission='$this->WritePermission',
                                 ReadPermission='$this->ReadPermission',
                                 OriginalFileName='$originalfilename',
                                 PhotographerID='$this->PhotographerID'
                                 WHERE ID='$this->ID'
                                 " );
        }
        
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Delete the eZMediaVariation object from the database and the filesystem.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZMediaCatalogue_Media WHERE ID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_MediaPermission WHERE ObjectID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_MediaCategoryLink WHERE MediaID='$this->ID'" );
            $db->query( "DELETE FROM eZMediaCatalogue_MediaCategoryDefinition WHERE MediaID='$this->ID'" );

            // Delete from the filesystem
            if ( eZFile::file_exists( $this->filePath( true ) ) )
            {
                eZFile::unlink( $this->filePath( true ) );
            }
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
            $db->array_query( $media_array, "SELECT * FROM eZMediaCatalogue_Media WHERE ID='$id'" );
            if( count( $media_array ) > 0 )
            {
                if ( count( $media_array ) > 1 )
                {
                    print( "<br /><b>Error: Media's with the same ID was found in the database. This shouldent happen.</b><br />" );
                }
                $this->ID =& $media_array[0][$db->fieldName("ID")];
                $this->Name =& $media_array[0][$db->fieldName("Name")];
                $this->Caption =& $media_array[0][$db->fieldName("Caption")];
                $this->Description =& $media_array[0][$db->fieldName("Description")];
                $this->FileName =& $media_array[0][$db->fieldName("FileName")];
                $this->OriginalFileName =& $media_array[0][$db->fieldName("OriginalFileName")];
                $this->UserID =& $media_array[0][$db->fieldName("UserID")];
                $this->WritePermission =& $media_array[0][$db->fieldName("WritePermission")];
                $this->ReadPermission =& $media_array[0][$db->fieldName("ReadPermission")];
                $this->PhotographerID =& $media_array[0][$db->fieldName("PhotographerID")];

                $ret = true;
            }
        }

        return $ret;
    }
    
    /*!
        \static
      Fetches an media from the database if one with the same "original filename" is found.
    */
    function getByOriginalFileName( $id = "" )
    {
        $db =& eZDB::globalDatabase();
        $ret =& new eZMedia();
        if ( $id != "" )
        {
            $db->array_query( $media_array, "SELECT * FROM eZMediaCatalogue_Media WHERE OriginalFileName='$id'" );
            if( count( $media_array ) > 0 )
            {
                if ( count( $media_array ) > 1 )
                {
                    print( "<br /><b>Error: Media's with the same  was found in the database. This shouldn't happen.</b><br />" );
                }
                $ret =& new eZMedia( $media_array[0][$db->fieldName("ID")] );
            }
        }
        return $ret;
    }

    /*!
      Returns true if the media is assigned to the category given
      as argument. False if not.
    */
    function existsInCategory( $category )
    {
       $ret = false;
       if ( get_class( $category ) == "ezmediacategory" )
       {
           $db =& eZDB::globalDatabase();
           $catID = $category->id();

           $db->array_query( $ret_array, "SELECT ID FROM eZMediaCatalogue_MediaCategoryLink
                                    WHERE MediaID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           } 
       }
       return $ret;
    }
    
    /*!
      Set's the medias defined category. This is the main category for the media.
      Additional categories can be added with eZMediaCategory::addMedia();
    */
    function setCategoryDefinition( $value )
    {
        if ( get_class( $value ) == "ezmediacategory" )
        {
            $db =& eZDB::globalDatabase();

            $db->begin( );

            $categoryID = $value->id();

            $db->query( "DELETE FROM eZMediaCatalogue_MediaCategoryDefinition
                                     WHERE MediaID='$this->ID'" );

            $db->lock( "eZMediaCatalogue_MediaCategoryDefinition" );

            $nextID = $db->nextID( "eZMediaCatalogue_MediaCategoryDefinition", "ID" );

            $query = "INSERT INTO eZMediaCatalogue_MediaCategoryDefinition ( ID, CategoryID, MediaID )
                      VALUES ( '$nextID', '$categoryID', '$this->ID' )";
            
            $res = $db->query( $query );

            $db->unlock();
    
            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();            
        }
    }

    /*!
      Returns the media's definition category.
    */
    function categoryDefinition( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                                            eZMediaCatalogue_MediaCategoryDefinition
                                            WHERE MediaID='$this->ID'" );

        $category = false;
        if ( count( $res ) == 1 )
        {
            $category = new eZMediaCategory( $res[0][$db->fieldName("CategoryID")] );
        }
        else
        {
            return -1;
        }

        return $category;
    }

    /*!
      Get all the medias that is not assigned to a category.

      The medias are returned as an array of eZMedia objects.
     */
    function getUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $mediaArray, "SELECT Media.ID, Link.MediaID
                                        FROM eZMediaCatalogue_Media AS Media
                                        LEFT JOIN  eZMediaCatalogue_MediaCategoryLink AS Link
                                        ON Media.ID=Link.MediaID
                                        WHERE MediaID IS NULL" );

        foreach( $mediaArray as $media )
        {
            $returnArray[] = new eZMedia( $media[$db->fieldName("ID")] );
        }

        return $returnArray;
    }

    /*!
      Get the total count of all the unassigned medias.
     */
    function countUnassigned()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $media, "SELECT COUNT(Media.ID) as Count, Link.MediaID
                                        FROM eZMediaCatalogue_Media AS Media
                                        LEFT JOIN  eZMediaCatalogue_MediaCategoryLink AS Link
                                        ON Media.ID=Link.MediaID
                                        WHERE MediaID IS NULL
                                        GROUP By MediaID" );
        return $media[$db->fieldName("Count")];
    }

    /*!
      Check what read permission the user have to this eZVirtualFile object.

      Returns:
      User - if the user owns the file
      Group - if the user is member of the group
      All - if the file can be read by everybody
      False - if the user don't have access
    */
    function checkReadPermission( &$currentUser )
    {
        $ret = false;

        $read = $this->readPermission();
        
        if ( get_class( $currentUser ) == "ezuser" )
        {
            if ( $read == "User" )
            {
                if ( $this->UserID != 0 )
                {
                    if ( $currentUser->id() == $this->UserID )
                    {
                        $ret = "User";
                    }
                    else
                    {
                        return $ret;
                    }
                }
            }
            else if ( $read == "Group" )
            {
                if ( $this->UserID != 0 )
                {
                    $currentGroups =& $currentUser->groups();
                    foreach( $currentGroups as $Groups )
                    {
                        $user = new eZUser( $this->UserID );
                        $userGroups =& $user->groups();
                            
                        foreach( $userGroups as $userGroup )
                        {
                            if ( $Groups->id() == $userGroup->id() )
                            {
                                $ret = "Group";
                            }
                            else
                            {
                                return $ret;
                            }
                        }
                    }
                }
            }
            else if ( $read == "All" )
            {
                $ret = "Group";
            }
        }
        else
        {
            if ( $read == "All" )
            {
                $ret = "All";
            }
        }

        return $ret;

    }

    /*!
      Check what write permission the user have to this eZVirtualFile object.

      Returns:
      User - if the user owns the file
      Group - if the user is member of the group
      All - if the file can be write by everybody
      False - if the user don't have access
    */
    function checkWritePermission( &$currentUser )
    {
        $ret = false;
        
        $write = $this->writePermission();
        
        if ( get_class( $currentUser ) == "ezuser" )
        {

            if ( $write == "User" )
            {
                if ( $this->UserID != 0 )
                {
                    if ( $currentUser->id() == $this->UserID )
                    {
                        $ret = "User";
                    }
                    else
                    {
                        return $ret;
                    }
                }
            }
            else if ( $write == "Group" )
            {
                if ( $this->UserID != 0 )
                {
                    $currentGroups =& $currentUser->groups();
                    foreach( $currentGroups as $Groups )
                    {
                        $user = new eZUser( $this->UserID );
                        $userGroups =& $user->groups();
                            
                        foreach( $userGroups as $userGroup )
                        {
                            if ( $Groups->id() == $userGroup->id() )
                            {
                                $ret = "Group";
                            }
                            else
                            {
                                return $ret;
                            }
                        }
                    }
                }
            }
            else if ( $write == "All" )
            {
                $ret = "Group";
            }
        }
        else
        {
            if ( $write == "All" )
            {
                $ret = "All";
            }
        }

        return $ret;
    }    
    
    
    /*!
      Returns the id of the media.
    */
    function id()
    {
        return $this->ID;
    }

    
    
    /*!
      Returns the name of the media.
    */
    function &name( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the caption of the media.
    */
    function &caption( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Caption );
       else
           return $this->Caption;
    }

    /*!
      Returns the description of the media.
    */
    function &description( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
    }    

    /*!
      Returns the filename of the media.
    */
    function &fileName()
    {
        return $this->FileName;
    }

    /*!
      Returns the original file name of the media.
    */
    function &originalFileName()
    {
        return $this->OriginalFileName;
    }
    
    function &fileExists( $relative=false )
    {
       if ( $relative == true )
       {
           $path = "ezmediacatalogue/catalogue/" .$this->FileName;
       }
       else
       {
           $path = "/ezmediacatalogue/catalogue/" .$this->FileName;
       }
       
       $relPath = "ezmediacatalogue/catalogue/" . $this->FileName;

       return eZFile::file_exists( $relPath ) and is_file( $relPath );
    }

    /*!
      Returns the path and filename to the original media.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function &filePath( $relative=false )
    {
       $relPath = "ezmediacatalogue/catalogue/" . $this->FileName;
       
       if ( $relative == true )
       {
           $path = "ezmediacatalogue/catalogue/" . $this->FileName;
       }
       else
       {
           $path = "/ezmediacatalogue/catalogue/" . $this->FileName;
       }
       
       if ( !eZFile::file_exists( $relPath ) or !is_file( $relPath ) )
       {
           $path = "ezmediacatalogue/admin/medias/failedmedia.gif";
           if ( !$relative )
               $path = "/$path";
       }
       return $path;
    }

    /*!
      Same as filePath()
     */
    function &mediaPath( $relative=false )
    {
        return $this->filePath( $relative );
    }    

    /*!
      Returns the writePermission permission of the eZMedia object.
    */
    function writePermission()
    {
       switch( $this->WritePermission )
       {
           case 1:
           {
               $ret = "User";
           }
           break;

           case 2:
           {
               $ret = "Group";
           }
           break;
           
           case 3:
           {
               $ret = "All";
           }
           break;

           default:
               $ret = "User";
       }

       return $ret;
    }

    /*!
      Returns the read permission of the eZMedia object.
    */
    function readPermission()
    {
       switch( $this->ReadPermission )
       {
           case 1:
           {
               $ret = "User";
           }
           break;

           case 2:
           {
               $ret = "Group";
           }
           break;
           
           case 3:
           {
               $ret = "All";
           }
           break;

           default:
               $ret = "User";
       }
       
       return $ret;
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
      Sets the media name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the media caption.
    */
    function setCaption( $value )
    {
        $this->Caption = $value;
    }

    /*!
      Sets the media description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the original medianame.
    */
    function setOriginalFileName( $value )
    {
        $this->OriginalFileName = $value;
    }
    
    /*!
      Returns true if the file is a valid media.
    */
    function checkMedia( &$file )
    {
       if ( get_class( $file ) == "ezmediafile" )
       {
           $name = $file->tmpName();
           if ( !eZFile::file_exists( $name ) or !is_file( $name ) )
               return false;
           return true;
       }
       return false;
    }

    /*!
      Makes a copy of the media and stores the media in the catalogue.
      
      If the media is not of the type .jpg or .gif the media is converted to .jpg.
    */
    function setMedia( &$file )
    {
       if ( get_class( $file ) == "ezmediafile" )
       {
           $this->OriginalFileName = $file->name();
           $tmpname = $file->tmpName();
           if ( !eZFile::file_exists( $tmpname ) or !is_file( $tmpname ) )
               return false;

           $info = eZMediaFile::information( $this->OriginalFileName );
           $suffix = $info["suffix"];
           $postfix = $info["dot-suffix"];

           if ( $postfix != "" )
           {
               // Copy the file since we support it directly
               $file->copy( "ezmediacatalogue/catalogue/" . basename( $file->tmpName() ) . $postfix );
           }
           else
           {
               $postfix = "";
           }

           $this->FileName = basename( $file->tmpName() ) . $postfix;

           $name = $file->name();
           
           $this->OriginalFileName =& $name;
           
           return true;
       }
       return false;
    }

    /*!
      Sets the writePermission permission of the eZMedia object.

      1 = User
      2 = Group
      3 = All
      
    */
    function setWritePermission( $value )
    {
       switch ( $value )
       {
           case "User":
           {
               $value = 1;
           }
           break;
           
           case "Group":
           {
               $value = 2;
           }
           break;
           
           case "All":
           {
               $value = 3;
           }
           break;
           
           default:
               $value = 1;
       }
       
       $this->WritePermission = $value;
    }

    /*!
      Sets the read permission of the eZMedia object.

      1 = User
      2 = Group
      3 = All
      
    */
    function setReadPermission( $value )
    {
       switch ( $value )
       {
           case "User":
           {
               $value = 1;
           }
           break;
           
           case "Group":
           {
               $value = 2;
           }
           break;
           
           case "All":
           {
               $value = 3;
           }
           break;
           
           default:
               $value = 1;
       }
       
       $this->ReadPermission = $value;
    }

    /*!
      Sets the user of the eZMedia object.
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
      Returns the media's categories.
    */
    function categories()
    {
        $db =& eZDB::globalDatabase();

        $res = array();
        $db->array_query( $res, "SELECT CategoryID, MediaID FROM
                                 eZMediaCatalogue_MediaCategoryLink
                                 WHERE MediaID='$this->ID'" );
        $category = false;

        if ( count( $res ) > 0 )
            $category = array();
    
        for ( $i = 0; $i < count( $res ); $i++ )
        {
            array_push( $category, $res[$i][$db->fieldName("CategoryID")] );
        }

        return $category;
    }

    /*!
      Adds read permission to the user.
    */
    function addReadPermission( $value )
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );
        $db->lock( "eZMediaCatalogue_MediaReadGroupLink" );
        $nextID = $db->nextID( "eZMediaCatalogue_MediaReadGroupLink", "ID" );

        $query = "INSERT INTO eZMediaCatalogue_MediaReadGroupLink ( ID, MediaID, GroupID ) VALUES ( '$nextID', '$this->ID', '$value' )";
        
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
        $db->lock( "eZMediaCatalogue_MediaWriteGroupLink" );
        $nextID = $db->nextID( "eZMediaCatalogue_MediaWriteGroupLink", "ID" );
        
        $query = "INSERT INTO eZMediaCatalogue_MediaWriteGroupLink ( ID, MediaID, GroupID ) VALUES ( '$nextID', '$this->ID', '$value' )";
        
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
    function hasReadPermissions( $user = false )
    {
        $db =& eZDB::globalDatabase();
   
        
       $db->array_query( $userArrayID, "SELECT UserID FROM eZMediaCatalogue_Media WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0][$db->fieldName("UserID")] == $user->id() )
           {
               return true;
           }
           
           $groups = $user->groups();
       }
       $db->array_query( $readPermissions, "SELECT ReadPermission FROM eZMediaCatalogue_Media WHERE ID='$this->ID'" );

       for ( $i = 0; $i < count( $readPermissions ); $i++ )
       {
           if ( $readPermissions[$i][$db->fieldName("GroupID")] == 0 )
           {
               return true;
           }
           else
           {
               if ( count( $groups ) > 0 )
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

       $db->array_query( $userArrayID, "SELECT UserID FROM eZMediaCatalogue_Media WHERE ID='$this->ID'" );

       if ( $user )
       {
           if ( $userArrayID[0][$db->fieldName("UserID")] == $user->id() )
           {
               return true;
           }
           
           $groups = $user->groups();
       }

       $db->array_query( $writePermissions, "SELECT GroupID FROM eZMediaCatalogue_MediaWriteGroupLink WHERE MediaID='$this->ID'" );

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

       $db->array_query( $readPermissions, "SELECT GroupID FROM eZMediaCatalogue_MediaReadGroupLink WHERE MediaID='$this->ID'" );
      
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

       $db->array_query( $writePermissions, "SELECT GroupID FROM eZMediaCatalogue_MediaWriteGroupLink WHERE MediaID='$this->ID'" );
      
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

        $db->query( "DELETE FROM eZMediaCatalogue_MediaWriteGroupLink WHERE FolderID='$this->ID'" );
    }


    /*!
      Remove the write permissions from this eZVirtualFolder object.

    */
    function removeWritePermissions()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZMediaCatalogue_MediaWriteGroupLink WHERE FolderID='$this->ID'" );
    }

    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $media is the ID of the media.
     */
    function isOwner( $user, $media )
    {
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZMediaCatalogue_Media WHERE ID='$media'");
        $userID = $res[$db->fieldName("UserID")];
        if(  $userID == $user->id() )
            return true;

        return false;
    }

    /*!
      Sets the photographer of the media
    */
    function setPhotographer( $author )
    {
        if ( get_class( $author ) == "ezauthor" )
            $this->PhotographerID = $author->id();
        else if ( is_numeric( $author ) )
            $this->PhotographerID = $author;        
    }

    /*!
      Returns the photographer og the media
    */
    function photographer()
    {
        return new eZAuthor( $this->PhotographerID );
    }

    /*!
      Sets the links type.
    */
    function setType( $type )
    {
        if ( get_class( $type ) == "ezmediatype" )
        {
            $db =& eZDB::globalDatabase();
            
            $db->begin();
            
            $typeID = $type->id();
            
            $res[] = $db->query( "DELETE FROM eZMediaCatalogue_AttributeValue
                                     WHERE MediaID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZMediaCatalogue_TypeLink
                                     WHERE MediaID='$this->ID'" );
            
            $db->lock( "eZMediaCatalogue_TypeLink" );
            
            $nextID = $db->nextID( "eZMediaCatalogue_TypeLink", "ID" );
            
            $query = "INSERT INTO eZMediaCatalogue_TypeLink
                      (ID, TypeID, MediaID)
                      VALUES
                      ('$nextID',
                       '$typeID',
                       '$this->ID')";
            
            $res[] = $db->query( $query );
            
            $db->unlock();
            
            if ( in_array( false, $res ) )
                $db->rollback();
            else
                $db->commit();
            
        }
    }

    /*!
      Returns the link's type.
    */
    function type()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT TypeID FROM
                                 eZMediaCatalogue_TypeLink WHERE MediaID='$this->ID'" );

        $type = false;
       
        if ( count( $res ) == 1 )
        {
            $type = new eZMediaType( $res[0][$db->fieldName("TypeID")] );
        }

        return $type;
    }
    
    /*!
      Removes the links type definition.
    */
    function removeType()
    {
        $db =& eZDB::globalDatabase();

        // delete values
        $db->query( "DELETE FROM eZMediaCatalogue_AttributeValue WHERE MediaID='$this->ID'" );

        $db->query( "DELETE FROM eZMediaCatalogue_TypeLink WHERE MediaID='$this->ID'" );
            
    }

    function &attributeString( )
    {
        $type =& $this->type();
        $attributes = $type->attributes();
    
        foreach( $attributes as $attribute )
        {
            $attString .= " " . $attribute->name() . "=\"" . $attribute->value( $this ) . "\"";
        }
        return $attString;
    }

    
    var $ID;
    var $Name;
    var $Caption;
    var $Description;
    var $FileName;
    var $OriginalFileName;
    var $ReadPermission;
    var $WritePermission;
    var $UserID;
    var $PhotographerID;
}

?>
