<?php
// 
// $Id: productview.php,v 1.2 2001/01/31 18:58:08 gl Exp $
//
// Definition of productview class
//
// Jan Borsodi <jb@ez.no>
// Created on: <29-Jan-2001 14:39:50 amos>
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

include_once( "ezexchange/classes/ezquote.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

function listQuotes( &$t, &$product_id )
{
    $locale = new eZLocale( $Language );

    $t->set_var( "quote_item", "" );
    $t->set_var( "rfq_item", "" );
    $t->set_var( "offer_item", "" );
    $quotes = eZQuote::getAllQuotes( $product_id, true );
    $rfqs = eZQuote::getAllRFQs( $product_id, true );
    $offers = eZQuote::getAllOffers( $product_id, true );
    foreach( $quotes as $quote )
    {
        $t->set_var( "quote_customers", eZQuote::getTotalUsers( $product_id, $quote->price() ) );
        $t->set_var( "quote_quantity", eZQuote::getTotalQuantity( $product_id, $quote->price() ) );
        $price = new eZCurrency( $quote->price() );
        $t->set_var( "quote_price", $locale->format( $price ) );
        $t->parse( "quote_item", "quote_item_tpl", true );
    }
    foreach( $rfqs as $rfq )
    {
        $t->set_var( "rfq_customers", eZQuote::getTotalUsers( $product_id, $rfq->price() ) );
        $t->set_var( "rfq_quantity", eZQuote::getTotalQuantity( $product_id, $rfq->price() ) );
        $price = new eZCurrency( $rfq->price() );
        $t->set_var( "rfq_price", $locale->format( $price ) );
        $t->parse( "rfq_item", "rfq_item_tpl", true );
    }
    foreach( $offers as $offer )
    {
        $t->set_var( "offer_suppliers", eZQuote::getTotalUsers( $product_id, $offer->price() ) );
        // fix: missing quantity
        $offer_quantity = eZQuote::getTotalQuantity( $product_id, $offer->price() );
        if ( $offer_quantity == "" )
            $offer_quantity = "&nbsp;";
        $t->set_var( "offer_quantity", $offer_quantity );
        $price = new eZCurrency( $offer->price() );
        $t->set_var( "offer_price", $locale->format( $price ) );
        $t->parse( "offer_item", "offer_item_tpl", true );
    }

    $t->set_var( "your_quote_item", "" );
    $t->set_var( "no_quote_item", "" );
    $t->set_var( "your_quote_info_item", "" );
    $t->set_var( "no_quote_info_item", "" );

    $t->set_var( "your_rfq_item", "" );
    $t->set_var( "no_rfq_item", "" );
    $t->set_var( "your_rfq_info_item", "" );
    $t->set_var( "no_rfq_info_item", "" );

    $t->set_var( "your_offer_item", "" );
    $t->set_var( "no_offer_item", "" );
    $t->set_var( "your_offer_info_item", "" );
    $t->set_var( "no_offer_info_item", "" );

    $quote = eZQuote::getUserQuote( $product_id, true );
    $rfq = eZQuote::getUserRFQ( $product_id, true );
    $offer = eZQuote::getUserOffer( $product_id, true );

    if ( get_class( $quote ) == "ezquote" )
    {
        $t->set_var( "your_quote_quantity", $quote->quantity() );
        $price = new eZCurrency( $quote->price() );
        $t->set_var( "your_quote_price", $locale->format( $price ) );
        $t->parse( "your_quote_item", "your_quote_item_tpl" );
        $t->parse( "your_quote_info_item", "your_quote_info_item_tpl" );
    }
    else
    {
        $t->parse( "no_quote_item", "no_quote_item_tpl" );
        $t->parse( "no_quote_info_item", "no_quote_info_item_tpl" );
    }

    if ( get_class( $rfq ) == "ezquote" )
    {
        $t->set_var( "your_rfq_quantity", $rfq->quantity() );
        $price = new eZCurrency( $rfq->price() );
        $t->set_var( "your_rfq_price", $locale->format( $price ) );
        $t->parse( "your_rfq_item", "your_rfq_item_tpl" );
        $t->parse( "your_rfq_info_item", "your_rfq_info_item_tpl" );
    }
    else
    {
        $t->parse( "no_rfq_item", "no_rfq_item_tpl" );
        $t->parse( "no_rfq_info_item", "no_rfq_info_item_tpl" );
    }

    if( get_class( $offer ) == "ezquote" )
    {
        $t->set_var( "your_offer_quantity", $offer->quantity() );
        $price = new eZCurrency( $offer->price() );
        $t->set_var( "your_offer_price", $locale->format( $price ) );
        $t->parse( "your_offer_item", "your_offer_item_tpl" );
        $t->parse( "your_offer_info_item", "your_offer_info_item_tpl" );
    }
    else
    {
        $t->parse( "no_offer_item", "no_offer_item_tpl" );
        $t->parse( "no_offer_info_item", "no_offer_info_item_tpl" );
    }

    $t->set_var( "do_quote_item", "" );
    $t->set_var( "no_do_quote_item", "" );
    $t->set_var( "do_rfq_item", "" );
    $t->set_var( "no_do_rfq_item", "" );
    $t->set_var( "do_offer_item", "" );
    $t->set_var( "no_do_offer_item", "" );

    $user = eZUser::currentUser();

    $can_buy = false;
    $can_sell = false;

    if ( eZPermission::checkPermission( $user, "eZExchange", "BuyGoods" ) )
        $can_buy = true;
    if ( eZPermission::checkPermission( $user, "eZExchange", "SellGoods" ) )
        $can_sell = true;

    if ( $can_buy )
    {
        $t->parse( "do_quote_item", "do_quote_item_tpl" );
        $t->parse( "do_rfq_item", "do_rfq_item_tpl" );
    }
    else
    {
        $t->parse( "no_do_quote_item", "no_do_quote_item_tpl" );
        $t->parse( "no_do_rfq_item", "no_do_rfq_item_tpl" );
    }

    if ( $can_sell )
    {
        $t->parse( "do_offer_item", "do_offer_item_tpl" );
    }
    else
    {
        $t->parse( "no_do_offer_item", "no_do_offer_item_tpl" );
    }
}

$ini =& $GLOBALS["GlobalSiteIni"];

$IntlDir = array( "eztrade/user/intl",
                  "ezexchange/user/intl" );
$IniFile = array( "productview.php",
                  "productview.php" );
$template_array = array( "extra_productview_tpl" =>
                         array( "ezexchange/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                                "productview.tpl" ) );
$variable_array = array( "extra_product_info" => "extra_productview_tpl" );
$block_array = array( array( "extra_productview_tpl", "quote_item_tpl", "quote_item" ),
                      array( "extra_productview_tpl", "rfq_item_tpl", "rfq_item" ),
                      array( "extra_productview_tpl", "offer_item_tpl", "offer_item" ),

                      array( "extra_productview_tpl", "your_quote_item_tpl", "your_quote_item" ),
                      array( "extra_productview_tpl", "no_quote_item_tpl", "no_quote_item" ),
                      array( "extra_productview_tpl", "your_quote_info_item_tpl", "your_quote_info_item" ),
                      array( "extra_productview_tpl", "no_quote_info_item_tpl", "no_quote_info_item" ),

                      array( "extra_productview_tpl", "your_rfq_item_tpl", "your_rfq_item" ),
                      array( "extra_productview_tpl", "no_rfq_item_tpl", "no_rfq_item" ),
                      array( "extra_productview_tpl", "your_rfq_info_item_tpl", "your_rfq_info_item" ),
                      array( "extra_productview_tpl", "no_rfq_info_item_tpl", "no_rfq_info_item" ),

                      array( "extra_productview_tpl", "your_offer_item_tpl", "your_offer_item" ),
                      array( "extra_productview_tpl", "no_offer_item_tpl", "no_offer_item" ),
                      array( "extra_productview_tpl", "your_offer_info_item_tpl", "your_offer_info_item" ),
                      array( "extra_productview_tpl", "no_offer_info_item_tpl", "no_offer_info_item" ),

                      array( "extra_productview_tpl", "do_quote_item_tpl", "do_quote_item" ),
                      array( "extra_productview_tpl", "no_do_quote_item_tpl", "no_do_quote_item" ),
                      array( "extra_productview_tpl", "do_rfq_item_tpl", "do_rfq_item" ),
                      array( "extra_productview_tpl", "no_do_rfq_item_tpl", "no_do_rfq_item" ),
                      array( "extra_productview_tpl", "do_offer_item_tpl", "do_offer_item" ),
                      array( "extra_productview_tpl", "no_do_offer_item_tpl", "no_do_offer_item" )
                      );
$func_array = array( "listQuotes" );

if ( $PageCaching == "enabled" )
{
    if ( !isset( $cachedFile ) or empty( $cachedFile ) )
        $cachedFile = "ezexchange/cache/productview," .$ProductID . "," . $CategoryID .".cache";
    if ( file_exists( $cachedFile ) )
    {
        include( $cachedFile );
    }
    else
    {
        $GenerateStaticPage = "true";
        $NoOutput = true;
        include( "eztrade/user/productview.php" );
    }
}
else
{
    $NoOutput = true;
    include( "eztrade/user/productview.php" );
}

?>
