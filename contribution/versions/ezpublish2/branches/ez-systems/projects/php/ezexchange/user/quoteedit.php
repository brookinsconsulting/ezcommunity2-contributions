<?php
// 
// $Id: quoteedit.php,v 1.1 2001/01/30 20:12:10 jb Exp $
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
include_once( "eztrade/classes/ezproduct.php" );
include_once( "ezexchange/classes/ezquote.php" );


if( isset( $Cancel ) )
{
    header( "Location: /exchange/product/view/$ProductID" );
    exit();
}

if( is_numeric( $ProductID ) )
{
    $product = new eZProduct( $ProductID );
    $product_name = $product->name();
    $product_id = $product->id();

    switch( $Action )
    {
        case "offer":
        {
            $quote = eZQuote::getUserOffer( $ProductID, true );
            break;
        }
        case "rfq":
        {
            $quote = eZQuote::getUserRFQ( $ProductID, true );
            break;
        }
        default:
        case "quote":
        {
            $quote = eZQuote::getUserQuote( $ProductID, true );
        }
    }
    if ( $quote )
    {
        $quantity = $quote->quantity();
        $old_price = $price = $quote->price();
        $old_expire = $expire = $quote->expireDays();
        $quote_type = $quote->type();
    }
}

if ( isset( $QuoteType ) )
    $quote_type = $QuoteType;
if ( isset( $Quantity ) )
    $quantity = $Quantity;
if ( isset( $Price ) )
    $price = $Price;
if ( isset( $Days ) )
    $expire = $Days;

$error = false;
$error_array = array();

if ( isset( $OK ) )
{
    if ( $Action != "rfq" and ( !is_numeric( $Quantity ) || $Quantity < 1 ) )
    {
        $error_array[] = "error_quantity_item";
        $error = true;
    }
    if ( !is_numeric( $Price ) || $Price < 1 )
    {
        $error_array[] = "error_price_item";
        $error = true;
    }
    if ( !is_numeric( $Days ) || $Days < 0 )
    {
        $error_array[] = "error_expire_item";
        $error = true;
    }
    if ( is_numeric( $Price ) and $Price <= $old_price )
    {
        $error_array[] = "error_low_price_item";
        $error = true;
    }
    if ( is_numeric( $Days ) and $Days <= $old_expire )
    {
        $error_array[] = "error_low_expire_item";
        $error = true;
    }

    if ( !$error )
    {
        if ( !$quote )
        {
            $quote = new eZQuote();
        }
        $quote->setDate( new eZDate() );
        $quote->setExpireDays( $expire );
        $quote->setQuantity( $quantity );
        $quote->setPrice( $price );
        $quote->setType( $quote_type );
        $quote->setQuoteState( $Action );
        $ret = $quote->store();
        if( $ret == "insert" )
            $quote->addToUser( $ProductID );

        header( "Location: /exchange/product/view/$ProductID" );
        exit();
    }
}

$ini =& $GlobalSiteIni;

$Language = $ini->read_var( "eZExchangeMain", "Language" );

$module = "ezexchange";

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
    default:
    case "quote":
    {
        $langfile = "quoteedit.php";
    }
}

$t = new eZTemplate( "$module/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                     "$module/user/intl/", $Language, $langfile );

$t->set_file( "quote_edit_tpl", "quoteedit.tpl" );

$t->set_block( "quote_edit_tpl", "last_quote_tpl", "last_quote" );
$t->set_block( "quote_edit_tpl", "quantity_title_tpl", "quantity_title" );
$t->set_block( "quote_edit_tpl", "quantity_edit_tpl", "quantity_edit" );
$t->set_block( "quote_edit_tpl", "rfq_quantity_edit_tpl", "rfq_quantity_edit" );
$t->set_block( "quote_edit_tpl", "no_quantity_edit_tpl", "no_quantity_edit" );

$t->set_block( "quote_edit_tpl", "errors_tpl", "errors_item" );
$t->set_block( "errors_tpl", "error_quantity_item_tpl", "error_quantity_item" );
$t->set_block( "errors_tpl", "error_price_item_tpl", "error_price_item" );
$t->set_block( "errors_tpl", "error_expire_item_tpl", "error_expire_item" );
$t->set_block( "errors_tpl", "error_low_price_item_tpl", "error_low_price_item" );
$t->set_block( "errors_tpl", "error_low_expire_item_tpl", "error_low_expire_item" );

$t->set_var( "errors_item", "" );
$t->set_var( "error_quantity_item", "" );
$t->set_var( "error_price_item", "" );
$t->set_var( "error_expire_item", "" );
$t->set_var( "error_low_price_item", "" );
$t->set_var( "error_low_expire_item", "" );

$t->set_var( "all_selected", "" );
$t->set_var( "any_selected", "" );

$t->set_var( "last_quote", "" );

$t->set_var( "quantity_title", "" );
$t->set_var( "quantity_edit", "" );
$t->set_var( "no_quantity_edit", "" );
$t->set_var( "rfq_quantity_edit", "" );

$t->set_var( "product_type", $Action );

$t->set_var( "product_name", $product_name );
$t->set_var( "product_id", $product_id );
$t->set_var( "quantity", $quantity );
$t->set_var( "days", $expire );
$t->set_var( "price", $price );

$t->set_var( "quote_type", $Action );

foreach( $error_array as $error )
{
    $t->parse( $error, $error . "_tpl" );
}
if ( $error )
    $t->parse( "errors_item", "errors_tpl" );

if ( get_class( $quote ) == "ezquote" and $Action != "rfq" )
{
    $t->set_var( "last_quantity", $quote->quantity() );
    $t->set_var( "last_price", $quote->price() );
    $t->set_var( "last_days", $quote->expireDays() );
    $t->parse( "last_quote", "last_quote_tpl" );
    $t->parse( "no_quantity_edit", "no_quantity_edit_tpl" );
    $t->parse( "quantity_title", "quantity_title_tpl" );
}
else if ( $Action != "rfq" )
{
    $t->parse( "quantity_edit", "quantity_edit_tpl" );
    $t->parse( "quantity_title", "quantity_title_tpl" );
}

if ( $quote_type == 0 )
{
    $t->set_var( "all_selected", "selected" );
}
else
{
    $t->set_var( "any_selected", "selected" );
}

$t->setAllStrings();

$t->pparse( "output", "quote_edit_tpl" );


?>
