<?
// 
// $Id: folderedit.php,v 1.7 2001/01/25 19:08:20 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <08-Jan-2001 11:13:29 ce>
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
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

$user = eZUser::currentUser();

if ( ( !$user ) || ( eZPermission::checkPermission( $user, "eZFileManager", "WritePermission" ) == false ) )
{
    eZHTTPTool::header( "Location: /" );
    exit();
}


$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "folderedit.php" );

$t->set_file( "folder_edit_tpl", "folderedit.tpl" );

$t->setAllStrings();

$t->set_block( "folder_edit_tpl", "value_tpl", "value" );
$t->set_block( "folder_edit_tpl", "errors_tpl", "errors" );

$t->set_var( "errors", "" );
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
$permissionCheck = true;
$nameCheck = true;
$descriptionCheck = true;
$readCheck = true;
$writeCheck = true;

if ( $Action == "Insert" || $Action == "Update" )
{
    if ( $permissionCheck )
    {
        $t->set_block( "errors_tpl", "error_write_permission", "error_write" );
        $t->set_var( "error_write", "" );

        if ( $ParentID == 0 )
        {
            if ( eZPermission::checkPermission( $user, "eZFileManager", "WriteToRoot"  ) == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
        else
        {
            $user = eZUser::currentUser();
            $parentFolder = new eZVirtualFolder( $ParentID );
            
            if ( $parentFolder->checkWritePermission( $user ) == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
    }

    if ( $nameCheck )
    {
        $t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
        $t->set_var( "error_name", "&nbsp;" );
        
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    if ( $descriptionCheck )
    {
        $t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
        $t->set_var( "error_description", "&nbsp;" );
        
        if ( empty ( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    if ( $writeCheck )
    {
        $t->set_block( "errors_tpl", "error_write_check_tpl", "error_write_check" );
        $t->set_var( "error_write_check", "&nbsp;" );
        
        if ( empty ( $Write ) )
        {
            $t->parse( "error_write_check", "error_write_check_tpl" );
            $error = true;
        }
    }

    if ( $readCheck )
    {
        $t->set_block( "errors_tpl", "error_read_check_tpl", "error_read_check" );
        $t->set_var( "error_read_check", "&nbsp;" );
        
        if ( empty ( $Read ) )
        {
            $t->parse( "error_read_check", "error_read_check_tpl" );
            $error = true;
        }

    }

    if ( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}


if ( ( $Action == "Insert" || $Action == "Update" ) && $error == false )
{
    if ( $Action == "Update" )
        $folder = new eZVirtualFolder( $FolderID );
    else
        $folder = new eZVirtualFolder();

    $folder->setName( $Name );
    $folder->setDescription( $Description );

    $folder->setReadPermission( $Read );
    $folder->setWritePermission( $Write );
    
    $folder->setUser( $user );

    $parent = new eZVirtualFolder( $ParentID );
    $folder->setParent( $parent );

    $folder->store();

    eZHTTPTool::header( "Location: /filemanager/list/$ParentID" );
    exit();
}

if ( $Action == "Delete" && $error == false )
{
    $folder = new eZVirtualFolder( $FolderID );
    $folder->delete();
}
    

if ( $Action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "folder_id", "" );
}

if ( $Action == "Edit" )
{
    $folder = new eZVirtualFolder( $FolderID );

    $t->set_var( "name_value", $folder->name() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "description_value", $folder->description() );

    $write = $folder->writePermission();

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

    $read = $folder->readPermission();

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

$folderList =& $folder->getTree( );

if ( count ( $folderList ) == 0 )
{
    $t->set_var( "option_level", "" );
    $t->set_var( "value", "" );
}

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

$t->pparse( "output", "folder_edit_tpl" );


?>

