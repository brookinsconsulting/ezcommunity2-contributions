<?php
// 
// $Id: voucherinformation.php,v 1.12.4.1 2001/10/22 11:52:22 ce Exp $
//
// Created on: <06-Aug-2001 13:02:18 ce>
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
$locale = new eZLocale( $Language );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezvoucherinformation.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductpricerange.php" );
include_once( "ezsession/classes/ezsession.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "voucherinformation.php" );

$t->setAllStrings();

$t->set_file( "voucher_tpl", "voucherinformation.tpl" );

$t->set_block( "voucher_tpl", "email_tpl", "email" );
$t->set_block( "voucher_tpl", "smail_tpl", "smail" );

$t->set_block( "voucher_tpl", "price_to_high_tpl", "price_to_high" );
$t->set_block( "voucher_tpl", "price_to_low_tpl", "price_to_low" );


setType( $PriceRange, "integer" );

$t->set_var( "to_email", "" );
$t->set_var( "to_name", "" );
$t->set_var( "from_name", "" );
$t->set_var( "smail_var", "" );
$t->set_var( "description", "" );
$t->set_var( "next", "" );
$t->set_var( "name_value", "" );
$t->set_var( "street1_value", "" );
$t->set_var( "street2_value", "" );
$t->set_var( "zip_value", "" );
$t->set_var( "place_value", "" );
$t->set_var( "country_name", "" );
$t->set_var( "smail", "" );
$t->set_var( "email", "" );
$t->set_var( "from_email", "" );
$t->set_var( "price_to_low", "" );
$t->set_var( "price_to_high", "" );
$error = false;

$product = new eZProduct( $ProductID );

// Check for wrong price range
if ( isSet ( $OK ) )
{
    $range = $product->priceRange();
    if ( ( $range->min() != 0 ) && ( $range->min() > $PriceRange ) )
    {
        $error = true;
        $t->parse( "price_to_low", "price_to_low_tpl" );
    }
    if ( ( $range->max() != 0 ) && ( $range->max() < $PriceRange ) )
    {
        $error = true;
        $t->parse( "price_to_high", "price_to_high_tpl" );
    }
}

$user =& eZUser::currentUser();

// Insert the voucher information
if ( ( $product ) and ( isSet( $OK ) and $error == false ) )
{
    $voucherInfo = new eZVoucherInformation( $VoucherInformationID );
    
            
    if ( $Mail == 1 )
    {
        $online = new eZOnline();
        $online->setUrl( $Email );
        $online->store();
        $voucherInfo->setToOnline( $online );
    }
    else if ( $Mail == 2 )
    {
        $toAddress = new eZAddress();
        $toAddress->setName( $ToName );
        $toAddress->setStreet1( $ToStreet1 );
        $toAddress->setStreet2( $ToStreet2 );
        $toAddress->setZip( $ToZip );
        $toAddress->setPlace( $ToPlace );
        $toAddress->store();
        $voucherInfo->setToAddress( $toAddress );

        $fromAddress = new eZAddress();
        $fromAddress->setName( $FromName );
        $fromAddress->setStreet1( $FromStreet1 );
        $fromAddress->setStreet2( $FromStreet2 );
        $fromAddress->setZip( $FromZip );
        $fromAddress->setPlace( $FromPlace );
        $fromAddress->sfromre();
        $voucherInfo->setFromAddress( $fromAddress );
    }

    $online = new eZOnline();
    $online->setUrl( $FromEmail );
    $online->store();
    $voucherInfo->setFromOnline( $online );
    $voucherInfo->setMailMethod( $Mail );
    $voucherInfo->setFromName( $FromName );
    $voucherInfo->setFromName( $FromName );
    $voucherInfo->setToName( $ToName );
    $voucherInfo->setProduct( $product );
    
    $voucherInfo->setDescription( $Description );

    if ( $PriceRange == 0 )
    {
        $priceRange =& $product->priceRange();
        $voucherInfo->setPrice( $priceRange->min() );
    }
    else
        $voucherInfo->setPrice( $PriceRange );

    $voucherInfo->store();

    $id = $voucherInfo->id();

    $session->setVariable( "VoucherInformationID", $id );

    if ( isSet ( $OK ) && $id )
    {
        if ( $VoucherInformationID != "" )
            eZHTTPTool::header( "Location: /trade/cart/" );
        else
            eZHTTPTool::header( "Location: /trade/cart/add/$ProductID/" );
        exit();
    }
}
else if ( $product ) // Print out the addresses forms
{
    $range = $product->priceRange();

    $currency = new eZCurrency();
    $currency->setValue( $range->min() );
    $t->set_var( "min_price", $locale->format( $currency ) );
    $currency->setValue( $range->max() );
    $t->set_var( "max_price", $locale->format( $currency ) );
    $error = false;

    $t->set_var( "mail_method", $MailMethod );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_id", $product->id() );

    if ( $VoucherInformationID != 0 )
    {
        $info = new eZVoucherInformation( $VoucherInformationID );
        $t->set_var( "description", $info->description() );

        if ( $info->mailMethod() == 1 )
        {
            $from =& $info->fromOnline();
            $to =& $info->toOnline();
            $t->set_var( "voucher_info_id", $info->id() );
            $t->set_var( "from_email", $from->url() );
            $t->set_var( "to_email", $to->url() );
            $t->set_var( "to_name", $info->toName() );
            $t->set_var( "from_name", $info->fromName() );
            $t->set_var( "price_range", $info->price() );
        }
    }
    else
    {
        $t->set_var( "price_range", "" );
    }

    if ( $MailMethod == 1 )
    {
        $t->set_var( "smail", "" );
        $t->parse( "email", "email_tpl" );
    }
    else if ( $MailMethod == 2 )
    {
        $t->set_var( "email", "" );
        $t->parse( "smail", "smail_tpl" );
    }

}

$t->pparse( "output", "voucher_tpl" );
?>
