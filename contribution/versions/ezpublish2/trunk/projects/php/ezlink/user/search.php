<?
// 
// $Id: search.php,v 1.3 2000/10/26 13:08:34 ce-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
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

include_once( "classes/eztemplate.php" );
include_once( "common/ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php"  );
include_once( "ezlink/classes/ezhit.php" );

//  include_once( "classes/ezquery.php" );

// setter template filer
$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                     "ezlink/user/intl", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "search_list" => "searchlist.tpl"
    ) );

$t->set_block( "search_list", "search_item_tpl", "search_item" );

$t->set_block( "search_list", "empty_result_tpl", "empty_result" );
$t->set_block( "search_list", "search_result_tpl", "search_result" );

$t->set_block( "search_list", "previous_tpl", "previous" );
$t->set_block( "search_list", "next_tpl", "next" );

$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

$link = new eZLink();


if ( isSet( $URLQueryString ) )
{
    $QueryString = urldecode( $URLQueryString );
}

$t->set_var( "query_string", $QueryString );

$t->set_var( "previous", "" );
$t->set_var( "next", "" );

$t->set_var( "search_result", "" );



if ( $QueryString != "" )
{
    $t->set_var( "query_string", $QueryString );

    if ( !isset( $Offset ) )
        $Offset = 0;

    if ( !isset( $Limit ) )
        $Limit = 30;

    $link_array = $link->getQuery( $QueryString, $Limit, $Offset );    
    $total_count = $link->getQueryCount( $QueryString );

    $t->set_var( "empty_result_tpl", "" );
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
            $t->set_var( "link_groupid", $linkItem->linkgroupid() );
            $t->set_var( "link_keywords", $linkItem->keywords() );
            $t->set_var( "link_created", $linkItem->created() );
            $t->set_var( "link_modified", $linkItem->modified() );
            $t->set_var( "link_accepted", $linkItem->accepted() );
            $t->set_var( "link_url", $linkItem->url() );
                
            $prevOffs = $Offset - $Limit;
            $nextOffs = $Offset + $Limit;
                
            if ( $prevOffs >= 0 )
            {
                $t->set_var( "prev_offset", $prevOffs  );
                $t->parse( "previous", "previous_tpl" );
            }
            else
            {
                $t->set_var( "previous", "" );
            }
                
            if ( $nextOffs <= $total_count )
            {
                $t->set_var( "next_offset", $nextOffs  );
                $t->parse( "next", "next_tpl" );
            }
            else
            {
                $t->set_var( "next", "" );
            }
                
            $t->set_var( "limit", $Limit );
            $t->set_var( "query_text", $QueryText );
                
            $LGID =  ( $link_array[ $i ][ "LinkGroup" ] );
                
            $t->set_var( "printpath", $linkGroup->printPath( $LGID, "ezlink/linklist.php" ) );  
                
            $hit = new eZHit();
            $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

            $t->set_var( "link_hits", $hits );
                
            $tlink_message = "Linker";

            $t->set_var( "empty_result", "" );

            $t->parse( "search_item", "search_item_tpl", true );
            $i++;
        }
}
else
{
    $t->parse( "empty_result", "empty_result_tpl" );
} 


$t->set_var( "hit_count", $total_count );

$t->set_var( "link_message", $tlink_message );

$t->set_var( "linkgroup_id", $LGID );

$t->set_var( "printpath", $linkGroup->printPath( 0, "ezlink/linklist.php" ) );                       

$t->pparse( "output", "search_list" );
?>
