<?php
// 
// $Id: imageedit.php,v 1.2 2001/09/07 17:33:55 fh Exp $
//
// Created on: <21-Sep-2000 10:32:36 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

include_once( "classes/ezimagefile.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezuser/classes/ezauthor.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZRfpMain", "Language" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );

if ( $Action == "Insert" )
{
    $file = new eZImageFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $rfp = new eZRfp( $RfpID );
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );
        if ( trim( $NewPhotographerName ) != "" &&
             trim( $NewPhotographerEmail ) != ""
             )
        {
            $author = new eZAuthor( );
            $author->setName( $NewPhotographerName );
            $author->setEmail( $NewPhotographerEmail );
            $author->store();
            $image->setPhotographer( $author );
        }
        else
        {
            $image->setPhotographer( $PhotoID );
        }

        if( $image->checkImage( $file ) && $image->setImage( $file ) )
        {
            $image->store();
            $rfp->addImage( $image );
            if ( count( $rfp->images() ) == 1 )
            {
                $rfp->setThumbnailImage( $image );
            }
            eZLog::writeNotice( "Picture added to rfp: $RfpID  from IP: $REMOTE_ADDR" );
        }
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/" . $RfpID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZImageFile();
    $image = new eZImage( $ImageID );
    
    if ( trim( $NewPhotographerName ) != "" &&
         trim( $NewPhotographerEmail ) != ""
         )
    {
        $author = new eZAuthor( );
        $author->setName( $NewPhotographerName );
        $author->setEmail( $NewPhotographerEmail );
        $author->store();
        $image->setPhotographer( $author );
    }
    else
    {
        $image->setPhotographer( $PhotoID );
    }

    if ( $file->getUploadedFile( "userfile" ) )
    {
        $rfp = new eZRfp( $RfpID );


        $variations =& $image->variations();

        if( $image->checkImage( $file ) && $image->setImage( $file ) )
        {
            if ( count ( $variations ) > 0 )
            {
                foreach( $variations as $variation )
                    $variation->delete();
            }
//            $oldImage = new eZImage( $ImageID );
//            $rfp->deleteImage( $oldImage );

            $image->setName( $Name );
            $image->setCaption( $Caption );

            $image->store();
        
//            $rfp->addImage( $image );
        }
    }
    else
    {
        $image = new eZImage( $ImageID );
        $image->setName( $Name );
        $image->setCaption( $Caption );
        $image->store();
    }
    
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/" . $RfpID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $rfp = new eZRfp( $RfpID );

    if ( count ( $ImageArrayID ) != 0 )
    {
        foreach( $ImageArrayID as $ImageID )
        {
            $image = new eZImage( $ImageID );
            $rfp->deleteImage( $image );
        }
    }

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/" . $RfpID . "/" );
    exit();
}

// store the image definition
if ( $Action == "StoreDef" )
{
    $rfp = new eZRfp( $RfpID );

    // Unset frontpage image radiobutton
    if ( isset( $NoFrontImage ) )
    {
        $rfp->setThumbnailImage( false );
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/" . $RfpID . "/" );
        exit();
    }

    if ( isset( $ThumbnailImageID ) && ( $ThumbnailImageID != 0 ) && ( $ThumbnailImageID != "" ) )
    {
        $thumbnail = new eZImage( $ThumbnailImageID );
        $rfp->setThumbnailImage( $thumbnail );
    }

    if ( isset( $NewImage ) )
    {
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /rfp/rfpedit/imageedit/new/$RfpID/" );
        exit();
    }

    include_once( "classes/ezhttptool.php" );
    $RfpID = $session->variable( "RfpEditID" );
    eZHTTPTool::header( "Location: /rfp/rfpedit/edit/" . $RfpID . "/" );
    exit();
}

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );


$t->set_block( "image_edit_page", "image_tpl", "image" );
$t->set_block( "image_edit_page", "photographer_item_tpl", "photographer_item" );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "image", "" );

if ( $Action == "Edit" )
{
    $rfp = new eZRfp( $RfpID );
    $image = new eZImage( $ImageID );

    $photographer = $image->photographer();
    $photographerID = $photographer->id();

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

$author = new eZAuthor();
$authorArray = $author->getAll();
foreach ( $authorArray as $author )
{
    if ( $photographerID == $author->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->set_var( "photo_id", $author->id() );
    $t->set_var( "photo_name", $author->name() );
    $t->parse( "photographer_item", "photographer_item_tpl", true );
}


$rfp = new eZRfp( $RfpID );
    
$t->set_var( "rfp_name", $rfp->name() );
$t->set_var( "rfp_id", $rfp->id() );



$t->pparse( "output", "image_edit_page" );

?>
