<?php
// 
// $Id: payment.php,v 1.64 2001/08/30 11:06:37 ce Exp $
//
// Created on: <02-Feb-2001 16:31:53 bf>
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

unset( $PaymentSuccess );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

include_once( "classes/ezhttptool.php" );
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "eztrade/classes/ezpreorder.php" );
include_once( "eztrade/classes/ezorder.php" );
include_once( "eztrade/classes/ezorderitem.php" );
include_once( "eztrade/classes/ezorderoptionvalue.php" );
include_once( "eztrade/classes/ezwishlist.php" );
include_once( "eztrade/classes/ezcheckout.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "classes/ezgpg.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$OrderSenderEmail = $ini->read_var( "eZTradeMain", "OrderSenderEmail" );
$OrderReceiverEmail = $ini->read_var( "eZTradeMain", "OrderReceiverEmail" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$DiscontinueQuantityless = $ini->read_var( "eZTradeMain", "DiscontinueQuantityless" ) == "true";
$SiteURL =  $ini->read_var( "site", "SiteURL" );
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" );

function deleteCache( $ProductID, $CategoryID, $CategoryArray, $Hotdeal )
{
    if ( get_class( $ProductID ) == "ezproduct" )
    {
        $CategoryID =& $ProductID->categoryDefinition( false );
        $CategoryArray =& $ProductID->categories( false );
        $Hotdeal = $ProductID->isHotDeal();
        $ProductID = $ProductID->id();
    }

    $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, $CategoryID ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }
    $files = eZCacheFile::files( "eztrade/cache/",
                                 array( "productlist",
                                        array_merge( $CategoryID, $CategoryArray ) ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }
    
    if ( $Hotdeal )
    {
        $files = eZCacheFile::files( "eztrade/cache/", array( "hotdealslist", NULL ),
                                     "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }
}

$session =& eZSession::globalSession();

// fetch the cart
$cart = new eZCart();
$cart = $cart->getBySession( $session, "Cart" );

if ( !$cart )
{
    eZHTTPTool::header( "Location: /trade/cart/" );
}

$items = $cart->items();

// generate a new checkout instance, must have a unique number
// for VISA clearing etc.

// this is the value to charge the customer with
$ChargeTotal = $session->variable( "TotalCost" ) ;

// this is the total vat.
$ChargeVATTotal = $session->variable( "TotalVAT" ) ;


$checkout = new eZCheckout();
$instance =& $checkout->instance();

//print( $ChargeTotal );

$PreOrderID = $session->variable( "PreOrderID" ) ;

$preOrder = new eZPreOrder( $PreOrderID );

// print( "Checkout number: " . $PreOrderID . "<br>" );

$paymentMethod = $session->variable( "PaymentMethod" );
$locale = new eZLocale( $Language );

if ( $paymentMethod == true )
    include( $instance->paymentFile( $paymentMethod ) );
else
$PaymentSuccess = "true";

// create an order and empty the cart.
// only do this if the payment was OK.
if ( $PaymentSuccess == "true" ) 
{
    $locale = new eZLocale( $Language );
    $currency = new eZCurrency();
    
    // create a new order
    $order = new eZOrder();
    $user =& eZUser::currentUser();
    $order->setUser( $user );

    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) != "enabled" )
    {
        $billingAddressID = $shippingAddressID;
    }
    
    $shippingAddress = new eZAddress( $session->variable( "ShippingAddressID" ) );
    $billingAddress = new eZAddress( $session->variable( "BillingAddressID" ) );

    $order->setShippingAddress( $shippingAddress );
    $order->setBillingAddress( $billingAddress );

    $order->setShippingCharge( $session->variable( "ShippingCost" ) );
    $order->setShippingVAT( $session->variable( "ShippingVAT" ) );
    $order->setPaymentMethod( $session->variable( "PaymentMethod" ) );

    $order->setShippingTypeID( $session->variable( "ShippingTypeID" ) );

    $order->setPersonID( $cart->personID() );
    $order->setCompanyID( $cart->companyID() );

    $order->setIsVATInc( $vat );
    
    $order->store();

    $order_id = $order->id();

    // fetch the cart items
    $items = $cart->items();

    foreach ( $items as $item )
    {
        $product = $item->product();

        // product price

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
                    if ( $PricesIncludeVAT == "enabled" )
                    {
                        $totalVAT = $product->addVAT( $price );
                        $price += $totalVAT;
                    }
                    else
                    {
                        $totalVAT = $product->extractVAT( $price );
                    }

                    $found_price = true;
                    $priceobj->setValue( $price * $item->count() );
                }
            }
            if ( !$found_price )
            {
                if ( $PricesIncludeVAT == "enabled" )
                {
                    $totalVAT += $product->addVAT( $product->price() );
                    $price += $product->price() + $totalVAT;
                }
                else
                {
                    $totalVAT += $product->extractVAT( $product->price() );
                    $price += $product->price();
                }

                $priceobj->setValue( $price * $item->count() );
            }
        }
        else
        {
            if ( $PricesIncludeVAT == "enabled" )
            {
                $totalVAT = $product->addVAT( $item->price() );
                $price = $item->price() + $totalVAT;
            }
            else
            {
                $totalVAT = $product->extractVAT( $item->price() );
                $price = $item->price();
            }
            $priceobj->setValue( $price * $item->count() );
        }

        $price = $priceobj->value();

        // create a new order item
        $orderItem = new eZOrderItem();
        $orderItem->setOrder( $order );
        $orderItem->setProduct( $product );
        $orderItem->setCount( $item->count() );
        $orderItem->setPrice( $price );
        $orderItem->setVAT( $totalVAT );

        $expiryTime = $product->expiryTime();
        if ( $expiryTime > 0 )
            $orderItem->setExpiryDate( eZDateTime::timeStamp( true ) + ( $expiryTime * 86400 ) );
        else
            $orderItem->setExpiryDate( 0 );
        
        $orderItem->store();
        
        $optionValues =& $item->optionValues();

        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();

            $orderOptionValue = new eZOrderOptionValue();
            $orderOptionValue->setOrderItem( $orderItem );

            $orderOptionValue->setRemoteID( $optionValue->remoteID() );
 
            $descriptions =& $value->descriptions();
            
            $orderOptionValue->setOptionName( $option->name() );
            $orderOptionValue->setValueName( $descriptions[0] );
            // fix
            
            $orderOptionValue->store();
        }
    }

    //
    // Send mail confirmation
    //  
    $locale = new eZLocale( $Language );
    $currency = new eZCurrency();
    
    $user =& eZUser::currentUser();

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
    $items = $order->items();

    // Get the strings for the headers

    $headProduct = $mailTemplateIni->read_var( "strings", "product" );
    $headProductNumber = $mailTemplateIni->read_var( "strings", "product_number" );
    $headCount = $mailTemplateIni->read_var( "strings", "count" );
    $headPrice = $mailTemplateIni->read_var( "strings", "price" );
    $footTotal = $mailTemplateIni->read_var( "strings", "total" );
    $footVAT = $mailTemplateIni->read_var( "strings", "vat" );
    $footSandH = $mailTemplateIni->read_var( "strings", "ship_hand" );
    $footSubT = $mailTemplateIni->read_var( "strings", "sub_total" );

    $productString = substr( $headProduct, 0, 27 );
    $productString = $productString . ": ";
    $productString = str_pad( $productString, 29, " " );
    
    $productNumberString = substr( $headProductNumber, 0, 20 );
    $productNumberString = $productNumberString . ": ";
    $productNumberString = str_pad( $productNumberString, 22, " ", STR_PAD_LEFT );
    
    $countString = substr( $headCount, 0, 10 );
    $countString = $countString . ": ";
    $countString = str_pad( $countString, 12, " ", STR_PAD_LEFT );
    
    $priceString = substr( $headPrice, 0, 13 );
    $priceString = $priceString . ": ";
    $priceString = str_pad( $priceString, 15, " ", STR_PAD_LEFT );

    $VATString = substr( $footVAT, 0, 56 );
    $VATString = $VATString . ": ";
    $VATString = str_pad( $VATString, 58, " ", STR_PAD_LEFT );

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
    $mailTemplate->set_var( "product_number_string", $productNumberString );
    $mailTemplate->set_var( "count_string", $countString );
    $mailTemplate->set_var( "price_string", $priceString );
    $mailTemplate->set_var( "stringline", $lineString );
    $mailTemplate->set_var( "product_total_string", $totalString );
    $mailTemplate->set_var( "vat_total_string", $VATString );
    $mailTemplate->set_var( "product_sub_total_string", $subTotalString );
    $mailTemplate->set_var( "product_ship_hand_string", $tshString );
    $mailTemplate->set_var( "site_url", $SiteURL );
    
    $user = $order->user();


    // name to ship to    
    $mailTemplate->set_var( "customer_first_name", $user->firstName() );
    $mailTemplate->set_var( "customer_last_name", $user->lastName() );

    if ( $order->companyID() == 0 && $order->personID() == 0 )
    {
        $shippingUser = $order->shippingUser();
    
        if ( $shippingUser )
        {
            $mailTemplate->set_var( "shipping_customer_first_name", $shippingUser->firstName() );
            $mailTemplate->set_var( "shipping_customer_last_name", $shippingUser->lastName() );
        }
    }
    else
    {
        if ( $order->companyID() > 0 )
        {
            $customer = new eZCompany( $order->companyID() );
            $mailTemplate->set_var( "shipping_customer_first_name", $customer->name() );
            $mailTemplate->set_var( "shipping_customer_last_name", "" );
        }
        else
        {
            $customer = new eZPerson( $order->personID() );
            $mailTemplate->set_var( "shipping_customer_first_name", $customer->firstName() );
            $mailTemplate->set_var( "shipping_customer_last_name", $customer->lastName() );
        }
    }
    
    // the shipping type text
    $shippingType = $order->shippingType();
    if ( $shippingType )
    {    
        $mailTemplate->set_var( "shipping_type", $shippingType->name() );
    }


    // payment method text
    $checkout = new eZCheckout();
    $instance =& $checkout->instance();
    $paymentMethod = $instance->paymentName( $order->paymentMethod() );

    $mailTemplate->set_var( "payment_method", $paymentMethod );
    
    
    // print out the addresses
    $mailTemplate->set_var( "billing_street1", $billingAddress->street1() );
    $mailTemplate->set_var( "billing_street2", $billingAddress->street2() );
    $mailTemplate->set_var( "billing_zip", $billingAddress->zip() );
    $mailTemplate->set_var( "billing_place", $billingAddress->place() );
    
    $country = $billingAddress->country();

    if ( ( get_class( $country ) == "ezcountry" ) )
    {        
        if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
            $mailTemplate->set_var( "billing_country", $country->name() );
        else
            $mailTemplate->set_var( "billing_country", "" );
    }
        
    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
        $mailTemplate->parse( "billing_address", "billing_address_tpl" );
    else
        $mailTemplate->set_var( "billing_address", "" );

   
    $mailTemplate->set_var( "shipping_street1", $shippingAddress->street1() );
    $mailTemplate->set_var( "shipping_street2", $shippingAddress->street2() );
    $mailTemplate->set_var( "shipping_zip", $shippingAddress->zip() );
    $mailTemplate->set_var( "shipping_place", $shippingAddress->place() );
    
    $country = $shippingAddress->country();

    if ( $country )
    {
        if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
            $mailTemplate->set_var( "shipping_country", $country->name() );
        else
            $mailTemplate->set_var( "shipping_country", "" );
    }
        
    $mailTemplate->parse( "shipping_address", "shipping_address_tpl" );


    $totalPrice = 0;
    foreach ( $items as $item )
    {
        $product = $item->product();

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
                    if ( $PricesIncludeVAT == "enabled" )
                    {
                        $totalVAT = $product->addVAT( $price );
                        $price += $totalVAT;
                    }
                    else
                    {
                        $totalVAT = $product->extractVAT( $price );
                    }

                    $found_price = true;
                    $priceobj->setValue( $price * $item->count() );
                }
            }
            if ( !$found_price )
            {
                if ( $PricesIncludeVAT == "enabled" )
                {
                    $totalVAT = $product->addVAT( $product->price() );
                    $price = $product->price() + $totalVAT;
                }
                else
                {
                    $totalVAT = $product->extractVAT( $product->price() );
                    $price = $product->price();
                }

                $priceobj->setValue( $price * $item->count() );
            }
        }
        else
        {
            if ( $PricesIncludeVAT == "enabled" )
            {
                $totalVAT = $product->addVAT( $item->price() );
                $price = $item->price() + $totalVAT;
            }
            else
            {
                $totalVAT = $product->extractVAT( $item->price() );
                $price = $item->price();
            }
            
            $priceobj->setValue( $price * $item->count() );
        }
        
        $price = $priceobj->value();    

        $currency->setValue( $price );

        $mailTemplate->set_var( "debug", $debug );
        
        $nameString = substr(  $product->name(), 0, 33 );
        $nameString = str_pad( $nameString, 35, " " );

        $numberString = substr(  $product->productNumber(), 0, 8 );
        $numberString = str_pad( $numberString, 10, " " );

        $countString = substr(  $item->count(), 0, 10 );
        $countString = str_pad( $countString, 12, " ", STR_PAD_LEFT );
        
        $priceString = substr(  $locale->format( $currency ), 0, 21 );
        $priceString = str_pad( $priceString, 23, " ", STR_PAD_LEFT );

        $mailTemplate->set_var( "order", $nameString );
        $mailTemplate->set_var( "number", $numberString );
        $mailTemplate->set_var( "count", $countString );
        $mailTemplate->set_var( "price", $priceString );

        $optionValues =& $item->optionValues();

        $mailTemplate->set_var( "cart_item_option", "" );
        $mailTemplate->set_var( "option_item", "" );

        $optionNameLength = 0;

        $optionValues =& $item->optionValues();
        
        foreach ( $optionValues as $optionValue )
        {
            $optionString = substr( $optionValue->optionName(), 0, 35 );
            $optionString = str_pad( $optionString, 36, " ", STR_PAD_LEFT );
            $valueString = substr( $optionValue->valueName(), 0, 38 );
            $valueString = str_pad( $valueString, 39, " " );
    
            $mailTemplate->set_var( "name", $optionString );
            $mailTemplate->set_var( "value", $valueString );
            $mailTemplate->parse( "option_item", "option_item_tpl", true );
        }

        $mailTemplate->parse( "order_item", "order_item_tpl", true );
    }

//    $totalPrice = $order->totalPrice();

    $currency->setValue( $totalPrice );
        
    $priceString = substr(  $locale->format( $currency ), 0, 13 );
    $priceString = str_pad( $priceString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "product_sub_total", $priceString );

    $shippinglPrice = $order->shippingCharge();
    $currency->setValue( $shippinglPrice );
    
    $shippingPriceString = substr(  $locale->format( $currency ), 0, 13 );
    $shippingPriceString = str_pad( $shippingPriceString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "product_ship_hand", $shippingPriceString );

    $grantVAT = $order->totalVAT();

    $grandTotal = $order->totalPrice() + $order->shippingCharge();

    $currency->setValue( $grandTotal );

    $grandTotalString = substr(  $locale->format( $currency ), 0, 13 );
    $grandTotalString = str_pad( $grandTotalString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "product_total", $grandTotalString );

    $currency->setValue( $grantVAT );

    $grandVATTotalString = substr(  $locale->format( $currency ), 0, 13 );
    $grandVATTotalString = str_pad( $grandVATTotalString, 15, " ", STR_PAD_LEFT );
    $mailTemplate->set_var( "vat_total", $grandVATTotalString );

    $mailTemplate->set_var( "order_number", $order->id() );

    $checkout = new eZCheckout();
    $instance =& $checkout->instance();
    $paymentMethod = $instance->paymentName( $order->paymentMethod() );
    
    $mailTemplate->set_var( "payment_method", $paymentMethod );

    // get the subjects
    $mailSubjectUser = $mailTemplate->parse( "subject_user", "subject_user_tpl" );
    $mailTemplate->set_var( "subject_user", "" );

    $mailSubject = $mailTemplate->parse( "subject_admin", "subject_admin_tpl" );
    $mailTemplate->set_var( "subject_admin", "" );


    // payment method
    $checkout = new eZCheckout();
    $instance =& $checkout->instance();
    $paymentMethod = $instance->paymentName( $order->paymentMethod() );
    
    $mailTemplate->set_var( "payment_method", $paymentMethod );
    
    
    // Send E-mail    
    $mail = new eZMail();
    
    $mailBody = $mailTemplate->parse( "dummy", "mail_order_tpl" );
    $mail->setFrom( $OrderSenderEmail );
    
    $mail->setTo( $user->email() );
    $mail->setSubject( $mailSubjectUser );
    $mail->setBody( $mailBody );
    $mail->send();
    
    // admin email
    // check to see if the email should be encrypted for the administrator
    $mailEncrypt = $ini->read_var( "eZTradeMain", "MailEncrypt" );
    
	if ( $mailEncrypt == "GPG" )
	{	
	    // initialize GPG class 
	    $wwwUser = $ini->read_var( "eZTradeMain", "ApacheUser" );
        $mailKeyname = $ini->read_var( "eZTradeMain", "RecipientGPGKey" );
        // At this point you can add any information to the template as needed
        // remember to provide a variable in the template for it.
     	// add credit card info for the administrator
    	$mailTemplate->set_var( "payment_method", $paymentMethod );
    	$mailTemplate->set_var( "cc_number", $CCNumber );
    	$mailTemplate->set_var( "cc_expiremonth", $ExpireMonth );
    	$mailTemplate->set_var( "cc_expireyear", $ExpireYear );

	    $mailBody = $mailTemplate->parse( "dummy", "mail_order_tpl" );
    	// encrypt mailBody
		$mytext = new ezgpg( $mailBody, $mailKeyname, $wwwUser );
		$mailBody=($mytext->body);
		$mail->setBody( $mailBody );
	}
    
    $mail->setSubject( $mailSubject );
    $mail->setTo( $OrderReceiverEmail );
    $mail->setFrom( $user->email() );

    $mail->send();

    // get the cart or create it
    $cart = new eZCart();
    $cart = $cart->getBySession( $session );
    foreach ( $cart->items() as $item )
    {
        // set the wishlist item to bought if the cart item is
        // fetched from a wishlist
        $wishListItem = $item->wishListItem();
        if ( $wishListItem )
        {
            $wishListItem->setIsBought( true );
            $wishListItem->store();
        }
    }

    // Decrease product/option quantity
    $items =& $cart->items();
    foreach ( $items as $item )
    {
        $product =& $item->product();
        $count = $item->count();
        $quantity = $product->totalQuantity();
        $values =& $item->optionValues();
        $selected_values = array();
        foreach ( $values as $value )
        {
            $option_value =& $value->optionValue();
            $selected_values[] = $option_value->id();
        }

        $changed_quantity = false;
        if ( !(is_bool( $quantity ) and !$quantity) )
        {
            $max_value = max( $quantity - $count, 0 );
            $product->setTotalQuantity( $max_value );
            if ( $max_value == 0 and $DiscontinueQuantityless )
                $product->setDiscontinued( true );
            $product->store();
            $changed_quantity = true;
        }
        $options =& $product->options();
        $change_discontinuity = false;
        $max_max_value = 0;
        $has_value = false;
        foreach( $options as $option )
        {
            $option_values =& $option->values();
            foreach( $option_values as $option_value )
            {
                if ( in_array( $option_value->id(), $selected_values ) )
                {
                    $value_quantity = $option_value->totalQuantity();
                    if ( !(is_bool( $value_quantity ) and !$value_quantity) )
                    {
                        $max_value = max( $value_quantity - $count, 0 );
                        $max_max_value = max( $max_max_value, $max_value );
                        $option_value->setTotalQuantity( $max_value );
                        $option_value->store();
                        $changed_quantity = true;
                    }
                }
                $value_quantity = $option_value->totalQuantity();
                if ( (is_bool( $value_quantity ) and !$value_quantity) or $value_quantity > 0 )
                {
                    $has_value = true;
                }
            }
        }
        $productQuantity = $product->totalQuantity();
        if ( ( $max_max_value == 0 and !$has_value and $DiscontinueQuantityless ) and !(is_bool( $productQuantity ) and !$productQuantity ) )
        {
            $product->setDiscontinued( true );
            $product->store();
        }
        if ( $changed_quantity )
        {
            deleteCache( $product, false, false, false );
        }
    }

    //
    if ( is_file ( "checkout/user/postpayment.php" ) )
    {
        include( "checkout/user/postpayment.php" );
    }
    
    $cart->clear();

    $OrderID = $order->id();

    $preOrder = new eZPreOrder( $PreOrderID );
    $preOrder->setOrderID( $OrderID );
    $preOrder->store();

    foreach( $vouchers as $voucherInformation )
    {
        $newVoucher = new eZVoucher();
        $voucherInformation->addVoucher( $newVoucher );
        $voucher->generateKey();
        $voucher->store();
    }

    // call the payment script after the payment is successful.
    // some systems needs this, e.g. to print out the OrderID which was cleared..
    $Action = "PostPayment";
    include( $instance->paymentFile( $paymentMethod ) );

    // Turn of SSL and redirect to http://

    $session->setVariable( "SSLMode", "" );

    eZHTTPTool::header( "Location: http://$HTTP_HOST/trade/ordersendt/$OrderID/" );
    exit();
}


?>
