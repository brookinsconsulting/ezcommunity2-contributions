<?php
//
// $Id: transaction.php,v 1.1.2.1 2002/01/29 14:02:30 br Exp $
//
// Definition of ||| class
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <20-Jan-2002 15:28:49 br>
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

if ( isSet( $Cancel ) )
{
    Header( "Location: /trade/orderedit/$OrderID/" );
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

include_once( "eztrade/classes/ezorder.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "transaction.php" );

$t->setAllStrings();

// set the template blocks.
$t->set_file( "transaction_tpl", "transaction.tpl" );
$t->set_block( "transaction_tpl", "online_payment_list_tpl", "online_payment_list" );
$t->set_block( "online_payment_list_tpl", "online_payment_item_tpl", "online_payment_item" );
$t->set_block( "transaction_tpl", "new_transaction_tpl", "new_transaction" );
$t->set_block( "transaction_tpl", "refund_amount_tpl", "refund_amount" );

$t->set_var( "new_transaction", "" );
$t->set_var( "refund_amount", "" );

// Get the amount from session.

$session =& eZSession::globalSession();
$Amount = $session->variable( "OrderPaymentAmount" );


// print visa / mastercard / eurocard transactions with paynet.

$order = new eZOrder( $OrderID );
$currency = new eZCurrency();
$locale = new eZLocale( $Language );


$paidArray = $order->paidAmount();
$totalPaidAmount = 0;

if ( count( $paidArray ) > 0  )
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

        $t->parse( "online_payment_item", "online_payment_item_tpl", true );
    }
    $t->parse( "online_payment_list", "online_payment_list_tpl" );
}
else
{
    $t->set_var( "online_payment_list", "" );
}

$Amount = $session->variable( "OrderPaymentAmount" );
$PaymentMode = $session->variable( "OrderPaymentMode" );

$currency->setValue( $Amount );
$t->set_var( "amount", $locale->format( $currency ) );

$t->set_var( "order_id", $OrderID );


// handle payment info and verify the amount.
$continueTransaction = false;
if ( is_Numeric( $Amount ) && $Amount > 0 )
{
    $order = new eZOrder( $OrderID );
    $order->orderTotals( $tax, $total );
    
    $paidArray = $order->paidAmount();
    $totalPaidAmount = 0;
    if ( count( $paidArray ) > 0 )
    {
        $j=0;
        foreach( $paidArray as $paid )
        {
            $totalPaidAmount += $paid["Paid"];
        }
    }
    $maxAmount = $total["inctax"] - $totalPaidAmount;
    
    
    if ( $PaymentMode == "transaction" )
    {
        if ( $Amount <= $maxAmount )
        {
            $continueTransaction = true;
            // includes the payment transaction if the amount is verified.
            if( isSet( $TransactionOK ) )
            {
                if ( is_file( "ezpaynet/user/transaction.php" ) )
                {
                    include( "ezpaynet/user/transaction.php" );
                }
                Header( "Location: /trade/orderedit/$OrderID/" );
                exit();
            }
        }
        if ( $continueTransaction == false )
        {
            Header( "Location: /trade/orderedit/$OrderID/" );
            exit();
        }
        $t->set_var( "refund_amount", "" );
        $t->parse( "new_transaction", "new_transaction_tpl" );
    }
    else if ( $PaymentMode == "refund" )
    {
        $rest = $total["inctax"] - $totalPaidAmount - $Amount;
        if ( $rest >= 0 )
        {
            $currency->setValue( $totalPaidAmount );
            $t->set_var( "paid_amount", $locale->format( $currency ) );

            if ( isSet( $TransactionOK ) )
            {
                if ( is_file( "ezpaynet/user/refund.php" ) )
                {
                    include( "ezpaynet/user/refund.php" );
                }
                
                Header( "Location: /trade/orderedit/$OrderID/" );
                exit();
            }

            $t->set_var( "new_transaction", "" );
            $t->parse( "refund_amount", "refund_amount_tpl" );
        }
    }
}
$t->pparse( "output", "transaction_tpl" );

?>
