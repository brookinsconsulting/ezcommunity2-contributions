<?php
// 
// $Id: ordersendt.php,v 1.31 2001/07/30 14:19:03 jhe Exp $
//
// Created on: <06-Oct-2000 14:04:17 bf>
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


include_once( "classes/eztemplate.php" ); 
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" ); 
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "eztrade/classes/ezorder.php" ); 
include_once( "eztrade/classes/ezproduct.php" ); 
include_once( "eztrade/classes/ezcheckout.php" ); 

include_once( "classes/ezhttptool.php" );


$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "ordersendt.php" );

$t->setAllStrings();

$t->set_file( "order_sendt_tpl", "ordersendt.tpl" );

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

    if ( $order->personID() == 0 && $order->companyID() == 0 )
    {
        $t->set_var( "customer_first_name", $user->firstName() );
        $t->set_var( "customer_last_name", $user->lastName() );
    }
    else
    {
        if ( $order->personID() > 0 )
        {
            $customer = new eZPerson( $order->personID() );
            $t->set_var( "customer_first_name", $customer->firstName() );
            $t->set_var( "customer_last_name", $customer->lastName() );
        }
        else
        {
            $customer = new eZCompany( $order->companyID() );
            $t->set_var( "customer_first_name", $customer->name() );
            $t->set_var( "customer_last_name", "" );
        }
    }
    
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
    else
    {
        $t->set_var( "billing_country", "" );
    }
    
    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
        $t->parse( "billing_address", "billing_address_tpl" );
    else
        $t->set_var( "billing_address", "" );

    if ( $order->personID() == 0 && $order->companyID() == 0 )
    {
        $shippingUser = $order->shippingUser();

        if ( $shippingUser )
        {
            $t->set_var( "shipping_first_name", $shippingUser->firstName() );
            $t->set_var( "shipping_last_name", $shippingUser->lastName() );
        }
    }
    else
    {
        if ( $order->personID() > 0 )
        {
            $customer = new eZPerson( $order->personID() );
            $t->set_var( "shipping_first_name", $customer->firstName() );
            $t->set_var( "shipping_last_name", $customer->lastName() );
        }
        else
        {
            $customer = new eZCompany( $order->companyID() );
            $t->set_var( "shipping_first_name", $customer->name() );
            $t->set_var( "shipping_last_name", "" );
        }
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
    else
    {
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



    $priceobj = new eZCurrency();

    if ( ( !$RequireUserLogin or get_class( $user ) == "ezuser" ) and
         $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
    {
        $found_price = false;
        if ( $ShowPriceGroups and $PriceGroup > 0 )
        {
            $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup );
            if ( $price )
            {
                $found_price = true;
                $priceobj->setValue( $price * $item->count() );
            }
        }
        if ( !$found_price )
        {
            $priceobj->setValue( $product->price() * $item->count() );
        }
        $t->set_var( "product_price", $locale->format( $priceobj ) );
    }
    else
    {
        $priceArray = "";
        $priceArray = "";
        $options =& $product->options();
        if ( count ( $options ) == 1 )
        {
            $option = $options[0];
            if ( get_class ( $option ) == "ezoption" )
            {
                $optionValues =& $option->values();
                if ( count ( $optionValues ) > 1 )
                {
                    $i=0;
                    foreach ( $optionValues as $optionValue )
                    {
                        $found_price = false;
                        if ( $ShowPriceGroups and $PriceGroup > 0 )
                        {
                            $priceArray[$i] = eZPriceGroup::correctPrice( $product->id(), $PriceGroup, $option->id(), $optionValue->id() );
                            if ( $priceArray[$i] )
                            {
                                $found_price = true;
                                $priceArray[$i] = $priceArray[$i];
                            }
                        }
                        if ( !$found_price )
                        {
                            $priceArray[$i] = $optionValue->price();
                        }
                        $i++;
                    }
                    $high = new eZCurrency( max( $priceArray ) );
                    $low = new eZCurrency( min( $priceArray ) );

                    $t->set_var( "product_price", $locale->format( $low ) . " - " . $locale->format( $high ) );
                }
            }
        }
        else
            $t->set_var( "product_price", "" );
    }
    
    $price = $priceobj->value();
   
    $currency->setValue( $price );

    $sum += $price;

    $totalVAT += $product->vat( $price );
    
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
