<?php
// 
// $Id: checkout.php,v 1.3 2000/10/25 19:21:42 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Sep-2000 15:52:08 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$OrderSenderEmail = $ini->read_var( "eZTradeMain", "OrderSenderEmail" );
$OrderReceiverEmail = $ini->read_var( "eZTradeMain", "OrderReceiverEmail" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "eztrade/classes/ezorder.php" );
include_once( "eztrade/classes/ezorderitem.php" );
include_once( "eztrade/classes/ezorderoptionvalue.php" );

include_once( "ezsession/classes/ezsession.php" );

include_once( "classes/ezmail.php" );


$cart = new eZCart();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

// get the cart or create it
$cart = $cart->getBySession( $session, "Cart" );
if ( !$cart )
{
    print( "ERROR: no cart." );
}

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "checkout.php" );

$t->setAllStrings();

$t->set_file( array(
    "checkout_tpl" => "checkout.tpl"
    ) );

$t->set_block( "checkout_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );

$t->set_block( "checkout_tpl", "address_tpl", "address" );

    
//  $t->set_block( "cart_page", "cart_header_tpl", "cart_header" );


// create an order and empty the cart.
if ( $SendOrder == "true" ) 
{ 
    // create a new order
    $order = new eZOrder();
    $user = eZUser::currentUser();
    $order->setUser( $user );
    $order->setAddress( 42 );
    $order->setShippingCharge( 120.0 );
    $order->store();

    // fetch the cart items
    $items = $cart->items( $CartType );

    foreach ( $items as $item )
        {
            $product = $item->product();
            print( $product->name() . "<br>" );

            // create a new order item
            $orderItem = new eZOrderItem();
            $orderItem->setOrder( $order );
            $orderItem->setProduct( $product );
            $orderItem->setCount( $item->count() );
            $orderItem->setPrice( $product->price() );
            $orderItem->store();

            $optionValues =& $item->optionValues();

            $t->set_var( "cart_item_option", "" );
            foreach ( $optionValues as $optionValue )
                {
                    $option =& $optionValue->option();
                    $value =& $optionValue->optionValue();

                    $orderOptionValue = new eZOrderOptionValue();
                    $orderOptionValue->setOrderItem( $orderItem );
                    $orderOptionValue->setOptionName( $option->name() );
                    $orderOptionValue->setValueName( $value->name() );
                    $orderOptionValue->store();
        
                    print( "&nbsp;&nbsp;" . $option->name() . " " . $value->name() . "<br>");
                }    
        }

    $cart->clear();

    Header( "Location: /trade/ordersendt/" );
}

// print the cart contents

{
// fetch the cart items
    $items = $cart->items( $CartType );

    $locale = new eZLocale( $Language );
    $currency = new eZCurrency();
    
    $i = 0;
    $sum = 0.0;
    foreach ( $items as $item )
    {
        $product = $item->product();

        $image = $product->thumbnailImage();

        $thumbnail =& $image->requestImageVariation( 35, 35 );        

        $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
        $t->set_var( "product_image_width", $thumbnail->width() );
        $t->set_var( "product_image_height", $thumbnail->height() );
        $t->set_var( "product_image_caption", $image->caption() );

        $price = $product->price() * $item->count();
        $currency->setValue( $price );

        $sum += $price;
        
        $t->set_var( "product_name", $product->name() );
        $t->set_var( "product_price", $locale->format( $currency ) );

        $t->set_var( "cart_item_count", $item->count() );
        
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $optionValues =& $item->optionValues();

        $t->set_var( "cart_item_option", "" );
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();
                 
            $t->set_var( "option_name", $option->name() );
            $t->set_var( "option_value", $value->name() );
            
            $t->parse( "cart_item_option", "cart_item_option_tpl", true );
        }
        
        $t->parse( "cart_item", "cart_item_tpl", true );
        
        $i++;
    }

    $shippingCost = 100.0;
    $currency->setValue( $shippingCost );
    $t->set_var( "shipping_cost", $locale->format( $currency ) );

    $sum += $shippingCost;
    $currency->setValue( $sum );
    $t->set_var( "cart_sum", $locale->format( $currency ) );

    $mail = new eZMail();
    $mail->setFrom( $OrderSenderEmail );
    $mail->setTo( $OrderReceiverEmail );
    $mail->setSubject( "Ny ordre" );
    $mail->setBody( "Ny ordre" );
    $mail->send();
}

$t->parse( "cart_item_list", "cart_item_list_tpl" );

$user = eZUser::currentUser();

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
    
    $t->parse( "address", "address_tpl", true );
}

$t->pparse( "output", "checkout_tpl" );

?>
