<?
// 
// $Id: overview.php,v 1.1 2001/01/07 12:31:48 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <05-Jan-2001 11:23:51 bf>
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
                     "ezstats/admin/intl", $Language, "overview.php" );

$t->setAllStrings();

$t->set_file( array(
    "overview_tpl" => "overview.tpl"
    ) );

$query = new eZPageViewQuery();

$t->set_var( "total_page_views", $query->totalPageViews() );

$today = new eZDate();
$pagesToday = $query->totalPageViewsDay( $today );
$pagesThisMonth = $query->totalPageViewsMonth( $today );

$t->set_var( "total_pages_today", $pagesToday );

$t->set_var( "total_pages_this_month", $pagesThisMonth );

$t->pparse( "output", "overview_tpl" );

?>
