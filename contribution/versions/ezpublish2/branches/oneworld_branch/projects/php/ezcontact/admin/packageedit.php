<?php
//
// $Id: packageedit.php,v 1.1.2.1 2002/06/04 06:40:22 jhe Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezcontact/classes/ezcontactpackage.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "packageedit.php" );
$t->setAllStrings();

$item_error = true;

if ( $Action == "delete" )
{
    $package = new eZContactPackage( $PackageID );
    $package->delete( true );
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/package/list" );
    exit();
}

if ( $Action == "up" )
{
    $item_type->moveUp();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if ( $Action == "down" )
{
    $item_type->moveDown();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if ( $Action == "insert" || $Action == "update" )
{
    if ( $Action == "insert" )
        $package = new eZContactPackage();
    else
        $package = new eZContactPackage( $PackageID );

    $package->setName( $Name );
    $package->setDescription( $Description );
    $package->store();

    eZObjectPermission::removePermissions( $package->id(), "contact_package", "w" );
    if ( in_array( "0", $GroupArray ) )
    {
        eZObjectPermission::setPermission( -1, $package->id(), "contact_package", "w" );
    }
    else
    {
        foreach ( $GroupArray as $group )
        {
            eZObjectPermission::setPermission( $group, $package->id(), "contact_package", "w" );
        }
    }
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/package/list" );
}

$t->set_file( "list_page", "packageedit.tpl" );

$t->set_block( "list_page", "group_item_tpl", "group_item" );

$t->set_var( "package_name", "" );
$t->set_var( "package_description", "" );
$t->set_var( "group_item", "" );

$t->set_var( "item_id", $PackageID );

$groupList = eZUserGroup::getAll();

if ( $Action == "edit" )
{
    $t->set_var( "action", "update" );
    $package = new eZContactPackage( $PackageID );
    $t->set_var( "package_name", $package->name() );
    $t->set_var( "package_description", $package->description() );
    $packageGroups = eZObjectPermission::getGroups( $package->id(), "contact_package", "w", false );
    $t->set_var( "all_selected", in_array( -1, $packageGroups ) ? "selected" : "" );
    foreach ( $groupList as $group )
    {
        $t->set_var( "group_name", $group->name() );
        $t->set_var( "group_id", $group->id() );
        $t->set_var( "selected", in_array( $group->id(), $packageGroups ) ? "selected" : "" );
        $t->parse( "group_item", "group_item_tpl", true );
    }
}
else if ( $Action == "new" )
{
    $t->set_var( "action", "insert" );
    $t->set_var( "package_name", "" );
    $t->set_var( "package_description", "" );
    foreach ( $groupList as $group )
    {
        $t->set_var( "group_name", $group->name() );
        $t->set_var( "group_id", $group->id() );
        $t->set_var( "selected", "" );
        $t->parse( "group_item", "group_item_tpl", true );
    }
}

$t->pparse( "output", "list_page" );

?>
