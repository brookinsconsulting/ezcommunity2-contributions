<?
// 
// $Id: requestpagelist.php,v 1.1 2001/01/07 16:50:10 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Jan-2001 16:25:31 bf>
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
                     "ezstats/admin/intl", $Language, "requestpagelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "request_page_tpl" => "requestpagelist.tpl"
    ) );

$t->set_block( "request_page_tpl", "request_list_tpl", "request_list" );
$t->set_block( "request_list_tpl", "request_tpl", "request" );

$query = new eZPageViewQuery();

$latest =& $query->topRequests( $ViewLimit );

$headers = getallheaders();
$request_domain = $headers["Host"];

$request_domain = preg_replace( "#^admin.#", "", $request_domain );


if ( count( $latest ) > 0 )
{
    foreach ( $latest as $request )
    {
        $t->set_var( "request_domain", $request_domain );
        
        $t->set_var( "request_uri", $request["URI"] );
        
        $t->set_var( "page_view_count", $request["Count"] );

        $t->parse( "request", "request_tpl", true );
    }

    $t->parse( "request_list", "request_list_tpl" );
}
else
{
    $t->set_var( "request_list", "" );
}



$t->pparse( "output", "request_page_tpl" );


?>
