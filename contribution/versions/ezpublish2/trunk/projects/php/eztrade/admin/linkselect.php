<?php
// 
// $Id: linkselect.php,v 1.1 2001/03/21 13:36:26 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <16-Mar-2001 15:51:06 amos>
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
include_once( "eztrade/classes/ezproduct.php" );

function deleteCache( $ProductID )
{
    $CategoryID =& $ProductID->categoryDefinition( false );
    $CategoryArray =& $ProductID->categories( false );
    $Hotdeal = $ProductID->isHotDeal();
    $ProductID = $ProductID->id();

    $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, $CategoryID ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    $files = eZCacheFile::files( "eztrade/cache/", array( "productlist",
                                                          array_merge( $CategoryID, $CategoryArray ) ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    if ( $Hotdeal )
    {
        $files = eZCacheFile::files( "eztrade/cache/", array( "hotdealslist", NULL ),
                                     "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
    }
}

if ( isset( $Back ) )
{
    eZHTTPTool::header( "Location: /trade/productedit/edit/$ProductID" );
    exit();
}

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$Modules = $ini->read_array( "eZTradeMain", "ModuleList" );
$DefaultSection = $ini->read_var( "eZTradeMain", "DefaultSectionName" );
$ProductSections = $ini->read_array( "eZTradeMain", "ProductLinkSections" );

if ( isset( $ModuleType ) )
{
    include_once( "ezsession/classes/ezpreferences.php" );
    $preferences = new eZPreferences();
    $preferences->setVariable( "ProductLinkType", $ModuleType );
}

include_once( "classes/ezlinksection.php" );
if ( is_array( $SectionIDList ) )
{
    foreach( $SectionIDList as $id )
    {
        $name = $SectionName[$id];
        $section = new eZLinkSection( $id, "eZTrade" );
        $section->setName( $name );
        $section->store();
    }
}
$product = new eZProduct( $ProductID );
deleteCache( $product );
if ( isset( $SubmitInfo ) )
{
    eZHTTPTool::header( "Location: /trade/productedit/link/list/$ProductID" );
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
            eZLinkItem::delete( $id, "eZTrade" );
            $deleted = true;
        }
    }
    if ( is_array( $DeleteSectionID ) )
    {
        $module = new eZModuleLink( "eZTrade", "Product", $ProductID );
        foreach( $DeleteSectionID as $id )
        {
            $module->removeSection( $id );
            eZLinkSection::delete( $id, "eZTrade" );
            $deleted = true;
        }
    }
    if ( $deleted )
    {
        $product = new eZProduct( $ProductID );
        deleteCache( $product );
    }
    eZHTTPTool::header( "Location: /trade/productedit/link/list/$ProductID" );
    exit();
}

if ( isset( $NewSection ) )
{
    include_once( "classes/ezlinksection.php" );
    include_once( "classes/ezmodulelink.php" );
    $section = new eZLinkSection( false, "eZTrade" );
    $module = new eZModuleLink( "eZTrade", "Product", $ProductID );
    $section_count = $module->sectionCount();
    $section_name = isset( $ProductSections[$section_count] ) ? $ProductSections[$section_count] : $DefaultSection;
    $section->setName( $section_name );
    $section->store();
    $module->addSection( $section );
    $product = new eZProduct( $ProductID );
    deleteCache( $product );
    eZHTTPTool::header( "Location: /trade/productedit/link/list/$ProductID" );
    exit();
}

if ( !isset( $ModuleName ) or !isset( $Type ) )
{
    list($ModuleName,$Type) = explode( "/", $ModuleType );
    $module_lower = strtolower( $ModuleName );
    if ( $Type == "" )
        $Type = "null";
    eZHTTPTool::header( "Location: /trade/productedit/link/select/$ProductID/$module_lower/$Type/$SectionID" );
    exit();
}

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );

include_once( "eztrade/classes/ezproduct.php" );

$intl_dirs = array( "eztrade/admin/intl/" );
$php_files = array( "linkselect.php" );
$dir = strtolower( $ModuleName ) . "/user/intl/";
$file = strtolower( $ModuleName ) . "/user/urlsupplier.php";
if ( file_exists( $file ) )
{
    $intl_dirs[] = $dir;
    $php_files[] = "urlsupplier.php";
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     $intl_dirs, $Language, $php_files );

$t->set_file( "link_select_tpl", "linkselect.tpl" );

$t->set_block( "link_select_tpl", "normal_select_tpl", "normal_select" );
$t->set_block( "normal_select_tpl", "tree_select_tpl", "tree_select" );
$t->set_block( "normal_select_tpl", "url_select_tpl", "url_select" );

$t->set_block( "tree_select_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "tree_select_tpl", "company_list_tpl", "company_list" );
$t->set_block( "company_list_tpl", "company_item_tpl", "company_item" );

$t->set_block( "link_select_tpl", "module_select_tpl", "module_select" );
$t->set_block( "module_select_tpl", "module_item_tpl", "module_item" );

$t->set_var( "category_list", "" );
$t->set_var( "company_list", "" );

$module = $ModuleName;
$type = $Type;

$t->set_var( "product_id", $ProductID );
$t->set_var( "module_type", "$ModuleName/$Type" );
$t->set_var( "category_id", $Category );
$t->set_var( "section_id", $SectionID );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;
if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 2;

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
            eZHTTPTool::header( "Location: /trade/productedit/link/select/$ProductID/$module_lower/$Type/$SectionID" );
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
                    $list =& $Supplier->urlList( $type, $Category, $Offset, $Limit );
                    $categories =& $list["categories"];
                    $t->set_var( "category_item", "" );
                    $i = 0;
                    foreach( $categories as $category )
                    {
                        $t->set_var( "td_class", ($i % 2 ) == 0 ? "bglight" : "bgdark" );
                        $category_id = $category["id"];
                        $module_lower = strtolower( $module );
                        $url = "/trade/productedit/link/select/$ProductID/$module_lower/$type/$SectionID/$category_id";
                        $t->set_var( "category_item_id", $category_id );
                        $t->set_var( "category_url", $url );
                        $t->set_var( "category_name", $category["name"] );
                        $t->set_var( "category_orig_url", $category["url"] );
                        $t->parse( "category_item", "category_item_tpl", true );
                        ++$i;
                    }
                    if ( count( $categories ) > 0 )
                        $t->parse( "category_list", "category_list_tpl" );

                    $companies =& $list["items"];
                    $t->set_var( "company_item", "" );
                    $i = 0;
                    foreach( $companies as $company )
                    {
                        $t->set_var( "td_class", ($i % 2 ) == 0 ? "bglight" : "bgdark" );
                        $company_id = $company["id"];
                        $module_lower = strtolower( $module );
                        $t->set_var( "item_id", $company_id );
                        $t->set_var( "company_name", $company["name"] );
                        $t->set_var( "company_orig_url", $company["url"] );
                        $t->parse( "company_item", "company_item_tpl", true );
                        ++$i;
                    }
                    if ( count( $companies ) > 0 )
                        $t->parse( "company_list", "company_list_tpl" );

                    $TotalTypes = $list["item_total_count"];
                    eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "tree_select_tpl" );
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
