<?php
// 
// $Id: quoteedit.php,v 1.10 2001/02/05 17:39:17 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <30-Jan-2001 14:54:24 amos>
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
include_once( "classes/ezdate.php" );
include_once( "classes/ezmail.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "ezexchange/classes/ezquote.php" );

function sendMails( &$ini, &$locale, &$quote, &$offer, $quote_quantity, $offer_quantity,
                    &$product_name, &$customer, &$supplier, &$Language )
{
    $admin = $ini->read_var( "eZExchangeMain", "SiteAdmin" );
    $admin_mail = $ini->read_var( "eZExchangeMain", "SiteAdminMail" );

    $mail_c_t = new eZTemplate( "ezexchange/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                                "ezexchange/user/intl", $Language, "mail" );
    $mail_c_t->setAllStrings();

    $mail_c_t->set_file( "customer_mail", "customermail.tpl" );
    $mail_c_t->set_block( "customer_mail", "subject_tpl", "subject" );
    $mail_c_t->set_block( "customer_mail", "body_tpl", "body" );
    $mail_c_t->set_block( "body_tpl", "body_type_all_tpl", "body_type_all" );
    $mail_c_t->set_block( "body_tpl", "body_type_any_tpl", "body_type_any" );

    $mail_c_t->set_var( "body_type_all", "" );
    $mail_c_t->set_var( "body_type_any", "" );

    $mail_c_t->set_var( "product_name", $product_name );
    $mail_c_t->set_var( "quantity", $quote->quantity() );
    $mail_c_t->set_var( "used_quantity", $quote_quantity );
    $currency = new eZCurrency( $quote->price() );
    $mail_c_t->set_var( "price", $locale->format( $currency ) );
    if ( $quote->type() == QUOTE_ALL_TYPE )
        $mail_c_t->parse( "body_type_all", "body_type_all_tpl" );
    else
        $mail_c_t->parse( "body_type_any", "body_type_any_tpl" );

    $customer_mail = new eZMail();
    $customer_mail->setFromName( $admin );
    $customer_mail->setFrom( $admin_mail );

    $mail_c_t->parse( "subject", "subject_tpl" );
    $mail_c_t->parse( "body", "body_tpl" );

    $customer_mail->setSubject( $mail_c_t->get_var( "subject" ) );
    $customer_mail->setBody( $mail_c_t->get_var( "body" ) );

    $customer_mail->setTo( $customer->email() );
    $customer_mail->send();

    $mail_s_t = new eZTemplate( "ezexchange/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                                "ezexchange/user/intl", $Language, "mail" );
    $mail_s_t->setAllStrings();

    $mail_s_t->set_file( "supplier_mail", "suppliermail.tpl" );
    $mail_s_t->set_block( "supplier_mail", "subject_tpl", "subject" );
    $mail_s_t->set_block( "supplier_mail", "body_tpl", "body" );
    $mail_s_t->set_block( "body_tpl", "body_type_all_tpl", "body_type_all" );
    $mail_s_t->set_block( "body_tpl", "body_type_any_tpl", "body_type_any" );

    $mail_s_t->set_var( "body_type_all", "" );
    $mail_s_t->set_var( "body_type_any", "" );

    $mail_s_t->set_var( "product_name", $product_name );
    $mail_s_t->set_var( "quantity", $offer->quantity() );
    $mail_s_t->set_var( "used_quantity", $offer_quantity );
    $currency = new eZCurrency( $offer->price() );
    $mail_s_t->set_var( "price", $locale->format( $currency ) );
    if ( $offer->type() == QUOTE_ALL_TYPE )
        $mail_s_t->parse( "body_type_all", "body_type_all_tpl" );
    else
        $mail_s_t->parse( "body_type_any", "body_type_any_tpl" );

    $supplier_mail = new eZMail();
    $supplier_mail->setFromName( $admin );
    $supplier_mail->setFrom( $admin_mail );

    $mail_s_t->parse( "subject", "subject_tpl" );
    $mail_s_t->parse( "body", "body_tpl" );

    $supplier_mail->setSubject( $mail_s_t->get_var( "subject" ) );
    $supplier_mail->setBody( $mail_s_t->get_var( "body" ) );

    $supplier_mail->setTo( $supplier->email() );
    $supplier_mail->send();

    $mail_a_t = new eZTemplate( "ezexchange/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                                "ezexchange/user/intl", $Language, "mail" );
    $mail_a_t->setAllStrings();

    $mail_a_t->set_file( "siteadmin_mail", "siteadminmail.tpl" );
    $mail_a_t->set_block( "siteadmin_mail", "subject_tpl", "subject" );
    $mail_a_t->set_block( "siteadmin_mail", "body_tpl", "body" );
    $mail_a_t->set_block( "body_tpl", "quote_type_all_tpl", "quote_type_all" );
    $mail_a_t->set_block( "body_tpl", "quote_type_any_tpl", "quote_type_any" );
    $mail_a_t->set_block( "body_tpl", "offer_type_all_tpl", "offer_type_all" );
    $mail_a_t->set_block( "body_tpl", "offer_type_any_tpl", "offer_type_any" );

    $mail_a_t->set_var( "quote_type_all", "" );
    $mail_a_t->set_var( "quote_type_any", "" );
    $mail_a_t->set_var( "offer_type_all", "" );
    $mail_a_t->set_var( "offer_type_any", "" );

    $mail_a_t->set_var( "product_name", $product_name );
    $mail_a_t->set_var( "quote_quantity", $quote->quantity() );
    $mail_a_t->set_var( "used_quote_quantity", $quote_quantity );
    $currency = new eZCurrency( $quote->price() );
    $mail_a_t->set_var( "quote_price", $locale->format( $currency ) );
    if ( $quote->type() == QUOTE_ALL_TYPE )
        $mail_a_t->parse( "quote_type_all", "quote_type_all_tpl" );
    else
        $mail_a_t->parse( "quote_type_any", "quote_type_any_tpl" );

    $mail_a_t->set_var( "offer_quantity", $offer->quantity() );
    $mail_a_t->set_var( "used_offer_quantity", $offer_quantity );
    $currency = new eZCurrency( $offer->price() );
    $mail_a_t->set_var( "offer_price", $locale->format( $currency ) );
    if ( $offer->type() == QUOTE_ALL_TYPE )
        $mail_a_t->parse( "offer_type_all", "offer_type_all_tpl" );
    else
        $mail_a_t->parse( "offer_type_any", "offer_type_any_tpl" );

    $mail_a_t->set_var( "customer_name", $customer->name() );
    $mail_a_t->set_var( "customer_id", $customer->id() );

    $mail_a_t->set_var( "supplier_name", $supplier->name() );
    $mail_a_t->set_var( "supplier_id", $supplier->id() );

    $siteadmin_mail = new eZMail();
    $siteadmin_mail->setFromName( $admin );
    $siteadmin_mail->setFrom( $admin_mail );

    $mail_a_t->parse( "subject", "subject_tpl" );
    $mail_a_t->parse( "body", "body_tpl" );

    $siteadmin_mail->setSubject( $mail_a_t->get_var( "subject" ) );
    $siteadmin_mail->setBody( $mail_a_t->get_var( "body" ) );

    $siteadmin_mail->setTo( $admin_mail );
    $siteadmin_mail->send();
}

function showNotice( &$ini, &$locale, &$module, &$Language, &$quote, &$used_quantity,
                     &$product_name, &$ProductID, &$CategoryID )
{
    $t = new eZTemplate( "$module/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                         "$module/user/intl/", $Language, "productmatch.php" );

    $t->set_file( "product_match_tpl", "productmatch.tpl" );

    $t->set_block( "product_match_tpl", "match_offer_tpl", "match_offer" );
    $t->set_block( "product_match_tpl", "match_quote_tpl", "match_quote" );

    $t->set_block( "product_match_tpl", "match_type_all_tpl", "match_type_all" );
    $t->set_block( "product_match_tpl", "match_type_any_tpl", "match_type_any" );

    $t->set_var( "match_offer", "" );
    $t->set_var( "match_quote", "" );

    $t->set_var( "match_type_all", "" );
    $t->set_var( "match_type_any", "" );

    if ( $quote->quoteState() == OFFER_TYPE )
        $t->parse( "match_offer", "match_offer_tpl" );
    else
        $t->parse( "match_quote", "match_quote_tpl" );

    if ( $quote->type() == QUOTE_ALL_TYPE )
        $t->parse( "match_type_all", "match_type_all_tpl" );
    else
        $t->parse( "match_type_any", "match_type_any_tpl" );

    $t->set_var( "product_name", $product_name );
    $t->set_var( "quantity", $used_quantity );
    $t->set_var( "remain_quantity", $quote->quantity() - $used_quantity );
    $currency = new eZCurrency( $quote->price() );
    $t->set_var( "price", $locale->format( $currency ) );
    $t->set_var( "type", $quote->type() );
    $t->set_var( "product_id", $ProductID );
    $t->set_var( "category_id", $CategoryID );

    $t->setAllStrings();

    $t->pparse( "output", "product_match_tpl" );
}

function matchAllAll( &$quote, &$offer, &$q_notice, &$ini, &$locale, &$module, &$Language,
                      &$product_name, &$ProductID, &$CategoryID )
{
    $q_quan = $quote->quantity();
    $o_quan = $offer->quantity();
    settype( $q_quan, "double" );
    settype( $o_quan, "double" );
    if ( $q_quan == $o_quan )
    {
        $customer = $quote->user( false, true );
        $supplier = $offer->user( false, true );

        sendMails( $ini, $locale, $quote, $offer, $quote->quantity(), $offer->quantity(),
                   $product_name, $customer, $supplier, $Language );
        $used_quantity = $quote->quantity();

        showNotice( $ini, $locale, $module, $Language, $q_notice, $used_quantity,
                    $product_name, $ProductID, $CategoryID );

        $quote->delete();
        $offer->delete();

        return true;
    }
    return false;
}

function matchAllPartial( &$quote, &$offer, &$q_notice, &$ini, &$locale, &$module, &$Language,
                          &$product_name, &$ProductID, &$CategoryID )
{
    $q_quan = $quote->quantity();
    $o_quan = $offer->quantity();
    settype( $q_quan, "double" );
    settype( $o_quan, "double" );
    if ( $q_quan <= $o_quan )
    {
        $customer = $quote->user( false, true );
        $supplier = $offer->user( false, true );

        sendMails( $ini, $locale, $quote, $offer, $quote->quantity(), $quote->quantity(),
                   $product_name, $customer, $supplier, $Language );

        $used_quantity = $quote->quantity();

        showNotice( $ini, $locale, $module, $Language, $q_notice, $used_quantity,
                    $product_name, $ProductID, $CategoryID );

        if ( $quote->quantity() == $offer->quantity() )
        {
            $offer->delete();
        }
        else
        {
            $offer->setQuantity( $offer->quantity() - $quote->quantity() );
            $offer->store();
        }
        $quote->delete();

        return true;
    }
    return false;
}

function matchPartialAll( &$quote, &$offer, &$q_notice, &$ini, &$locale, &$module, &$Language,
                          &$product_name, &$ProductID, &$CategoryID )
{
    $q_quan = $quote->quantity();
    $o_quan = $offer->quantity();
    settype( $q_quan, "double" );
    settype( $o_quan, "double" );
    if ( $q_quan >= $o_quan )
    {
        $customer = $quote->user( false, true );
        $supplier = $offer->user( false, true );

        sendMails( $ini, $locale, $quote, $offer, $offer->quantity(), $offer->quantity(),
                   $product_name, $customer, $supplier, $Language );

        $used_quantity = $offer->quantity();

        showNotice( $ini, $locale, $module, $Language, $q_notice, $used_quantity,
                    $product_name, $ProductID, $CategoryID );

        if ( $quote->quantity() == $offer->quantity() )
        {
            $quote->delete();
        }
        else
        {
            $quote->setQuantity( $quote->quantity() - $offer->quantity() );
            $quote->store();
        }
        $offer->delete();

        return true;
    }
    return false;
}

function matchPartialPartial( &$quote, &$offer, &$q_notice, &$ini, &$locale, &$module, &$Language,
                              &$product_name, &$ProductID, &$CategoryID )
{
    $min_quantity = min( $quote->quantity(), $offer->quantity() );
    $customer = $quote->user( false, true );
    $supplier = $offer->user( false, true );

    sendMails( $ini, $locale, $quote, $offer, $min_quantity, $min_quantity,
               $product_name, $customer, $supplier, $Language );

    $used_quantity = $min_quantity;

    showNotice( $ini, $locale, $module, $Language, $q_notice, $used_quantity,
                $product_name, $ProductID, $CategoryID );

    if ( $quote->quantity() == $min_quantity )
    {
        $quote->delete();
    }
    else
    {
        $quote->setQuantity( $quote->quantity() - $min_quantity );
        $quote->store();
    }
    if ( $offer->quantity() == $min_quantity )
    {
        $offer->delete();
    }
    else
    {
        $offer->setQuantity( $offer->quantity() - $min_quantity );
        $offer->store();
    }

    return true;
}

$ini =& $GlobalSiteIni;

if( isset( $Cancel ) )
{
    header( "Location: /exchange/product/view/$ProductID/$CategoryID" );
    exit();
}

if ( $Action == "quote" and isset( $Price ) and ( strtolower( $Price ) == "rfq" || empty( $Price ) )
     and ( get_class( $quote ) != "ezquote" or $quote->quoteState() == RFQ_TYPE ) )
    $Action = "rfq";

$Language = $ini->read_var( "eZExchangeMain", "Language" );
$module = "ezexchange";

if ( is_numeric( $ProductID ) )
{
    $product = new eZProduct( $ProductID );
    $product_name = $product->name();
    $product_id = $product->id();

    switch( $Action )
    {
        case "offer":
        {
            $best_quote = eZQuote::bestPricedQuote( $ProductID, true );
            $quote = eZQuote::getUserOffer( $ProductID, true );
            if ( get_class( $quote ) == "ezquote" and $quote->rfqLink() )
                $best_quote = new eZQuote( $quote->rfqLink() );
            break;
        }
        case "request":
        {
            $best_quote = eZQuote::bestPricedQuote( $ProductID, true );
            $quote = eZQuote::getUserOffer( $ProductID, true );
            if ( get_class( $quote ) == "ezquote" )
            {
                header( "Location: /exchange/product/view/$ProductID/$CategoryID" );
                exit();
            }
            $quote = new eZQuote( $QuoteID );
            break;
        }
        default:
        case "rfq":
        case "quote":
        {
            $best_quote = eZQuote::bestPricedOffer( $ProductID, true );
            $quote = eZQuote::getUserQuote( $ProductID, true );
            if ( get_class( $quote ) != "ezquote" )
            {
                $quote = eZQuote::getUserRFQ( $ProductID, true );
                if ( get_class( $quote ) == "ezquote" and $quote->offerLink() )
                    $best_quote = new eZQuote( $quote->offerLink() );
            }
        }
    }
    if ( $quote and get_class( $quote ) == "ezquote" )
    {
        $old_expire = $expire = $quote->expireDays();
        $expire_date = $quote->expireDate();
        $quote_type = $quote->type();
        $old_quantity = $quantity = $quote->quantity();
        $old_price = $price = $quote->price();
    }
}
else
{
    header( "Location: /exchange/product/list" );
}

if ( get_class( $quote ) == "ezquote" and $quote->quoteState() == "rfq" and $Action != "request" )
    $Action = "rfq";

$today = new eZDate();
$locale = new eZLocale( $Language );

if ( isset( $QuoteType ) )
    $quote_type = $QuoteType;
if ( isset( $Quantity ) )
    $quantity = $Quantity;
if ( isset( $Price ) )
    $price = $Price;
if ( isset( $DaysLeft ) )
    $expire = $DaysLeft;

$error = false;
$error_array = array();
$match = false;

if ( isset( $OK ) )
{
    if ( !is_numeric( $Price ) || $Price <= 0 )
    {
        if ( ( get_class( $quote ) == "ezquote" and $quote->quoteState() != RFQ_TYPE ) or
             ( $Action != "rfq" or ( $Price != "rfq" && !empty( $Price ) ) ) )
        {
            $error_array[] = "error_price_item";
            $error = true;
        }
    }
    if ( !is_numeric( $DaysLeft ) || $DaysLeft < 0 )
    {
        $error_array[] = "error_expire_item";
        $error = true;
    }
    if ( !is_numeric( $Quantity ) || $Quantity < 1 )
    {
        $error_array[] = "error_quantity_item";
        $error = true;
    }
    if ( $Action == "offer" )
    {
        if ( is_numeric( $Price ) and is_numeric( $old_price ) and $Price > $old_price )
        {
            $error_array[] = "error_low_price_item";
            $error = true;
        }
    }
    else
    {
        if ( is_numeric( $Price ) and is_numeric( $old_price ) and $Price < $old_price )
        {
            $error_array[] = "error_low_price_item";
            $error = true;
        }
    }
    if ( is_numeric( $DaysLeft ) and $DaysLeft < $old_expire )
    {
        $error_array[] = "error_low_expire_item";
        $error = true;
    }
    if ( is_numeric( $Quantity ) and $Quantity < $old_quantity )
    {
        $error_array[] = "error_low_quantity_item";
        $error = true;
    }
    if ( is_numeric( $Price ) )
    {
        switch( $Action )
        {
            case "offer":
            case "request":
            {
                if( get_class( $quote ) != "ezquote" or !$quote->rfqLink() )
                {
                    $best_quote = eZQuote::bestPricedQuote( $ProductID, true );
                    if ( get_class( $best_quote ) == "ezquote" and $Price < $best_quote->price() )
                    {
                        $error_array[] = "error_high_price_item";
                        $high_price = $best_quote->price();
                        $error = true;
                    }
                }
                break;
            }
            case "rfq":
            {
                break;
            }
            default:
            case "quote":
            {
                if( get_class( $quote ) != "ezquote" or !$quote->offerLink() )
                {
                    $best_offer = eZQuote::bestPricedOffer( $ProductID, true );
                    if ( get_class( $best_offer ) == "ezquote" and $Price > $best_offer->price() )
                    {
                        $error_array[] = "error_high_price_item";
                        $high_price = $best_offer->price();
                        $error = true;
                    }
                }
            }
        }
    }

    if ( !$error )
    {
        if ( !$quote || $Action == "request" )
        {
            $quote = new eZQuote();
        }
        $old_action = $Action;
        if ( $Action == "request" )
            $Action = "offer";
        $quote->setDate( new eZDate() );
        $quote->setExpireDays( $expire );
        $quote->setQuantity( $quantity );
        $quote->setPrice( $price );
        $quote->setType( $quote_type );
        if ( $Action == "rfq" and $quote->offerLink() )
            $quote->setQuoteState( QUOTE_TYPE );
        else
            $quote->setQuoteState( $Action );
        $ret = $quote->store();
        if( $ret == "insert" )
            $quote->addToUser( $ProductID );

        if ( $old_action == "request" )
            $quote->linkWithRFQ( $QuoteID );

        $match = false;
        switch( $Action )
        {
            case "offer":
            {
                if ( $quote->rfqLink() )
                {
                    $quote_match = new eZQuote( $quote->rfqLink() );
                    if ( $quote_match->price() != $quote->price() )
                        break;
                    $match = false;
                    if ( $quote_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                    {
                        $match = matchAllAll( $quote_match, $quote, $quote,
                        $ini, $locale, $module, $Language, $product_name,
                        $ProductID, $CategoryID );
                    }
                    else if ( $quote_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                    {
                        $match = matchAllPartial( $quote_match, $quote, $quote,
                        $ini, $locale, $module, $Language, $product_name,
                        $ProductID, $CategoryID );
                    }
                    else if ( $quote_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                    {
                        $match = matchPartialAll( $quote_match, $quote, $quote,
                        $ini, $locale, $module, $Language, $product_name,
                        $ProductID, $CategoryID );
                    }
                    else if ( $quote_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                    {
                        $match = matchPartialPartial( $quote_match, $quote, $quote,
                        $ini, $locale, $module, $Language, $product_name,
                        $ProductID, $CategoryID );
                    }
                }
                else
                {
                    $quotes = eZQuote::getAllQuotes( $ProductID, true, $Price );
                    $match = false;
                    foreach( $quotes as $quote_match )
                    {
                        if ( $quote_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                        {
                            $match = matchAllAll( $quote_match, $quote, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        else if ( $quote_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                        {
                            $match = matchAllPartial( $quote_match, $quote, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        else if ( $quote_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                        {
                            $match = matchPartialAll( $quote_match, $quote, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        else if ( $quote_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                        {
                            $match = matchPartialPartial( $quote_match, $quote, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        if ( $match )
                            break;
                    }
                }
                break;
            }
            case "quote":
            {
                if ( $quote->offerLink() )
                {
                    $offer_match = new eZQuote( $quote->offerLink() );
                    if ( $offer_match->price() != $quote->price() )
                        break;
                    $match = false;
                    if ( $offer_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                    {
                        $match = matchAllAll( $quote, $offer_match, $quote,
                                              $ini, $locale, $module, $Language, $product_name,
                                              $ProductID, $CategoryID );
                    }
                    else if ( $offer_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                    {
                        $match = matchPartialAll( $quote, $offer_match, $quote,
                                                  $ini, $locale, $module, $Language, $product_name,
                                                  $ProductID, $CategoryID );
                    }
                    else if ( $offer_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                    {
                        $match = matchAllPartial( $quote, $offer_match, $quote,
                                                  $ini, $locale, $module, $Language, $product_name,
                                                  $ProductID, $CategoryID );
                    }
                    else if ( $offer_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                    {
                        $match = matchPartialPartial( $quote, $offer_match, $quote,
                                                      $ini, $locale, $module, $Language, $product_name,
                                                      $ProductID, $CategoryID );
                    }
                }
                else
                {
                    $offers = eZQuote::getAllOffers( $ProductID, true, $Price );
                    $match = false;
                    foreach( $offers as $offer_match )
                    {
                        if ( $offer_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                        {
                            $match = matchAllAll( $quote, $offer_match, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        else if ( $offer_match->type() == QUOTE_ALL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                        {
                            $match = matchPartialAll( $quote, $offer_match, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        else if ( $offer_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_ALL_TYPE )
                        {
                            $match = matchAllPartial( $quote, $offer_match, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        else if ( $offer_match->type() == QUOTE_PARTIAL_TYPE && $quote_type == QUOTE_PARTIAL_TYPE )
                        {
                            $match = matchPartialPartial( $quote, $offer_match, $quote,
                            $ini, $locale, $module, $Language, $product_name,
                            $ProductID, $CategoryID );
                        }
                        if ( $match )
                            break;
                    }
                }
                break;
            }
        }

        if ( !$match )
        {
            header( "Location: /exchange/product/view/$ProductID/$CategoryID" );
            exit();
        }
    }
}

if ( !$match )
{
    switch( $Action )
    {
        case "offer":
        {
            $langfile = "offeredit.php";
            break;
        }
        case "rfq":
        {
            $langfile = "rfqedit.php";
            break;
        }
        case "request":
        {
            $langfile = "requestedit.php";
            break;
        }
        default:
        case "quote":
        {
            $langfile = "quoteedit.php";
        }
    }

    $t = new eZTemplate( "$module/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                         "$module/user/intl/", $Language, $langfile );

    $t->set_file( "quote_edit_tpl", "quoteedit.tpl" );

    $t->set_block( "quote_edit_tpl", "quote_header_price_tpl", "quote_header_price" );
    $t->set_block( "quote_edit_tpl", "quote_rfq_price_tpl", "quote_rfq_price" );

    $t->set_block( "quote_edit_tpl", "best_quote_tpl", "best_quote" );
    $t->set_block( "best_quote_tpl", "best_quote_values_tpl", "best_quote_values" );
    $t->set_block( "best_quote_tpl", "best_quote_rfq_values_tpl", "best_quote_rfq_values" );
    $t->set_block( "best_quote_tpl", "best_quote_all_type_tpl", "best_quote_all_type" );
    $t->set_block( "best_quote_tpl", "best_quote_any_type_tpl", "best_quote_any_type" );
    $t->set_block( "best_quote_tpl", "quote_best_price_tpl", "quote_best_price" );
    $t->set_block( "best_quote_tpl", "quote_best_rfq_price_tpl", "quote_best_rfq_price" );

    $t->set_block( "quote_edit_tpl", "edit_quote_tpl", "edit_quote" );
    $t->set_block( "edit_quote_tpl", "quote_all_type_tpl", "quote_all_type" );
    $t->set_block( "edit_quote_tpl", "quote_any_type_tpl", "quote_any_type" );
    $t->set_block( "edit_quote_tpl", "quote_2_all_type_tpl", "quote_2_all_type" );
    $t->set_block( "edit_quote_tpl", "quote_2_any_type_tpl", "quote_2_any_type" );
    $t->set_block( "edit_quote_tpl", "quote_edit_quantity_tpl", "quote_edit_quantity" );
    $t->set_block( "edit_quote_tpl", "quote_show_quantity_tpl", "quote_show_quantity" );
    $t->set_block( "edit_quote_tpl", "quote_edit_price_tpl", "quote_edit_price" );

    $t->set_block( "quote_edit_tpl", "new_quote_tpl", "new_quote" );
    $t->set_block( "new_quote_tpl", "quote_new_price_tpl", "quote_new_price" );

    $t->set_block( "quote_edit_tpl", "errors_tpl", "errors_item" );
    $t->set_block( "errors_tpl", "error_quantity_item_tpl", "error_quantity_item" );
    $t->set_block( "errors_tpl", "error_price_item_tpl", "error_price_item" );
    $t->set_block( "errors_tpl", "error_expire_item_tpl", "error_expire_item" );
    $t->set_block( "errors_tpl", "error_low_price_item_tpl", "error_low_price_item" );
    $t->set_block( "errors_tpl", "error_low_expire_item_tpl", "error_low_expire_item" );
    $t->set_block( "errors_tpl", "error_low_quantity_item_tpl", "error_low_quantity_item" );
    $t->set_block( "errors_tpl", "error_high_price_item_tpl", "error_high_price_item" );

    $t->set_var( "best_quote", "" );
    $t->set_var( "best_quote_all_type", "" );
    $t->set_var( "best_quote_any_type", "" );

    $t->set_var( "best_quantity", "" );
    $t->set_var( "best_price", "" );
    $t->set_var( "best_days", "" );
    $t->set_var( "best_expire-date", "" );

    $t->set_var( "new_quote", "" );
    $t->set_var( "edit_quote", "" );
    $t->set_var( "quote_all_type", "" );
    $t->set_var( "quote_any_type", "" );
    $t->set_var( "quote_2_all_type", "" );
    $t->set_var( "quote_2_any_type", "" );

    $t->set_var( "errors_item", "" );
    $t->set_var( "error_quantity_item", "" );
    $t->set_var( "error_price_item", "" );
    $t->set_var( "error_expire_item", "" );
    $t->set_var( "error_low_price_item", "" );
    $t->set_var( "error_low_expire_item", "" );
    $t->set_var( "error_low_quantity_item", "" );
    $t->set_var( "error_high_price_item", "" );

    $t->set_var( "all_selected", "" );
    $t->set_var( "any_selected", "" );

    $t->set_var( "product_type", $Action );
    $t->set_var( "product_name", $product_name );
    $t->set_var( "product_id", $product_id );
    $t->set_var( "category_id", $CategoryID );
    $t->set_var( "quote_id", $QuoteID );
    $t->set_var( "quote_type", $Action );

    $t->set_var( "cur_quantity", $quantity );
    $t->set_var( "cur_price", $price );
    $t->set_var( "cur_days", $expire );

    $t->set_var( "high_price", $high_price );

    $t->set_var( "quote_best_price", "" );
    $t->set_var( "quote_best_rfq_price", "" );
    $t->set_var( "best_quote_values", "" );
    $t->set_var( "best_quote_rfq_values", "" );

    if ( get_class( $best_quote ) == "ezquote" )
    {
        $t->set_var( "best_quantity", $best_quote->quantity() );
        if ( $Action == "rfq" )
        {
            $currency = new eZCurrency( $best_quote->price() );
            $t->set_var( "best_price", $locale->format( $currency ) );
            $t->parse( "quote_best_price", "quote_best_price_tpl" );
            if ( $quote->offerLink() )
                $t->parse( "best_quote_rfq_values", "best_quote_rfq_values_tpl" );
            else
                $t->parse( "best_quote_values", "best_quote_values_tpl" );
        }
        else if ( $best_quote->quoteState() != RFQ_TYPE )
        {
            $currency = new eZCurrency( $best_quote->price() );
            $t->set_var( "best_price", $locale->format( $currency ) );
            if ( get_class( $quote ) == "ezquote" and $quote->rfqLink() == $best_quote->id() )
            {
                $t->parse( "quote_best_price", "quote_best_price_tpl" );
                $t->parse( "best_quote_rfq_values", "best_quote_rfq_values_tpl" );
            }
            else
            {
                $t->parse( "quote_best_price", "quote_best_price_tpl" );
                $t->parse( "best_quote_values", "best_quote_values_tpl" );
            }
        }
        else
        {
            $t->parse( "quote_best_rfq_price", "quote_best_rfq_price_tpl" );
            $t->parse( "best_quote_rfq_values", "best_quote_rfq_values_tpl" );
        }
        $t->set_var( "best_days", $best_quote->expireDays() );
        $t->set_var( "best_expire_date", $locale->format( $best_quote->expireDate() ) );
        if ( $best_quote->type() == QUOTE_ALL_TYPE )
        {
            $t->parse( "best_quote_all_type", "best_quote_all_type_tpl" );
        }
        else
        {
            $t->parse( "best_quote_any_type", "best_quote_any_type_tpl" );
        }

        $t->parse( "best_quote", "best_quote_tpl" );
    }

    foreach( $error_array as $error )
    {
        $t->parse( $error, $error . "_tpl" );
    }
    if ( $error )
        $t->parse( "errors_item", "errors_tpl" );

    $t->set_var( "quote_show_quantity", "" );
    $t->set_var( "quote_edit_quantity", "" );
    if ( $Action == "request" or
         ( get_class( $quote ) == "ezquote" and $quote->quoteState() == OFFER_TYPE and $quote->rfqLink() ) )
    {
        $t->parse( "quote_show_quantity", "quote_show_quantity_tpl" );
    }
    else
    {
        $t->parse( "quote_edit_quantity", "quote_edit_quantity_tpl" );
    }

    if ( $Action != "rfq" or $quote->offerLink() )
    {
        $t->parse( "quote_header_price", "quote_header_price_tpl" );
        $t->parse( "quote_edit_price", "quote_edit_price_tpl" );
        $t->parse( "quote_new_price", "quote_new_price_tpl" );
        $t->parse( "quote_best_price", "quote_best_price_tpl" );
        $t->set_var( "quote_rfq_price", "" );
    }
    else
    {
        $t->set_var( "quote_header_price", "" );
        $t->set_var( "quote_edit_price", "" );
        $t->set_var( "quote_new_price", "" );
        $t->parse( "quote_rfq_price", "quote_rfq_price_tpl" );
    }


    if ( get_class( $quote ) == "ezquote" )
    {
        $t->set_var( "today", $locale->format( $today ) );
        $t->set_var( "last_days", $quote->expireDays() );
        $t->set_var( "last_expire_date", $locale->format( $quote->expireDate() ) );
        $t->set_var( "last_quantity", $quote->quantity() );
        if ( is_numeric( $quote->price() ) )
        {
            $currency = new eZCurrency( $quote->price() );
            $t->set_var( "last_price", $locale->format( $currency ) );
        }
        else
            $t->set_var( "last_price", $quote->price() );
        if ( $quote_type == QUOTE_ALL_TYPE )
        {
            $t->parse( "quote_all_type", "quote_all_type_tpl" );
            $t->parse( "quote_2_all_type", "quote_2_all_type_tpl" );
        }
        else
        {
            $t->parse( "quote_any_type", "quote_any_type_tpl" );
            $t->parse( "quote_2_any_type", "quote_2_any_type_tpl" );
        }

        $t->parse( "edit_quote", "edit_quote_tpl" );
    }
    else
    {
        $t->set_var( "today", $locale->format( $today ) );
        if ( $quote_type == QUOTE_ALL_TYPE )
            $t->set_var( "all_selected", "selected" );
        else
            $t->set_var( "any_selected", "selected" );

        $t->parse( "new_quote", "new_quote_tpl" );
    }


    $t->setAllStrings();

    $t->pparse( "output", "quote_edit_tpl" );

}

?>
