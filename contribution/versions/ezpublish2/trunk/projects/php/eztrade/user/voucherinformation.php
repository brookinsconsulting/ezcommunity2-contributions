<?php
// 
// $Id: voucherinformation.php,v 1.2 2001/08/24 07:21:08 ce Exp $
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

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezvouchersmail.php" );
include_once( "eztrade/classes/ezvoucheremail.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "ezsession/classes/ezsession.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "voucherinformation.php" );

$t->setAllStrings();

$t->set_file( "voucher_tpl", "voucherinformation.tpl" );

$t->set_block( "voucher_tpl", "email_tpl", "email" );
$t->set_block( "voucher_tpl", "smail_tpl", "smail" );
$t->set_block( "voucher_tpl", "next_tpl", "next" );
$t->set_block( "voucher_tpl", "ok_tpl", "ok" );

$t->set_var( "email_var", "" );
$t->set_var( "smail_var", "" );
$t->set_var( "email_text", "" );
$t->set_var( "smail_text", "" );
$t->set_var( "next", "" );
$t->set_var( "name_value", "" );
$t->set_var( "street1_value", "" );
$t->set_var( "street2_value", "" );
$t->set_var( "zip_value", "" );
$t->set_var( "place_value", "" );
$t->set_var( "country_name", "" );


$voucherIDArray = $session->arrayValue( "VoucherID" );
$voucherMail = $session->arrayValue( "VoucherMail" );

$preOrderID = $session->variable( "PreOrderID" );

if ( ( isSet ( $Next ) || isSet ( $OK ) ) && ( is_numeric( $preOrderID ) ) )
{
    $product = new eZProduct( $ProductID );
    
    $voucher = new eZVoucher();
    $voucher->generateKey();
    $voucher->setPrice( $product->price() );
    $voucher->setAvailable( false );
    $voucher->store();
    
    if ( $MailType == 1 )
    {
        $voucherInfo = new eZVoucherEMail();
        $voucherInfo->setEmail( $Email );
        
    }
    else if ( $MailType == 2 )
    {
        $voucherInfo = new eZVoucherSMail();
        $address = new eZAddress();
        $address->setName( $Name );
        $address->setStreet1( $Street1 );
        $address->setStreet2( $Street2 );
        $address->setZip( $Zip );
        $address->setPlace( $Place );
        $address->store();

        $voucherInfo->setAddress( $address );
    }
    $voucherInfo->setPreOrder( $preOrderID );
    $voucherInfo->setDescription( $Description );
    $voucherInfo->setVoucher( $voucher );
    
    $voucherInfo->store();

    if ( isSet ( $OK ) )
    {
        eZHTTPTool::header( "Location: /trade/payment/" );
        exit();
    }
}

$voucherID = $voucherIDArray[$Key];
$mailID = $voucherMail[$Key];

if ( is_numeric( $voucherID ) )
{
    $product = new eZProduct( $voucherID );
    $t->set_var( "product_name", $product->name() );

    if ( $mailID == 1 )
    {
        $t->set_var( "smail", "" );
        $t->set_var( "mail_type", "1" );
        $t->parse( "email", "email_tpl" );
    }
    else if ( $mailID == 2 )
    {
        $t->set_var( "mail_type", "2" );
        $t->set_var( "email", "" );
        $t->parse( "smail", "smail_tpl" );
    }
}

if ( is_numeric( $voucherIDArray[$Key+1] ) )
{
    $t->set_var( "url_arg", $Key+1 );
    $t->set_var( "ok", "" );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
    $t->parse( "ok", "ok_tpl" );
}

$t->set_var( "product_id", $voucherID );

$t->pparse( "output", "voucher_tpl" );

?>
