<?php
// 
// $Id: groupedit.php,v 1.21 2001/07/30 14:19:03 jhe Exp $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZUserMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

require( "ezuser/admin/admincheck.php" );

if ( isSet( $DeleteGroups ) and isSet( $GroupArrayID ) )
{
    foreach ( $GroupArrayID as $groupid )
    {
        eZUserGroup::delete( $groupid );
    }
    eZHTTPTool::header( "Location: /user/grouplist" );
    exit();
}

if ( isSet( $Back ) )
{
    eZHTTPTool::header( "Location: /user/grouplist/" );
    exit();
}


if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "GroupAdd" ) )
    {
        if ( $Name == "" || $Description == "" )
        {
            $error = new INIFile( "ezuser/admin/intl/" . $Language . "/groupedit.php.ini", false );
            $error_msg =  $error->read_var( "strings", "error_msg" );
        }
        else
        {
            $group = new eZUserGroup();
            $group->setName( $Name );
            $group->setDescription( $Description );
            $group->setSessionTimeout( $SessionTimeout );

            if ( isSet( $IsRoot ) )
                $group->setIsRoot( true );
            else
                $group->setIsRoot( false );
            $permission = new eZPermission(); 

            $group->store();

            $group->get( $group->id() );

            $permissionList = $permission->getAll();

            foreach ( $permissionList as $permissionItem )
            {
                $permissionItem->setEnabled( $group, false );
            }
    
            foreach ( $PermissionArray as $PermissionID )
            {
                $permission->get( $PermissionID );
                $permission->setEnabled( $group, true );
            }

            eZHTTPTool::header( "Location: /user/grouplist/" );
            exit();
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /error/403/" );
        exit();
    }
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "GroupDelete" ) )
    {

        $group = new eZUserGroup();
        $group->get( $GroupID );

        $group->delete();

        eZHTTPTool::header( "Location: /user/grouplist/" );
        exit();
    }
    else
    {
        print( "No rights.");
    }
}
 
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "GroupModify" ) )
    {
        $permission = new eZPermission();
        $group = new eZUserGroup();
        $group->get( $GroupID );
        $group->setName( $Name );
        $group->setDescription( $Description );
        $group->setSessionTimeout( $SessionTimeout );

        if ( isSet( $IsRoot ) )
            $group->setIsRoot( true );
        else
            $group->setIsRoot( false );

        $permissionList = $permission->getAll();

        foreach ( $permissionList as $permissionItem )
        {
            $permissionItem->setEnabled( $group, false );
        }
    
        foreach ( $PermissionArray as $PermissionID )
        {
            $permission->get( $PermissionID );
            $permission->setEnabled( $group, true );
        }

        $group->store();
        
        eZHTTPTool::header( "Location: /user/grouplist/" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /error/403/" );
        exit();
    }
}

// Template
$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
"ezuser/admin/" . "/intl", $Language, "groupedit.php" );
$t->setAllStrings();

$t->set_file( "group_edit", "groupedit.tpl" );

$t->set_block( "group_edit", "module_list_header_tpl", "module_header" );
$t->set_block( "module_list_header_tpl", "permission_list_tpl", "permission_item" );
$t->set_block( "permission_list_tpl", "permission_enabled_tpl", "is_enabled_item" );

$headline = new INIFile( "ezuser/admin/intl/" . $Language . "/groupedit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

if ( $Action == "new" )
{
    $Name = "";
    $Description = "";
} 
$ActionValue = "insert";

// Edit
if ( $Action == "edit" )
{
    $group = new eZUserGroup();
    $group->get( $GroupID );

    $Name = $group->Name();
    $Description = $group->description();
    $SessionTimeout = $group->sessionTimeout();
    $IsRoot = $group->isRoot();
    $ActionValue = "update";

    $headline = new INIFile( "ezuser/admin/intl/" . $Language . "/groupedit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );
}

// List over all modules.
$module = new eZModule();
$moduleList = $module->getAll();

foreach ( $moduleList as $moduleItem )
{
    $t->set_var( "module_name", $moduleItem->name() );
    $t->set_var( "module_id", $moduleItem->id() );

    $permission = new eZPermission();
    $permissionList = $permission->getAllByModule( $moduleItem );

    $t->set_var( "permission_item", "" );

    foreach ( $permissionList as $permissionItem )
    {
        $t->set_var( "permission_name", $permissionItem->name() );
        $t->set_var( "permission_id", $permissionItem->id() );
        
        if ( $permissionItem->isEnabled( $group ) )
        {
            $t->set_var( "is_enabled", "checked" );
        }
        else
        {
            $t->set_var( "is_enabled", "" );
        }
        
        $t->parse( "permission_item", "permission_list_tpl", true );
    }

    if ( count( $permissionList ) > 0 )
        $t->parse( "module_header", "module_list_header_tpl", true );
}

$t->set_var( "error_msg", $error_msg );
$t->set_var( "name_value", $Name );
$t->set_var( "description_value", $Description );
$t->set_var( "session_timeout_value", $SessionTimeout );
$t->set_var( "action_value", $ActionValue );
( $IsRoot == true ) ? $t->set_var( "root_checked", "checked" ) : $t->set_var( "root_checked", "" );

$t->set_var( "group_id", $GroupID );

$t->pparse( "output", "group_edit" );

?>
