<?
// 
// $Id: imagelist.php,v 1.1 2001/01/10 21:32:37 ce Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <10-Dec-2000 16:16:20 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZFileManagerMain", "Language" );

$ImageDir = $ini->read_var( "eZFileManagerMain", "ImageDir" );

$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "imagelist.php" );

$t->set_file( "image_list_page_tpl", "imagelist.tpl" );

$t->setAllStrings();

if ( isSet ( $DetailView ) )
{
    $session = new eZSession();
    $session->setVariable( "ImageViewMode", "Detail" );
}
if ( isSet ( $NormalView ) )
{
    $session = new eZSession();
    $session->setVariable( "ImageViewMode", "Normal" );
}

$checkMode = new eZSession();

print( "Mode: " . $checkMode->variable( "ImageViewMode" ) . "<br>");

if ( $checkMode->variable( "ImageViewMode" ) == "Detail" )
{
    $DetailView = true;
}
else if ( $checkMode->variable( "ImageViewMode" ) == "Normal" )
{
    $NormalView = true;
}

$t->set_block( "image_list_page_tpl", "current_category_tpl", "current_category" );

// path
$t->set_block( "image_list_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "image_list_page_tpl", "image_list_tpl", "image_list" );

$t->set_block( "image_list_page_tpl", "normal_view_button", "normal_button" );
$t->set_block( "image_list_page_tpl", "detail_view_button", "detail_button" );

$t->set_block( "image_list_tpl", "image_tpl", "image" );
$t->set_block( "image_list_tpl", "detail_view_tpl", "detail_view" );

$t->set_block( "image_tpl", "read_tpl", "read" );
$t->set_block( "image_tpl", "write_tpl", "write" );

$t->set_block( "detail_view_tpl", "detail_read_tpl", "detail_read" );
$t->set_block( "detail_view_tpl", "detail_write_tpl", "detail_write" );

$t->set_block( "image_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$t->set_block( "category_tpl", "category_write_tpl", "category_write" );
$t->set_block( "category_tpl", "category_read_tpl", "category_read" );

$t->set_var( "read", "" );

$user = eZUser::currentUser();

$category = new eZImageCategory( $CategoryID );

$readPermission = $category->checkReadPermission( $user );

$error = true;

if ( ( $readPermission == "User" ) || ( $readPermission == "Group" ) || ( $readPermission == "All" ) )
{
    $error = false;
}

if ( $CategoryID == 0 )
{
    $error = false;
}

$t->set_var( "current_category", "" );
if ( $category->id() != 0 )
{
    $t->set_var( "current_category_description", $category->description() );
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );
    
    $t->parse( "current_category", "current_category_tpl" );
}

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}


$categoryList =& $category->getByParent( $category );

$i=0;
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_name", $categoryItem->name() );
    $t->set_var( "category_id", $categoryItem->id() );

    $writePermission = $categoryItem->checkWritePermission( $user );
    $readPermission = $categoryItem->checkReadPermission( $user );

    $t->set_var( "category_read", "" );
    $t->set_var( "category_write", "" );

    if ( ( $readPermission == "User" ) || ( $readPermission == "Group" ) || ( $readPermission == "All" ) )
    {
        $t->parse( "category_read", "category_read_tpl" );
    }
    else
    {
    }

    if ( ( $writePermission == "User" ) || ( $writePermission == "Group" ) || ( $writePermission == "All" ) )
    {
        $t->parse( "category_write", "category_write_tpl" );
    }
    else
    {
    }

    $t->parse( "category", "category_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )
{
    $t->parse( "category_list", "category_list_tpl" );
}
else
{
    $t->set_var( "category_list", "" );
}


$imageList =& $category->images();

//$i=0;
foreach ( $imageList as $image )
{

    if ( ( $i % 4 ) == 0 )
    {
        $t->set_var( "begin_tr", "<tr>" );
        $t->set_var( "end_tr", "" );        
    }
    else if ( ( $i % 4 ) == 3 )
    {
        $t->set_var( "begin_tr", "" );
        $t->set_var( "end_tr", "</tr>" );
    }
    else
    {
        $t->set_var( "begin_tr", "" );
        $t->set_var( "end_tr", "" );
        
    }

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "original_image_name", $image->originalFileName() );
    $t->set_var( "image_name", $image->name() );
    $t->set_var( "image_caption", $image->name() );
    $t->set_var( "image_url", $image->name() );

    $width = $ini->read_var( "eZImageCatalogueMain", "ThumbnailViewWidth" );
    
    $height = $ini->read_var( "eZImageCatalogueMain", "ThumbnailViewHight" );
    
    $variation = $image->requestImageVariation( $width, $height );

    $t->set_var( "image_alt", $image->name() );
    $t->set_var( "image_src", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    $t->set_var( "image_file_name", $image->originalFileName() );


    $imagePath = $variation->imagePath( true );

    $size = filesize( $imagePath );

    if ( $size == 0 )
    {
        $t->set_var( "image_size", 0 );
    }
    else
    {
        $t->set_var( "image_size", $size );
    }

    $writePermission = $image->checkWritePermission( $user );
    $readPermission = $image->checkReadPermission( $user );

    $t->set_var( "read", "" );
    $t->set_var( "write", "" );

    if ( ( $readPermission == "User" ) || ( $readPermission == "Group" ) || ( $readPermission == "All" ) )
    {
        if ( isSet ( $DetailView ) )
        {
            $t->parse( "detail_read", "detail_read_tpl" );
        }
        else
        {
            $t->parse( "read", "read_tpl" );
        }
    }
    else
    {
    }

    if ( ( $writePermission == "User" ) || ( $writePermission == "Group" ) || ( $writePermission == "All" ) )
    {
        if ( isSet ( $DetailView ) )
        {
            $t->parse( "detail_write", "detail_write_tpl" );
        }
        else
        {
            $t->parse( "write", "write_tpl" );
        }
    }
    else
    {
    }

    if ( isSet ( $DetailView ) )
    {
        $t->set_var( "is_detail_view", "true" );
        $t->set_var( "detail_button", "" );
        $t->set_var( "image", "" );
        
        $t->parse( "detail_view", "detail_view_tpl", true );
        $t->parse( "normal_button", "normal_view_button" );
    }
    else
    {
        $t->set_var( "is_detail_view", "" );
        $t->set_var( "detail_view", "" );
        $t->set_var( "normal_button", "" );
        
        $t->parse( "image", "image_tpl", true );
        $t->parse( "detail_button", "detail_view_button" );
    }
    
    $i++;
}


if ( count( $imageList ) > 0 )
{
    $t->parse( "image_list", "image_list_tpl" );
}
else
{
    $t->set_var( "image_list", "" );
}


$t->set_var( "image_dir", $ImageDir );
$t->set_var( "main_category_id", $CategoryID );


if ( $error == false )
    $t->pparse( "output", "image_list_page_tpl" );


?>

