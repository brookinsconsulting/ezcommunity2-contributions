<?
// 
// $Id: menubox.php,v 1.11 2001/05/08 12:46:38 bf Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <17-Oct-2000 12:16:07 bf>
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
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZLinkMain", "Language" );

$PageCaching = $ini->read_var( "eZLinkMain", "PageCaching");

unset( $menuCachedFile );
// do the caching 
if ( $PageCaching == "enabled" )
{
    $menuCacheFile = new eZCacheFile( "ezlink/cache",
                                      array( "menubox", $GlobalSiteDesign ),
                                      "cache", "," );

    if ( $menuCacheFile->exists() )
    {
        print( $menuCacheFile->contents() );
    }
    else
    {
        createLinkMenu( $menuCacheFile );
    }
}
else
{
    createLinkMenu();
}

function createLinkMenu( $menuCacheFile=false )
{
    global $ini;
    global $Language;
    global $menuCachedFile;
	global $GlobalSiteDesign;
    
    include_once( "classes/eztemplate.php" );

    include_once( "ezlink/classes/ezlinkgroup.php" );
    include_once( "ezlink/classes/ezlink.php" );
    include_once( "ezlink/classes/ezhit.php" );

    $t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                         "ezlink/user/intl", $Language, "categorylist.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "link_group_tpl", "link_group" );
    $t->set_block( "menu_box_tpl", "no_link_group_tpl", "no_link_group" );

    $t->set_var( "link_group", "" );
    $t->set_var( "no_link_group", "" );

// Lister alle kategorier
    $linkGroup = new eZLinkGroup();

    $linkGroup_array = $linkGroup->getByParent( 0 );

    if ( count( $linkGroup_array ) == 0 )
    {
        $t->set_var( "group_list", "" );
        $t->parse( "no_link_group", "no_link_group_tpl" );
    }
    else
    {
        foreach( $linkGroup_array as $groupItem )
        {
            $link_group_id = $groupItem->id();
            
            $t->set_var( "linkgroup_id", $link_group_id );
            $t->set_var( "linkgroup_title", $groupItem->title() );
            
            $t->parse( "link_group", "link_group_tpl", true );
        }
    }
    $t->set_var( "linkgroup_id", $LGID );
                       
    $t->set_var( "sitedesign", $GlobalSiteDesign );

    if ( get_class( $menuCacheFile ) == "ezcachefile" )
    {
        $output = $t->parse( $target, "menu_box_tpl" );
        $menuCacheFile->store( $output );
        print( $output );
    }
    else
    {
		$t->pparse( "output", "menu_box_tpl" );
    }
}

?>
