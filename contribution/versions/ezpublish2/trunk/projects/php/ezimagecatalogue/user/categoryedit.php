<?php
// 
// $Id: categoryedit.php,v 1.22 2001/09/16 20:34:45 fh Exp $
//
// Created on: <08-Jan-2001 11:13:29 ce>
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
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

if ( isSet ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/" );
    exit();
}

$user =& eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );

$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "categoryedit.php" );

$t->set_file( "category_edit_tpl", "categoryedit.tpl" );

$t->setAllStrings();

$t->set_block( "category_edit_tpl", "value_tpl", "value" );
$t->set_block( "category_edit_tpl", "errors_tpl", "errors" );

$t->set_block( "category_edit_tpl", "write_group_item_tpl", "write_group_item" );
$t->set_block( "category_edit_tpl", "read_group_item_tpl", "read_group_item" );
$t->set_block( "category_edit_tpl", "upload_group_item_tpl", "upload_group_item" );

$t->set_var( "errors", "" );
$t->set_var( "category_name", "$Name" );
$t->set_var( "category_description", "$Description" );

$t->set_block( "errors_tpl", "error_write_permission", "error_write" );
$t->set_var( "error_write", "" );

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_parent_check_tpl", "error_parent_check" );
$t->set_var( "error_parent_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_everybody_permission_tpl", "error_read_everybody_permission" );
$t->set_var( "error_read_everybody_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_everybody_permission_tpl", "error_write_everybody_permission" );
$t->set_var( "error_write_everybody_permission", "&nbsp;" );

$error = false;
$permissionCheck = true;
$nameCheck = true;
$descriptionCheck = true;

if ( $Action == "Insert" || $Action == "Update" )
{
    // Check if the user have write access to the category
    if ( $permissionCheck )
    {
        if ( $ParentID == 0 )
        {
            if ( eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot"  ) == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
        else
        {
            $parentCategory = new eZImageCategory( $ParentID );

            if ( eZObjectPermission::hasPermission( $parentCategory, "imagecatalogue_category", "w", $user ) == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
    }

    // Check if parent is the same as category.
    if ( $Action == "Update" )
    {
        if ( $ParentID == $CategoryID )
        {
            $t->parse( "error_parent_check", "error_parent_check_tpl" );
            $error = true;
        }
    }

    // Check if name is empty.
    if ( $nameCheck )
    {
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    // Check if description is empty.
    if ( $descriptionCheck )
    {
        if ( empty ( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    // Check if there was any errors.
 
    if ( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

// Insert or update a category
if( ( $Action == "Insert" || $Action == "Update" ) && $error == false )
{
    if( $Action == "Insert" )
    {
        $category = new eZImageCategory();
        $category->setUser( $user );
    }
    else
    {
        $category = new eZImageCategory( $CategoryID );
    }

    $category->setName( $Name );
    $category->setDescription( $Description );

    $parent = new eZImageCategory( $ParentID );
    $category->setParent( $parent );

    $category->store();
    $CategoryID = $category->id();
    changePermissions( $CategoryID, $ReadGroupArrayID, 'r' );
    changePermissions( $CategoryID, $WriteGroupArrayID, 'w' );
    changePermissions( $CategoryID, $UploadGroupArrayID, 'u' );
    
    eZHTTPTool::header( "Location: /imagecatalogue/image/list/$ParentID" );
    exit();
}

// Delete the selected categories.
if ( $Action == "Delete" && $error == false )
{
    if ( count ( $CategoryArrayID ) > 0 )
    {
        foreach ( $CategoryArrayID as $CategoryID )
        {
            $category = new eZImageCategory( $CategoryID );
            $category->delete();
        }
    }
}
    
// Insert default values when creating a new category.
if ( $Action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "category_id", "" );
    $readGroupArrayID[0] = -1;
    $writeGroupArrayID[0] = -1;
    $uploadGroupArrayID[0] = -1;
}

// Insert the category values when editing.
if ( $Action == "Edit" )
{
    $category = new eZImageCategory( $CategoryID );

    $t->set_var( "category_name", $category->name() );
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_description", $category->description() );

    $parent =& $category->parent();

    if ( $parent )
        $CurrentCategoryID = $parent->id();

    $t->set_var( "action_value", "update" );

    $readGroupArrayID =& eZObjectPermission::getGroups( $category->id(), "imagecatalogue_category", "r", false );

    $writeGroupArrayID =& eZObjectPermission::getGroups( $category->id(), "imagecatalogue_category", "w", false );
    $uploadGroupArrayID =& eZObjectPermission::getGroups( $category->id(), "imagecatalogue_category", "u", false );
}

// Print out all the groups.
$groups = eZUserGroup::getAll();
foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    $t->set_var( "is_write_selected1", "" );
    $t->set_var( "is_read_selected1", "" );
    $t->set_var( "is_upload_selected1", "" );

    if( in_array( $group->id(), $readGroupArrayID ) )
    {
        $t->set_var( "is_read_selected1", "selected" );
    }
    elseif( in_array( -1, $readGroupArrayID ) )
    {
        $t->set_var( "read_everybody", "selected" );
    }
    else
    {
        $t->set_var( "is_read_selected1", "" );
    }
    $t->parse( "read_group_item", "read_group_item_tpl", true );

    if( in_array( $group->id(), $writeGroupArrayID ) )
    {
        $t->set_var( "is_write_selected1", "selected" );
    }
    elseif( in_array( -1, $writeGroupArrayID ) )
    {
        $t->set_var( "write_everybody", "selected" );
    }
    else
    {
        $t->set_var( "is_write_selected1", "" );
    }
    $t->parse( "write_group_item", "write_group_item_tpl", true );

    if( in_array( $group->id(), $uploadGroupArrayID ) )
    {
        $t->set_var( "is_upload_selected1", "selected" );
    }
    elseif( in_array( -1, $uploadGroupArrayID ) )
    {
        $t->set_var( "upload_everybody", "selected" );
    }
    else
    {
        $t->set_var( "is_upload_selected1", "" );
    }
    $t->parse( "upload_group_item", "upload_group_item_tpl", true );
}

$category = new eZImageCategory() ;

$categoryList =& $category->getTree( );

if ( count ( $categoryList ) == 0 )
{
    $t->set_var( "value", "" );
}

// Print out the categories.
foreach ( $categoryList as $categoryItem )
{
    if( eZObjectPermission::hasPermission( $categoryItem[0]->id(), "imagecatalogue_category", 'w' )
        || eZObjectPermission::hasPermission( $categoryItem[0]->id(), "imagecatalogue_category", 'u' )
        || eZImageCategory::isOwner( eZUser::currentUser(), $categoryItem[0]->id() ) )
    {
        $t->set_var( "option_name", $categoryItem[0]->name() );
        $t->set_var( "option_value", $categoryItem[0]->id() );

        if ( $categoryItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->set_var( "is_selected", "" );

        if ( $CurrentCategoryID != 0 )
        {
            if ( $categoryItem[0]->id() == $CurrentCategoryID )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );
            }
        }
    
        $t->parse( "value", "value_tpl", true );
    }
}

$t->pparse( "output", "category_edit_tpl" );

/******* FUNCTIONS ****************************/
function changePermissions( $objectID, $groups , $permission )
{
    eZObjectPermission::removePermissions( $objectID, "imagecatalogue_category", $permission );
    if ( count( $groups ) > 0 )
    {
        foreach ( $groups as $groupItem )
        {
            if ( $groupItem == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $groupItem );
            
            eZObjectPermission::setPermission( $group, $objectID, "imagecatalogue_category", $permission );
        }
    }

}
?>

