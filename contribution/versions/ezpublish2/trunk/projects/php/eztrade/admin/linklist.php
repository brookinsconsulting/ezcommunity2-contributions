<?php
// 
// $Id: linklist.php,v 1.1 2001/03/21 13:36:26 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <16-Mar-2001 14:41:10 amos>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezhttptool.php" );
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

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$Modules = $ini->read_array( "eZTradeMain", "ModuleList" );

include_once( "eztrade/classes/ezproduct.php" );

$intl_dirs = array( "eztrade/admin/intl/" );
$php_files = array( "linklist.php" );
foreach( $Modules as $module )
{
    $dir = strtolower( $module ) . "/user/intl/";
    $file = strtolower( $module ) . "/user/urlsupplier.php";
    if ( file_exists( $file ) )
    {
        $intl_dirs[] = $dir;
        $php_files[] = "urlsupplier.php";
    }
}

include_once( "classes/ezmodulelink.php" );
include_once( "classes/ezlinksection.php" );
include_once( "classes/ezlinkitem.php" );

if ( isset( $ItemInsert ) )
{
    list($ModuleName,$Type) = explode( "/", $ModuleType );
    $module_lower = strtolower( $ModuleName );
    switch( $ModuleName )
    {
        case "std":
        {
            if ( $Type == "url" )
            {
                $link_item = new eZLinkItem( false, "eZTrade" );
                $link_item->setName( $URLName );
                $link_item->setURL( $URL );
                $link_item->setSection( $SectionID );
                $link_item->store();
                $product = new eZProduct( $ProductID );
                deleteCache( $product );
            }
            else
            {
            }
            break;
        }

        default:
        {
            $file = $module_lower . "/user/urlsupplier.php";
            if ( file_exists( $file ) )
            {
                unset( $Supplier );
                include( $file );
                if ( isset( $Supplier ) and get_class( $Supplier ) )
                {
                    if ( !is_array( $CategorySelect ) )
                        $CategorySelect = array();
                    if ( !is_array( $ItemSelect ) )
                        $ItemSelect = array();
                    foreach( $CategorySelect as $category )
                    {
                        $category_info = $Supplier->item( $Type, $category, true );
                        $link_item = new eZLinkItem( false, "eZTrade" );
                        $link_item->setName( $category_info["name"] );
                        $link_item->setURL( $category_info["url"] );
                        $link_item->setSection( $SectionID );
                        $link_item->store();
                    }
                    foreach( $ItemSelect as $item )
                    {
                        $item_info = $Supplier->item( $Type, $item, false );
                        $link_item = new eZLinkItem( false, "eZTrade" );
                        $link_item->setName( $item_info["name"] );
                        $link_item->setURL( $item_info["url"] );
                        $link_item->setSection( $SectionID );
                        $link_item->store();
                    }
                    $product = new eZProduct( $ProductID );
                    deleteCache( $product );
                }
            }
            break;
        }
    }
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     $intl_dirs, $Language, $php_files );

$t->set_file( "link_list_tpl", "linklist.tpl" );

$t->set_block( "link_list_tpl", "value_tpl", "value" );

$t->set_block( "link_list_tpl", "section_item_tpl", "section_item" );
$t->set_block( "section_item_tpl", "link_item_tpl", "link_item" );

$t->set_var( "value", "" );

include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();
$LinkType = $preferences->variable( "ProductLinkType" );
if ( is_bool( $LinkType ) )
    $LinkType = $ModuleType;

$t->set_var( "url_selected", "" );
foreach( $Modules as $module )
{
    $module_lower = strtolower( $module );
    $file = $module_lower . "/user/urlsupplier.php";
    if ( file_exists( $file ) )
    {
        unset( $Supplier );
        include( $file );
        if ( isset( $Supplier ) and get_class( $Supplier ) )
        {
            $types =& $Supplier->urlTypes();
            $t->set_var( "type_level", "" );
            $t->set_var( "selected", $LinkType == $module_lower ? "selected" : "" );
            $t->set_var( "module_type", $module_lower );
            $t->set_var( "type_name", $module );
            $t->parse( "value", "value_tpl", true );
            $t->set_var( "type_level", "&nbsp;" );
            reset( $types );
            while( list($key,$val) = each($types) )
            {
                $type = "$module_lower/$key";
                $t->set_var( "module_type", $type );
                $t->set_var( "type_name", $val );
                $t->set_var( "selected", $LinkType == $type ? "selected" : "" );
                $t->parse( "value", "value_tpl", true );
            }
            $t->set_var( "url_selected", $LinkType == "std/url" ? "selected" : "" );
        }
    }
}
$t->set_var( "url", $REQUEST_URI );
$t->set_var( "product_id", $ProductID );

$t->set_var( "section_item", "" );
$module_link = new eZModuleLink( "eZTrade", "Product", $ProductID );
$sections =& $module_link->sections();
$item = 0;
if ( !isset( $SectionID ) or ( $SectionID <= 0 ) )
{
    if ( count( $sections ) > 0 )
        $SectionID = $sections[0]->id();
    else
        $SectionID = 1;
}
foreach( $sections as $section )
{
    $t->set_var( "link_item", "" );
    $t->set_var( "section_name", $section->name() );
    $t->set_var( "section_id", $section->id() );
    $t->set_var( "section_checked", $section->id() == $SectionID ? "checked" : "" );
    $links =& $section->links();
    $i = 0;
    foreach( $links as $link )
    {
        $t->set_var( "td_class", ($i%2) == 0 ? "bglight" : "bgdark" );
        $t->set_var( "link_name", $link->name() );
        $t->set_var( "link_url", $link->url() );
        $t->set_var( "link_id", $link->id() );
        $t->parse( "link_item", "link_item_tpl", true );
        ++$i;
    }
    $t->parse( "section_item", "section_item_tpl", true );
    ++$item;
}

$t->setAllStrings();

$t->pparse( "output", "link_list_tpl" );

?>
