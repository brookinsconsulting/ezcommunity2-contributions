<?
// 
// $Id: productreport.php,v 1.1 2001/01/11 16:17:07 bf Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <11-Jan-2001 14:47:56 bf>
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
                     "ezstats/admin/intl", $Language, "productreport.php" );

$t->setAllStrings();

$t->set_file( array(
    "product_report_tpl" => "productreport.tpl"
    ) );

$t->set_block( "product_report_tpl", "monst_viewed_product_tpl", "monst_viewed_product" );

$query = new eZPageViewQuery();

$productReport =& $query->topProductRequests( );



foreach ( $productReport as $product )
{
    $productArray[] = 
    $t->set_var( "product_name", $product["URI"] );
    $t->set_var( "view_count", $product["Count"] );

    $t->parse( "monst_viewed_product", "monst_viewed_product_tpl", true );
}

foreach ( $productReport as $product )
{
    $t->set_var( "product_name", $product["URI"] );
    $t->set_var( "view_count", $product["Count"] );

    $t->parse( "monst_viewed_product", "monst_viewed_product_tpl", true );
}

$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );


$t->pparse( "output", "product_report_tpl" );
 

?>
