<?
// 
// $Id: ezimagevariation.php,v 1.17 2001/03/26 19:27:08 bf Exp $
//
// Definition of eZImageVariation class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 17:28:57 bf>
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
//! The eZImageVariation class hadles images variations.
/*!

  \sa eZImage eZImageVariationGroup
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezimagefile.php" );
//  include_once( "classes/ezimage.php" );
//  include_once( "classes/ezimagevariationgroup.php" );

class eZImageVariation
{
    /*!
      Constructs a new eZImageVariation object.
    */
    function eZImageVariation( $id="", $fetch=true )
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
      Stores a eZImageVariation object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZImageCatalogue_ImageVariation SET
                                 ImageID='$this->ImageID',
                                 VariationGroupID='$this->VariationGroupID',
                                 Width='$this->Width',
                                 Height='$this->Height',
                                 ImagePath='$this->ImagePath'
                                 " );
        
        $this->ID = mysql_insert_id();

        $this->State_ = "Coherent";
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariation WHERE ID='$id'" );
            if ( count( $image_variation_array ) > 1 )
            {
                die( "Error: ImageVariations's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $image_variation_array ) == 1 )
            {
                $this->ID =& $image_variation_array[0][ "ID" ];
                $this->ImageID =& $image_variation_array[0][ "ImageID" ];
                $this->VariationGroupID =& $image_variation_array[0][ "VariationGroupID" ];
                $this->ImagePath =& $image_variation_array[0][ "ImagePath" ];
                $this->Width =& $image_variation_array[0][ "Width" ];
                $this->Height =& $image_variation_array[0][ "Height" ];

                $this->State_ = "Coherent";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
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
            $this->Database->query( "DELETE FROM eZImageCatalogue_ImageVariation WHERE ID='$this->ID'" );
        }

        // Delete from the filesystem
        if ( file_exists( $this->imagePath( true ) ) )
        {
            unlink( $this->imagePath( true ) );
        }
    }


    /*!
      Fetches the object information from the database.
    */
    function &getByGroupAndImage( $groupID, $imageID )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $groupID != "" )
        {
            $this->Database->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariation WHERE VariationGroupID='$groupID' AND ImageID='$imageID'" );
            if ( count( $image_variation_array ) > 0 )
            {
                $this->ID =& $image_variation_array[0][ "ID" ];
                $this->ImageID =& $image_variation_array[0][ "ImageID" ];
                $this->VariationGroupID =& $image_variation_array[0][ "VariationGroupID" ];
                $this->ImagePath =& $image_variation_array[0][ "ImagePath" ];
                $this->Width =& $image_variation_array[0][ "Width" ];
                $this->Height =& $image_variation_array[0][ "Height" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
            if ( !file_exists( $this->ImagePath ) or !is_file( $this->ImagePath ) )
                $ret = false;
        }
        else
        {
            $this->State_ = "Dirty";
        }

        return $ret;
    }
    
    /*!
      Returns the variation if the variation exists, if it does not exist it is created.

      False is returned if the variation could not be created.
    */
    function &requestVariation( &$image, &$variationGroup )
    {
        $ret = false;
        if ( ( get_class( $image ) == "ezimage" ) && ( get_class( $variationGroup ) == "ezimagevariationgroup" ) )
        {
            $variation = new eZImageVariation();
            
            if ( $variation->getByGroupAndImage( $variationGroup->id(), $image->id() ) )
            {
                $ret =& $variation;
            }
            else
            {
                if ( !$image->fileExists( true ) )
                    return eZImageVariation::createErrorImage();
                $imageFile = new eZImageFile();
                $imageFile->getFile( $image->filePath( true ) );
                $imageFile->setType( "image/jpeg" );

                $info = eZImageFile::information( $image->originalFileName(), true );
                $suffix = $info["suffix"];
                $postfix = $info["dot-suffix"];
                $imageFile->setType( $info["image-type"] );

                $dest = "ezimagecatalogue/catalogue/variations/" . $image->id() . "-" . $variationGroup->width() . "x". $variationGroup->height() . $postfix;

                $result = $imageFile->scaleCopy( $dest, $variationGroup->width(), $variationGroup->height() );
                if ( !is_bool( $result ) and $result == "locked" )
                {
                    if ( $variation->getByGroupAndImage( $variationGroup->id(), $image->id() ) )
                    {
                        $ret =& $variation;
                    }
                    else
                    {
                        return eZImageVariation::createErrorImage();
                        print( "<br><b>Timeout when retrieveing variation</b><br>" );
                    }
                }
                else if ( $result )
                {
                    if ( !file_exists( $dest ) or !is_file( $dest ) )
                        return eZImageVariation::createErrorImage();
                    $size = GetImageSize( $dest );
                    if ( !$size )
                        return eZImageVariation::createErrorImage();

                    $variation->setWidth( $size[0] );
                    $variation->setHeight( $size[1] );
                    $variation->setImagePath( $dest );
                    $variation->setImageID(  $image->id() );                
                    $variation->setVariationGroupID(  $variationGroup->id() );

                    $variation->store();

                    $ret =& $variation;
                }
                else
                    return eZImageVariation::createErrorImage();
            }
        }
        
        return $ret;
    }

    /*!
      Returns the ImageID
    */
    function &imageID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ImageID;
    }

    /*!
      Returns the VariationGroupID
    */
    function &variationGroupID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->VariationGroupID; 
    }

    /*!
      Returns the variation path
    */
    function &imagePath()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ImagePath; 
    }

    /*!
      Returns the image width
    */
    function &width()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Width; 
    }

    /*!
      Returns the image height
    */
    function &height()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Height; 
    }

    /*!
      Sets the ImageID
    */
    function setImageID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ImageID = $value;
    }

    /*!
      Sets the VariationGroupID
    */
    function setVariationGroupID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->VariationGroupID = $value;
    }
    
    /*!
      Sets the image path
    */
    function setImagePath( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ImagePath = $value;
    }

    /*!
      Sets the width
    */
    function setWidth( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Width = $value;
    }

    /*!
      Sets the height
    */
    function setHeight( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Height = $value;
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

    function createErrorImage()
    {
        $imageVar = new eZImageVariation();
        $imageVar->setImagePath( "/ezimagecatalogue/admin/images/failedimage.gif" );
        $imageVar->ImageID = -1;
        $imageVar->setWidth( 120 );
        $imageVar->setHeight( 40 );
        return $imageVar;
    }
    
    var $ID;
    var $ImageID;
    var $VariationGroupID;
    var $ImagePath;
    var $Width;
    var $Height;
}

?>
