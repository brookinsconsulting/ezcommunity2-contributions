<?php
//
// $Id: checkoutaddress.php,v 1.1.2.1 2002/06/07 09:41:20 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <11-Dec-2001 13:48:54 ce>
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
include_once( "eztrade/classes/ezcart.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "classes/ezhttptool.php" );

$session =& eZSession::globalSession();

$session->setVariable( "CurrentAddress", 1 );

if ( isSet ( $Next ) )
{
    $session->setVariable( "CurrentBillingAddressID", $BillingAddressID );
    $session->setVariable( "CurrentShippingAddressID", $ShippingAddressID );

    eZHTTPTool::header( "Location: /trade/checkout/shipping/" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language , "checkoutaddress.php" );

$t->setAllStrings();

$t->set_file( "checkout_address_page_tpl", "checkoutaddress.tpl" );

$t->set_block( "checkout_address_page_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "billing_address_tpl", "billing_option_tpl", "billing_option" );
$t->set_block( "checkout_address_page_tpl", "shipping_address_tpl", "shipping_address" );
$t->set_block( "shipping_address_tpl", "shipping_option_tpl", "shipping_option" );
$t->set_block( "checkout_address_page_tpl", "wish_user_tpl", "wish_user" );

// get the cart or create it
$cart =& eZCart::getBySession( $session );

$checkoutDisplayer = new eZCheckoutDisplayer( $t, $cart );

$checkoutDisplayer->path( "checkout_address_page_tpl" );

$checkoutDisplayer->displayAddresses();

$t->pparse( "output", "checkout_address_page_tpl" );

?>
