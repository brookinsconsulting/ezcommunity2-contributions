<?
// $Id: unacceptedlist.php,v 1.4 2001/05/09 16:41:24 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:55:24 ce>
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
include_once( "classes/ezlist.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZLinkMain", "Language" );
$AdminLimit = $ini->read_var( "eZLinkMain", "AdminAcceptLimit" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
"ezlink/admin/intl/", $Language, "unacceptedlist.php" );
$t->setAllStrings();

$t->set_file( "unacceptedlist", "unacceptedlist.tpl" );

$t->set_block( "unacceptedlist", "link_item_tpl", "link_item" );
$t->set_block( "link_item_tpl", "category_item_tpl", "category_item" );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "link_item", "" );

if ( !$Offset )
    $Offset = 0;

$link = new eZLink();

$linkList =& $link->getNotAccepted( $Offset, $AdminLimit );
$linkCount = $link->unAcceptedCount();

$category = new eZLinkGroup();

$linkGroupList = $category->getTree();

$t->set_var( "link_count", count( $linkList ) );

$i=0;
foreach( $linkList as $linkItem )
{
    $t->set_var( "td_class", ( $i %2 ) == 0 ? "bglight" : "bgdark" );

    $t->set_var( "link_id", $linkItem->id() );
    $t->set_var( "link_name", $linkItem->title() );
    $t->set_var( "link_url", $linkItem->url() );
    $t->set_var( "link_description", $linkItem->description() );
    $t->set_var( "link_category_id", $linkItem->linkGroupID() );
    $t->set_var( "link_keywords", $linkItem->keywords() );

    $linkCategoryID = $linkItem->linkGroupID();

    $t->set_var( "category_item", "" );
    foreach( $linkGroupList as $linkGroupItem )
    {
        $t->set_var("link_group_id", $linkGroupItem[0]->id() );
        $t->set_var("link_group_title", $linkGroupItem[0]->title() );

        $t->set_var( "is_selected", $linkCategoryID == $linkGroupItem[0]->id() ? "selected" : "" );

        if ( $linkGroupItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $linkGroupItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "category_item", "category_item_tpl", true );
    }

    $t->set_var( "i", $i );

    $i++;
    $t->parse( "link_item", "link_item_tpl", true );
}
eZList::drawNavigator( $t, $linkCount, $AdminLimit, $Offset, "unacceptedlist" );

$t->set_var( "link_start", $Offset + 1 );
$t->set_var( "link_end", min( $Offset + $AdminLimit, $linkCount ) );
$t->set_var( "link_total", $linkCount );


$t->pparse( "output", "unacceptedlist" );
?>
