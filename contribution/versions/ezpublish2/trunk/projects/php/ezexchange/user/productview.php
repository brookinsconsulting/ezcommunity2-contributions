<?php
// 
// $Id: productview.php,v 1.4 2001/02/02 21:13:46 gl Exp $
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

    $quotes = eZQuote::getAllQuotes( $product_id, true );
    $rfqs = eZQuote::getAllRFQs( $product_id, true );
    $offers = eZQuote::getAllOffers( $product_id, true );

    $t->set_block( "quote_item_tpl", "quote_all_type_tpl", "quote_all_type" );
    $t->set_block( "quote_item_tpl", "quote_any_type_tpl", "quote_any_type" );
    $t->set_block( "offer_item_tpl", "offer_all_type_tpl", "offer_all_type" );
    $t->set_block( "offer_item_tpl", "offer_any_type_tpl", "offer_any_type" );

    $t->set_var( "quote_item", "" );
    $t->set_var( "offer_item", "" );

    foreach( $rfqs as $rfq )
    {
        // RFQ'er skal listes over quotes
    }
    foreach( $quotes as $quote )
    {
        $t->set_var( "quote_all_type", "" );
        $t->set_var( "quote_any_type", "" );

        $price = new eZCurrency( $quote->price() );
        $t->set_var( "quote_price", $locale->format( $price ) );
        $t->set_var( "quote_quantity", $quote->quantity() );
        $date = $quote->expireDate();
        $t->set_var( "quote_expire_date", $locale->format( $date ) );
        $type = $quote->type();
        if ( $type == 0 )
            $t->parse( "quote_all_type", "quote_all_type_tpl", true );
        else
            $t->parse( "quote_any_type", "quote_any_type_tpl", true );

        $t->parse( "quote_item", "quote_item_tpl", true );
    }
    foreach( $offers as $offer )
    {
        $t->set_var( "offer_all_type", "" );
        $t->set_var( "offer_any_type", "" );

        $price = new eZCurrency( $offer->price() );
        $t->set_var( "offer_price", $locale->format( $price ) );
        $t->set_var( "offer_quantity", $offer->quantity() );
        $date = $offer->expireDate();
        $t->set_var( "offer_expire_date", $locale->format( $date ) );
        $type = $offer->type();
        if ( $type == 0 )
            $t->parse( "offer_all_type", "offer_all_type_tpl", true );
        else
            $t->parse( "offer_any_type", "offer_any_type_tpl", true );

        $t->parse( "offer_item", "offer_item_tpl", true );
    }



    $t->set_block( "your_quote_item_tpl", "your_quote_item_content_tpl", "your_quote_item_content" );
    $t->set_block( "your_quote_item_content_tpl", "your_quote_all_type_tpl", "your_quote_all_type" );
    $t->set_block( "your_quote_item_content_tpl", "your_quote_any_type_tpl", "your_quote_any_type" );

    $t->set_block( "your_offer_item_tpl", "your_offer_item_content_tpl", "your_offer_item_content" );
    $t->set_block( "your_offer_item_content_tpl", "your_offer_all_type_tpl", "your_offer_all_type" );
    $t->set_block( "your_offer_item_content_tpl", "your_offer_any_type_tpl", "your_offer_any_type" );

    $t->set_var( "your_quote_item", "" );
    $t->set_var( "no_quote_item", "" );
    $t->set_var( "your_offer_item", "" );
    $t->set_var( "no_offer_item", "" );

    $t->set_var( "your_quote_all_type", "" );
    $t->set_var( "your_quote_any_type", "" );
    $t->set_var( "your_offer_all_type", "" );
    $t->set_var( "your_offer_any_type", "" );

    $quote = eZQuote::getUserQuote( $product_id, true );
    $rfq = eZQuote::getUserRFQ( $product_id, true );
    $offer = eZQuote::getUserOffer( $product_id, true );

    if ( get_class( $quote ) == "ezquote" )
    {
        $price = new eZCurrency( $quote->price() );
        $t->set_var( "quote_price", $locale->format( $price ) );
        $t->set_var( "quote_quantity", $quote->quantity() );
        $date = $quote->expireDate();
        $t->set_var( "quote_expire_date", $locale->format( $date ) );
        $type = $quote->type();
        if ( $type == 0 )
            $t->parse( "your_quote_all_type", "your_quote_all_type_tpl", true );
        else
            $t->parse( "your_quote_any_type", "your_quote_any_type_tpl", true );

        $t->parse( "your_quote_item_content", "your_quote_item_content_tpl" );
        $t->parse( "your_quote_item", "your_quote_item_tpl" );
    }
    else if ( get_class( $rfq ) == "ezquote" )
    {
        $t->set_var( "quote_price", "RFQ" );
        $t->set_var( "quote_quantity", $rfq->quantity() );
        $date = $rfq->expireDate();
        $t->set_var( "quote_expire_date", $locale->format( $date ) );
        $type = $rfq->type();
        if ( $type == 0 )
            $t->parse( "your_quote_all_type", "your_quote_all_type_tpl", true );
        else
            $t->parse( "your_quote_any_type", "your_quote_any_type_tpl", true );

        $t->parse( "your_quote_item_content", "your_quote_item_content_tpl" );
        $t->parse( "your_quote_item", "your_quote_item_tpl" );
    }
    else
    {
        $t->parse( "no_quote_item", "no_quote_item_tpl" );
    }

    if ( get_class( $offer ) == "ezquote" )
    {
        $price = new eZCurrency( $offer->price() );
        $t->set_var( "offer_price", $locale->format( $price ) );
        $t->set_var( "offer_quantity", $offer->quantity() );
        $date = $offer->expireDate();
        $t->set_var( "offer_expire_date", $locale->format( $date ) );
        $type = $offer->type();
        if ( $type == 0 )
            $t->parse( "your_offer_all_type", "your_offer_all_type_tpl", true );
        else
            $t->parse( "your_offer_any_type", "your_offer_any_type_tpl", true );

        $t->parse( "your_offer_item_content", "your_offer_item_content_tpl" );
        $t->parse( "your_offer_item", "your_offer_item_tpl" );
    }
    else
    {
        $t->parse( "no_offer_item", "no_offer_item_tpl" );
    }

    $t->set_var( "do_quote_item", "" );
    $t->set_var( "no_do_quote_item", "" );
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
        $t->parse( "do_quote_item", "do_quote_item_tpl" );
    else
        $t->parse( "no_do_quote_item", "no_do_quote_item_tpl" );

    if ( $can_sell )
        $t->parse( "do_offer_item", "do_offer_item_tpl" );
    else
        $t->parse( "no_do_offer_item", "no_do_offer_item_tpl" );
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
                      array( "extra_productview_tpl", "offer_item_tpl", "offer_item" ),

                      array( "extra_productview_tpl", "your_quote_item_tpl", "your_quote_item" ),
                      array( "extra_productview_tpl", "no_quote_item_tpl", "no_quote_item" ),
                      array( "extra_productview_tpl", "your_offer_item_tpl", "your_offer_item" ),
                      array( "extra_productview_tpl", "no_offer_item_tpl", "no_offer_item" ),

                      array( "extra_productview_tpl", "do_quote_item_tpl", "do_quote_item" ),
                      array( "extra_productview_tpl", "no_do_quote_item_tpl", "no_do_quote_item" ),
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
