<?
// 
// $Id: refererlist.php,v 1.1 2001/01/07 16:50:10 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Jan-2001 16:13:21 bf>
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
                     "ezstats/admin/intl", $Language, "refererlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "referer_page_tpl" => "refererlist.tpl"
    ) );

$t->set_block( "referer_page_tpl", "referer_list_tpl", "referer_list" );
$t->set_block( "referer_list_tpl", "referer_tpl", "referer" );

$query = new eZPageViewQuery();

$latest =& $query->topReferers( $ViewLimit );

if ( count( $latest ) > 0 )
{
    foreach ( $latest as $referer )
    {
        $t->set_var( "referer_domain", $referer["Domain"] );
        $t->set_var( "referer_uri", $referer["URI"] );
        
        $t->set_var( "page_view_count", $referer["Count"] );

        $t->parse( "referer", "referer_tpl", true );
    }

    $t->parse( "referer_list", "referer_list_tpl" );
}
else
{
    $t->set_var( "referer_list", "" );
}



$t->pparse( "output", "referer_page_tpl" );


?>
