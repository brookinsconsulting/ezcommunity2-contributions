<?
// 
// $Id: imageedit.php,v 1.6 2000/09/27 07:08:28 bf-cvs Exp $
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

// include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );
include_once( $DOC_ROOT . "/classes/ezproduct.php" );
include_once( $DOC_ROOT . "/classes/ezoption.php" );

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
}

// store the image definition
if ( $Action == "StoreDef" )
{
    print( $ThumbnailImageID );
    print( $MainImageID );
    
    $product = new eZProduct( $ProductID );

    $thumbnail = new eZImage( $ThumbnailImageID );
    $main = new eZImage( $MainImageID );

    $product->setMainImage( $main );
    $product->setThumbnailImage( $thumbnail );    

    header( "Location: /trade/productedit/imagelist/" . $ProductID . "/" );
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/imageedit/",
                     $DOC_ROOT . "/admin/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );

if ( $Action == "Edit" )
{
    $product = new eZProduct( $ProductID );
    $image = new eZImage( $ImageID );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "name_value", $image->name() );
    $t->set_var( "caption_value", $image->caption() );
    $t->set_var( "action_value", "Update" );
}

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );
$t->set_var( "product_id", $product->id() );



$t->pparse( "output", "image_edit_page" );

?>
