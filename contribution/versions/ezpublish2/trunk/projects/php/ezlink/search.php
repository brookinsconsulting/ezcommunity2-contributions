<?
// 
// $Id: search.php,v 1.18 2000/10/13 09:38:35 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

/*!
  listlink.php viser alle kategorier
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "common/ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php"  );
include_once( "ezlink/classes/ezhit.php" );

//  include_once( "classes/ezquery.php" );

// setter template filer
$t = new eZTemplate( "ezlink/" . $ini->read_var( "eZLinkMain", "TemplateDir" ). "/search/",
                     "ezlink/intl", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "search_item" => "searchitem.tpl",
    "search_list" => "searchlist.tpl"
    ) );

// make dynamic
$limit = 2;
$offset = 0;


$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

$t->set_var( "printpath", $linkGroup->printPath( $LGID, "ezlink/linklist.php" ) );

$link = new eZLink();

if ( $Action == "search" )
{
    $link_array = $link->getQuery( $QueryText, $limit, $offset );    
    $total_count = $link->getQueryCount( $QueryText, $limit );
}

// Lister alle linker i kategori
if ( count( $link_array ) == 0 )
{
    $t->set_var( "link_list", "Ingen linker funnet" );
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
                
        $t->set_var( "link_next_offs", $offset + $limit );
        $t->set_var( "link_prev_offs", $offset - $limit );
        $t->set_var( "query_text", $QueryText );

        $LGID =  ( $link_array[ $i ][ "LinkGroup" ] );

        $t->set_var( "printpath", $linkGroup->printPath( $LGID, "ezlink/linklist.php" ) );  

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

        $t->set_var( "link_hits", $hits );

        $tlink_message = "Linker";

        $t->parse( "link_list", "search_item", true );
    }
}

$t->set_var( "hit_count", $total_count );

$t->set_var( "link_message", $tlink_message );

$t->set_var( "linkgroup_id", $LGID );

$t->set_var( "printpath", $linkGroup->printPath( 0, "ezlink/linklist.php" ) );                       

$t->pparse( "output", "search_list" );
?>
