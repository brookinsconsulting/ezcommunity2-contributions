<?
// 
// $Id: filelist.php,v 1.7 2001/01/09 10:56:08 ce Exp $
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

include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZFileManagerMain", "Language" );

$ImageDir = $ini->read_var( "eZFileManagerMain", "ImageDir" );

$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "filelist.php" );

$t->set_file( "file_list_page_tpl", "filelist.tpl" );

$t->setAllStrings();

$t->set_block( "file_list_page_tpl", "current_folder_tpl", "current_folder" );

// path
$t->set_block( "file_list_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "file_list_page_tpl", "file_list_tpl", "file_list" );
$t->set_block( "file_list_tpl", "file_tpl", "file" );

$t->set_block( "file_tpl", "read_tpl", "read" );
$t->set_block( "file_tpl", "write_tpl", "write" );

$t->set_block( "file_list_page_tpl", "folder_list_tpl", "folder_list" );
$t->set_block( "folder_list_tpl", "folder_tpl", "folder" );

$t->set_block( "folder_tpl", "folder_write_tpl", "folder_write" );
$t->set_block( "folder_tpl", "folder_read_tpl", "folder_read" );

$t->set_var( "read", "" );

$user = eZUser::currentUser();

$folder = new eZVirtualFolder( $FolderID );

$readPermission = $folder->checkReadPermission( $user );

$error = true;

if ( ( $readPermission == "User" ) || ( $readPermission == "Group" ) || ( $readPermission == "All" ) )
{
    $error = false;
}

if ( $FolderID == 0 )
{
    $error = false;
}

$t->set_var( "current_folder", "" );
if ( $folder->id() != 0 )
{
    $t->set_var( "current_folder_description", $folder->description() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "folder_name", $folder->name() );
    
    $t->parse( "current_folder", "current_folder_tpl" );
}

// path
$pathArray = $folder->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "folder_id", $path[0] );

    $t->set_var( "folder_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}


$folderList =& $folder->getByParent( $folder );

$i=0;
foreach ( $folderList as $folderItem )
{
//      if ( ( $i % 2 ) == 0 )
//      {
//          $t->set_var( "begin_tr", "<tr>" );
//          $t->set_var( "end_tr", "" );        
//      }
//      else if ( ( $i % 4 ) == 3 )
//      {
//          $t->set_var( "begin_tr", "" );
//          $t->set_var( "end_tr", "</tr>" );
//      }
//      else
//      {
//          $t->set_var( "begin_tr", "" );
//          $t->set_var( "end_tr", "" );        
//      }

    $t->set_var( "folder_name", $folderItem->name() );
    $t->set_var( "folder_id", $folderItem->id() );

    $writePermission = $folderItem->checkWritePermission( $user );
    $readPermission = $folderItem->checkReadPermission( $user );

    $t->set_var( "folder_read", "" );
    $t->set_var( "folder_write", "" );

    if ( ( $readPermission == "User" ) || ( $readPermission == "Group" ) || ( $readPermission == "All" ) )
    {
        $t->parse( "folder_read", "folder_read_tpl" );
    }
    else
    {
    }

    if ( ( $writePermission == "User" ) || ( $writePermission == "Group" ) || ( $writePermission == "All" ) )
    {
        $t->parse( "folder_write", "folder_write_tpl" );
    }
    else
    {
    }

    $t->parse( "folder", "folder_tpl", true );
    $i++;
}

if ( count( $folderList ) > 0 )
{
    $t->parse( "folder_list", "folder_list_tpl" );
}
else
{
    $t->set_var( "folder_list", "" );
}


$fileList =& $folder->files();

//$i=0;
foreach ( $fileList as $file )
{
//      if ( ( $i % 4 ) == 0 )
//      {
//          $t->set_var( "begin_tr", "<tr>" );
//          $t->set_var( "end_tr", "" );        
//      }
//      else if ( ( $i % 4 ) == 3 )
//      {
//          $t->set_var( "begin_tr", "" );
//          $t->set_var( "end_tr", "</tr>" );
//      }
//      else
//      {
//          $t->set_var( "begin_tr", "" );
//          $t->set_var( "end_tr", "" );
        
//      }

    $t->set_var( "file_id", $file->id() );
    $t->set_var( "original_file_name", $file->originalFileName() );
    $t->set_var( "file_name", $file->name() );
    $t->set_var( "file_url", $file->name() );

    $filePath = $file->filePath( true );

    $size = filesize( $filePath );

    if ( $size == 0 )
    {
        $t->set_var( "file_size", 0 );
    }
    else
    {
        $t->set_var( "file_size", $size );
    }

    $writePermission = $file->checkWritePermission( $user );
    $readPermission = $file->checkReadPermission( $user );

    $t->set_var( "read", "" );
    $t->set_var( "write", "" );

    if ( ( $readPermission == "User" ) || ( $readPermission == "Group" ) || ( $readPermission == "All" ) )
    {
        $t->parse( "read", "read_tpl" );
    }
    else
    {
    }

    if ( ( $writePermission == "User" ) || ( $writePermission == "Group" ) || ( $writePermission == "All" ) )
    {
        $t->parse( "write", "write_tpl" );
    }
    else
    {
    }

    $t->parse( "file", "file_tpl", true );
    
    $i++;
}


if ( count( $fileList ) > 0 )
{
    $t->parse( "file_list", "file_list_tpl" );
}
else
{
    $t->set_var( "file_list", "" );
}


$t->set_var( "image_dir", $ImageDir );
$t->set_var( "main_folder_id", $FolderID );


if ( $error == false )
    $t->pparse( "output", "file_list_page_tpl" );


?>

