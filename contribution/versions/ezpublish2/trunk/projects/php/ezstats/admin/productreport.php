<?
// 
// $Id: productreport.php,v 1.2 2001/01/12 16:07:23 bf Exp $
//
// Bård Farstad <bf@ez.no>
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

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezorder.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "productreport.php" );

$t->setAllStrings();

$t->set_file( array(
    "product_report_tpl" => "productreport.tpl"
    ) );

$t->set_block( "product_report_tpl", "most_viewed_product_tpl", "most_viewed_product" );
$t->set_block( "product_report_tpl", "most_added_to_cart_products_tpl", "most_added_to_cart_products" );
$t->set_block( "product_report_tpl", "most_bought_products_tpl", "most_bought_products" );


$query = new eZPageViewQuery();

$tmpProduct = new eZProduct();

// most viewed products
$productReport =& $query->topProductRequests( );

$productArray = array();
foreach ( $productReport as $product )
{
    if ( preg_match( "#^/trade/productview/(.*?)/#", $product["URI"], $regArray ) )
    {
        $idx = $regArray[1];
        
        $count = $productArray[$idx]["Count"];
        
        $productArray[$idx]["Count"] = $count + $product["Count"];
        $productArray[$idx]["ID"] = $regArray[1];
    }
}

foreach ( $productArray as $productItem )
{
    $t->set_var( "product_name", $tmpProduct->productName( $productItem["ID"] ) );
    $t->set_var( "view_count", $productItem["Count"] );

    $t->parse( "most_viewed_product", "most_viewed_product_tpl", true );
}

// mostly added to cart

$productReport =& $query->topProductAddToCart( );

$productArray = array();
foreach ( $productReport as $product )
{
    if ( preg_match( "#^/trade/cart/add/(.*?)/#", $product["URI"], $regArray ) )
    {
        $idx = $regArray[1];
        
        $count = $productArray[$idx]["Count"];
        
        $productArray[$idx]["Count"] = $count + $product["Count"];
        $productArray[$idx]["ID"] = $regArray[1];
    }
}

foreach ( $productArray as $productItem )
{
    $t->set_var( "product_name", $tmpProduct->productName( $productItem["ID"]  ) );
    $t->set_var( "add_count", $productItem["Count"] );

    $t->parse( "most_added_to_cart_products", "most_added_to_cart_products_tpl", true );
}

// Most bought product

$order = new eZOrder();
$productReport =& $order->mostPopularProduct();

foreach ( $productReport as $productItem )
{
    $t->set_var( "product_name", $tmpProduct->productName( $productItem["ProductID"]  ) );

    $t->set_var( "buy_count", $productItem["Count"] );
    $t->set_var( "total_buy_count", $productItem["RealCount"] );

    $t->parse( "most_bought_products", "most_bought_products_tpl", true );
}

    
$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );


$t->pparse( "output", "product_report_tpl" );
 

?>
