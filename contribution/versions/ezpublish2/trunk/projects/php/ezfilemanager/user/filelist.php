<?php
// 
// $Id: filelist.php,v 1.39 2001/09/06 11:16:40 jhe Exp $
//
// Created on: <10-Dec-2000 16:16:20 bf>
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

include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFileManagerMain", "Language" );
$ImageDir = $ini->read_var( "eZFileManagerMain", "ImageDir" );
$Limit = $ini->read_var( "eZFileManagerMain", "Limit" );

$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "filelist.php" );

$t->set_file( "file_list_page_tpl", "filelist.tpl" );

$t->setAllStrings();

$t->set_block( "file_list_page_tpl", "current_folder_tpl", "current_folder" );

// path
$t->set_block( "file_list_page_tpl", "path_item_tpl", "path_item" );
$t->set_block( "file_list_page_tpl", "write_menu_tpl", "write_menu" );
$t->set_block( "file_list_page_tpl", "delete_menu_tpl", "delete_menu" );
$t->set_block( "file_list_page_tpl", "file_list_tpl", "file_list" );
$t->set_block( "file_list_page_tpl", "next_tpl", "next" );
$t->set_block( "file_list_page_tpl", "prev_tpl", "prev" );

$t->set_block( "file_list_tpl", "file_tpl", "file" );

$t->set_block( "file_tpl", "read_tpl", "read" );
$t->set_block( "file_tpl", "write_tpl", "write" );

$t->set_block( "file_list_page_tpl", "folder_list_tpl", "folder_list" );
$t->set_block( "folder_list_tpl", "folder_tpl", "folder" );

$t->set_block( "folder_tpl", "folder_write_tpl", "folder_write" );
$t->set_block( "folder_tpl", "folder_read_tpl", "folder_read" );

$t->set_var( "read", "" );

$user =& eZUser::currentUser();

$folder = new eZVirtualFolder( $FolderID );

$error = true;

// Check for read permission in the current folder.
if ( eZObjectPermission::hasPermission( $folder->id(), "filemanager_folder", "r", $user ) ||
     eZVirtualFolder::isOwner( $user, $folder->id() ) )
{
    $error = false;
} 

if ( $FolderID == 0 )
{
    $error = false;
}

$t->set_var( "write_menu", "" );
$t->set_var( "delete_menu", "" );
$t->set_var( "current_folder", "" );
$t->set_var( "current_folder_description", "" );

if ( $folder->id() != 0 )
{
    $t->set_var( "current_folder_description", $folder->description() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "folder_name", $folder->name() );
    
    $t->parse( "current_folder", "current_folder_tpl" );
}

// path
$pathArray =& $folder->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "folder_id", $path[0] );
    $t->set_var( "folder_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$t->set_var( "top_folder_name", $path[1] );

// Print out the folders.
$folderList =& $folder->getByParent( $folder );

$i = 0;
$deleteFolders = false;
foreach ( $folderList as $folderItem )
{
    $t->set_var( "folder_name", $folderItem->name() );
    $t->set_var( "folder_id", $folderItem->id() );

    $t->set_var( "folder_read", "" );
    $t->set_var( "folder_write", "" );

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    if ( eZObjectPermission::hasPermission( $folderItem->id(), "filemanager_folder", "r", $user ) ||
         eZVirtualFolder::isOwner( $user, $folderItem->id()) )
     
    {
        $t->parse( "folder_read", "folder_read_tpl" );
        $i++;
    }

    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $folderItem->id(), "filemanager_folder", "w", $user ) ) ||
         ( eZVirtualFolder::isOwner( $user, $folderItem->id()) ) )
    {
        $t->parse( "folder_write", "folder_write_tpl" );
        $deleteFolders = true;;
    }

    $t->parse( "folder", "folder_tpl", true );
}


if ( count( $folderList ) > 0 )
{
    $t->parse( "folder_list", "folder_list_tpl" );
}
else
{
    $t->set_var( "folder_list", "" );
}

// Print out the files.

$fileList =& $folder->files( "name", $Offset, $Limit );

$deleteFiles = false;
foreach ( $fileList as $file )
{
    $t->set_var( "file_id", $file->id() );
    $t->set_var( "original_file_name_without_spaces", str_replace( " ", "%20", $file->originalFileName() ) );
    $t->set_var( "original_file_name", $file->originalFileName() );
    $t->set_var( "file_name", $file->name() );
    $t->set_var( "file_url", $file->name() );
    $t->set_var( "file_description", $file->description() );

    $filePath = $file->filePath( true );

    $size = $file->siFileSize();
    $t->set_var( "file_size", $size["size-string"] );
    $t->set_var( "file_unit", $size["unit"] );

    $t->set_var( "file_read", "" );
    $t->set_var( "file_write", "" );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );

    if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) ||
         eZVirtualFile::isOwner( $user ,$file->id() ) )
    {
        $t->parse( "read", "read_tpl" );
        $i++;
    }
    else
    {
        $t->set_var( "read", "" );
    }
    
    if ( ( $user ) &&
        ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "w", $user ) )  ||
        ( eZVirtualFile::isOwner( $user, $file->id() )) )
    {
        $t->parse( "write", "write_tpl" );
        $deleteFiles = true;
    }
    else
    {
        $t->set_var( "write" );
    }

    $t->parse( "file", "file_tpl", true );
}

$fileNumber = $folder->countFiles();

if ( $Offset > 0 )
{
    $t->set_var( "prev_offset", ( $Offset - $Limit ) > 0 ? $Offset - $Limit : 0 );
    $t->parse( "prev", "prev_tpl" );
}
else
{
    $t->set_var( "prev", "" );
}

if ( $fileNumber > $Offset + $Limit )
{
    $t->set_var( "next_offset", $Offset + $Limit );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
}

if ( count( $fileList ) > 0 )
{
    $t->parse( "file_list", "file_list_tpl" );
}
else
{
    $t->set_var( "file_list", "" );
}

if ( $FolderID == 0 && eZPermission::checkPermission( eZUser::currentUser(), "eZFileManager", "WriteToRoot" ) )
{
    $t->parse( "write_menu", "write_menu_tpl" );
}
else if ( ( $user ) &&
          ( eZObjectPermission::hasPermission( $FolderID, "filemanager_folder", 'w' ) ) ||
          ( eZVirtualFolder::isOwner( eZUser::currentUser(), $FolderID ) ) )
{
    $t->parse( "write_menu", "write_menu_tpl" );
}

if ( $deleteFolders || $deleteFiles )
{
    $t->parse( "delete_menu", "delete_menu_tpl" );
}


$t->set_var( "image_dir", $ImageDir );
$t->set_var( "main_folder_id", $FolderID );

if ( $error == false )
{
    $t->pparse( "output", "file_list_page_tpl" );
}
else
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

?>

