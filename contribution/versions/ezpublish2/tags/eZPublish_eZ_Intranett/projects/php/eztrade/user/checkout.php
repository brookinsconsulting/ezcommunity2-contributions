<?php
// 
// $Id: checkout.php,v 1.76 2001/08/24 18:01:30 br Exp $
//
// Created on: <28-Sep-2000 15:52:08 bf>
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
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$OrderSenderEmail = $ini->read_var( "eZTradeMain", "OrderSenderEmail" );
$OrderReceiverEmail = $ini->read_var( "eZTradeMain", "OrderReceiverEmail" );
$ForceSSL = $ini->read_var( "eZTradeMain", "ForceSSL" );
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "eztrade/classes/ezpreorder.php" );
include_once( "eztrade/classes/ezwishlist.php" );
include_once( "eztrade/classes/ezvoucher.php" );

// shipping
include_once( "eztrade/classes/ezshippingtype.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );
include_once( "eztrade/classes/ezcheckout.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );

include_once( "ezsession/classes/ezsession.php" );

include_once( "ezmail/classes/ezmail.php" );

$cart = new eZCart();
$session =& eZSession::globalSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

// get the cart or create it
$cart = $cart->getBySession( $session, "Cart" );
if ( !$cart )
{
    eZHTTPTool::header( "Location: /trade/cart/" );
}

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "checkout.php" );

$t->setAllStrings();

$t->set_file( "checkout_tpl", "checkout.tpl" );

$t->set_block( "checkout_tpl", "payment_method_tpl", "payment_method" );

$t->set_block( "checkout_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_option_tpl", "cart_item_option_availability_tpl", "cart_item_option_availability" );
$t->set_block( "cart_item_tpl", "cart_image_tpl", "cart_image" );

$t->set_block( "cart_item_list_tpl", "shipping_type_tpl", "shipping_type" );
$t->set_block( "cart_item_list_tpl", "product_available_header_tpl", "product_available_header" );
$t->set_block( "cart_item_list_tpl", "price_ex_vat_tpl", "price_ex_vat" );
$t->set_block( "cart_item_list_tpl", "price_inc_vat_tpl", "price_inc_vat" );

$t->set_block( "cart_item_tpl", "product_available_item_tpl", "product_available_item" );

$t->set_block( "cart_item_tpl", "voucher_information_tpl", "voucher_information" );

$t->set_block( "checkout_tpl", "shipping_address_tpl", "shipping_address" );
$t->set_block( "checkout_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "billing_address_tpl", "billing_option_tpl", "billing_option" );
$t->set_block( "checkout_tpl", "wish_user_tpl", "wish_user" );

$t->set_block( "checkout_tpl", "sendorder_item_tpl", "sendorder_item" );

$t->set_block( "checkout_tpl", "show_payment_tpl", "show_payment" );

$t->set_block( "cart_item_list_tpl", "vouchers_tpl", "vouchers" );
$t->set_block( "vouchers_tpl", "voucher_item_tpl", "voucher_item" );

$t->set_var( "show_payment", "" );
$t->set_var( "price_ex_vat", "" );
$t->set_var( "price_inc_vat", "" );

if ( isSet( $SendOrder ) ) 
{
    
    // set the variables as session variables and make sure that it is not read by
    // the HTTP GET variables for security.

    $currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );
    
    $preOrder = new eZPreOrder();
    $preOrder->store();

    $session->setVariable( "PreOrderID", $preOrder->id() );

    $session->setVariable( "ShippingAddressID", eZHTTPTool::getVar( "ShippingAddressID", true ) );
    $session->setVariable( "BillingAddressID", eZHTTPTool::getVar( "BillingAddressID", true ) );

    $session->setVariable( "TotalCost", eZHTTPTool::getVar( "TotalCost", true ) );
    $session->setVariable( "PaymentMethod", eZHTTPTool::getVar( "PaymentMethod", true ) );

//    $session->setVariable( "ShippingCost", eZHTTPTool::getVar( "ShippingCost", true ) );
//    $session->setVariable( "ShippingVAT", eZHTTPTool::getVar( "ShippingVAT", true ) );

    $session->setVariable( "ShippingCost", $cart->shippingCost( new eZShippingType( $currentTypeID ) ) );
    $session->setVariable( "ShippingVAT", $cart->shippingVAT( new eZShippingType( $currentTypeID ) ) );
    
    $session->setVariable( "ShippingTypeID", eZHTTPTool::getVar( "ShippingTypeID", true ) );
    $session->setVariable( "IncludeVAT", eZHTTPTool::getVar( "IncludeVAT", true ) );

    if ( count( $VoucherIDArray ) > 0 )
    {
        $i=0;
        $voucherInfo = array();

        foreach ( $VoucherIDArray as $voucher )
        {
            $voucherMail[] = eZHTTPTool::getVar( "MailType-" . $voucher );
            $voucherID[] = $voucher;
            $i++;
        }

        $session->setArray( "VoucherMail", $voucherMail );
        $session->setArray( "VoucherID", $voucherID );
        $session->setVariable( "VoucherInfo", $voucherSession );
        eZHTTPTool::header( "Location: /trade/voucherinformation/0" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /trade/payment/" );
        exit();
    }
}

// show the shipping types
$type = new eZShippingType();
$types = $type->getAll();

$currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );
    
$currentShippingType = false;
foreach ( $types as $type )
{
    $t->set_var( "shipping_type_id", $type->id() );
    $t->set_var( "shipping_type_name", $type->name() );
    
    if ( is_numeric( $currentTypeID ) )
    {
        if ( $currentTypeID == $type->id() )
        {
            $currentShippingType = $type;
            $t->set_var( "type_selected", "selected" );
        }
        else
            $t->set_var( "type_selected", "" );            
    }
    else
    {
        if ( $type->isDefault() )
        {
            $currentShippingType = $type;
            $t->set_var( "type_selected", "selected" );
        }
        else
            $t->set_var( "type_selected", "" );
    }

    $t->parse( "shipping_type", "shipping_type_tpl", true );
}
// calculate the vat of the shiping
$shippingCost = $cart->shippingCost( $currentShippingType );
$t->set_var( "shipping_cost_value", $shippingCost );
$shippingVAT = $cart->shippingVAT( $currentShippingType );
$t->set_var( "shipping_vat_value", $shippingVAT );

$can_checkout = true;

// print the cart contents
{
// fetch the cart items
    $items = $cart->items();

    $locale = new eZLocale( $Language );
    $currency = new eZCurrency();
    
    $i = 0;
    $sum = 0.0;
    $totalVAT = 0.0;

    $t->set_var( "product_available_header", "" );
    if ( $ShowQuantity )
        $t->parse( "product_available_header", "product_available_header_tpl" );

    $ShippingCostValues = array();
    
    foreach ( $items as $item )
    {
        $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

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
        $t->set_var( "product_id", $product->id() );
        
        $wishListItem = $item->wishListItem();
        
        if ( $wishListItem )
        {
            $wishList = $wishListItem->wishList();

            if ( $wishList )
            {
                $wishUser = $wishList->user();

                if ( get_class( $wishUser ) == "ezuser" )
                {
                    $address = new eZAddress();
                    $mainAddress =& $address->mainAddress( $wishUser );

                    if ( get_class( $mainAddress ) == "ezaddress" )
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
            $options =& $product->options();
            if ( count( $options ) == 1 )
            {
                $option = $options[0];
                if ( get_class( $option ) == "ezoption" )
                {
                    $optionValues =& $option->values();
                    if ( count( $optionValues ) > 1 )
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
        
        // product price
        $currency->setValue( $price );
        
        $sum += $price;
        $totalVAT += $product->vat( $price );
        
        $t->set_var( "product_name", $product->name() );

        $t->set_var( "cart_item_count", $item->count() );

        $optionValues =& $item->optionValues();

        $Quantity = $product->totalQuantity();
        if ( !$product->hasPrice() )
        {
            $Quantity = 0;
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();
                $value_quantity = $value->totalQuantity();
                if ( $value_quantity > 0 )
                    $Quantity = $value_quantity;
            }
        }
        $t->set_var( "product_available_item", "" );
        if ( $ShowQuantity )
        {
            $NamedQuantity = $Quantity;
            if ( $ShowNamedQuantity )
                $NamedQuantity = eZProduct::namedQuantity( $Quantity );
            $t->set_var( "product_availability", $NamedQuantity );
            $t->parse( "product_available_item", "product_available_item_tpl" );
        }

        $t->set_var( "cart_item_option", "" );
        $min_quantity = $Quantity;
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();
            $value_quantity = $value->totalQuantity();

            $t->set_var( "option_name", $option->name() );

            $descriptions = $value->descriptions();
            $t->set_var( "option_value", $descriptions[0] );
            
            $t->set_var( "cart_item_option_availability", "" );
            if ( !( is_bool( $value_quantity ) and !$value_quantity ) )
            {
                if ( is_bool( $min_quantity ) )
                    $min_quantity =  $value_quantity;
                else
                    $min_quantity = min( $min_quantity , $value_quantity );
                $named_quantity = $value_quantity;
                if ( $ShowNamedQuantity )
                    $named_quantity = eZProduct::namedQuantity( $value_quantity );
                if ( $ShowOptionQuantity )
                {
                    $t->set_var( "option_availability", $named_quantity );
                    $t->parse( "cart_item_option_availability", "cart_item_option_availability_tpl" );
                }
            }

            $t->parse( "cart_item_option", "cart_item_option_tpl", true );
        }

        $t->set_var( "voucher_information", "" );
        if ( $product->productType() == 2 )
        {
            $t->parse( "voucher_information", "voucher_information_tpl" );
        }
        
        if ( !( is_bool( $min_quantity ) and !$min_quantity ) and
             $RequireQuantity and $min_quantity == 0 )
            $can_checkout = false;

        $t->parse( "cart_item", "cart_item_tpl", true );

        $i++;
    }

    $vat = true;
    if ( is_numeric( $BillingAddressID ) )
    {
        $address = new eZAddress( $BillingAddressID );
        $country =& $address->country();
        if ( !$country->hasVAT() )
            $vat = false;
    }
    else
    {
        $address = new eZAddress();
        $mainAddress = $address->mainAddress( $user );

        $country =& $mainAddress->country();
        if ( !$country->hasVAT() )
            $vat = false;
    }

    $shippingCost = $cart->shippingCost( $currentShippingType );
    $t->set_var( "shipping_cost_value", $shippingCost );

    // calculate the vat of the shiping
    if ( $vat )
    {
        $shippingVAT = $cart->shippingVAT( $currentShippingType );
        $t->set_var( "shipping_vat_value", $shippingVAT );
    }
    else
    {
        $t->set_var( "shipping_vat_value", 0 );
    }

    $currency->setValue( $shippingCost );
    $t->set_var( "shipping_cost", $locale->format( $currency ) );


    // Find the correct price.
    // Check if the site want VAT included or not.
    if ( $ini->read_var( "eZTradeMain", "IncludeVAT" ) == "disabled" )
    {
        $t->set_var( "include_vat", "false" );
        $sum += $shippingCost;
        $totalPrice = $sum;
    }
    else
    {
        $t->set_var( "include_vat", "true" );
        if ( $vat )
            $sum = $sum + $shippingCost + $totalVAT + $shippingVAT;
        else
        {
            $t->set_var( "include_vat", "false" );
            $sum = $sum + $shippingCost;
        }
        $totalPrice = $sum;
    }

    // Show the VAT value
    if ( $vat )
    {
        $currency->setValue( $totalVAT + $shippingVAT );
        $t->set_var( "cart_vat_sum", $locale->format( $currency ) );
    }
    else
    {
        $currency->setValue( 0 );
        $t->set_var( "cart_vat_sum", $locale->format( $currency ) );
    }

    // Vouchers
    $vouchers = $session->arrayValue( "PayWithVocuher" );

    $i=1;
    $t->set_var( "vouchers", "" );
    $t->set_var( "voucher_item", "" );
    foreach( $vouchers as $voucher )
    {
        $voucher = new eZVoucher( $voucher );
        $voucherPrice = $voucher->price();
        $voucherID = $voucher->id();
        if ( $voucherPrice > $sum )
        {
            $voucherPrice = $sum;
            $currency->setValue( $voucherPrice );
            $t->set_var( "voucher_price", $locale->format( $currency ) );
        }
        else
        {
            $currency->setValue( $voucherPrice );
            $t->set_var( "voucher_price", $locale->format( $currency ) );
        }

        $totalVoucher[] = $voucherPrice;
        $voucherSession[$voucherID] = $voucherPrice;
        $t->set_var( "number", $i );
        $t->parse( "voucher_item", "voucher_item_tpl", true );
        $i++;
    }
    if ( is_array ( $totalVoucher ) )
    {
        $t->parse( "vouchers", "vouchers_tpl" );
        $session->setArray( "PayedWith", $voucherSession );
        $totalVoucher = array_sum ( $totalVoucher );
    }

    // Print the total sum.
    $sum -= $totalVoucher;
    $currency->setValue( $sum );
    $t->set_var( "cart_sum", $locale->format( $currency ) );
    $t->set_var( "cart_colspan", 1 + $i );

    if ( $sum <= 0 )
        $payment = false;
    else
        $payment = true;
    
    // the total cost of the payment
    $t->set_var( "total_cost_value", $sum ); 
}

$t->parse( "cart_item_list", "cart_item_list_tpl" );

$user =& eZUser::currentUser();

// print out the addresses

if ( $cart->personID() == 0 && $cart->companyID() == 0 )
{
    $t->set_var( "customer_first_name", $user->firstName() );
    $t->set_var( "customer_last_name", $user->lastName() );

    $addressArray = $user->addresses();
}
else
{
    if ( $cart->personID() > 0 )
    {
        $customer = new eZPerson( $cart->personID() );
        $t->set_var( "customer_first_name", $customer->firstName() );
        $t->set_var( "customer_last_name", $customer->lastName() );
    }
    else
    {
        $customer = new eZCompany( $cart->companyID() );
        $t->set_var( "customer_first_name", $customer->name() );
        $t->set_var( "customer_last_name", "" );
    }
    
    $addressArray = $customer->addresses();
}   

foreach ( $addressArray as $address )
{
    $t->set_var( "address_id", $address->id() );
    $t->set_var( "street1", $address->street1() );
    $t->set_var( "street2", $address->street2() );
    $t->set_var( "zip", $address->zip() );
    $t->set_var( "place", $address->place() );
    
    $country = $address->country();
    
    if ( $country )
    {
        $country = ", " . $country->name();
    }
    
    if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
        $t->set_var( "country", $country );
    else
        $t->set_var( "country", "" );
    
    unset( $mainAddress );
    $t->set_var( "is_selected", "" );
    $mainAddress = $address->mainAddress( $user );
    
    if ( get_class( $mainAddress ) == "ezaddress" )
    {
        if ( $mainAddress->id() == $address->id() )
        {
            $t->set_var( "is_selected", "selected" );
        }
    }
    
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

if ( $payment )
{
    $checkout = new eZCheckout();
    
    $instance =& $checkout->instance();
    
    $paymentMethods =& $instance->paymentMethods();
    
    foreach ( $paymentMethods as $paymentMethod )
    {
        $t->set_var( "payment_method_id", $paymentMethod["ID"] );
        $t->set_var( "payment_method_text", $paymentMethod["Text"] );
        
        $t->parse( "payment_method", "payment_method_tpl", true );
    }
    $t->parse( "show_payment", "show_payment_tpl" );
}
else
{
    $t->set_var( "show_paymeny", "" );
}
$t->set_var( "sendorder_item", "" );
if ( $can_checkout )
    $t->parse( "sendorder_item", "sendorder_item_tpl" );

$t->pparse( "output", "checkout_tpl" );


?>
