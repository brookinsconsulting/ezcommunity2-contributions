<?
/*!
    $Id: linklist.php,v 1.18 2000/08/14 09:52:34 bf-cvs Exp $

    Author: B�rd Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*
  linklist.php viser alle kategorier
*/

include_once( "template.inc" );
include_once( "ezphputils.php" );
include_once( "class.INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "../ezlink/classes/ezlinkgroup.php" );
include_once( "../ezlink/classes/ezlink.php" );
include_once( "../ezlink/classes/ezhit.php" );

// setter template filer
$t = new Template( "." );
$t->set_file( array(
    "linkgroup_list" => "../ezlink/templates/linkgrouplist.tpl",
    "linkgroup_item" => "../ezlink/templates/linkgroupitem.tpl",
    "link_item" => "../ezlink/templates/linkitem.tpl"
    ) );

// Lister alle kategorier
$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

$linkGroup->printPath( $LGID, "../ezlink/admin/linklist.php" );

$linkGroup_array = $linkGroup->getByParent( $LGID );

if ( ( count( $linkGroup_array ) == 0 ) || ( $LGID == "incoming" ) )
{
    $t->set_var( "group_list", "<p>Ingen kategorier ble funnet.</p>" );
}
else
{
    for ( $i=0; $i<count( $linkGroup_array ); $i++ )
    {

        if ( ( ( $i ) % 2 ) == 0 )
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
        
        $t->set_var( "document_root", $DOC_ROOT );
    
        $t->parse( "group_list", "linkgroup_item", true );

    }
}

if ( ( $LGID == 0 ) && ( $LGID != "incoming" ) )
{
    if ( ( ( $i / 2 ) % 2 ) == 0 )
    {
        $t->set_var( "bg_color", "#f0f0f0" );
    }
    else
    {
        $t->set_var( "bg_color", "#dcdcdc" );
    }  


    $t->set_var( "linkgroup_id", "incoming" );
    $t->set_var( "linkgroup_title", "Ikke godkjente liker..." );
    $t->set_var( "linkgroup_parent", "" );

    $total_sub_links = $linkGroup->getTotalIncomingLinks();
    
    $t->set_var( "total_links", $total_sub_links );
    $t->set_var( "new_links", "X" );
    
    $t->set_var( "document_root", $DOC_ROOT );
    
    $t->parse( "group_list", "linkgroup_item", true );
}


// Lister alle linker i kategori
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
    $t->set_var( "link_list", "<p>Ingen linker ble funnet.</p>" );
}
else
{
    for ( $i=0; $i<count( $link_array ); $i++ )
    {
        if ( ( ( $i / 2 ) % 2 ) == 0 )
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

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

        $t->set_var( "link_hits", $hits );

        $t->set_var( "document_root", $DOC_ROOT );

        $t->parse( "link_list", "link_item", true );
    }
}


$t->set_var( "document_root", $DOC_ROOT );
                       
$t->pparse( "output", "linkgroup_list" );

?>
