<?php
//
// $Id: checkoutpaymentmethod.php,v 1.1.2.1 2002/06/07 09:41:20 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <11-Dec-2001 15:41:45 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!!
//! The class ||| does
/*!

*/

include_once( "ezuser/classes/ezuser.php" );
include_once( "eztrade/classes/ezcheckoutdisplayer.php" );
include_once( "eztrade/classes/ezvoucher.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "classes/ezhttptool.php" );

$session =& eZSession::globalSession();

if ( !isSet ( $VerifySuccess ) )
    $VerifySuccess = "false";

if ( isSet ( $Next ) )
{
    eZHTTPTool::header( "Location: /trade/checkout/overview/" );
    exit();
}
if ( isSet ( $UseVoucher ) )
{
    $session->setArray( "PaymentMethod", array_uniqe ( array( $session->arrayValue( "PaymentMethod" ), "voucher_done" ) ) );
    $voucher = eZVoucher::getFromKeyNumber( $KeyNumber );
    if ( ( get_class( $voucher ) == "ezvoucher" ) and ( !in_array( $voucher->id(), $session->arrayValue( "PayWithVoucher" ) ) ) )
    {
        $array = $session->arrayValue( "PayWithVoucher" );
        array_push( $array, $voucher->id() );
        $session->setArray( "PayWithVoucher", $array );
        $session->arrayValue( "PayWithVoucher" );
    }
}

$checkout = new eZCheckout();
$instance =& $checkout->instance();

if ( isSet ( $DeletePayments ) )
{
    $session->setArray( "PaymentMethod", array() );
    if ( $RemovePayment == "on" )
    {
        $VerifyData = array();
        $session->setArray( "VerifyData", $VerifyData );
    }
    if ( count ( $RemoveVoucherArray ) > 0 )
    {
        $newArray = array();
        $payWithVoucher = $session->arrayValue( "PayWithVoucher" );

        while( list($key,$voucherID) = each( $payWithVoucher ) )
        {
            if ( !in_array ( $voucherID, $RemoveVoucherArray ) )
                $newArray[] = $voucherID;
        }
        $session->setArray( "PayWithVoucher", $newArray );
    }
}

if ( isSet ( $Verify ) )
{
    include( $instance->paymentFile( $PaymentMethod ) );

    if ( $VerifySuccess == "true" )
    {
        if ( is_array ( $VerifyData ) )
        {
            $session->setArray( "PaymentMethod", array_unique ( array ( $session->arrayValue( "PaymentMethod" ), $PaymentMethod ) ) );

            $session->setArray( "VerifyData", $VerifyData );
        }
    }
}

$ini =& INIFile::globalINI();
$showPayment = false;

$Language = $ini->read_var( "eZTradeMain", "Language" );

if ( is_numeric ( $PaymentMethod ) )
{
    $tempFile =& $instance->paymentTemplate( $PaymentMethod );
    $payFile =& $instance->paymentFile( $PaymentMethod );
    if ( ( file_exists ( $tempFile ) ) and ( file_exists ( $payFile ) ) )
    {
        $showPayment = true;
        $array = explode( "/", $payFile );
        $count = count( $array );
        $payFile = $array[$count-1];

        $array = explode( "/", $tempFile );
        $count = count( $array );
        $tempFile = $array[$count-1];

        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             array( "eztrade/user/intl/", "eztrade/user/intl/" ), $Language , array( "checkoutpaymentmethod.php", $payFile ) );

        $templates = array ( "checkout_payment_method_page_tpl" => "checkoutpaymentmethod.tpl",
                             "payment_method_file_tpl" => $tempFile );
        $t->set_file( $templates );
    }
    else
    {
        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             "eztrade/user/intl/", $Language , "checkoutpaymentmethod.php" );

        $t->set_file( "checkout_payment_method_page_tpl", "checkoutpaymentmethod.tpl" );
    }
}
else
{
    $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                         "eztrade/user/intl/", $Language , "checkoutpaymentmethod.php" );

    $t->set_file( "checkout_payment_method_page_tpl", "checkoutpaymentmethod.tpl" );
}

$t->setAllStrings();

// Payments methods
$t->set_block( "checkout_payment_method_page_tpl", "show_payment_tpl", "show_payment" );
$t->set_block( "show_payment_tpl", "payment_method_tpl", "payment_method" );

// Vouchers
$t->set_block( "checkout_payment_method_page_tpl", "header_ex_tax_item_tpl", "header_ex_tax_item" );
$t->set_block( "checkout_payment_method_page_tpl", "header_inc_tax_item_tpl", "header_inc_tax_item" );

$t->set_block( "checkout_payment_method_page_tpl", "voucher_item_tpl", "voucher_item" );

$t->set_block( "checkout_payment_method_page_tpl", "total_ex_tax_item_tpl", "total_ex_tax_item" );
$t->set_block( "checkout_payment_method_page_tpl", "total_inc_tax_item_tpl", "total_inc_tax_item" );

$t->set_block( "voucher_item_tpl", "voucher_ex_tax_item_tpl", "voucher_ex_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_inc_tax_item_tpl", "voucher_inc_tax_item" );

$t->set_block( "checkout_payment_method_page_tpl", "payment_item_tpl", "payment_item" );

$t->set_block( "payment_item_tpl", "payment_ex_tax_item_tpl", "payment_ex_tax_item" );
$t->set_block( "payment_item_tpl", "payment_inc_tax_item_tpl", "payment_inc_tax_item" );

$t->set_block( "voucher_item_tpl", "delete_voucher_tpl", "delete_voucher" );

$t->set_block( "checkout_payment_method_page_tpl", "delete_payments_tpl", "delete_payments" );
// $t->set_block( "checkout_payment_method_page_tpl", "delete_voucher_header_tpl", "delete_voucher_header" );

$t->set_block( "checkout_payment_method_page_tpl", "rest_list_tpl", "rest_list" );
$t->set_block( "rest_list_tpl", "rest_inc_tax_item_tpl", "rest_inc_tax_item" );
$t->set_block( "rest_list_tpl", "rest_ex_tax_item_tpl", "rest_ex_tax_item" );

$t->set_block( "checkout_payment_method_page_tpl", "next_tpl", "next" );

$t->set_var( "header_inc_item", "" );
$t->set_var( "header_ex_item", "" );
$t->set_var( "voucher_ex_item", "" );
$t->set_var( "voucher_inc_item", "" );
$t->set_var( "delete_payments", "" );
$t->set_var( "delete_voucher", "" );
$t->set_var( "delete_voucher_header", "" );
$t->set_var( "voucher_item", "" );
$t->set_var( "rest_list", "" );
$t->set_var( "payment_item", "" );

// get the cart or create it
$cart =& eZCart::getBySession( $session );

$checkoutDisplayer = new eZCheckoutDisplayer( $t, $cart );

$cart->cartTotals( $tax, $total );

$checkoutDisplayer->displayPaymentMethods( $total );

// Show the vouchers
$checkoutDisplayer->displayPayments( $total, $session->arrayValue( "VerifyData" ) );

$checkoutDisplayer->path( "checkout_payment_method_page_tpl" );

if ( ( isSet ( $Choose ) ) or ( isSet ( $Verify ) ) and $showPayment and !$session->arrayValue( "VerifyData" ) )
{
    $t->set_var( "payment_method", $PaymentMethod );
    $t->set_var( "action_url", "checkout/paymentmethod/" );
    $visaTpl = $t->parse( "output", "payment_method_file_tpl" );
    $t->set_var( "payment_method_file_tpl", $visaTpl );
}
else
{
    $t->set_var( "payment_method_file_tpl", "" );
}

$t->set_var( "veryfy_success", $VerifySuccess );

$t->pparse( "output", "checkout_payment_method_page_tpl" );

?>
