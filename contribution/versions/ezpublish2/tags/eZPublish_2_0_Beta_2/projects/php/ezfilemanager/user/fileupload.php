<?
// 
// $Id: fileupload.php,v 1.12 2001/01/26 08:55:48 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Dec-2000 15:49:57 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/ezfile.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

if ( isSet ( $Download ) )
{
    eZHTTPTool::header( "Location: /filemanager/download/" . $FileID . "/" . $FileName . "/");
    exit();
}

$user = eZUser::currentUser();

if ( ( !$user ) || ( eZPermission::checkPermission( $user, "eZFileManager", "WritePermission" ) == false ) )
{
    eZHTTPTool::header( "Location: /" );
    exit();
}

if ( isSet ( $NewFile ) )
{
    $Action = "New";
}
if ( isSet ( $NewFolder ) )
{
    eZHTTPTool::header( "Location: /filemanager/folder/new/$FolderID" );
    exit();
}
if ( isSet( $Delete ) )
{
    $Action = "Delete";
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "fileupload.php" );

$t->set_file( "file_upload_tpl", "fileupload.tpl" );

$t->setAllStrings();

$t->set_block( "file_upload_tpl", "value_tpl", "value" );
$t->set_block( "file_upload_tpl", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp" );

$t->set_var( "name_value", "$Name" );
$t->set_var( "description_value", "$Description" );

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
$descriptionCheck = false;
$folderPermissionCheck = true;
$readCheck = true;
$writeCheck = true;
$fileCheck = true;

$t->set_block( "errors_tpl", "error_write_permission", "write_permission" );
$t->set_var( "write_permission", "&nbsp" );

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_check_tpl", "error_write_check" );
$t->set_var( "error_write_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_check_tpl", "error_read_check" );
$t->set_var( "error_read_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_file_upload_tpl", "error_file_upload" );
$t->set_var( "error_file_upload", "&nbsp" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

if ( $Action == "Insert" || $Action == "Update" )
{
    if ( $folderPermissionCheck )
    {
        
        $user = eZUser::currentUser();
        $folder = new eZVirtualFolder( $FolderID );
        if ( $folder->checkWritePermission( $user ) == false )
        {
            $t->parse( "write_permission", "error_write_permission" ); 
            $error = true;
        }
    }

    if ( $nameCheck )
    {
        
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
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
        
        $file = new eZFile();
        if ( $file->getUploadedFile( "userfile" ) == false )
        {
            $error = true;
            $t->parse( "error_file_upload", "error_file_upload_tpl" );
        }
    }

    if ( $error )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

if ( $Action == "Insert" && $error == false )
{
    $uploadedFile = new eZVirtualFile();
    $uploadedFile->setName( $Name );
    $uploadedFile->setDescription( $Description );
    $uploadedFile->setReadPermission( $Read );
    $uploadedFile->setWritePermission( $Write );
    
    $user = eZUser::currentUser();
    
    if ( !$user )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
    
    $uploadedFile->setUser( $user );
    
    $uploadedFile->setFile( $file );
    
    $uploadedFile->store();
    
    $folder = new eZVirtualFolder( $FolderID );
    
    $folder->addFile( $uploadedFile );
    
    eZLog::writeNotice( "File added to file manager from IP: $REMOTE_ADDR" );
    eZHTTPTool::header( "Location: /filemanager/list/$FolderID/" );
    exit();
}

if ( $Action == "Update" && $error == false )
{
    $file = new eZFile( );

    $uploadedFile = new eZVirtualFile( $FileID );

    $uploadedFile->setName( $Name );
    $uploadedFile->setDescription( $Description );
    $uploadedFile->setReadPermission( $Read );
    $uploadedFile->setWritePermission( $Write );
    
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $uploadedFile->setFile( $file );
    }    

    $uploadedFile->store();

    $folder = new eZVirtualFolder( $FolderID );

    $uploadedFile->removeFolders();
    
    $folder->addFile( $uploadedFile );
        

    eZLog::writeNotice( "File added to file manager from IP: $REMOTE_ADDR" );
    eZHTTPTool::header( "Location: /filemanager/list/$FolderID/" );
    
    exit();
}

if ( $Action == "Delete" )
{
    if ( count ( $FileArrayID ) != 0 )
    {

        foreach( $FileArrayID as $ID )
        {
            $file = new eZVirtualFile( $ID );
            $file->delete();
        }
    }

    eZHTTPTool::header( "Location: /filemanager/list/" );
    exit();
    
    
}
    
if ( $Action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "file_id", "" );
}

if ( $Action == "Edit" )
{
    $file = new eZVirtualFile( $FileID );

    $t->set_var( "name_value", $file->name() );
    $t->set_var( "description_value", $file->description() );
    $t->set_var( "file_id", $file->id() );

    $write = $file->writePermission();

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

    $read = $file->readPermission();

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

    $t->set_var( "action_value", "update" );
}

$folder = new eZVirtualFolder() ;

$folderList = $folder->getTree( );

foreach ( $folderList as $folderItem )
{
    $t->set_var( "option_name", $folderItem[0]->name() );
    $t->set_var( "option_value", $folderItem[0]->id() );

    if ( $folderItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $folderItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $t->set_var( "selected", "" );
    
    if ( $folder && !$FolderID )
    {
        $FolderID = $folder->id();
    }

    if ( $FolderID )
    {
        if ( $folderItem[0]->id() == $FolderID )
        {
            $t->set_var( "selected", "selected" );
        }
    }
    
    $t->parse( "value", "value_tpl", true );
}

$t->pparse( "output", "file_upload_tpl" );


?>

