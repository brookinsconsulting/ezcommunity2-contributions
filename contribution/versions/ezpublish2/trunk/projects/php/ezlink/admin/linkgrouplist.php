<?
// $Id: linkgrouplist.php,v 1.8 2000/12/15 11:45:30 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:55:24 ce>
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

//  $ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );
$languageIni = new INIFile( "ezlink/admin/intl/" . $Language . "/linkgrouplist.php.ini", false );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
"ezlink/admin/intl/", $Language, "linkgrouplist.php" );
$t->setAllStrings();

$t->set_file( array(
    "link_page_tpl" => "linkgrouplist.tpl"
    ) );

$t->set_block( "link_page_tpl", "group_list_tpl", "group_list" );
$t->set_block( "group_list_tpl", "group_item_tpl", "group_item" );

$t->set_block( "link_page_tpl", "link_list_tpl", "link_list" );
$t->set_block( "link_list_tpl", "link_item_tpl", "link_item" );

$t->set_block( "link_page_tpl", "path_item_tpl", "path_item" );

// Lister alle kategorier
$linkGroup = new eZLinkGroup();

$linkGroup->get ( $LinkGroupID );

// path
$pathArray = $linkGroup->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "group_id", $path[0] );

    $t->set_var( "group_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$linkGroupList = $linkGroup->getByParent( $LinkGroupID );

if ( $LinkGroupID == "incoming" )
{
    $linkGroupList = array();
}

if ( count( $linkGroupList ) == 0 )
{
    $t->set_var( "categories", "" );
    $t->set_var( "group_list", "" );
}
else
{
    $i=0;
    foreach( $linkGroupList as $linkGroupItem )
    {
        if ( ( ( $i ) % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#f0f0f0" );
        }
        else
        {
            $t->set_var( "bg_color", "#dcdcdc" );
        }  
        
        $link_group_id = $linkGroupItem->id();
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $linkGroupItem->title() );
        $t->set_var( "linkgroup_parent", $linkGroupItem->parent() );
        
//      $total_sub_links = $linkGroup->getTotalSubLinks( $link_group_id, $link_group_id );
//      $new_sub_links = $linkGroup->getNewSubLinks( $link_group_id, $link_group_id, 1 );
        
        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );
        
        $t->set_var( "document_root", $DOC_ROOT );

        $categories = $languageIni->read_var( "strings", "categories" );
        
        $t->parse( "group_item", "group_item_tpl", true );
        $i++;
    }
    $t->parse( "group_list", "group_list_tpl", true );
}

// List all the links in category
$link = new eZLink();
if ( $LinkGroupID == "incoming" )
{
    $linkList = $link->getNotAccepted( $LinkGroupID );
}
else
{
    $linkList = $link->getByGroup( $LinkGroupID );
} 

if ( !$linkList )
{
    $t->set_var( "links", "" );
    $t->set_var( "link_list", "" );
}
else
{
    $i=0;
    foreach( $linkList as $linkItem )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        
        $t->set_var( "link_id", $linkItem->id() );
        $t->set_var( "link_title", $linkItem->title() );
        $t->set_var( "link_description", $linkItem->description() );
        $t->set_var( "link_groupid", $linkItem->linkgroupid() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->id() );
        $t->set_var( "link_url", $linkItem->url() );
        
        $hit = new eZHit();
        $hits = $hit->getLinkHits( $linkItem->id() );
        
        $t->set_var( "link_hits", $hits );

        $links = $languageIni->read_var( "strings", "links" );
        
        $t->parse( "link_item", "link_item_tpl", true );
        $i++;
    }
    $t->parse( "link_list", "link_list_tpl", true );
}

$t->set_var( "categories", $categories );
$t->set_var( "links", $links );
$t->set_var( "document_root", $DOC_ROOT );
                       
$t->pparse( "output", "link_page_tpl" );
?>
