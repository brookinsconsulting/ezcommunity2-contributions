<?
// 
// $Id: linklist.php,v 1.36 2000/10/17 11:52:59 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <17-Oct-2000 12:17:13 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );

include_once( "common/ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );


$t = new eZTemplate( "ezlink/" . $ini->read_var( "eZLinkMain", "TemplateDir" ). "/linklist/",
                     "ezlink/intl", $Language, "linklist.php" );

$t->setAllStrings();

$t->set_file( array(
    "link_page_tpl" => "linkpage.tpl"
    ) );

$t->set_block( "link_page_tpl", "group_list_tpl", "group_list" );
$t->set_block( "link_page_tpl", "link_list_tpl", "link_list" );

// List all the categorys
$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

$linkGroup_array = $linkGroup->getByParent( $LGID );

if ( count( $linkGroup_array ) == 0 )
{
    $t->set_var( "group_list", "<p>Ingen grupper funnet.</p>" );
}
else
{
    for ( $i=0; $i<count( $linkGroup_array ); $i++ )
    {
                if ( ( ( $i / 2 ) % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#f0f0f0" );
        }
        else
        {
            $t->set_var( "bg_color", "#dcdcdc" );
        }  

        $link_group_id = $linkGroup_array[ $i ][ "ID" ];
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $linkGroup_array[ $i ][ "Title" ] );
        $t->set_var( "linkgroup_parent", $linkGroup_array[ $i ][ "Parent" ] );

        $total_sub_links = $linkGroup->getTotalSubLinks( $link_group_id, $link_group_id );
        $new_sub_links = $linkGroup->getNewSubLinks( $link_group_id, $link_group_id, 1 );
        
        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );
        
        
        if ( $i %2 == 0 )
        {
            $t->set_var( "start_tr", "<tr>" );
            $t->set_var( "stop_tr", "" );
        }
        else
        {
            $t->set_var( "start_tr", "" );
            $t->set_var( "stop_tr", "</tr>" );            
        }
        $t->parse( "group_list", "group_list_tpl", true );
    }
}


// List all the links in the category
$link = new eZLink();

if ( $Action == "search" )
{
    $link_array = $link->getQuery( $QueryText );    
}
else if ( $LGID == "incoming" )
{
    $link_array = $link->getNotAccepted( $LGID );
}
else
{
    $link_array = $link->getByGroup( $LGID );
} 

if ( count( $link_array ) == 0 )
{
    if ( $LGID == 0 )
    {
        $t->set_var( "link_list", "" );
    }
    else
    {
    $t->set_var( "link_list", "<p>Ingen linker ble funnet.</p>" );
    }
    
}
else
{
    for ( $i=0; $i<count( $link_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#f0f0f0" );
        }
        else
        {
            $t->set_var( "bg_color", "#dcdcdc" );
        }  

        $t->set_var( "link_id", $link_array[ $i ][ "ID" ] );
        $t->set_var( "link_title", $link_array[ $i ][ "Title" ] );
        $t->set_var( "link_description", $link_array[ $i ][ "Description" ] );
        $t->set_var( "link_groupid", $link_array[ $i ][ "LinkGroup" ] );
        $t->set_var( "link_keywords", $link_array[ $i ][ "KeyWords" ] );
        $t->set_var( "link_created", $link_array[ $i ][ "Created" ] );
        $t->set_var( "link_modified", $link_array[ $i ][ "Modified" ] );
        $t->set_var( "link_accepted", $link_array[ $i ][ "Accepted" ] );
        $t->set_var( "link_url", $link_array[ $i ][ "Url" ] );

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );
        $t->set_var( "link_hits", $hits );

        $t->parse( "link_list", "link_list_tpl", true );
    }
}

$t->set_var( "printpath", $linkGroup->printPath( $LGID, "ezlink/linklist.php" ) );

$t->set_var( "linkgroup_id", $LGID );
                       

if ( $GenerateStaticPage == "true" )
{
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse($target, "link_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "link_page_tpl" );    
}


?>
