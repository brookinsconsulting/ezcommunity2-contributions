<?php
// 
// $Id: linkselect.php,v 1.1 2001/04/30 17:42:15 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <30-Apr-2001 18:33:53 amos>
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

include_once( "classes/ezhttptool.php" );
include_once( "classes/ezcachefile.php" );

if ( !isset( $ClientIntlDir ) )
{
    $ClientIntlDir = "classes/admin/intl/";
}
if ( !isset( $ClientRoot ) )
{
    $ClientRoot = "classes/";
}

if ( isset( $Back ) )
{
    eZHTTPTool::header( sprintf( "Location: " . $URLS["back"], $ItemID ) );
    exit();
}

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( $INIGroup, "Language" );
$Modules = $ini->read_array( $INIGroup, "ModuleList" );
$DefaultSection = $ini->read_var( $INIGroup, "DefaultSectionName" );
$DefaultSections = $ini->read_array( $INIGroup, $DefaultSectionsName );

if ( isset( $ModuleType ) )
{
    include_once( "ezsession/classes/ezpreferences.php" );
    $preferences = new eZPreferences();
    $preferences->setVariable( $PreferencesSetting, $ModuleType );
}

include_once( "classes/ezlinksection.php" );
if ( is_array( $SectionIDList ) )
{
    foreach( $SectionIDList as $id )
    {
        $name = $SectionName[$id];
        $section = new eZLinkSection( $id, $ClientModuleName );
        $section->setName( $name );
        $section->store();
    }
}
$Funcs["delete"]( $ItemID );
if ( isset( $SubmitInfo ) )
{
    eZHTTPTool::header( sprintf( "Location: " . $URLS["linklist"], $ItemID ) );
    exit();
}

if ( isset( $DeleteLink ) )
{
    include_once( "classes/ezlinkitem.php" );
    include_once( "classes/ezlinksection.php" );
    include_once( "classes/ezmodulelink.php" );
    $deleted = false;
    if ( is_array( $DeleteLinkID ) )
    {
        foreach( $DeleteLinkID as $id )
        {
            eZLinkItem::delete( $id, $ClientModuleName );
            $deleted = true;
        }
    }
    if ( is_array( $DeleteSectionID ) )
    {
        $module = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
        foreach( $DeleteSectionID as $id )
        {
            $module->removeSection( $id );
            eZLinkSection::delete( $id, $ClientModuleName );
            $deleted = true;
        }
    }
    if ( $deleted )
    {
        $Funcs["delete"]( $ItemID );
    }
    eZHTTPTool::header( sprintf( "Location:" . $URLS["linklist"], $ItemID ) );
    exit();
}

include_once( "classes/ezmodulelink.php" );

$module_link = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
$sections =& $module_link->sections();

if ( isset( $NewSection ) or count( $sections ) == 0 )
{
    include_once( "classes/ezlinksection.php" );
    $section = new eZLinkSection( false, $ClientModuleName );
    $module = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
    $section_count = $module->sectionCount();
    $section_name = isset( $DefaultSections[$section_count] ) ? $DefaultSections[$section_count] : $DefaultSection;
    $section->setName( $section_name );
    $section->store();
    $SectionID = $section->id();
    $module->addSection( $section );
    $Funcs["delete"]( $ItemID );
    if ( isset( $NewSection ) )
    {
        eZHTTPTool::header( sprintf( "Location: " . $URLS["linklist"], $ItemID ) );
        exit();
    }
}

if ( !isset( $ModuleName ) or !isset( $Type ) )
{
    list($ModuleName,$Type) = explode( "/", $ModuleType );
    $module_lower = strtolower( $ModuleName );
    if ( $Type == "" )
        $Type = "null";
    eZHTTPTool::header( sprintf( "Location: " . $URLS["linkselect"], $ItemID, $module_lower, $Type, $SectionID, "" ) );
    exit();
}

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );

$intl_dirs = array( $ClientIntlDir );
$php_files = array( "linkselect.php" );
$dir = strtolower( $ModuleName ) . "/user/intl/";
$file = strtolower( $ModuleName ) . "/user/urlsupplier.php";
if ( file_exists( $file ) )
{
    $intl_dirs[] = $dir;
    $php_files[] = "urlsupplier.php";
}

$t = new eZTemplate( $ClientRoot . $ini->read_var( $INIGroup, "AdminTemplateDir" ),
                     $intl_dirs, $Language, $php_files );

$t->set_file( "link_select_tpl", "linkselect.tpl" );

$t->set_block( "link_select_tpl", "normal_select_tpl", "normal_select" );
$t->set_block( "normal_select_tpl", "tree_select_tpl", "tree_select" );
$t->set_block( "normal_select_tpl", "url_select_tpl", "url_select" );

$t->set_block( "tree_select_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "tree_select_tpl", "object_list_tpl", "object_list" );
$t->set_block( "object_list_tpl", "object_item_tpl", "object_item" );

$t->set_block( "link_select_tpl", "module_select_tpl", "module_select" );
$t->set_block( "module_select_tpl", "module_item_tpl", "module_item" );

$t->set_var( "category_list", "" );
$t->set_var( "object_list", "" );

$t->set_var( "link_select_url", sprintf( $URLS["linkselect"], "", "", "", "", "" ) );
$t->set_var( "link_list_url", sprintf( $URLS["linklist"], "" ) );

$module = $ModuleName;
$type = $Type;

$t->set_var( "object_id", $ItemID );
$t->set_var( "module_type", "$ModuleName/$Type" );
$t->set_var( "category_id", $Category );
$t->set_var( "section_id", $SectionID );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$t->set_var( "normal_select", "" );
$t->set_var( "module_select", "" );
$t->set_var( "tree_select", "" );
$t->set_var( "url_select", "" );

switch( $module )
{
    case "std":
    {
        if ( $type == "url" )
        {
            $t->parse( "url_select", "url_select_tpl" );
            $t->parse( "normal_select", "normal_select_tpl" );
        }
        else
        {
            $module_lower = strtolower( $module );
            eZHTTPTool::header( sprintf( "Location: " . $URLS["linkselect"], $ItemID, $module_lower, $Type, $SectionID, "" ) );
            exit();
        }
        break;
    }

    default:
    {
        if ( $Type == "null" )
        {
            $t->set_var( "module_item", "" );
            $file = strtolower( $module ) . "/user/urlsupplier.php";
            if ( file_exists( $file ) )
            {
                unset( $Supplier );
                include( $file );
                if ( isset( $Supplier ) and get_class( $Supplier ) )
                {
                    $types =& $Supplier->urlTypes();
                    $t->set_var( "module_name", $module );
                    reset( $types );
                    while( list($key,$val) = each( $types ) )
                    {
                        $t->set_var( "module_type", $key );
                        $t->set_var( "module_type_name", $val );
                        $t->parse( "module_item", "module_item_tpl", true );
                    }
                }
            }
            $t->parse( "module_select", "module_select_tpl" );
        }
        else
        {
            $file = strtolower( $module ) . "/user/urlsupplier.php";
            if ( file_exists( $file ) )
            {
                unset( $Supplier );
                include( $file );
                if ( isset( $Supplier ) and get_class( $Supplier ) )
                {
                    $list =& $Supplier->urlList( $type, $Category, $Offset );
                    $categories =& $list["categories"];
                    $t->set_var( "category_item", "" );
                    $i = 0;
                    foreach( $categories as $category )
                    {
                        $t->set_var( "td_class", ($i % 2 ) == 0 ? "bglight" : "bgdark" );
                        $category_id = $category["id"];
                        $module_lower = strtolower( $module );
                        $url = sprintf( $URLS["linkselect"], $ItemID, $module_lower, $type, $SectionID, $category_id );
                        $t->set_var( "category_item_id", $category_id );
                        $t->set_var( "category_url", $url );
                        $t->set_var( "category_name", $category["name"] );
                        $t->set_var( "category_orig_url", $category["url"] );
                        $t->parse( "category_item", "category_item_tpl", true );
                        ++$i;
                    }
                    if ( count( $categories ) > 0 )
                        $t->parse( "category_list", "category_list_tpl" );

                    $objects =& $list["items"];
                    $t->set_var( "object_item", "" );
                    $i = 0;
                    foreach( $objects as $object )
                    {
                        $t->set_var( "td_class", ($i % 2 ) == 0 ? "bglight" : "bgdark" );
                        $object_id = $object["id"];
                        $module_lower = strtolower( $module );
                        $t->set_var( "item_id", $object_id );
                        $t->set_var( "object_name", $object["name"] );
                        $t->set_var( "object_orig_url", $object["url"] );
                        $t->parse( "object_item", "object_item_tpl", true );
                        ++$i;
                    }
                    if ( count( $objects ) > 0 )
                        $t->parse( "object_list", "object_list_tpl" );

                    $TotalTypes = $list["item_total_count"];
                    eZList::drawNavigator( $t, $TotalTypes, $list["max_items_shown"], $Offset, "tree_select_tpl" );
                }
            }
            $t->parse( "tree_select", "tree_select_tpl" );
            $t->parse( "normal_select", "normal_select_tpl" );
        }
        break;
    }
}

$t->set_var( "site_style", $SiteStyle );

$t->setAllStrings();

$t->pparse( "output", "link_select_tpl" );

?>
