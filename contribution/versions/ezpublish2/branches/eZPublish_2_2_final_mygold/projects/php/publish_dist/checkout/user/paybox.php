<?php
// 
// $Id: paybox.php,v 1.1.2.1 2001/11/22 09:52:40 ce Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <23-Apr-2001 13:46:34 amos>
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
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezdatetime.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$CountryCodes = $ini->read_array( "PayboxMain", "CountryCodes" );
$Currency = $ini->read_var( "PayboxMain", "Currency" );


$errors = array();
$error = false;
if ( $Action == "Verify" )
{
    if ( !is_numeric( $MobileNumber ) or $MobileNumber == "" )
        $errors[] = "error_mobile_number";
    if ( $CountryCode[0] != "+" or !is_numeric( substr( $CountryCode, 1 ) ) or $CountryCode == "" )
        $errors[] = "error_country_code";
    include_once( "checkout/classes/ezpayboxmessage.php" );

    if ( count( $errors ) == 0 )
    {
        include_once( "checkout/classes/ezpayboxmessage.php" );

        $Host = $ini->read_var( "PayboxMain", "LHLHost" );
        $Port = $ini->read_var( "PayboxMain", "LHLPort" );
        $Lang = $ini->read_var( "PayboxMain", "Language" );
        $Auth = $ini->read_var( "PayboxMain", "AuthenticationType" );
        $ShopNumber = $ini->read_var( "PayboxMain", "ShopNumber" );
        $PaymentDays = $ini->read_var( "PayboxMain", "PaymentDays" );

        $message = new eZPayboxMessage( $Host, $Port );

        $message->setLanguage( $Lang );
        $message->setAuthenticationType( $Auth );
//      $message->setPayerNumber( "+491773729269" );
        $message->setPayerNumber( $CountryCode . $MobileNumber );
        $message->setPayeeNumber( $ShopNumber );
        $message->setAmount( $Auth == "test" ? 1 : $ChargeTotal );
        $message->setCurrency( $Currency );
        $message->setPaymentDays( $PaymentDays );
        $message->setPreOrderID( $PreOrderID );

        $message->transfer();
        if ( $message->receive() )
        {
            $message->sendAcknowledge();
            $PaymentSuccess = "true";
        }
        else
        {
            $session->setVariable( "PaymentTry", $tryNr + 1 );
            $error_text = $message->errorText();
            $error_code = $message->errorCode();
            $error = true;
            $PaymentSuccess = "false";
//          print( "Error #$message->ErrorCode, $message->LongErrorText\n" );
        }

        $message->closeSocket();

        // Write to log file
        $logFile = "checkout/log/checkout.log";
        $header = "";
        if ( !file_exists( $logFile ) )
            $header = "# TYPE PREORDER_ID TA_ID DATE TIME AMOUNT RC_CODE RC_TEXT\n";
        $log = fopen( $logFile, "a" );
        if ( $header != "" )
            fwrite( $log, $header );
        fwrite( $log, "PAYBOX\t$PreOrderID\t".$message->transactionNumber()."\t".$message->date()."\t".$message->time()."\t".$message->amount()."\t".$message->errorCode()."\t".$message->errorText()."\n" );
    }
}

$t = new eZTemplate( "checkout/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "checkout/user/intl/", $Language, "paybox.php" );

$t->set_file( "paybox_tpl", "paybox.tpl" );

$t->set_block( "paybox_tpl", "error_tpl", "error" );
$t->set_block( "error_tpl", "error_transfer_tpl", "error_transfer" );
$t->set_block( "error_tpl", "error_country_code_tpl", "error_country_code" );
$t->set_block( "error_tpl", "error_mobile_number_tpl", "error_mobile_number" );

$t->set_block( "paybox_tpl", "country_code_tpl", "country_code" );

$t->set_var( "error_transfer", "" );
$t->set_var( "error_mobile_number", "" );
$t->set_var( "error_country_code", "" );

$t->set_var( "error", "" );

if ( $error || count( $errors ) > 0 )
{
    if ( $error )
    {
        $t->set_var( "error_text", $error_text );
        $t->set_var( "error_code", $error_code );
        $t->parse( "error_transfer", "error_transfer_tpl" );
    }
    foreach( $errors as $error_item )
    {
        $t->parse( $error_item, $error_item . "_tpl" );
    }
    $t->parse( "error", "error_tpl" );
}

$t->setAllStrings();

$t->set_var( "order_id", $PreOrderID );
$t->set_var( "payment_type", $PaymentType );

$t->set_var( "country_code_selected", "" );
$t->set_var( "country_code", "" );
foreach( $CountryCodes as $code )
{
    $t->set_var( "country_code_selected", $code == $CountryCode ? "selected" : "" );
    $t->set_var( "country_code_number", $code );
    $t->set_var( "country_code_text", $code );
    $t->parse( "country_code", "country_code_tpl", true );
}

$t->set_var( "mobile_number", $MobileNumber );

$currency = new eZCurrency( $ChargeTotal );
$locale = new eZLocale( $Language );
$t->set_var( "amount", $locale->format( $currency ) );

$t->pparse( "output", "paybox_tpl" );

?>
