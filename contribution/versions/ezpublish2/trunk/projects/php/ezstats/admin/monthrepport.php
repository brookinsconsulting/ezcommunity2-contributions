<?
// 
// $Id: monthrepport.php,v 1.1 2001/01/07 15:56:31 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Jan-2001 14:47:04 bf>
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

$Language = $ini->read_var( "eZStatsMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "monthrepport.php" );

$t->setAllStrings();

$t->set_file( array(
    "month_repport_tpl" => "monthrepport.tpl"
    ) );

$t->set_block( "month_repport_tpl", "result_list_tpl", "result_list" );
$t->set_block( "result_list_tpl", "day_tpl", "day" );

$query = new eZPageViewQuery();

$monthRepport =& $query->monthStats( $Year, $Month );

if ( count( $monthRepport ) > 0 )
{
    $i=1;
    foreach ( $monthRepport["Days"] as $day )
    {
        $count = $day["Count"];
        $totalCount = $monthRepport["TotalPages"];
        
        $t->set_var( "page_view_count", $count );
        $t->set_var( "current_day", $i );

        $pageViewPercent = ( $count / $totalCount ) * 100;
        $pageViewPercent = round($pageViewPercent);

        $t->set_var( "page_view_percent", $pageViewPercent );
        $t->set_var( "page_view_percent_inverted", 100 - $pageViewPercent );

        $t->parse( "day", "day_tpl", true );
        $i++;
    }
    $t->set_var( "total_page_views", $monthRepport["TotalPages"] );

    $t->parse( "result_list", "result_list_tpl" );
}
else
{
    $t->set_var( "result_list", "" );
}

$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );



$t->pparse( "output", "month_repport_tpl" );


?>
