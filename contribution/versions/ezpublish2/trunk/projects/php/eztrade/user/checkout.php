<?php
// 
// $Id: checkout.php,v 1.28 2001/02/02 20:49:16 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Sep-2000 15:52:08 bf>
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
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZTradeMain", "Language" );
$OrderSenderEmail = $ini->read_var( "eZTradeMain", "OrderSenderEmail" );
$OrderReceiverEmail = $ini->read_var( "eZTradeMain", "OrderReceiverEmail" );
$ShippingCost = $ini->read_var( "eZTradeMain", "ShippingCost" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "eztrade/classes/ezorder.php" );
include_once( "eztrade/classes/ezorderitem.php" );
include_once( "eztrade/classes/ezorderoptionvalue.php" );
include_once( "eztrade/classes/ezwishlist.php" );

include_once( "eztrade/classes/ezcheckout.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );

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
    Header( "Location: /trade/cart/" );
}

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "checkout.php" );

$t->setAllStrings();

$t->set_file( array(
    "checkout_tpl" => "checkout.tpl"
    ) );

$t->set_block( "checkout_tpl", "payment_method_tpl", "payment_method" );

$t->set_block( "checkout_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_tpl", "cart_image_tpl", "cart_image" );

$t->set_block( "checkout_tpl", "shipping_address_tpl", "shipping_address" );
$t->set_block( "checkout_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "billing_address_tpl", "billing_option_tpl", "billing_option" );
$t->set_block( "checkout_tpl", "wish_user_tpl", "wish_user" );


// create an order and empty the cart.
if ( $SendOrder == "true" ) 
{
    $locale = new eZLocale( $Language );
    $currency = new eZCurrency();
    // create a new order
    $order = new eZOrder();
    $user = eZUser::currentUser();
    $order->setUser( $user );

    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) != "enabled" )
    {
        $BillingAddressID = $ShippingAddressID;
    }
    
    $shippingAddress = new eZAddress( $ShippingAddressID );
    $billingAddress = new eZAddress( $BillingAddressID );
    
    $order->setShippingAddress( $shippingAddress );
    $order->setBillingAddress( $billingAddress );
    
    $order->setShippingCharge( $ShippingCost );
    $order->setPaymentMethod( $PaymentMethod );

    $order->store();

    $order_id = $order->id();

    // Setup the template for email
    $mailTemplate = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                                        "eztrade/user/intl", $Language, "mailorder.php" );

    $mailTemplateIni = new INIFile( "eztrade/user/intl/" . $Language . "/mailorder.php.ini", false );
    $mailTemplate->set_file( "mail_order_tpl", "mailorder.tpl" );
    $mailTemplate->setAllStrings();

    // subject
    $mailTemplate->set_block( "mail_order_tpl", "subject_admin_tpl", "subject_admin" );
    $mailTemplate->set_block( "mail_order_tpl", "subject_user_tpl", "subject_user" );    
    
    $mailTemplate->set_block( "mail_order_tpl", "order_item_tpl", "order_item" );
    $mailTemplate->set_block( "order_item_tpl", "option_item_tpl", "option_item" );

    $mailTemplate->set_block( "mail_order_tpl", "billing_address_tpl", "billing_address" );
    $mailTemplate->set_block( "mail_order_tpl", "shipping_address_tpl", "shipping_address" );
    
    // fetch the cart items
    $items = $cart->items( $CartType );

    // Get the strings for the headers

    $headProduct = $mailTemplateIni->read_var( "strings", "product" );
    $headCount = $mailTemplateIni->read_var( "strings", "count" );
    $headPrice = $mailTemplateIni->read_var( "strings", "price" );
    $footTotal = $mailTemplateIni->read_var( "strings", "total" );
    $footSandH = $mailTemplateIni->read_var( "strings", "ship_hand" );
    $footSubT = $mailTemplateIni->read_var( "strings", "sub_total" );

    $productString = substr( $headProduct, 0, 56 );
    $productString = $productString . ": ";
    $productString = str_pad( $productString, 58, " " );

    $countString = substr( $headCount, 0, 5 );
    $countString = $countString . ": ";
    $countString = str_pad( $countString, 7, " ", STR_PAD_LEFT );

    $priceString = substr( $headPrice, 0, 13 );
    $priceString = $priceString . ": ";
    $priceString = str_pad( $priceString, 15, " ", STR_PAD_LEFT );

    $totalString = substr( $footTotal, 0, 56 );
    $totalString = $totalString . ": ";
    $totalString = str_pad( $totalString, 58, " ", STR_PAD_LEFT );
    
    $tshString = substr( $footSandH, 0, 56 );
    $tshString = $tshString . ": ";
    $tshString = str_pad( $tshString, 58, " ", STR_PAD_LEFT );

    $subTotalString = substr( $footSubT, 0, 56 );
    $subTotalString = $subTotalString . ": ";
    $subTotalString = str_pad( $subTotalString, 58, " ", STR_PAD_LEFT );
    
    $lineString = str_pad( $lineString, 78, "-");
    
    $mailTemplate->set_var( "product_string", $productString );
    $mailTemplate->set_var( "count_string", $countString );
    $mailTemplate->set_var( "price_string", $priceString );
    $mailTemplate->set_var( "stringline", $lineString );
    $mailTemplate->set_var( "product_total_string", $totalString );
    $mailTemplate->set_var( "product_sub_total_string", $subTotalString );
    $mailTemplate->set_var( "product_ship_hand_string", $tshString );
    
    $user = $order->user();

    
    $mailTemplate->set_var( "user_first_name", $user->firstName() );
    $mailTemplate->set_var( "user_last_name", $user->lastName() );

   // print out the addresses

    $billingAddress = $order->billingAddress();

    $mailTemplate->set_var( "billing_street1", $billingAddress->street1() );
    $mailTemplate->set_var( "billing_street2", $billingAddress->street2() );
    $mailTemplate->set_var( "billing_zip", $billingAddress->zip() );
    $mailTemplate->set_var( "billing_place", $billingAddress->place() );
    
    $country = $billingAddress->country();
    $mailTemplate->set_var( "billing_country", $country->name() );

    if ( $ini->read_var( "eZTradeMain", "BillingAddress" ) == "Enabled" )
        $mailTemplate->parse( "billing_address", "billing_address_tpl" );
    else
        $mailTemplate->set_var( "billing_address", "" );

    $shippingAddress = $order->shippingAddress();

    $mailTemplate->set_var( "shipping_street1", $shippingAddress->street1() );
    $mailTemplate->set_var( "shipping_street2", $shippingAddress->street2() );
    $mailTemplate->set_var( "shipping_zip", $shippingAddress->zip() );
    $mailTemplate->set_var( "shipping_place", $shippingAddress->place() );
    
    $country = $shippingAddress->country();
    $mailTemplate->set_var( "shipping_country", $country->name() );
    
    $mailTemplate->parse( "shipping_address", "shipping_address_tpl" );

    foreach( $items as $item )
    {
        // set the wishlist item to bought if the cart item is
        // fetched from a wishlist

        $wishListItem = $item->wishListItem();
        if ( $wishListItem )
        {
            $wishListItem->setIsBought( true );
            $wishListItem->store();
        }
        
        $product = $item->product();
        // create a new order item
        $orderItem = new eZOrderItem();
        $orderItem->setOrder( $order );
        $orderItem->setProduct( $product );
        $orderItem->setCount( $item->count() );
        $orderItem->setPrice( $product->price() );
        $orderItem->store();
        $price = $product->price() * $item->count();
        $currency->setValue( $price );

        $mailTemplate->set_var( "debug", $debug );
        
        $nameString = substr(  $product->name(), 0, 56 );
        $nameString = str_pad( $nameString, 58, " " );
        
        $countString = substr(  $item->count(), 0, 5 );
        $countString = str_pad( $countString, 7, " ", STR_PAD_LEFT );
        
        $priceString = substr(  $locale->format( $currency ), 0, 13 );
        $priceString = str_pad( $priceString, 15, " ", STR_PAD_LEFT );

        $mailTemplate->set_var( "order", $nameString );
        $mailTemplate->set_var( "count", $countString );
        $mailTemplate->set_var( "price", $priceString );

        $optionValues =& $item->optionValues();

        $mailTemplate->set_var( "cart_item_option", "" );
        $mailTemplate->set_var( "option_item", "" );

        $optionNameLength = 0;

        $optionValues =& $item->optionValues();
        
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();

            $orderOptionValue = new eZOrderOptionValue();
            $orderOptionValue->setOrderItem( $orderItem );
            $orderOptionValue->setOptionName( $option->name() );
            $orderOptionValue->setValueName( $value->name() );
            $orderOptionValue->store();

            $optionString = substr( $option->name(), 0, 35 );
            $optionString = str_pad( $optionString, 36, " ", STR_PAD_LEFT );
            $valueString = substr( $value->name(), 0, 38 );
            $valueString = str_pad( $valueString, 39, " " );
    
            $mailTemplate->set_var( "name", $optionString );
            $mailTemplate->set_var( "value", $valueString );
            $mailTemplate->parse( "option_item", "option_item_tpl", true );
        }

        $mailTemplate->parse( "order_item", "order_item_tpl", true );
    }

    $totalPrice = $order->totalPrice();
    $currency->setValue( $totalPrice );
    
    $priceString = substr(  $locale->format( $currency ), 0, 13 );
    $priceString = str_pad( $priceString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "product_sub_total", $priceString );

    $shippinglPrice = $order->shippingCharge();
    $currency->setValue( $shippinglPrice );
    
    $shippingPriceString = substr(  $locale->format( $currency ), 0, 13 );
    $shippingPriceString = str_pad( $shippingPriceString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "product_ship_hand", $shippingPriceString );

    $grandTotal = $order->totalPrice() + $order->shippingCharge();
    $currency->setValue( $grandTotal );

    $grandTotalString = substr(  $locale->format( $currency ), 0, 13 );
    $grandTotalString = str_pad( $grandTotalString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "product_total", $grandTotalString );

   
    $mailTemplate->set_var( "order_number", $order->id() );

    // get the subjects
    $mailSubjectUser = $mailTemplate->parse( "subject_user", "subject_user_tpl" );
    $mailTemplate->set_var( "subject_user", "" );

    $mailSubjectAdmin = $mailTemplate->parse( "subject_admin", "subject_admin_tpl" );
    $mailTemplate->set_var( "subject_admin", "" );
    
    
    // Send E-mail    
    $mail = new eZMail();
    $mailToAdmin = $ini->read_var( "eZTradeMain", "mailToAdmin" );
    
    $mailBody = $mailTemplate->parse( "dummy", "mail_order_tpl" );
    $mail->setFrom( $OrderSenderEmail );
    
    $mail->setTo( $user->email() );
    $mail->setSubject( $mailSubjectUser );
    $mail->setBody( $mailBody );
    $mail->send();
    
    $mail->setSubject( $mailSubjectAdmin );
    $mail->setTo( $mailToAdmin );

//      $mail->send();

    $cart->clear();
    Header( "Location: /trade/payment/$order_id/$PaymentMethod/" );
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

        if ( $image )
        {
            $thumbnail =& $image->requestImageVariation( 35, 35 );        

            $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
            $t->set_var( "product_image_width", $thumbnail->width() );
            $t->set_var( "product_image_height", $thumbnail->height() );
            $t->set_var( "product_image_caption", $image->caption() );
            
            $t->parse( "cart_image", "cart_image_tpl" );            
        }
        else
        {
            $t->set_var( "cart_image", "" );
        }
        
        $t->set_var( "wish_user", "" );
        
        $wishListItem = $item->wishListItem();
        
        if ( $wishListItem )
        {
            $wishList = $wishListItem->wishList();

            if ( $wishList )
            {
                $wishUser = $wishList->user();

                if ( get_class ( $wishUser ) == "ezuser" )
                {
                    $address = new eZAddress();
                
                    $mainAddress =& $address->mainAddress( $wishUser );

                    if ( get_class ( $mainAddress ) == "ezaddress" )
                    {
                        $t->set_var( "wish_user_address_id", $mainAddress->id() );
                        $t->set_var( "wish_first_name", $wishUser->firstName() );
                        $t->set_var( "wish_last_name", $wishUser->lastName() );
                    
                        $t->parse( "wish_user", "wish_user_tpl" );
                    }
                    else
                    {
                    }
                }
            }
        }

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

    $shippingCost = $ShippingCost;
    $currency->setValue( $shippingCost );
    $t->set_var( "shipping_cost", $locale->format( $currency ) );

    $sum += $shippingCost;
    $currency->setValue( $sum );
    $t->set_var( "cart_sum", $locale->format( $currency ) );
}

$t->parse( "cart_item_list", "cart_item_list_tpl" );

$user = eZUser::currentUser();

$t->set_var( "customer_first_name", $user->firstName() );
$t->set_var( "customer_last_name", $user->lastName() );

// print out the addresses

$addressArray = $user->addresses();

foreach ( $addressArray as $address )
{
    $t->set_var( "address_id", $address->id() );
    $t->set_var( "street1", $address->street1() );
    $t->set_var( "street2", $address->street2() );
    $t->set_var( "zip", $address->zip() );
    $t->set_var( "place", $address->place() );
    $country = $address->country();
    $t->set_var( "country", $country->name() );

    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
        $t->parse( "billing_option", "billing_option_tpl", true );
    else
        $t->set_var( "billing_option" );
        
    $t->parse( "shipping_address", "shipping_address_tpl", true );
}

if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
    $t->parse( "billing_address", "billing_address_tpl", true );
else
$t->set_var( "billing_address" );


// show the checkout types

$checkout = new eZCheckout();

$instance =& $checkout->instance();

$paymentMethods =& $instance->paymentMethods();

foreach ( $paymentMethods as $paymentMethod )
{
    $t->set_var( "payment_method_id", $paymentMethod["ID"] );
    $t->set_var( "payment_method_text", $paymentMethod["Text"] );

    $t->parse( "payment_method", "payment_method_tpl", true );
}


   
$t->pparse( "output", "checkout_tpl" );


?>
