<?php
// 
// $Id: productview.php,v 1.5 2001/02/03 18:30:27 jb Exp $
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

    $quote = eZQuote::getUserQuote( $product_id, true );
    $rfq = eZQuote::getUserRFQ( $product_id, true );
    $offer = eZQuote::getUserOffer( $product_id, true );

    $user = eZUser::currentUser();

    $can_buy = false;
    $can_sell = false;

    if ( eZPermission::checkPermission( $user, "eZExchange", "BuyGoods" ) )
        $can_buy = true;
    if ( eZPermission::checkPermission( $user, "eZExchange", "SellGoods" ) )
        $can_sell = true;

    $t->set_var( "quote_item", "" );
    $t->set_var( "offer_item", "" );

    $t->set_var( "full_offer_item", "" );
    $t->parse( "empty_offer_item", "empty_offer_item_tpl" );
    $t->set_var( "real_quote_item", "" );
    // List RFQs
    foreach( $rfqs as $cur_rfq )
    {
        $t->set_var( "quote_all_type", "" );
        $t->set_var( "quote_any_type", "" );
        $t->set_var( "rfq_quote_item", "" );
        $t->set_var( "rfq_linked_quote_item", "" );

        $own_item = false;
        if ( get_class( $rfq ) == "ezquote" and $cur_rfq->id() == $rfq->id() )
            $own_item = true;

        if ( $own_item )
            $t->set_var( "quote_current", "class=\"bgdark\"" );
        else
            $t->set_var( "quote_current", "" );

        $t->set_var( "quote_quantity", $cur_rfq->quantity() );
        $date = $cur_rfq->expireDate();
        $t->set_var( "quote_expire_date", $locale->format( $date ) );
        $type = $cur_rfq->type();
        if ( $type == 0 )
            $t->parse( "quote_all_type", "quote_all_type_tpl", true );
        else
            $t->parse( "quote_any_type", "quote_any_type_tpl", true );

        if ( $can_sell && !$own_item )
        {
            $t->set_var( "rfq_id", $cur_rfq->id() );
            $t->parse( "rfq_linked_quote_item", "rfq_linked_quote_item_tpl" );
        }
        else
            $t->parse( "rfq_quote_item", "rfq_quote_item_tpl" );

        $t->parse( "quote_item", "quote_item_tpl", true );
        $t->parse( "offer_item", "offer_item_tpl", true );
    }
    $t->set_var( "rfq_quote_item", "" );
    $t->set_Var( "rfq_linked_quote_item", "" );
    $t->parse( "real_quote_item", "real_quote_item_tpl" );
    // List Quotes
    foreach( $quotes as $cur_quote )
    {
        $t->set_var( "quote_all_type", "" );
        $t->set_var( "quote_any_type", "" );

        if ( get_class( $quote ) == "ezquote" and $cur_quote->id() == $quote->id() )
            $t->set_var( "quote_current", "class=\"bgdark\"" );
        else
            $t->set_var( "quote_current", "" );

        $price = new eZCurrency( $cur_quote->price() );
        $t->set_var( "quote_price", $locale->format( $price ) );
        $t->set_var( "quote_quantity", $cur_quote->quantity() );
        $date = $cur_quote->expireDate();
        $t->set_var( "quote_expire_date", $locale->format( $date ) );
        $type = $cur_quote->type();
        if ( $type == 0 )
            $t->parse( "quote_all_type", "quote_all_type_tpl", true );
        else
            $t->parse( "quote_any_type", "quote_any_type_tpl", true );

        $t->parse( "quote_item", "quote_item_tpl", true );
    }
    $t->set_var( "empty_offer_item", "" );
    // List offers
    foreach( $offers as $cur_offer )
    {
        $t->set_var( "offer_all_type", "" );
        $t->set_var( "offer_any_type", "" );

        if ( get_class( $offer ) == "ezquote" and $cur_offer->id() == $offer->id() )
            $t->set_var( "offer_current", "class=\"bgdark\"" );
        else
            $t->set_var( "offer_current", "" );

        $price = new eZCurrency( $cur_offer->price() );
        $t->set_var( "offer_price", $locale->format( $price ) );
        $t->set_var( "offer_quantity", $cur_offer->quantity() );
        $date = $cur_offer->expireDate();
        $t->set_var( "offer_expire_date", $locale->format( $date ) );
        $type = $cur_offer->type();
        if ( $type == 0 )
            $t->parse( "offer_all_type", "offer_all_type_tpl", true );
        else
            $t->parse( "offer_any_type", "offer_any_type_tpl", true );

        $t->parse( "full_offer_item", "full_offer_item_tpl" );
        $t->parse( "offer_item", "offer_item_tpl", true );
    }

    $t->set_var( "do_quote_item", "" );
    $t->set_var( "no_do_quote_item", "" );
    $t->set_var( "do_offer_item", "" );
    $t->set_var( "no_do_offer_item", "" );

    $t->set_var( "do_edit_quote_item", "" );
    $t->set_var( "do_new_quote_item", "" );
    $t->set_var( "do_edit_offer_item", "" );
    $t->set_var( "do_new_offer_item", "" );
    if ( $can_buy )
    {
        if ( get_class( $quote ) == "ezquote" || get_class( $rfq ) == "ezquote" )
            $t->parse( "do_edit_quote_item", "do_edit_quote_item_tpl" );
        else
            $t->parse( "do_new_quote_item", "do_new_quote_item_tpl" );
        $t->parse( "do_quote_item", "do_quote_item_tpl" );
    }
    else
    {
        $t->parse( "no_do_quote_item", "no_do_quote_item_tpl" );
    }

    if ( $can_sell )
    {
        if ( get_class( $offer ) == "ezquote" )
            $t->parse( "do_edit_offer_item", "do_edit_offer_item_tpl" );
        else
            $t->parse( "do_new_offer_item", "do_new_offer_item_tpl" );
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
                      array( "quote_item_tpl", "real_quote_item_tpl", "real_quote_item" ),
                      array( "quote_item_tpl", "rfq_quote_item_tpl", "rfq_quote_item" ),
                      array( "quote_item_tpl", "rfq_linked_quote_item_tpl", "rfq_linked_quote_item" ),
                      array( "extra_productview_tpl", "offer_item_tpl", "offer_item" ),
                      array( "offer_item_tpl", "full_offer_item_tpl", "full_offer_item" ),
                      array( "offer_item_tpl", "empty_offer_item_tpl", "empty_offer_item" ),

                      array( "quote_item_tpl", "quote_all_type_tpl", "quote_all_type" ),
                      array( "quote_item_tpl", "quote_any_type_tpl", "quote_any_type" ),
                      array( "full_offer_item_tpl", "offer_all_type_tpl", "offer_all_type" ),
                      array( "full_offer_item_tpl", "offer_any_type_tpl", "offer_any_type" ),

                      array( "extra_productview_tpl", "do_quote_item_tpl", "do_quote_item" ),
                      array( "extra_productview_tpl", "no_do_quote_item_tpl", "no_do_quote_item" ),
                      array( "extra_productview_tpl", "do_offer_item_tpl", "do_offer_item" ),
                      array( "extra_productview_tpl", "no_do_offer_item_tpl", "no_do_offer_item" ),
                      array( "do_quote_item_tpl", "do_edit_quote_item_tpl", "do_edit_quote_item" ),
                      array( "do_quote_item_tpl", "do_new_quote_item_tpl", "do_new_quote_item" ),
                      array( "do_offer_item_tpl", "do_edit_offer_item_tpl", "do_edit_offer_item" ),
                      array( "do_offer_item_tpl", "do_new_offer_item_tpl", "do_new_offer_item" ),
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
