<?
// 
// $Id: imageedit.php,v 1.8 2000/10/29 12:40:41 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 10:32:36 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

// include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );

if ( $Action == "Insert" )
{
    $file = new eZImageFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $product = new eZProduct( $ProductID );
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $product->addImage( $image );

        eZLog::writeNotice( "Picture added to product: $ProductID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZImageFile();
    
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $product = new eZProduct( $ProductID );

        $oldImage = new eZImage( $ImageID );
        $product->deleteImage( $oldImage );
        
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $product->addImage( $image );
    }
    else
    {
        $image = new eZImage( $ImageID );
        $image->setName( $Name );
        $image->setCaption( $Caption );
        $image->store();
    }
    
    header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $product = new eZProduct( $ProductID );
    $image = new eZImage( $ImageID );
        
    $product->deleteImage( $image );
    
    header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
    exit();    
}

// store the image definition
if ( $Action == "StoreDef" )
{
    $product = new eZProduct( $ProductID );

    if ( isset( $ThumbnailImageID ) &&  ( $ThumbnailImageID != 0 ) &&  ( $ThumbnailImageID != "" ) )
    {
        $thumbnail = new eZImage( $ThumbnailImageID );
        $product->setThumbnailImage( $thumbnail );
    }

    if ( isset( $MainImageID ) &&  ( $MainImageID != 0 ) &&  ( $MainImageID != "" ) )
    {
        $main = new eZImage( $MainImageID );
        $product->setMainImage( $main );
    }
    
    if ( isset( $NewImage ) )
    {
        print( "new image" );
        header( "Location: /trade/productedit/imageedit/new/$ProductID/" );
        exit();
    }

    header( "Location: /trade/productedit/edit/" . $ProductID . "/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );


$t->set_block( "image_edit_page", "image_tpl", "image" );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "image", "" );

if ( $Action == "Edit" )
{
    $product = new eZProduct( $ProductID );
    $image = new eZImage( $ImageID );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "name_value", $image->name() );
    $t->set_var( "caption_value", $image->caption() );
    $t->set_var( "action_value", "Update" );


    $t->set_var( "image_alt", $image->caption() );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_src", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    $t->set_var( "image_file_name", $image->originalFileName() );
    $t->parse( "image", "image_tpl" );
}

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );
$t->set_var( "product_id", $product->id() );

$t->pparse( "output", "image_edit_page" );

?>
