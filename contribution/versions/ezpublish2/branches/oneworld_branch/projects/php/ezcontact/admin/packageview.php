<?php
//
// $Id: packageview.php,v 1.1.2.1 2002/06/04 06:40:22 jhe Exp $
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

if ( isSet( $Cancel ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/package/list" );
    exit();
}

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezcontact/classes/ezcontactpackage.php" );
include_once( "ezcontact/classes/ezcompany.php" );

require( "ezuser/admin/admincheck.php" );

if ( !eZObjectPermission::hasPermission( $PackageID, "contact_package", "w" ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/package/list" );
    exit();
}    

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "packageview.php" );
$t->setAllStrings();

$item_error = true;

if ( $Action == "update" )
{
    $package = new eZContactPackage( $PackageID );

    $package->removeAllCompanies();
    foreach ( $CompanyArray as $company )
    {
        $package->addCompany( $company );
    }
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/package/list" );
    exit();
}

$t->set_file( "list_page", "packageview.tpl" );

$t->set_block( "list_page", "group_item_tpl", "group_item" );

$t->set_var( "package_name", "" );
$t->set_var( "group_item", "" );

$t->set_var( "item_id", $PackageID );

$groupList = eZCompany::getAll();

if ( $Action == "edit" )
{
    $t->set_var( "action", "update" );
    $package = new eZContactPackage( $PackageID );
    $t->set_var( "package_name", $package->name() );
    $t->set_var( "package_description", $package->description() );
    $packageGroups = $package->getCompanies( false );
    foreach ( $groupList as $group )
    {
        $t->set_var( "group_name", $group->name() );
        $t->set_var( "group_id", $group->id() );
        $t->set_var( "selected", in_array( $group->id(), $packageGroups ) ? "selected" : "" );
        $t->parse( "group_item", "group_item_tpl", true );
    }
}

$t->pparse( "output", "list_page" );

?>
