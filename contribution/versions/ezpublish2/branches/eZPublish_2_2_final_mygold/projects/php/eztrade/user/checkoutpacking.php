<?php
//
// $Id: checkoutpacking.php,v 1.1.2.1 2002/06/07 09:41:20 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <11-Dec-2001 14:41:49 ce>
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

if ( isSet ( $Next ) )
{
    $session->setVariable( "CurrentPackingID", $PackingTypeID );

    eZHTTPTool::header( "Location: /trade/checkout/paymentmethod/" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language , "checkoutpacking.php" );

$t->setAllStrings();

$t->set_file( "checkout_packing_page_tpl", "checkoutpacking.tpl" );

$t->set_block( "checkout_packing_page_tpl", "packing_list_tpl", "packing_list" );
$t->set_block( "packing_list_tpl", "packing_item_tpl", "packing_item" );
$t->set_block( "packing_item_tpl", "image_tpl", "image" );
$t->set_block( "packing_item_tpl", "no_image_tpl", "no_image" );


// get the cart or create it
$cart =& eZCart::getBySession( $session );

$checkoutDisplayer = new eZCheckoutDisplayer( $t, $cart );

$checkoutDisplayer->displayPacking();

$checkoutDisplayer->path( "checkout_packing_page_tpl" );
$t->pparse( "output", "checkout_packing_page_tpl" );


?>
