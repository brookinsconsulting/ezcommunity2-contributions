<?
// 
// $Id: search.php,v 1.14 2001/05/09 16:41:25 ce Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
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

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZLinkMain", "Language" );
$UserLimit = $ini->read_var( "eZLinkMain", "UserSearchLimit" );

include_once( "classes/eztemplate.php" );

include_once( "ezlink/classes/ezlink.php"  );
include_once( "ezlink/classes/ezhit.php" );
include_once( "classes/ezlist.php" );
include_once( "classes/ezhttptool.php" );

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

if ( !$Offset )
    $Offset = 0;

$link = new eZLink();

$t->set_var( "query_string", "" );
$t->set_var( "search_result", "" );

if ( $QueryString != "" )
{
    $link_array =& $link->getQuery( $QueryString, $UserLimit, $Offset );    
    $total_count = $link->getQueryCount( $QueryString );

    $t->set_var( "query_string", urlencode( $QueryString ) );
    $t->set_var( "empty_result", "" );
    $i=0;
    if ( count ( $link_array ) > 0 )
    {
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
                
            $hit = new eZHit();
            $hits = $hit->getLinkHits( $linkItem->id() );

            $t->set_var( "link_hits", $hits );
                
            $t->set_var( "empty_result", "" );
            $t->parse( "search_item", "search_item_tpl", true );
            $i++;
        }
    }
    else
    {
        $t->set_var( "search_item", "" );
        $t->parse( "empty_result", "empty_result_tpl" );
    }
}
else
{
    $t->set_var( "query_string", "" );
    $t->set_var( "search_item", "" );
    $t->parse( "empty_result", "empty_result_tpl" );
}
eZList::drawNavigator( $t, $total_count, $UserLimit, $Offset, "search_list" );

$t->set_var( "link_start", $Offset + 1 );
$t->set_var( "link_end", min( $Offset + $UserLimit, $total_count ) );
$t->set_var( "link_total", $total_count );


$t->set_var( "hit_count", $total_count );

$t->pparse( "output", "search_list" );
?>
