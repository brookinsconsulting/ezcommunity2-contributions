<?
// 
// $Id: visitorlist.php,v 1.5 2001/03/01 14:06:25 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Jan-2001 12:56:58 bf>
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

$Language = $ini->read_var( "eZStatsMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/ezlist.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "visitorlist.php" );

$t->setAllStrings();

$t->set_file( "visitor_page_tpl", "visitorlist.tpl" );

$t->set_block( "visitor_page_tpl", "visitor_list_tpl", "visitor_list" );
$t->set_block( "visitor_list_tpl", "visitor_tpl", "visitor" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$latest =& eZPageViewQuery::topVisitors( $ViewLimit, $Offset );
$ItemCount = eZPageViewQuery::topVisitorsCount();

$t->set_var( "item_start", $Offset + 1 );
$t->set_var( "item_end", $Offset + $ViewLimit );
$t->set_var( "item_count", $ItemCount );
$t->set_var( "item_limit", $ViewLimit );

if ( count( $latest ) > 0 )
{
    $i = 0;
    foreach ( $latest as $visitor )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "bg_color", "bglight" );
        else
            $t->set_var( "bg_color", "bgdark" );

        $t->set_var( "remote_ip", $visitor["IP"] );
        $t->set_var( "remote_host_name", $visitor["HostName"] );
        $t->set_var( "page_view_count", $visitor["Count"] );

        $t->parse( "visitor", "visitor_tpl", true );
        $i++;
    }

    $t->parse( "visitor_list", "visitor_list_tpl" );
}
else
{
    $t->set_var( "visitor_list", "" );
}

eZList::drawNavigator( $t, $ItemCount, $ViewLimit, $Offset, "visitor_list" );

$t->pparse( "output", "visitor_page_tpl" );


?>
