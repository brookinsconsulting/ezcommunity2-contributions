<?
// 
// $Id: menubox.php,v 1.4 2000/10/17 11:52:59 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <17-Oct-2000 12:16:07 bf>
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
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );

$PageCaching = $ini->read_var( "eZLinkMain", "PageCaching");

// do the caching 
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "ezlink/cache/menubox.cache";
                    
    if ( file_exists( $cachedFile ) )
    {
        include( $cachedFile );
    }
    else
    {
        $GenerateStaticPage = "true";
        createLinkMenu();
    }            
}
else
{
    createLinkMenu();
}

function createLinkMenu()
{
    global $ini;
    global $Language;
    
    include_once( "classes/eztemplate.php" );
    include_once( "common/ezphputils.php" );

    include_once( "ezlink/classes/ezlinkgroup.php" );
    include_once( "ezlink/classes/ezlink.php" );
    include_once( "ezlink/classes/ezhit.php" );

    $t = new eZTemplate( "ezlink/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                         "ezlink/intl", $Language, "categorylist.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "link_group_tpl", "link_group" );

// Lister alle kategorier
    $linkGroup = new eZLinkGroup();
    $linkGroup->get ( $LGID );

    $linkGroup_array = $linkGroup->getByParent( 0 );

    if ( count( $linkGroup_array ) == 0 )
    {
        $t->set_var( "group_list", "" );

    }
    else
    {
        for ( $i=0; $i<count( $linkGroup_array ); $i++ )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "bg_color", "#FFFFFF" );
            }
            else
            {
                $t->set_var( "bg_color", "#FFFFFF" );
            }  

            $link_group_id = $linkGroup_array[ $i ][ "ID" ];
            $t->set_var( "linkgroup_id", $link_group_id );
            $t->set_var( "linkgroup_title", $linkGroup_array[ $i ][ "Title" ] );
            $t->set_var( "linkgroup_parent", $linkGroup_array[ $i ][ "Parent" ] );

            $total_sub_links = $linkGroup->getTotalSubLinks( $link_group_id, $link_group_id );
            $new_sub_links = $linkGroup->getNewSubLinks( $link_group_id, $link_group_id, 1 );
        
            $t->set_var( "total_links", $total_sub_links );
            $t->set_var( "new_links", $new_sub_links );
        
            $t->parse( "link_group", "link_group_tpl", true );

        }
    }
    $t->set_var( "linkgroup_id", $LGID );
                       
//      $t->pparse( "output", "link_group_list" );

    if ( $GenerateStaticPage == "true" )
    {
        $fp = fopen ( $menuCachedFile, "w+");

        $output = $t->parse( $target, "menu_box_tpl" );
        // print the output the first time while printing the cache file.
    
        print( $output );
        fwrite ( $fp, $output );
        fclose( $fp );
    }
    else
    {
        $t->pparse( "output", "menu_box_tpl" );
    }
    
}

?>
