<?php
// 
// $Id: smallcart.php,v 1.13 2001/03/19 10:07:04 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <12-Dec-2000 15:21:10 bf>
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

include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezsession/classes/ezsession.php" );

$cart = new eZCart();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

$user = eZUser::currentUser();

$cart = $cart->getBySession( $session );

if ( !$cart )
{
    $cart = new eZCart();
    $cart->setSession( $session );
    
    $cart->store();
}

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "smallcart.php" );

$t->setAllStrings();

$t->set_file( array(
    "cart_page_tpl" => "smallcart.tpl"
    ) );

$t->set_block( "cart_page_tpl", "cart_checkout_tpl", "cart_checkout" );
$t->set_block( "cart_page_tpl", "empty_cart_tpl", "empty_cart" );


$t->set_block( "cart_page_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );


// fetch the cart items
$items = $cart->items( );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
    
$i = 0;
$sum = 0.0;
$totalVAT = 0.0;
$t->set_var( "cart_item", "" );
foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
    $t->set_var( "cart_item_id", $item->id() );
    
    $product = $item->product();
    if ( $product )
    {
        $price = $product->price() * $item->count();
    
        $currency->setValue( $price );

        // product price
        $price = $item->price();
        $currency->setValue( $price );
    
        $sum += $price;
        $totalVAT += $product->vat( $price );
    
        $t->set_var( "product_id", $product->id() );
        $t->set_var( "product_name", $product->name() );

        $t->set_var( "cart_item_count", $item->count() );
    
        $t->set_var( "product_price", $locale->format( $currency ) );

        $optionValues =& $item->optionValues();
        $Quantity = $product->totalQuantity();
        
        if ( !$product->hasPrice() )
        {
            $min_quantity = false;
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();
                $value_quantity = $value->totalQuantity();
                if ( !(is_bool( $value_quantity ) and !$value_quantity) )
                {
                    if ( is_bool( $min_quantity ) )
                        $min_quantity = $value_quantity;
                    else
                        $min_quantity = min( $min_quantity , $value_quantity );
                }                
            }
        }
        
        if ( !(is_bool( $min_quantity ) and !$min_quantity) and
             $RequireQuantity and $min_quantity == 0 )
            $can_checkout = false;

        $t->parse( "cart_item", "cart_item_tpl", true );
    }
    
    $i++;
}



$currency->setValue( $sum );
$t->set_var( "cart_sum", $locale->format( $currency ) );
$currency->setValue( $totalVAT );
$t->set_var( "cart_vat_sum", $locale->format( $currency ) );



if ( count( $items ) > 0 and $can_checkout )
{
    $t->parse( "cart_checkout", "cart_checkout_tpl" );
}
else
{
    $t->set_var( "cart_checkout", "" );
}

if ( count( $items ) > 0 )
{
    $t->parse( "cart_item_list", "cart_item_list_tpl" );
    $t->set_var( "empty_cart", "" );    
}
else
{
    $t->parse( "empty_cart", "empty_cart_tpl" );    
    $t->set_var( "cart_item_list", "" );
}


$t->pparse( "output", "cart_page_tpl" );

?>

