<?php
// 
// $Id: orderedit.php,v 1.31.8.5 2002/01/30 20:47:04 br Exp $
//
// Created on: <30-Sep-2000 13:03:13 bf>
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

if ( isSet( $Cancel ) )
{
    Header( "Location: /trade/orderlist/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";

$languageINI = new INIFIle( "eztrade/admin/intl/" . $Language . "/orderedit.php.ini", false );

$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" );
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$ShowExTaxColumn = $ini->read_var( "eZTradeMain", "ShowExTaxColumn" ) == "enabled" ? true : false;
$ShowIncTaxColumn = $ini->read_var( "eZTradeMain", "ShowIncTaxColumn" ) == "enabled" ? true : false;
$ShowExTaxTotal = $ini->read_var( "eZTradeMain", "ShowExTaxTotal" ) == "enabled" ? true : false;
$ColSpanSizeTotals = $ini->read_var( "eZTradeMain", "ColSpanSizeTotals" );


include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezorder.php" );
include_once( "eztrade/classes/ezpreorder.php" );
include_once( "eztrade/classes/ezcheckout.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

include_once( "eztrade/classes/ezorderstatustype.php" );


if ( $Action == "newstatus" )
{
    $status = new eZOrderStatus();
    
    // store the status
    $statusType = new eZOrderStatusType( $StatusID );

    $status = new eZOrderStatus();
    $status->setType( $statusType );
    $status->setComment( $StatusComment );
    $status->setOrderID( $OrderID );

    $user =& eZUser::currentUser();

    $status->setAdmin( $user );
    $status->store();

    Header( "Location: /trade/orderlist/" );
    exit();
}
else if ( $Action == "payment" )
{
    // handle payment info and verify the amount.
    if ( is_Numeric( $PaymentAmount ) && $PaymentAmount > 0 || isSet( $CancelAmount ) )
    {
        $session =& eZSession::globalSession();
        $order = new eZOrder( $OrderID );
        $order->orderTotals( $tax, $total );

        // get the total amount which is
        // already paid.
        $paidArray = $order->paidAmount();
        $totalPaidAmount = 0;
        if ( count( $paidArray > 0 ) )
        {
            foreach( $paidArray as $paid )
            {
                $totalPaidAmount += $paid["Paid"];
            }
        }
        $refundAmount = $order->refundAmount();
        // includes the payment transaction if the amount is verified.
        $maxAmount = $total["inctax"] - $totalPaidAmount - $refundAmount;

        if ( isSet( $CancelAmount ) )
        {
            $session->setVariable( "OrderPaymentAmount", $maxAmount );
            $session->setVariable( "OrderPaymentMode", "refund" );
            
            Header( "Location: /trade/transaction/$OrderID/" );
            exit();
        }        
        else if ( $PaymentAmount <= $maxAmount )
        {
            $session->setVariable( "OrderPaymentAmount", $PaymentAmount );
            $session->setVariable( "OrderPaymentMode", "transaction" );

            Header( "Location: /trade/transaction/$OrderID/" );
            exit();
        }
        else
        {
            Header( "Location: /trade/orderedit/$OrderID/" );
            exit();
        }
    }
    else
    {
        Header( "Location: /trade/orderedit/$OrderID/" );
        exit();
    }
}
else if ( $Action == "delete" )
{
    $order = new eZOrder( $OrderID );
    $order->delete();
    
    Header( "Location: /trade/orderlist/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "orderedit.php" );

$t->setAllStrings();

$t->set_file( "order_edit_tpl", "orderedit.tpl" );

$t->set_block( "order_edit_tpl", "visa_tpl", "visa" );
$t->set_block( "order_edit_tpl", "mastercard_tpl", "mastercard" );
$t->set_block( "order_edit_tpl", "cod_tpl", "cod" );
$t->set_block( "order_edit_tpl", "invoice_tpl", "invoice" );

$t->set_block( "order_edit_tpl", "order_status_option_tpl", "order_status_option" );
$t->set_block( "order_edit_tpl", "order_status_history_tpl", "order_status_history" );



$t->set_block( "order_edit_tpl", "full_cart_tpl", "full_cart" );
$t->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "header_savings_item_tpl", "header_savings_item" );
$t->set_block( "cart_item_list_tpl", "header_inc_tax_item_tpl", "header_inc_tax_item" );
$t->set_block( "cart_item_list_tpl", "header_ex_tax_item_tpl", "header_ex_tax_item" );

$t->set_block( "full_cart_tpl", "voucher_item_list_tpl", "voucher_item_list" );
$t->set_block( "voucher_item_list_tpl", "voucher_used_header_inc_tax_item_tpl", "voucher_used_header_inc_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_used_header_ex_tax_item_tpl", "voucher_used_header_ex_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_left_header_inc_tax_item_tpl", "voucher_left_header_inc_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_left_header_ex_tax_item_tpl", "voucher_left_header_ex_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_item_tpl", "voucher_item" );
$t->set_block( "voucher_item_tpl", "voucher_used_inc_tax_item_tpl", "voucher_used_inc_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_used_ex_tax_item_tpl", "voucher_used_ex_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_left_inc_tax_item_tpl", "voucher_left_inc_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_left_ex_tax_item_tpl", "voucher_left_ex_tax_item" );

$t->set_block( "full_cart_tpl", "total_ex_tax_item_tpl", "total_ex_tax_item" );
$t->set_block( "full_cart_tpl", "total_inc_tax_item_tpl", "total_inc_tax_item" );
$t->set_block( "full_cart_tpl", "subtotal_ex_tax_item_tpl", "subtotal_ex_tax_item" );
$t->set_block( "full_cart_tpl", "subtotal_inc_tax_item_tpl", "subtotal_inc_tax_item" );
$t->set_block( "full_cart_tpl", "shipping_ex_tax_item_tpl", "shipping_ex_tax_item" );
$t->set_block( "full_cart_tpl", "shipping_inc_tax_item_tpl", "shipping_inc_tax_item" );

$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_savings_item_tpl", "cart_savings_item" );
$t->set_block( "cart_item_tpl", "cart_inc_tax_item_tpl", "cart_inc_tax_item" );
$t->set_block( "cart_item_tpl", "cart_ex_tax_item_tpl", "cart_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_option_tpl", "option_savings_item_tpl", "option_savings_item" );
$t->set_block( "cart_item_option_tpl", "option_inc_tax_item_tpl", "option_inc_tax_item" );
$t->set_block( "cart_item_option_tpl", "option_ex_tax_item_tpl", "option_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_basis_tpl", "cart_item_basis" );
$t->set_block( "cart_item_basis_tpl", "basis_savings_item_tpl", "basis_savings_item" );
$t->set_block( "cart_item_basis_tpl", "basis_inc_tax_item_tpl", "basis_inc_tax_item" );
$t->set_block( "cart_item_basis_tpl", "basis_ex_tax_item_tpl", "basis_ex_tax_item" );

$t->set_block( "full_cart_tpl", "tax_specification_tpl", "tax_specification" );
$t->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );

$t->set_block( "order_edit_tpl", "online_payment_list_tpl", "online_payment_list" );
$t->set_block( "online_payment_list_tpl", "online_payment_item_tpl", "online_payment_item" );
$t->set_block( "order_edit_tpl", "online_payment_pay_tpl", "online_payment_pay" );
$t->set_block( "order_edit_tpl", "refunded_amount_tpl", "refunded_amount" );
$t->set_block( "order_edit_tpl", "online_payment_verified_tpl", "online_payment_verified" );

$order = new eZOrder( $OrderID );

// get the customer

$user = $order->user();

if ( $user )
{
    if ( $order->personID() == 0 && $order->companyID() == 0 )
    {
        $t->set_var( "customer_email", $user->email() );
        $t->set_var( "customer_id", $user->id() );    
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
        $emailList = $customer->emailAddress();
        $t->set_var( "customer_email", $emailList[0] );
    }

    // print preorder id
    $preOrderId = new eZPreOrder();
    $preOrderId->getByOrderId( $OrderID );
    if ( $preOrderId->id() )
    {
        $t->set_var( "preorder_id", $preOrderId->id() );
    }
    else
    {
        $t->set_var( "preorder_id", "" );
    }

    // print out the addresses
    $shippingAddress =& $order->shippingAddress();

    $t->set_var( "shipping_street1", $shippingAddress->street1() );
    $t->set_var( "shipping_street2", $shippingAddress->street2() );
    $t->set_var( "shipping_zip", $shippingAddress->zip() );
    $t->set_var( "shipping_place", $shippingAddress->place() );

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

    $country = $shippingAddress->country();
    if ( is_object( $country ) )
    {
        $t->set_var( "shipping_country", $country->name() );
    }
    else
    {
        $t->set_var( "shipping_country", "" );
    }
    $billingAddress =& $order->billingAddress();

    $t->set_var( "billing_street1", $billingAddress->street1() );
    $t->set_var( "billing_street2", $billingAddress->street2() );
    $t->set_var( "billing_zip", $billingAddress->zip() );
    $t->set_var( "billing_place", $billingAddress->place() );
    
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( false ) );

    $country = $billingAddress->country();
    if ( is_object( $country ) )
    {
        $t->set_var( "billing_country", $country->name() );
    }
    else
    {
        $t->set_var( "billing_country", "" );
    }
}

// fetch the order items
$items = $order->items( $OrderType );
$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$i = 0;
$sum = 0.0;
$totalVAT = 0.0;

function turnColumnsOnOff( $rowName )
{
    global $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn;
    if ( $ShowSavingsColumn == true )
    {
        $t->parse( $rowName . "_savings_item", $rowName . "_savings_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_savings_item", "" );
    }

    if ( $ShowExTaxColumn == true )
    {
        $t->parse( $rowName . "_ex_tax_item", $rowName . "_ex_tax_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_ex_tax_item", "" );
    }

    if ( $ShowIncTaxColumn == true )
    {
        $t->parse( $rowName . "_inc_tax_item", $rowName . "_inc_tax_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_inc_tax_item", "" );
    }
}

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$numberOfItems = 0;
$j = 0;

foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
    $j++;  
    $t->set_var( "cart_item_id", $item->id() );
    $product =& $item->product();
    $vatPercentage = $product->vatPercentage();
    $productHasVAT = $product->priceIncVAT();
    
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_number", $product->productNumber() );
    $t->set_var( "product_price", $item->localePrice( false, true, $PricesIncludeVAT ) );
    $t->set_var( "product_count", $item->count() );
    $t->set_var( "product_total_ex_tax", $item->localePrice( true, true, false ) );
    $t->set_var( "product_total_inc_tax", $item->localePrice( true, true, true ) );

    $numberOfItems++;

    $numberOfOptions = 0;
    
    $optionValues =& $item->optionValues();

    $t->set_var( "cart_item_option", "" );
    $t->set_var( "cart_item_basis", "" );

    foreach ( $optionValues as $optionValue )
    {
        turnColumnsOnOff( "option" );
    
        $t->set_var( "option_id", "" );
        $t->set_var( "option_name", $optionValue->valueName() );
        $t->set_var( "option_value", $optionValue->optionName() );
        $t->set_var( "option_price", "" );
        $t->parse( "cart_item_option", "cart_item_option_tpl", true );
        
        $numberOfOptions++;
    }
    turnColumnsOnOff( "cart" );
    turnColumnsOnOff( "basis" );
    
    if ( $numberOfOptions ==  0 )
    {
        $t->set_var( "cart_item_option", "" );
        $t->set_var( "cart_item_basis", "" );
    }
    else
    {
        if( false )
        {
            $t->set_var( "basis_price", $item->localePrice( false, false, $PricesIncludeVAT ) );
            $t->parse( "cart_item_basis", "cart_item_basis_tpl", true );
        }
        else
        {
            $t->set_var( "cart_item_basis", "" );
        }
   }

    $t->parse( "cart_item", "cart_item_tpl", true );
}

if ( $numberOfItems > 0 )
{
    $ShowCart = true;
}

$t->setAllStrings();

turnColumnsOnOff( "header" );

if ( $ShowCart == true )
{
    
    $order->orderTotals( $tax, $total );

    $t->set_var( "empty_cart", "" );

    $currency->setValue( $total["subinctax"] );
    $t->set_var( "subtotal_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["subextax"] );
    $t->set_var( "subtotal_ex_tax", $locale->format( $currency ) );
    
    $currency->setValue( $total["inctax"] );
    $t->set_var( "total_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["extax"] );
    $t->set_var( "total_ex_tax", $locale->format( $currency ) );
    
    $currency->setValue( $total["shipinctax"] );
    $t->set_var( "shipping_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["shipextax"] );
    $t->set_var( "shipping_ex_tax", $locale->format( $currency ) );
    
    if ( $ShowSavingsColumn == false )
    {
        $ColSpanSizeTotals--;
    }
    
    $SubTotalsColumns = $ColSpanSizeTotals;
    
    if ( $ShowExTaxColumn == true )
    {
        if ( $ShowExTaxTotal == true or $ShowIncTaxColumn == false )
        {
            $t->parse( "total_ex_tax_item", "total_ex_tax_item_tpl" );
            $t->parse( "subtotal_ex_tax_item", "subtotal_ex_tax_item_tpl" );
            $t->parse( "shipping_ex_tax_item", "shipping_ex_tax_item_tpl" );
        }
        else
        {
            $t->set_var( "total_ex_tax_item", "" );
            $t->set_var( "subtotal_ex_tax_item", "" );
            $t->set_var( "shipping_ex_tax_item", "" );
        }
    }
    else
    {
        $ColSpanSizeTotals--;
        $t->set_var( "total_ex_tax_item", "" );
        $t->set_var( "subtotal_ex_tax_item", "" );
        $t->set_var( "shipping_ex_tax_item", "" );
    }

    if ( $ShowIncTaxColumn == true )
    {
        $t->parse( "total_inc_tax_item", "total_inc_tax_item_tpl" );
        $t->parse( "subtotal_inc_tax_item", "subtotal_inc_tax_item_tpl" );
        $t->parse( "shipping_inc_tax_item", "shipping_inc_tax_item_tpl" );
    }
    else
    {
        $ColSpanSizeTotals--;
        $t->set_var( "total_inc_tax_item", "" );
        $t->set_var( "subtotal_inc_tax_item", "" );
        $t->set_var( "shipping_inc_tax_item", "" );
    }
    
    if ( $ShowIncTaxColumn and $ShowExTaxColumn and $ShowExTaxTotal )
    {
        $t->set_var( "subtotals_span_size", $SubTotalsColumns - 1 );
    }
    else
    {
        $t->set_var( "subtotals_span_size", $ColSpanSizeTotals  );        
    }
    
    $t->set_var( "totals_span_size", $ColSpanSizeTotals );
    $t->parse( "cart_item_list", "cart_item_list_tpl" );
    $t->parse( "full_cart", "full_cart_tpl" );

    $currency->setValue( $total["tax"] );
    $t->set_var( "tax", $locale->format( $currency ) );

    $j = 0;

    foreach( $tax as $taxGroup )
    {
        $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
        $j++;  
        $currency->setValue( $taxGroup["basis"] );    
        $t->set_var( "sub_tax_basis", $locale->format( $currency ) );

        $currency->setValue( $taxGroup["tax"] );    
        $t->set_var( "sub_tax", $locale->format( $currency ) );

        $t->set_var( "sub_tax_percentage", $taxGroup["percentage"] );
        $t->parse( "tax_item", "tax_item_tpl", true );
    }

    $t->parse( "tax_specification", "tax_specification_tpl" );
}

$usedVouchers =& $order->usedVouchers();

$t->set_var( "voucher_item_list", "" );

if ( count ( $usedVouchers ) > 0 )
{
    turnColumnsOnOff( "voucher_used_header");
    turnColumnsOnOff( "voucher_left_header");
    $j = 0;
    foreach ( $usedVouchers as $voucherUsed )
    {
        $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
        $j++;  

        $voucher =& $voucherUsed->voucher();
        $t->set_var( "voucher_number", $voucher->keyNumber() );

        eZOrder::voucherTotal( $tax, $total, $voucherUsed );
        $currency->setValue( $total["extax"] );
        $t->set_var( "voucher_used_ex_tax", $locale->format( $currency ) );
        $currency->setValue( $total["inctax"] );
        $t->set_var( "voucher_used_inc_tax", $locale->format( $currency ) );

        eZOrder::voucherTotal( $tax, $total, $voucher );
        $currency->setValue( $total["extax"] );
        $t->set_var( "voucher_left_ex_tax", $locale->format( $currency ) );
        $currency->setValue( $total["inctax"] );
        $t->set_var( "voucher_left_inc_tax", $locale->format( $currency ) );

        turnColumnsOnOff( "voucher_used" );
        turnColumnsOnOff( "voucher_left" );
        $t->parse( "voucher_item", "voucher_item_tpl", true );
        
    }
    $t->parse( "voucher_item_list", "voucher_item_list_tpl" );
}



// Code for visa / mastercard / eurocard with paynet.

$paidArray = $order->paidAmount();
$totalPaidAmount = 0;

$preOrder = new eZPreOrder();
$preOrder->getByOrderID( $order->id() );
$pnutr = $preOrder->pnutr();

if ( $pnutr )
{
    $edate = $preOrder->edate();
    $t->set_var( "pnutr", $pnutr );

    $t->set_var( "year", $edate->year() );
    $t->set_var( "month", $edate->month() );
    $t->set_var( "day", $edate->day() );

    $t->parse( "online_payment_verified", "online_payment_verified_tpl" );
}
else
{
    $t->set_var( "online_payment_verified", "" );
}

if ( count( $paidArray ) > 0 && $pnutr )
{
    $j=0;
    foreach( $paidArray as $paid )
    {
        $totalPaidAmount += $paid["Paid"];

        $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
        $j++;

        $currency->setValue( $paid["Paid"] );
        $t->set_var( "online_payment", $locale->format( $currency ) );

        $hour = $paid["Date"]->hour();
        $minute = $paid["Date"]->minute();
        $second = $paid["Date"]->second();
        $t->set_var( "year", $paid["Date"]->year() );
        $t->set_var( "month", $paid["Date"]->month() );
        $t->set_var( "day", $paid["Date"]->day() );
        $t->set_var( "hour", $hour < 10 ? "0" . $hour : $hour );
        $t->set_var( "minute", $minute < 10 ? "0" . $minute : $minute );
        $t->set_var( "second", $second < 10 ? "0" . $second : $second );

	$t->set_var( "pnutr", $paid["Pnutr"] );

        $t->parse( "online_payment_item", "online_payment_item_tpl", true );
    }
    $t->parse( "online_payment_list", "online_payment_list_tpl" );
}
else
{
    $t->set_var( "online_payment_list", "" );
}


$t->set_var( "payment_amount", "" );
$refundAmount = $order->refundAmount();
$maxAmount = $total["inctax"] - $totalPaidAmount - $refundAmount;

// set the minimum and maximum amount which can be
// paid on this order.
if ( $maxAmount > 0 && $pnutr )
{
    $currency->setValue( 0 );
    $t->set_var( "lowest_amount", $locale->format( $currency ) );
    $currency->setValue( $maxAmount );
    $t->set_var( "highest_amount", $locale->format( $currency ) );
    
    $t->parse( "online_payment_pay", "online_payment_pay_tpl" );
}
else
{
    $t->set_var( "online_payment_pay", "" );
}

// print the refunded amount if any.
if ( $refundAmount > 0 )
{
    $currency->setValue( $refundAmount );
    $t->set_var( "refund_amount", $locale->format( $currency ) );
    $t->parse( "refunded_amount", "refunded_amount_tpl" );
}
else
{
    $t->set_var( "refunded_amount", "" );
}

// print the status for the order.
$statusType = new eZOrderStatusType();
$statusTypeArray = $statusType->getAll();
foreach ( $statusTypeArray as $status )
{
    $statusName = preg_replace( "#intl-#", "", $status->name() );
    $statusName =  $languageINI->read_var( "strings", $statusName );
    
    $t->set_var( "option_name", $statusName );
    $t->set_var( "option_id", $status->id() );
    $t->parse( "order_status_option", "order_status_option_tpl", true );
}


$historyArray = $order->statusHistory();
$i=0;
foreach ( $historyArray as $status )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $admin =  $status->admin();
    
    $statusType = $status->type();

    $statusName = preg_replace( "#intl-#", "", $statusType->name() );
    $statusName =  $languageINI->read_var( "strings", $statusName );
    
    
    $t->set_var( "status_date", $locale->format( $status->altered() ) );
    $t->set_var( "status_name", $statusName );
    $t->set_var( "status_comment", $status->comment() );
    $t->set_var( "admin_login", $admin->login() );
    $t->parse( "order_status_history", "order_status_history_tpl", true );
    $i++;
}


$checkout = new eZCheckout();
$instance =& $checkout->instance();
$paymentMethod = $instance->paymentName( $order->paymentMethod() );

$t->set_var( "payment_method", $paymentMethod );

$shippingType = $order->shippingType();

$t->set_var( "shipping_method", "" );
if ( $shippingType )
{    
    $t->set_var( "shipping_method", $shippingType->name() );
}

$t->set_var( "order_id", $order->id() );


$t->pparse( "output", "order_edit_tpl" );

?>
