<?
// 
// $Id: entryexitpages.php,v 1.3 2001/01/22 14:43:01 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <12-Jan-2001 16:31:41 bf>
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
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZStatsMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "entryexitpages.php" );

$t->setAllStrings();

$t->set_file( array(
    "entry_exit_report_tpl" => "entryexitpages.tpl"
    ) );

$t->set_block( "entry_exit_report_tpl", "exit_page_tpl", "exit_page" );
$t->set_block( "entry_exit_report_tpl", "entry_page_tpl", "entry_page" );

$query = new eZPageViewQuery();

// exit pages
$exitPages =& $query->topExitPage();

$exitPageArray = array();

foreach ( $exitPages as $page )
{
    $exitPageArray[$page]["Count"] += 1;
    $exitPageArray[$page]["PageID"] = $page;
}

arsort( $exitPageArray );

$pageView = new eZPageView();
$ExitPageLimit = 20;

$i=0;
foreach ( $exitPageArray as $exitPage )
{
    $t->set_var( "page_uri", $pageView->requestPageByID( $exitPage["PageID"] ) );
    $t->set_var( "exit_count", $exitPage["Count"] );

    $t->parse( "exit_page", "exit_page_tpl", true );
    
    $i++;
    if ( $i>=$ExitPageLimit )
        break;
}

// entry pages
$entryPages =& $query->topEntryPage();

$entryPageArray = array();

foreach ( $entryPages as $page )
{
    $entryPageArray[$page]["Count"] += 1;
    $entryPageArray[$page]["PageID"] = $page;
}

arsort( $entryPageArray );

$EntryPageLimit = 20;

$i=0;
foreach ( $entryPageArray as $entryPage )
{
    $t->set_var( "page_uri", $pageView->requestPageByID( $entryPage["PageID"] ) );
    $t->set_var( "entry_count", $entryPage["Count"] );

    $t->parse( "entry_page", "entry_page_tpl", true );
    
    $i++;
    if ( $i>=$EntryPageLimit )
        break;
}


$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );

$t->pparse( "output", "entry_exit_report_tpl" );


?>
