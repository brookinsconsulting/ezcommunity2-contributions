<?
// 
// $Id: fileupload.php,v 1.5 2001/01/05 14:21:55 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Dec-2000 15:49:57 bf>
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

//  $folder = new eZVirtualFolder();
//  $folder->setName( "Documentation" );
//  $folder->setDescription( "Documentation goes here" );
//  $folder->store();

if ( $Action == "Insert" )
{
    print( "uploading file" );
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    {
        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );
        $uploadedFile->setReadPermission( $Read );
        $uploadedFile->setWritePermission( $Write );

        $user = eZUser::currentUser();

        if ( !$user )
        {
            Header( "Location: /" );
            exit();
        }

        $uploadedFile->setUser( $user );
        
        $uploadedFile->setFile( $file );
        
        $uploadedFile->store();

        $folder = new eZVirtualFolder( $FolderID );

        $folder->addFile( $uploadedFile );
        

        eZLog::writeNotice( "File added to file manager from IP: $REMOTE_ADDR" );
        Header( "Location: /filemanager/list/$FolderID/" );
        exit();
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

}

if ( $Action == "Update" )
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
    Header( "Location: /filemanager/list/$FolderID/" );
    
    exit();
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "fileupload.php" );

$t->set_file( "file_upload_tpl", "fileupload.tpl" );

$t->setAllStrings();

$t->set_block( "file_upload_tpl", "value_tpl", "value" );

if ( $Action == "New" )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "name_value", "" );
    $t->set_var( "description_value", "" );
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

$folder = new eZVirtualFolder( $FolderID );

$folderList =& $folder->getByParent( $folder );

foreach ( $folderList as $folder )
{
    $t->set_var( "option_name", $folder->name() );
    $t->set_var( "option_value", $folder->id() );

    $t->set_var( "selected", "" );
    $t->set_var( "option_level", "" );

    $t->parse( "value", "value_tpl", true );
}

$t->pparse( "output", "file_upload_tpl" );


?>

