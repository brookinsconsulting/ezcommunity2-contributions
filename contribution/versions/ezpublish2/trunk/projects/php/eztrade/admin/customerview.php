<?php
// 
// $Id: customerview.php,v 1.1 2001/09/21 15:11:24 bf Exp $
//
// Created on: <21-Sep-2001 16:06:44 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezlist.php" );

include_once( "eztrade/classes/ezorder.php" );
include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "customerview.php" );

$t->set_file( "customer_view_tpl", "customerview.tpl" );

$t->set_block( "customer_view_tpl", "order_list_tpl", "order_list" );
$t->set_block( "order_list_tpl", "order_item_tpl", "order_item" );

$t->setAllStrings();

$customer = new eZUser( $CustomerID );

$t->set_var( "customer_id", $customer->id() );
$t->set_var( "customer_first_name", $customer->firstName() );
$t->set_var( "customer_last_name", $customer->lastName() );
$t->set_var( "customer_email", $customer->email() );

$orders =& eZOrder::getByCustomer( $customer );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$t->set_var( "order_count", count( $orders ) );
foreach ( $orders as $order )
{
    $t->set_var( "order_id", $order->id() );
    $t->set_var( "order_date", $order->id() );

    if ( $order->isVATInc() == true )
        $currency->setValue( $order->totalPriceIncVAT() + $order->shippingCharge());
    else
        $currency->setValue( $order->totalPrice() + $order->shippingCharge() );
    $t->set_var( "order_price", $locale->format( $currency ) );

    $t->parse( "order_item",  "order_item_tpl", true );    
}

if ( count( $orders ) > 0 )
{
    $t->parse( "order_list",  "order_list_tpl" );
}
else
{
    $t->set_var( "order_list", "" );
}

$t->pparse( "output", "customer_view_tpl" );

?>
