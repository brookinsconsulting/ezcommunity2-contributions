<?php
// 
// $Id: ordersendt.php,v 1.28 2001/03/23 16:57:59 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <06-Oct-2000 14:04:17 bf>
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


include_once( "classes/eztemplate.php" ); 
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" ); 
include_once( "eztrade/classes/ezorder.php" ); 
include_once( "eztrade/classes/ezproduct.php" ); 
include_once( "eztrade/classes/ezcheckout.php" ); 

include_once( "classes/ezhttptool.php" );


$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "ordersendt.php" );

$t->setAllStrings();

$t->set_file( array(
    "order_sendt_tpl" => "ordersendt.tpl"
    ) );


$t->set_block( "order_sendt_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "order_sendt_tpl", "shipping_address_tpl", "shipping_address" );

$t->set_block( "order_sendt_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_item_list_tpl", "order_item_tpl", "order_item" );
$t->set_block( "order_item_tpl", "order_image_tpl", "order_image" );

$t->set_block( "order_item_tpl", "order_item_option_tpl", "order_item_option" );


$order = new eZOrder( $OrderID );


// get the customer
$user = $order->user();

$currentUser = eZUser::currentUser();

// check if the user is logged i
if ( !( $currentUser && $user ) ) 
{
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

// check if the user owns the order
if ( $currentUser->id() != $user->id() )
{
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

if ( $user )
{
    // print out the addresses
    $billingAddress = $order->billingAddress();

    $t->set_var( "customer_first_name", $user->firstName() );
    $t->set_var( "customer_last_name", $user->lastName() );

    $t->set_var( "billing_street1", $billingAddress->street1() );
    $t->set_var( "billing_street2", $billingAddress->street2() );
    $t->set_var( "billing_zip", $billingAddress->zip() );
    $t->set_var( "billing_place", $billingAddress->place() );
    
    $country = $billingAddress->country();

    if ( $country )
    {
        if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
            $t->set_var( "billing_country", $country->name() );
        else
            $t->set_var( "billing_country", "" );
    }
        
    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
        $t->parse( "billing_address", "billing_address_tpl" );
    else
        $t->set_var( "billing_address", "" );

    $shippingUser = $order->shippingUser();

    if ( $shippingUser )
    {
        $t->set_var( "shipping_first_name", $shippingUser->firstName() );
        $t->set_var( "shipping_last_name", $shippingUser->lastName() );
        
    }

    $shippingAddress = $order->shippingAddress();

    $t->set_var( "shipping_street1", $shippingAddress->street1() );
    $t->set_var( "shipping_street2", $shippingAddress->street2() );
    $t->set_var( "shipping_zip", $shippingAddress->zip() );
    $t->set_var( "shipping_place", $shippingAddress->place() );
    
    $country = $shippingAddress->country();

    if ( $country )
    {
        if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
            $t->set_var( "shipping_country", $country->name() );
        else
            $t->set_var( "shipping_country", "" );
    }
        
    $t->parse( "shipping_address", "shipping_address_tpl" );
}


// fetch the order items
$items = $order->items( $OrderType );


$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$i = 0;
$sum = 0.0;
$totalVAT = 0.0;
foreach ( $items as $item )
{
    $product = $item->product();

    $image = $product->thumbnailImage();

    if ( $image )
    {
        $thumbnail =& $image->requestImageVariation( 35, 35 );        

        $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
        $t->set_var( "product_image_width", $thumbnail->width() );
        $t->set_var( "product_image_height", $thumbnail->height() );
        $t->set_var( "product_image_caption", $image->caption() );
            
        $t->parse( "order_image", "order_image_tpl" );            
    }
    else
    {
        $t->set_var( "order_image", "" );
    }
    
    $price = $item->price() * $item->count();
    $currency->setValue( $price );

    $sum += $price;

    $totalVAT += $product->vat( $price ) * $item->count();
    
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_price", $locale->format( $currency ) );

    $t->set_var( "order_item_count", $item->count() );
    
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $optionValues =& $item->optionValues();

    $t->set_var( "order_item_option", "" );
    foreach ( $optionValues as $optionValue )
    {
        $t->set_var( "option_name", $optionValue->optionName() );
        $t->set_var( "option_value", $optionValue->valueName() );
            
        $t->parse( "order_item_option", "order_item_option_tpl", true );
    }
        
    $t->parse( "order_item", "order_item_tpl", true );
        
    $i++;
}

$t->parse( "order_item_list", "order_item_list_tpl" );

$checkout = new eZCheckout();
$instance =& $checkout->instance();
$paymentMethod = $instance->paymentName( $order->paymentMethod() );

$t->set_var( "payment_method", $paymentMethod );

$shippingType = $order->shippingType();
if ( $shippingType )
{    
    $t->set_var( "shipping_type", $shippingType->name() );
}


$shippingCost = $order->shippingCharge();
$shippingVAT = $order->shippingVAT();
$currency->setValue( $shippingCost );

$t->set_var( "shipping_cost", $locale->format( $currency ) );

$sum += $shippingCost;
$currency->setValue( $sum );
$t->set_var( "order_sum", $locale->format( $currency ) );

$currency->setValue( $totalVAT + $shippingVAT );
$t->set_var( "order_vat_sum", $locale->format( $currency ) );


$t->set_var( "order_id", $OrderID );



$t->pparse( "output", "order_sendt_tpl" );

?>
