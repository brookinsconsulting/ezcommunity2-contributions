<?php
<<<<<<< checkout.php
=======
// 
// $Id: checkout.php,v 1.96.4.9 2002/06/07 09:41:19 ce Exp $
>>>>>>> 1.100
//
// $Id: checkout.php,v 1.96.4.9 2002/06/07 09:41:19 ce Exp $
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
include_once( "eztrade/classes/ezcheckoutdisplayer.php" );

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
$user =& eZUser::currentUser();

if ( get_class( $user ) != "ezuser" )
    eZHTTPTool::header( "Location: /user/login/" );

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

// get the cart or create it
$cart = $cart->getBySession( $session, "Cart" );
if ( !$cart || !$cart->items() )
{
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language , "checkout.php" );

$t->setAllStrings();

$t->set_file( "checkout_page_tpl", "checkout.tpl" );

$t->set_block( "checkout_page_tpl", "empty_cart_tpl", "empty_cart" );

$t->set_block( "checkout_page_tpl", "full_cart_tpl", "full_cart" );
$t->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "header_savings_item_tpl", "header_savings_item" );
$t->set_block( "cart_item_list_tpl", "header_inc_tax_item_tpl", "header_inc_tax_item" );
$t->set_block( "cart_item_list_tpl", "header_ex_tax_item_tpl", "header_ex_tax_item" );

$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_savings_item_tpl", "cart_savings_item" );
$t->set_block( "cart_item_tpl", "cart_inc_tax_item_tpl", "cart_inc_tax_item" );
$t->set_block( "cart_item_tpl", "cart_ex_tax_item_tpl", "cart_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_basis_tpl", "cart_item_basis" );
$t->set_block( "cart_item_basis_tpl", "basis_savings_item_tpl", "basis_savings_item" );
$t->set_block( "cart_item_basis_tpl", "basis_inc_tax_item_tpl", "basis_inc_tax_item" );
$t->set_block( "cart_item_basis_tpl", "basis_ex_tax_item_tpl", "basis_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_option_tpl", "option_savings_item_tpl", "option_savings_item" );
$t->set_block( "cart_item_option_tpl", "option_inc_tax_item_tpl", "option_inc_tax_item" );
$t->set_block( "cart_item_option_tpl", "option_ex_tax_item_tpl", "option_ex_tax_item" );

$t->set_block( "full_cart_tpl", "subtotal_ex_tax_item_tpl", "subtotal_ex_tax_item" );
$t->set_block( "full_cart_tpl", "subtotal_inc_tax_item_tpl", "subtotal_inc_tax_item" );

$t->set_block( "full_cart_tpl", "shipping_ex_tax_item_tpl", "shipping_ex_tax_item" );
$t->set_block( "full_cart_tpl", "shipping_inc_tax_item_tpl", "shipping_inc_tax_item" );

$t->set_block( "full_cart_tpl", "vouchers_tpl", "vouchers_tpl" );
$t->set_block( "vouchers_tpl", "voucher_item_tpl", "voucher_item" );
$t->set_block( "checkout_page_tpl", "remove_voucher_tpl", "remove_voucher" );

$t->set_block( "full_cart_tpl", "total_ex_tax_item_tpl", "total_ex_tax_item" );
$t->set_block( "full_cart_tpl", "total_inc_tax_item_tpl", "total_inc_tax_item" );

$t->set_block( "full_cart_tpl", "tax_specification_tpl", "tax_specification" );
$t->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );

$t->set_block( "full_cart_tpl", "shipping_type_tpl", "shipping_type" );

$t->set_block( "checkout_page_tpl", "shipping_address_tpl", "shipping_address" );
$t->set_block( "checkout_page_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "billing_address_tpl", "billing_option_tpl", "billing_option" );
$t->set_block( "checkout_page_tpl", "wish_user_tpl", "wish_user" );

$t->set_block( "checkout_page_tpl", "sendorder_item_tpl", "sendorder_item" );

$t->set_block( "checkout_page_tpl", "show_payment_tpl", "show_payment" );
$t->set_block( "show_payment_tpl", "payment_method_tpl", "payment_method" );

$t->set_var( "show_payment", "" );
$t->set_var( "price_ex_vat", "" );
$t->set_var( "price_inc_vat", "" );
$t->set_var( "cart_item", "" );
$t->set_var( "pay_with_voucher", "false" );

$checkoutDisplayer = new eZCheckoutDisplayer( $t, $cart );

if ( isSet ( $RemoveVoucher ) )
{
    if ( count ( $RemoveVoucherArray ) > 0 )
    {
        $newArray = array();
        $payWithVoucher = $session->arrayValue( "PayWithVoucher" );

        while( list($key,$voucherID) = each( $payWithVoucher ) )
        {
            if ( !in_array ( $voucherID, $RemoveVoucherArray ) )
                 $newArray[$voucherID] = $price;
        }

        $session->setVariable( "PayWithVoucher", "" );
    }
}

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
    $session->setVariable( "TotalVAT", eZHTTPTool::getVar( "TotalVAT", true ) );

    if ( eZHTTPTool::getVar( "PayWithVoucher", true ) == "true" )
    {
        if ( eZHTTPTool::getVar( "PaymentMethod", true ) )
            $session->setArray( "PaymentMethod", array( eZHTTPTool::getVar( "PaymentMethod", true ), "voucher_done" ) );
        else
            $session->setArray( "PaymentMethod", array( "voucher_done" ) );
    }
    else
        $session->setArray( "PaymentMethod", array( eZHTTPTool::getVar( "PaymentMethod", true ) ) );

    $session->setVariable( "Comment", eZHTTPTool::getVar( "Comment", true ) );

    $session->setVariable( "ShippingCost", $cart->shippingCost( new eZShippingType( $currentTypeID ) ) );
    $session->setVariable( "ShippingVAT", $cart->shippingVAT( new eZShippingType( $currentTypeID ) ) );

    $session->setVariable( "ShippingTypeID", eZHTTPTool::getVar( "ShippingTypeID", true ) );

    eZHTTPTool::header( "Location: /trade/payment/" );
    exit();
}

// display the shipping types
$checkoutDisplayer->displayShipping();

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
    {
        $vat = false;
        $totalVAT = 0;
    }
}

if ( $vat == false )
{
    $ShowExTaxColumn = true;
    $PricesIncludeVAT = false;
    $ShowExTaxTotal = true;
    $ShowIncTaxColumn = false;
}

// Show items

$checkoutDisplayer->displayItems();

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

// Display the cart
$cart->cartTotals( $tax, $total );
$checkoutDisplayer->displayCart( $total );

$can_checkout = true;

// Display the addresses
$checkoutDisplayer->displayAddresses();

// Display the checkout types
$checkoutDisplayer->displayPaymentMethods( $total );

// Print the total sum.
$total["inctax"] = $total["inctax"] - $totalVoucher["inctax"];

$currency->setValue( $total["inctax"] );
$t->set_var( "cart_sum", $locale->format( $currency ) );
$t->set_var( "cart_colspan", 1 + $i );

if ( $sum <= 0 )
        $payment = false;
else
$payment = true;

// the total cost of the payment
$t->set_var( "total_cost_value", $total["inctax"] );
$t->set_var( "total_vat_value", $totalVAT );

// A check should be done in the code above for qty.
$can_checkout = true;

if ( $can_checkout )
    $t->parse( "sendorder_item", "sendorder_item_tpl" );

$t->pparse( "output", "checkout_page_tpl" );
?>
