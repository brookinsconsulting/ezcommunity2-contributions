<?
// 
// $Id: linklist.php,v 1.2 2000/10/23 07:16:09 ce-cvs Exp $
//
// 
//
// B�rd Farstad <bf@ez.no>
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


$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                     "ezlink/user/intl", $Language, "linklist.php" );

$t->setAllStrings();

$t->set_file( array(
    "link_page_tpl" => "linklist.tpl"
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
    $i=0;
    foreach( $linkGroup_array as $groupItem )
    {
        if ( ( ( $i / 2 ) % 2 ) == 0 )
            $t->set_var( "td_class", "bglight"  );
        else
            $t->set_var( "td_class", "bgdark"  );

        $link_group_id = $groupItem->id();
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $groupItem->title() );
        $t->set_var( "linkgroup_parent", $groupItem->parent() );

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
        $i++;
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
    $i=0;
    foreach( $link_array as $linkItem )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#f0f0f0" );
        }
        else
        {
            $t->set_var( "bg_color", "#dcdcdc" );
        }
        $t->set_var( "link_id", $linkItem->id() );
        $t->set_var( "link_title", $linkItem->title() );
        $t->set_var( "link_description", $linkItem->description() );
        $t->set_var( "link_groupid",$linkItem->linkGroupID() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->accepted() );
        $t->set_var( "link_url", $linkItem->url() );

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $linkItem->id() );
        $t->set_var( "link_hits", $hits );

        $t->parse( "link_list", "link_list_tpl", true );
        $i++;
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
