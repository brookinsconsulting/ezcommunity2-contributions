<?
// 
// $Id: imageedit.php,v 1.9 2001/01/29 10:36:57 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <09-Jan-2001 10:45:44 ce>
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

include_once( "classes/ezhttptool.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user = eZUser::currentUser();

$CategoryID = eZHTTPTool::getVar( "CategoryID" );

if ( ( !$user ) || ( eZPermission::checkPermission( $user, "eZImageCatalogue", "WritePermission" ) == false ) )
{
    eZHTTPTool::header( "Location: /" );
    exit();
}

if ( isSet ( $NewCategory ) )
{
    eZHTTPTool::header( "Location: /imagecatalogue/category/new/" . $CurrentCategoryID );
    exit();
}

if ( isSet ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/" . $MainCategoryID . "/" );
    exit();
}


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

// include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );

$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );

$t->set_block( "image_edit_page", "value_tpl", "value" );
$t->set_block( "image_edit_page", "image_tpl", "image" );
$t->set_block( "image_edit_page", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp;" );

$t->set_var( "name_value", "$Name" );
$t->set_var( "image_description", "$Description" );
$t->set_var( "caption_value", "$Caption" );


if ( $Read == "User" )
    $t->set_var( "user_read_checked", "checked" );
if ( $Read == "Group" )
    $t->set_var( "group_read_checked", "checked" );
if ( $Read == "All" )
    $t->set_var( "all_read_checked", "checked" );

if ( $Write == "User" )
    $t->set_var( "user_write_checked", "checked" );
if ( $Write == "Group" )
    $t->set_var( "group_write_checked", "checked" );
if ( $Write == "All" )
    $t->set_var( "all_write_checked", "checked" );

$error = false;
$nameCheck = true;
$captionCheck = true;
$descriptionCheck = true;
$fileCheck = true;
$writeCheck = true;
$readCheck = true;

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_caption_tpl", "error_caption" );
$t->set_var( "error_caption", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_check_tpl", "error_write_check" );
$t->set_var( "error_write_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_check_tpl", "error_read_check" );
$t->set_var( "error_read_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_file_upload_tpl", "error_file_upload" );
$t->set_var( "error_file_upload", "&nbsp" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

// Check for errors when inserting or updating.
if ( $Action == "Insert" || $Action == "Update" )
{
    if ( $nameCheck )
    {
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    if ( $captionCheck )
    {
        if ( empty ( $Caption ) )
        {
            $t->parse( "error_caption", "error_caption_tpl" );
            $error = true;
        }
    }

    if ( $descriptionCheck )
    {
        if ( empty ( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    if ( $writeCheck )
    {
        
        if ( empty ( $Write ) )
        {
            $t->parse( "error_write_check", "error_write_check_tpl" );
            $error = true;
        }
    }

    if ( $readCheck )
    {
        
        if ( empty ( $Read ) )
        {
            $t->parse( "error_read_check", "error_read_check_tpl" );
            $error = true;
        }
    }

    if ( $fileCheck )
    {
        $file = new eZImageFile();
        
        if ( $file->getUploadedFile( "userfile" ) )
        {
            $fileOK = true;
        }
        else
        {
            if ( $Action == "Insert" )
            {
                $error = true;
                $t->parse( "error_file_upload", "error_file_upload_tpl" );
            }
        }

    }

    if ( $error )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

// Insert if error == false
if ( $Action == "Insert" && $error == false )
{
    $image = new eZImage();
    $image->setName( $Name );
    $image->setCaption( $Caption );
    $image->setDescription( $Description );
    $image->setReadPermission( $Read );
    $image->setWritePermission( $Write );
    $image->setUser( $user );

    $image->setImage( $file );

    $image->store();

    $category = new eZImageCategory( $CategoryID );
    
    $category->addImage( $image );

    eZLog::writeNotice( "Picture added to catalogue: $image->name() from IP: $REMOTE_ADDR" );

    eZHTTPTool::header( "Location: /imagecatalogue/image/list/" . $CategoryID . "/" );
    exit();
}

// Update if error == false
if ( $Action == "Update" && $error == false )
{
    $image = new eZImage( $ImageID );
    $image->setName( $Name );
    $image->setCaption( $Caption );
    $image->store();

    $image->setDescription( $Description );
    $image->setReadPermission( $Read );
    $image->setWritePermission( $Write );

    if ( $fileOK )
        $image->setImage( $file );
    
    $image->store();
        
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/" . $MainCategoryID . "/" );
    exit();
}

// Delete a image
if ( $Action == "Delete" )
{
    $image = new eZImage( $ImageID );
        
    $article->deleteImage( $image );
    
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/" . $MainCategoryID . "/" );
    exit();    
}

// Set the default values to null
if ( $Action == "New" || $error )
{
    $t->set_var( "name_value", "" );
    $t->set_var( "caption_value", "" );
    $t->set_var( "description_value", "" );
    $t->set_var( "action_value", "Insert" );
    $t->set_var( "option_id", "" );
    $t->set_var( "image", "" );
    $t->set_var( "user_read_checked", "checked" );
    $t->set_var( "user_write_checked", "checked" );
    $t->set_var( "image_id", "" );
}

// Sets the values to the current image
if ( $Action == "Edit" )
{
    $image = new eZImage( $ImageID );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "name_value", $image->name() );
    $t->set_var( "caption_value", $image->caption() );
    $t->set_var( "image_description", $image->description() );
    $t->set_var( "action_value", "update" );

    
    $t->set_var( "image_alt", $image->caption() );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_src", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    $t->set_var( "image_file_name", $image->originalFileName() );
    $t->parse( "image", "image_tpl" );

    $write = $image->writePermission();

    if ( $write == "User" )
    {
        $t->set_var( "user_write_checked", "checked" );
    }
    else if ( $write == "Group" )
    {
        $t->set_var( "group_write_checked", "checked" );
    }
    else if ( $write == "All" )
    {
        $t->set_var( "all_write_checked", "checked" );
    }

    $read = $image->readPermission();

    if ( $read == "User" )
    {
        $t->set_var( "user_read_checked", "checked" );
    }
    else if ( $read == "Group" )
    {
        $t->set_var( "group_read_checked", "checked" );
    }
    else if ( $read == "All" )
    {
        $t->set_var( "all_read_checked", "checked" );
    }

}

$category = new eZImageCategory() ;

$categoryList = $category->getTree( );

// Make a category list
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "option_name", $categoryItem[0]->name() );
    $t->set_var( "option_value", $categoryItem[0]->id() );

    if ( $categoryItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $t->set_var( "selected", "" );

    // Get the current category when makeing a new image
    if ( $CurrentCategoryID )
    {
        if ( $categoryItem[0]->id() == $CurrentCategoryID )
        {
            $t->set_var( "selected", "selected" );
        }
    }

    // Get the rigth category when updating
    if ( $CategoryID )
    {
        if ( $categoryItem[0]->id() == $CurrentCategoryID )
        {
            $t->set_var( "selected", "selected" );
        }
    }
    
    $t->parse( "value", "value_tpl", true );
}

$t->pparse( "output", "image_edit_page" );

?>
