<?
// 
// $Id: ezimagevariation.php,v 1.6 2000/11/01 09:32:55 ce-cvs Exp $
//
// Definition of eZImageVariation class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 17:28:57 bf>
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
      Fetches the object information from the database.
    */
    function getByGroupAndImage( $groupID, $imageID )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $groupID != "" )
        {
            $this->Database->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariation WHERE VariationGroupID='$groupID' AND ImageID='$imageID'" );
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
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }

        return $ret;
    }
    
    /*!
      Returns true if the variation exists, if it does not exist it is created.

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
                $imageFile = new eZImageFile();
                $imageFile->getFile( $image->filePath( true ) );
                $imageFile->setType( "image/jpeg" );

                $postfix = ".jpg";
                if ( ereg( "gif$", $image->originalFileName() ) )
                {
                    $postfix = ".gif";
                    $imageFile->setType( "image/gif" );
                }

//                if ( ereg( "png$", $image->originalFileName() ) )
//                {
//                    $postfix = ".png";
//                    $imageFile->setType( "image/png" );
//                }
                
                $dest = "ezimagecatalogue/catalogue/variations/" . $image->id() . "-" . $variationGroup->width() . "x". $variationGroup->height() . $postfix;

                $imageFile->scaleCopy( $dest, $variationGroup->width(), $variationGroup->height() );

                $size = GetImageSize( $dest );
                
                $variation->setWidth( $size[0] );
                $variation->setHeight( $size[1] );
                $variation->setImagePath( $dest );
                $variation->setImageID(  $image->id() );                
                $variation->setVariationGroupID(  $variationGroup->id() );

                $variation->store();

                $ret =& $variation;
            }
        }
        
        return $ret;
    }

    /*!
      Returns the ImageID
    */
    function imageID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ImageID;
    }

    /*!
      Returns the VariationGroupID
    */
    function variationGroupID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->VariationGroupID; 
    }

    /*!
      Returns the variation path
    */
    function imagePath()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ImagePath; 
    }

    /*!
      Returns the image width
    */
    function width()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Width; 
    }

    /*!
      Returns the image height
    */
    function height()
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
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }    

    var $ID;
    var $ImageID;
    var $VariationGroupID;
    var $ImagePath;
    var $Width;
    var $Height;
}

?>
