<?
// 
// $Id: folderedit.php,v 1.23 2001/05/10 14:56:51 ce Exp $
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
include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

$user =& eZUser::currentUser();

//om folder ID finnes 

if( isset( $FolderID ) && $FolderID != 0 && !eZObjectPermission::hasPermission( $FolderID, "filemanager_folder", 'w' ) &&
                            !eZVirtualFolder::isOwner( $user, $FolderID ) ) 
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

if ( isSet ( $Cancel ) )
{
    $folder = new eZVirtualFolder( $FolderID );

    $parent = $folder->parent();

    if( !isset( $parentID ) )
        $parentID = "0";
    
    if ( $parent )
        $parentID = $parent->id();

    eZHTTPTool::header( "Location: /filemanager/list/" . $parentID );
    exit();

}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "folderedit.php" );

$t->set_file( "folder_edit_tpl", "folderedit.tpl" );

$t->setAllStrings();

$t->set_block( "folder_edit_tpl", "value_tpl", "value" );
$t->set_block( "folder_edit_tpl", "errors_tpl", "errors" );
$t->set_block( "folder_edit_tpl", "write_group_item_tpl", "write_group_item" );
$t->set_block( "folder_edit_tpl", "read_group_item_tpl", "read_group_item" );

$t->set_var( "errors", "" );
$t->set_var( "name_value", "$Name" );
$t->set_var( "description_value", "$Description" );

$error = false;
$permissionCheck = true;
$nameCheck = true;
$descriptionCheck = true;

$t->set_block( "errors_tpl", "error_write_permission", "error_write" );
$t->set_var( "error_write", "" );

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_parent_check_tpl", "error_parent_check" );
$t->set_var( "error_parent_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_everybody_permission_tpl", "error_read_everybody_permission" );
$t->set_var( "error_read_everybody_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_everybody_permission_tpl", "error_write_everybody_permission" );
$t->set_var( "error_write_everybody_permission", "&nbsp;" );

if ( $Action == "Insert" || $Action == "Update" )
{
    if ( count ( $ReadGroupArrayID ) > 1 )
    {
        foreach ( $ReadGroupArrayID as $Read )
        {
            if ( $Read == 0 )
            {
                $t->parse( "error_read_everybody_permission", "error_read_everybody_permission_tpl" );
                $error = true;
            }
        }
    }

    if ( count ( $WriteGroupArrayID ) > 1 )
    {
        foreach ( $WriteGroupArrayID as $Write )
        {
            if ( $Write == 0 )
            {
                $t->parse( "error_write_everybody_permission", "error_write_everybody_permission_tpl" );
                $error = true;
            }
        }
    }

    if ( $permissionCheck )
    {

        if ( $FolderID == 0 )
        {
        }
        else
        {
            $parentFolder = new eZVirtualFolder( $FolderID );
            
            if ( eZObjectPermission::hasPermission( $parentCategory, "filemanager_folder", "w", $user ) != true )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
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

    if ( $Action == "Update" )
    {
        if ( $ParentID == $FolderID )
        {
            $t->parse( "error_parent_check", "error_parent_check_tpl" );
            $error = true;
        }
    }
   
    if ( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

// Insert a folder.
if ( $Action == "Insert" && $error == false )
{
    $folder = new eZVirtualFolder();
    $folder->setName( $Name );
    $folder->setDescription( $Description );

    $folder->setUser( $user );


    $parent = new eZVirtualFolder( $ParentID );
    $folder->setParent( $parent );

    $folder->store();

    if ( count ( $ReadGroupArrayID ) > 0 )
    {
        foreach ( $ReadGroupArrayID as $Read )
        {
            if ( $Read == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $Read );

            eZObjectPermission::setPermission( $group, $folder->id(), "filemanager_folder", "r" );
        }
    }

    if ( count ( $WriteGroupArrayID ) > 0 )
    {
        foreach ( $WriteGroupArrayID as $Write )
        {
            if ( $Write == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $Write );
            
            eZObjectPermission::setPermission( $group, $folder->id(), "filemanager_folder", "w" );
        }
    }

    eZHTTPTool::header( "Location: /filemanager/list/" . $ParentID );
    exit();
}

// Update a folder.
if ( $Action == "Update" && $error == false )
{
    $folder = new eZVirtualFolder( $FolderID );
    $folder->setName( $Name );
    $folder->setDescription( $Description );

    $folder->setUser( $user );

    $parent = new eZVirtualFolder( $ParentID );
    $folder->setParent( $parent );

    $folder->store();

    eZObjectPermission::removePermissions( $FolderID, "filemanager_folder", 'r' );
    if ( count ( $ReadGroupArrayID ) > 0 )
    {
        foreach ( $ReadGroupArrayID as $Read )
        {
            if ( $Read == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $Read );

            eZObjectPermission::setPermission( $group, $folder->id(), "filemanager_folder", "r" );
        }
    }

    eZObjectPermission::removePermissions( $FolderID, "filemanager_folder", 'w' );
    if ( count ( $WriteGroupArrayID ) > 0 )
    {
        foreach ( $WriteGroupArrayID as $Write )
        {
            if ( $Write == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $Write );
            
            eZObjectPermission::setPermission( $group, $folder->id(), "filemanager_folder", "w" );
        }
    }

    eZHTTPTool::header( "Location: /filemanager/list/" . $ParentID );
    exit();

}

if ( $Action == "Delete" && $error == false )
{
    if ( count ( $FolderArrayID ) > 0 )
    {
        foreach ( $FolderArrayID as $FolderID )
        {
            $folder = new eZVirtualFolder( $FolderID );
            $folder->delete();
        }
    }
}

$t->set_var( "write_everybody", "" );
$t->set_var( "read_everybody", "" );
if ( $Action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "folder_id", "" );
    $t->set_var( "write_everybody", "selected" );
    $t->set_var( "read_everybody", "selected" );
}

if ( $Action == "Edit" )
{
    $folder = new eZVirtualFolder( $FolderID );

    $t->set_var( "name_value", $folder->name() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "description_value", $folder->description() );

    $parent = $folder->parent();

    if ( $parent )
        $parentID = $parent->id();

    $readGroupArrayID =& eZObjectPermission::getGroups( $folder->id(), "filemanager_folder", "r", false );

    $writeGroupArrayID =& eZObjectPermission::getGroups( $folder->id(), "filemanager_folder", "w", false );

    $t->set_var( "action_value", "update" );
}

$folder = new eZVirtualFolder() ;

$folderList = $folder->getTree( );

if ( count ( $folderList ) == 0 )
{
    $t->set_var( "option_level", "" );
    $t->set_var( "value", "" );
}

// Print out all the groups.
//$groups = $user->groups();
$group = new eZUserGroup();
$groups = $group->getAll();

foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    $t->set_var( "is_write_selected1", "" );
    $t->set_var( "is_read_selected1", "" );
    
    if ( $readGroupArrayID )
    {
        foreach ( $readGroupArrayID as $readGroup )
        {
            if ( $readGroup == $group->id() )
            {
                $t->set_var( "is_read_selected1", "selected" );
            }
            elseif ( $readGroup == -1 )
            {
                $t->set_var( "read_everybody", "selected" );
            }
            else
            {
                $t->set_var( "is_read_selected", "" );
            }
        }
    }

    $t->parse( "read_group_item", "read_group_item_tpl", true );
    
    if ( $writeGroupArrayID )
    {
        foreach ( $writeGroupArrayID as $writeGroup )
        {
            if ( $writeGroup == $group->id() )
            {
                $t->set_var( "is_write_selected1", "selected" );
            }
            elseif ( $writeGroup == -1 )
            {
                $t->set_var( "write_everybody", "selected" );
            }
            else
            {
                $t->set_var( "is_write_selected", "" );
            }
        }
    }

    $t->parse( "write_group_item", "write_group_item_tpl", true );
}

// Print out all the folders.
foreach ( $folderList as $folderItem )
{
    if( eZObjectPermission::hasPermission( $folderItem[0]->id(), "filemanager_folder", 'w' )
        || eZVirtualFolder::isOwner( eZUser::currentUser(), $folderItem[0]->id() ) )
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

        $selectFolderID = $folderItem[0]->id();

        if ( $parentID )
        {
            if ( $selectFolderID == $parentID )
            {
                $t->set_var( "selected", "selected" );
            }
        }

        $t->parse( "value", "value_tpl", true );
    }
}

$t->pparse( "output", "folder_edit_tpl" );


?>

 
