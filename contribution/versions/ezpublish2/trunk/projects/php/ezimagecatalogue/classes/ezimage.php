<?
// 
// $Id: ezimage.php,v 1.28 2001/02/02 12:27:20 ce Exp $
//
// Definition of eZImage class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 11:22:21 bf>
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
//!! eZImageCatalogue
//! The eZImage class hadles images in the image catalogue.
/*!
  Example code:
  \code
  // Fetch an uploaded file and store it in the imagecatalogue.
    $file = new eZImageFile();

    // userfile is the name of the input in the html form
    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // Get an image from the database and request a variation from it

    // gets an image from the eZProduct class
    // can also use code like $mainImage = new eZImage( 2 );
    // where 2 is the id of the image in the catalogue.
    $mainImage = $product->mainImage();
    
    if ( $mainImage )
    {
        $variation = $mainImage->requestImageVariation( 250, 250 );

        // set some template variables
        $t->set_var( "main_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "main_image_width", $variation->width() );
        $t->set_var( "main_image_height", $variation->height() );
        $t->set_var( "main_image_caption", $mainImage->caption() );
    }
    else
    {
        $t->set_var( "main_image", "" );    
    }
    
    \endcode
  \sa eZImageVariation eZImageVariationGroup eZImageFile
*/
/*!TODO
    $t in the example just pops out of nowhere, giving us no indication
    of where it was created or what connection it has with this class
 */
include_once( "classes/ezdb.php" );

include_once( "ezimagecatalogue/classes/ezimagevariation.php" );
include_once( "ezimagecatalogue/classes/ezimagevariationgroup.php" );

class eZImage
{
    /*!
      Constructs a new eZImage object.
    */
    function eZImage( $id="", $fetch=true )
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
      Stores a eZImage object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZImageCatalogue_Image SET
                                 Name='$this->Name',
                                 Caption='$this->Caption',
                                 Description='$this->Description',
                                 FileName='$this->FileName',
                                 UserID='$this->UserID',
                                 WritePermission='$this->WritePermission',
                                 ReadPermission='$this->ReadPermission',
                                 OriginalFileName='$this->OriginalFileName'
                                 " );
        }
        else
        {
            $variationArray =& $this->variations();

            foreach( $variationArray as $variation )
            {
                $variation->delete();
            }
            
            $this->Database->query( "UPDATE eZImageCatalogue_Image SET
                                 Name='$this->Name',
                                 Caption='$this->Caption',
                                 Description='$this->Description',
                                 FileName='$this->FileName',
                                 UserID='$this->UserID',
                                 WritePermission='$this->WritePermission',
                                 ReadPermission='$this->ReadPermission',
                                 OriginalFileName='$this->OriginalFileName'
                                 WHERE ID='$this->ID'
                                 " );
        }
        
        $this->ID = mysql_insert_id();

        $this->State_ = "Coherent";
    }

    /*!
      Delete the eZImageVariation object from the database and the filesystem.
    */
    function delete()
    {
        // Delete from the database
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $variationArray =& $this->variations();

            foreach( $variationArray as $variation )
            {
                $variation->delete();
            }

            $this->Database->query( "DELETE FROM eZImageCatalogue_Image WHERE ID='$this->ID'" );

            // Delete from the filesystem
            if ( file_exists ( $this->filePath( true ) ) )
            {
                unlink( $this->filePath( true ) );
            }
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
            $this->Database->array_query( $image_array, "SELECT * FROM eZImageCatalogue_Image WHERE ID='$id'" );
            if ( count( $image_array ) > 1 )
            {
                die( "Error: Image's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $image_array ) == 1 )
            {
                $this->ID =& $image_array[0][ "ID" ];
                $this->Name =& $image_array[0][ "Name" ];
                $this->Caption =& $image_array[0][ "Caption" ];
                $this->Description =& $image_array[0][ "Description" ];
                $this->FileName =& $image_array[0][ "FileName" ];
                $this->OriginalFileName =& $image_array[0][ "OriginalFileName" ];
                $this->UserID =& $image_array[0][ "UserID" ];
                $this->WritePermission =& $image_array[0][ "WritePermission" ];
                $this->ReadPermission =& $image_array[0][ "ReadPermission" ];

                $this->State_ = "Coherent";
                $ret = true;

            }
            else if( count( $image_array ) < 1 )
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }

        return $ret;
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
      Returns the id of the image.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->ID;
    }

    
    
    /*!
      Returns the name of the image.
    */
    function &name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the caption of the image.
    */
    function &caption()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Caption;
    }

    /*!
      Returns the description of the image.
    */
    function &description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }    

    /*!
      Returns the filename of the image.
    */
    function &fileName()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->FileName;
    }

    /*!
      Returns the original file name of the image.
    */
    function &originalFileName()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->OriginalFileName;
    }
    
    /*!
      Returns the path and filename to the original image.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function &filePath( $relative=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $relative == true )
       {
           $path = "ezimagecatalogue/catalogue/" .$this->FileName;
       }
       else
       {
           $path = "/ezimagecatalogue/catalogue/" .$this->FileName;
       }
       
       return $path;
    }

    /*!
      Returns the eZImageVariation object to a scaled version of the image.
      If the scaled version does not exist it is created.

      The required image variation group is also created if it does not exist.

      The path to the file is returned.
    */
    function &requestImageVariation( $width, $height )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $group = new eZImageVariationGroup();
       $variation = new eZImageVariation();

       if ( $group->groupExists( $width, $height ) )
       {

           $group->get( $group->groupExists( $width, $height ) );

           $ret =& $variation->requestVariation( $this, $group );
       }
       else
       {
           $group->setWidth( $width );
           $group->setHeight( $height );
           $group->store();

           $ret =& $variation->requestVariation( $this, $group );
       }

       return $ret;
    }

    /*!
      Returns the writePermission permission of the eZImage object.
    */
    function writePermission()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
      Returns the read permission of the eZImage object.
    */
    function readPermission()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $this->UserID != 0 )
        {
            $ret = new eZUser( $this->UserID );
        }
        
        return $ret;
    }

    
    /*!
      Sets the image name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the image caption.
    */
    function setCaption( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Caption = $value;
    }

    /*!
      Sets the image description.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the original imagename.
    */
    function setOriginalFileName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->OriginalFileName = $value;
    }
    
    /*!
      Makes a copy of the image and stores the image in the catalogue.
      
      If the image is not of the type .jpg or .gif the image is converted to .jpg.
    */
    function setImage( &$file )
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );
        
       if ( get_class( $file ) == "ezimagefile" )
       {
           print( "storing image" );

           $this->OriginalFileName = $file->name();

           $suffix = "";
           if ( ereg( "\\.([a-z]+)$", $this->OriginalFileName, $regs ) )
           {
               // We got a suffix, make it lowercase and store it
               $suffix = strtolower( $regs[1] );
           }

           $postfix = "";
           // Preserve jpg's
           if ( $suffix == "jpg" || $suffix == "jpeg" )
           {
               $postfix = ".jpg";
           }
           // Preserve gif's
           else if ( $suffix == "gif" )
           {
               $postfix = ".gif";
           }
           // Preserve png's
           else if ( $suffix == "png" )
           {
               $postfix = ".png";
           }

//             if ( ereg( "\\.png$", $this->OriginalFileName ) )
//             {
//                 $postfix = ".png";
//             }
           
           // the path to the catalogue

//           ( ereg( "\\.jpg$", $this->OriginalFileName ) || ereg( "\\.jpeg$", $this->OriginalFileName ));
           if ( $postfix != "" )
           {
               // Copy the file since we support it directly
               $file->copy( "ezimagecatalogue/catalogue/" . basename( $file->tmpName() ) . $postfix );
           }
           else
           {
               // Convert it to jpg.
               $file->convertCopy( "ezimagecatalogue/catalogue/" . basename( $file->tmpName() ) . ".jpg" );
               $postfix = ".jpg";
           }

           $this->FileName = basename( $file->tmpName() ) . $postfix;

           $name = $file->name();
           
           $this->OriginalFileName =& $name;
       }
    }

    /*!
      Sets the writePermission permission of the eZImage object.

      1 = User
      2 = Group
      3 = All
      
    */
    function setWritePermission( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
      Sets the read permission of the eZImage object.

      1 = User
      2 = Group
      3 = All
      
    */
    function setReadPermission( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
      Sets the user of the eZImage object.
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
        Checks if the object is in the coherent state. This check can be applied
        after a get to check if the object data really exists.
        
        /return
            Returns true if the object is coherent.
    */

    function isCoherent()
    {
        $value = false;
        
        if( $this->State_ == "Coherent" )
        {
            $value = true;
        }
        
        return $value;
    }

    /*!
      Returns the width of the image.
    */
    function &width()
    {
        $size = getimagesize( $this->filePath( true ) );
        
        return $size[0];
    }

    /*!
      Returns the height of the image.
    */
    function &height()
    {
        $size = getimagesize( $this->filePath( true ) );
        
        return $size[1];
    }

    /*!
      Returns every variation to a image as a array of eZVariation objects.
    */
    function &variations()
    {
        $this->dbInit();

        $variationArray = array();

        $this->Database->array_query( $variationArray, "SELECT ID
                                                        FROM eZImageCatalogue_ImageVariation
                                                        WHERE ImageID='$this->ID'" );

        foreach ( $variationArray as $variation )
        {
            $returnArray[] =& new eZImageVariation( $variation["ID"] );
        }

        return $returnArray;
    }

    /*!
      Returns the image's category.
    */
    function category( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $res, "SELECT CategoryID FROM
                                            eZImageCatalogue_ImageCategoryLink
                                            WHERE ImageID='$this->ID'" );
       $category = false;
       if ( count( $res ) == 1 )
       {
           $category = new eZImageCategory( $res[0]["CategoryID"] );
       }

       return $category;
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
    var $Caption;
    var $Description;
    var $FileName;
    var $OriginalFileName;
    var $ReadPermission;
    var $WritePermission;
    var $UserID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
