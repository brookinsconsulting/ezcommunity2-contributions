<?php
// 
// $Id: ordersendt.php,v 1.9 2000/11/02 12:11:00 bf-cvs Exp $
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

$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "ordersendt.php" );

$t->setAllStrings();

$t->set_file( array(
    "order_sendt_tpl" => "ordersendt.tpl"
    ) );


$t->set_block( "order_sendt_tpl", "visa_tpl", "visa" );
$t->set_block( "order_sendt_tpl", "mastercard_tpl", "mastercard" );
$t->set_block( "order_sendt_tpl", "cod_tpl", "cod" );
$t->set_block( "order_sendt_tpl", "invoice_tpl", "invoice" );

$t->set_block( "order_sendt_tpl", "address_tpl", "address" );

$t->set_block( "order_sendt_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_item_list_tpl", "order_item_tpl", "order_item" );
$t->set_block( "order_item_tpl", "order_image_tpl", "order_image" );

$t->set_block( "order_item_tpl", "order_item_option_tpl", "order_item_option" );


$order = new eZOrder( $OrderID );

// get the customer

$user = $order->user();

if ( $user )
{
    $t->set_var( "customer_first_name", $user->firstName() );
    $t->set_var( "customer_last_name", $user->lastName() );

// print out the addresses

    $addressArray = $user->addresses();

    foreach ( $addressArray as $address )
    {
        $t->set_var( "street1", $address->street1() );
        $t->set_var( "street2", $address->street2() );
        $t->set_var( "zip", $address->zip() );
        $t->set_var( "place", $address->place() );

        $country = $address->country();
        $t->set_var( "country", $country->name() );

        $t->parse( "address", "address_tpl", true );
    }

}


// fetch the order items
$items = $order->items( $OrderType );


$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$i = 0;
$sum = 0.0;
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
    
    $price = $product->price() * $item->count();
    $currency->setValue( $price );

    $sum += $price;
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

$t->set_var( "visa", "" );
$t->set_var( "mastercard", "" );
$t->set_var( "cod", "" );
$t->set_var( "invoice", "" );
switch ( $order->paymentMethod() )
{
    case "1" :
    {// VISA
        $t->parse( "visa", "visa_tpl" );        
    }
    break;
    case "2" :
    {// Mastercard
        $t->parse( "mastercard", "mastercard_tpl" );
    }
    break;
    case "3" :
    {// Postordre
        $t->parse( "cod", "cod_tpl" );
    }
    break;
    case "4" :
    {// Faktura
        $t->parse( "invoice", "invoice_tpl" );
    }
    break;
}

$shippingCost = $order->shippingCharge();
$currency->setValue( $shippingCost );
$t->set_var( "shipping_cost", $locale->format( $currency ) );

$sum += $shippingCost;
$currency->setValue( $sum );
$t->set_var( "order_sum", $locale->format( $currency ) );

$t->set_var( "order_id", $OrderID );



$t->pparse( "output", "order_sendt_tpl" );

?>
