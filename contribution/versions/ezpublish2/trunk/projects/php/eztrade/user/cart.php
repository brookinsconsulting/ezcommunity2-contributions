<?php
// 
// $Id: cart.php,v 1.56 2001/09/07 11:13:39 ce Exp $
//
// Created on: <27-Sep-2000 11:57:49 bf>
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

include_once( "classes/ezhttptool.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "eztrade/classes/ezshippingtype.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

if ( ( $Action == "Refresh" ) || isSet( $DoCheckOut ) )
{
    $i = 0;
    if ( count( $CartIDArray ) > 0 )
    foreach ( $CartIDArray as $cartID )
    {
        $cartItem = new eZCartItem( $cartID );
        $product =& $cartItem->product();

        if ( ( $product->totalQuantity() < $CartCountArray[$i] ) and ( $product->totalQuantity() != false ) )
        {
            $cartItem->setCount( $product->totalQuantity() );
        }
        else
        {
            $cartItem->setCount( $CartCountArray[$i] );
        }

        $cartItem->store();

        $i++;

        // Check for negative entries
        if ( ( $cartItem->count() < 1 ) )
        {
            $cartItem->delete();
        }
    }
    $i = 0;

    if ( count ( $ValueIDArray ) > 0 )
    {
        foreach ( $ValueIDArray as $valueID )
        {
            $value = new eZCartOptionValue( $valueID );
            $valueOption = $value->optionValue();

            if ( ( $valueOption->totalQuantity() < $ValueCountArray[$i] ) and ( $valueOption->totalQuantity() != false ) )
            {
                $value->setCount( $valueOption->totalQuantity() );
            }
            else
            {
//            print( $ValueCountArray[$i] );
                $value->setCount( $ValueCountArray[$i] );
            }

            $value->store();

            $i++;

            // Check for negative entries
            if ( ( $value->count() < 1 ) )
            {
                $value->delete();
            }
        }
    }
}

if ( isset( $ShopMore ) ) 
{
    eZHTTPTool::header( "Location: /trade/productlist/1" );
    exit();
}

// checkout
if ( isSet( $DoCheckOut ) )
{
    eZHTTPTool::header( "Location: /trade/customerlogin/" );
    exit();
}

$session =& eZSession::globalSession();

$cart = new eZCart();
$cart = $cart->getBySession( $session );

$user =& eZUser::currentUser();

if ( !$cart )
{
    $cart = new eZCart();
    $cart->setSession( $session );
    
    $cart->store();
}

//  $cart->delete();

if ( $Action == "AddToBasket" )
{
    $product = new eZProduct();
    if ( !$product->get( $ProductID ) )
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }

    // check if a product like this is already in the basket.
    // if so-> add the count value.
    $Quantity = $product->totalQuantity();
    if ( $product->hasQuantity( $RequireQuantity ) )
    {
        $productAddedToBasket = false;
        {
            // fetch the cart items
            $items = $cart->items( );

            foreach ( $items as $item )
            {
                $productItem =  $item->product();
                // the same product
                if ( ( $ProductID == $productItem->id() ) && ( $productAddedToBasket == false ) )
                {
                    $optionValues =& $item->optionValues();

                    if ( count( $optionValues ) > 0 )
                    { // product with options
                        $hasTheSameOptions = true;

                        foreach ( $optionValues as $optionValue )
                        {
                            $option =& $optionValue->option();
                            $value =& $optionValue->optionValue();

                            $optionValueFound = false;

                            if ( count( $OptionValueArray ) > 0 )
                            {
                                $i=0;
                                foreach ( $OptionValueArray as $valueItem )
                                {
                                    if ( ( $OptionIDArray[$i] == $option->id() )
                                         && ( $valueItem == $value->id() ) )
                                    {
                                        $optionValueFound = true;
                                    }
                                    $i++;
                                }
                            }
                        
                            if ( $optionValueFound == false )
                            {
                                $hasTheSameOptions = false;
                            }
                        }

                        if ( $hasTheSameOptions == true )
                        {
                            $item->setCount( $item->count() + 1 );
                            $item->store();
                            $productAddedToBasket = true;
                        }
                    }
                    else
                    { // product without options
                        if ( count( $OptionValueArray ) == 0 )
                        {
                            $item->setCount( $item->count() + 1 );
                            $item->store();
                            $productAddedToBasket = true;
                        }
                    }
                }
            }
        }

        if ( $productAddedToBasket == false )
        {
            $can_add = true;
            if ( !$product->hasQuantity() )
                $can_add = false;
            if ( count( $OptionValueArray ) > 0 )
            {
                foreach ( $OptionValueArray as $value )
                {
                    $optionValue = new eZOptionValue( $value );
                    if ( !$optionValue->hasQuantity( $RequireQuantity ) )
                    {
                        $can_add = false;
                    }
                }
            }
            if ( $can_add )
            {
                $cartItem = new eZCartItem();

                $cartItem->setProduct( $product );
                $cartItem->setCart( $cart );
                $cartItem->setPriceRange( $PriceRange );

                $cartItem->store();
                if ( count( $OptionValueArray ) > 0 )
                {
                    $i = 0;
                    foreach ( $OptionValueArray as $value )
                    {
                        $option = new eZOption( $OptionIDArray[$i] );
                        $optionValue = new eZOptionValue( $value );
                        if ( $optionValue->hasQuantity( $RequireQuantity ) )
                        {
                            $cartOption = new eZCartOptionValue();
                            $cartOption->setCartItem( $cartItem );
                            $cartOption->setOption( $option );
                            $cartOption->setRemoteID( $optionValue->remoteID() );
                            $cartOption->setOptionValue( $optionValue );

                            $cartOption->store();
                        }
                        $i++;
                    }
                }
            }
        }
    }

    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}


// TODO add check for user's cart.
// So the user can't delete cart items from other users:)
if ( $Action == "RemoveFromBasket" )
{
    $cartItem = new eZCartItem( $CartItemID );
    $cartItem->delete();
    
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}



$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "cart.php" );

$t->setAllStrings();

$t->set_file( "cart_page_tpl", "cart.tpl" );


$t->set_block( "cart_page_tpl", "cart_checkout_tpl", "cart_checkout" );
$t->set_block( "cart_checkout_tpl", "cart_checkout_button_tpl", "cart_checkout_button" );
$t->set_block( "cart_page_tpl", "empty_cart_tpl", "empty_cart" );

$t->set_block( "cart_page_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_list_tpl", "price_ex_vat_tpl", "price_ex_vat" );
$t->set_block( "cart_item_list_tpl", "price_inc_vat_tpl", "price_inc_vat" );
$t->set_block( "cart_item_list_tpl", "product_available_header_tpl", "product_available_header" );

$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_option_tpl", "cart_item_option_availability_tpl", "cart_item_option_availability" );
$t->set_block( "cart_item_tpl", "cart_image_tpl", "cart_image" );
$t->set_block( "cart_item_tpl", "product_available_item_tpl", "product_available_item" );

// fetch the cart items
$items = $cart->items( );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$i = 0;
$sum = 0.0;
$totalVAT = 0.0;

$t->set_var( "product_available_header", "" );
if ( $ShowQuantity )
    $t->parse( "product_available_header", "product_available_header_tpl" );

$can_checkout = true;

foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

    $t->set_var( "cart_item_id", $item->id() );
    $product =& $item->product();
    if ( $product->discontinued() )
        $can_checkout = false;
    
    $t->set_var( "product_price", "" );
    
    // thumbnail
    $image = $product->thumbnailImage();
    if  ( $image )
    {
        $thumbnail =& $image->requestImageVariation( 35, 35 );        

        $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
        $t->set_var( "product_image_width", $thumbnail->width() );
        $t->set_var( "product_image_height", $thumbnail->height() );
        $t->set_var( "product_image_caption", $image->caption() );
        $t->parse( "cart_image", "cart_image_tpl" );
    }
    else
    {
        $t->set_var( "cart_image", "" );
    }


    $Quantity = $product->totalQuantity();

    // product options
    $optionValues =& $item->optionValues();
    
    if ( !$product->hasPrice() )
    {
        $Quantity = 0;
        foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();
                $value_quantity = $value->totalQuantity();
                if ( $value_quantity > 0 )
                    $Quantity = $value_quantity;
            }
    }
    
    $t->set_var( "product_available_item", "" );
    if ( $ShowQuantity )
    {
        $NamedQuantity = $Quantity;
        if ( $ShowNamedQuantity )
            $NamedQuantity = eZProduct::namedQuantity( $Quantity );
        $t->set_var( "product_availability", $NamedQuantity );
        $t->parse( "product_available_item", "product_available_item_tpl" );
    }

    // Show the product price
    $addPrice = true;
    $foundPriceGroup = false;
    if ( ( !$RequireUserLogin or get_class( $user ) == "ezuser" ) and
         $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
    {
        $found_price = false;
        if ( $ShowPriceGroups and $PriceGroup > 0 )
        {
            $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup );
            if ( $price )
            {
                $price = $price * $item->count();
                                    
                $foundPriceGroup = true;
                if ( $PricesIncludeVAT == "enabled" )
                {
                    $totalVAT = $product->addVAT( $price );
                    $price += $totalVAT;
                }
                else
                {
                    $totalVAT = $product->extractVAT( $price );
                }

                $found_price = true;
            }
        }
        if ( !$found_price )
        {
            if ( $PricesIncludeVAT == "enabled" )
            {
                $totalVAT = $product->addVAT( $item->price() );
                $price = $item->price( true, false ) + $totalVAT;
            }
            else
            {
                $totalVAT = $product->extractVAT( $item->price() );
                $price = $item->price( true, false );
            }
        }
        $currency->setValue( $price );
        $t->set_var( "product_price", $locale->format( $currency ) );
    }
    else
    {
        $addPrice = false;
        if ( $PricesIncludeVAT == "enabled" )
        {
            $totalVAT = $product->addVAT( $item->price() );
            $price = $item->price() + $totalVAT;
        }
        else
        {
            $totalVAT = $product->extractVAT( $item->price( true, true ) );
            $price = $item->price( true, true );
        }
        $currency->setValue( $price );
        $t->set_var( "product_price", $locale->format( $currency ) );
    }

    $currency->setValue( $price );

    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "cart_item_count", $item->count() );


    // Print all the options
    if ( count ( $optionValues ) > 0 )
    {
        foreach ( $optionValues as $optionValue )
        {
            $t->set_var( "cart_item_option", "" );
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();
            $value_quantity = $value->totalQuantity();

            $t->set_var( "option_name", $option->name() );

            $descriptions = $value->descriptions();
            $t->set_var( "option_value", $descriptions[0] );

            $t->set_var( "value_item_count", $optionValue->count() );
            $t->set_var( "value_item_id", $optionValue->id() );

            $t->set_var( "cart_item_option_availability", "" );

            if ( $foundPriceGroup )
            {
                // Add code for options
            }
            else
            {
                if ( $PricesIncludeVAT == "enabled" )
                {
                    $totalVAT += $product->addVAT( $value->price() );
                    $optionPrice = ( $value->price() * $optionValue->count() ) + $totalVAT;
                }
                else
                {
                    $totalVAT += $product->extractVAT( $value->price() );
                    $optionPrice = $value->price() * $optionValue->count();
                }
            }

            if ( $addPrice )
                $price += $optionPrice;
                
            $currency->setValue( $optionPrice );
            $t->set_var( "option_price", $locale->format( $currency ) );

            if ( !(is_bool( $value_quantity ) and !$value_quantity) )
            {
                if ( is_bool( $min_quantity ) )
                    $min_quantity =  $value_quantity;
                else
                    $min_quantity = min( $min_quantity , $value_quantity );
                $named_quantity = $value_quantity;
                if ( $ShowNamedQuantity )
                    $named_quantity = eZProduct::namedQuantity( $value_quantity );
                if ( $ShowOptionQuantity )
                {
                    $t->set_var( "option_availability", $named_quantity );
                    $t->parse( "cart_item_option_availability", "cart_item_option_availability_tpl" );
                }
            }
            $t->parse( "cart_item_option", "cart_item_option_tpl", true );
        }
    }
    else
        $t->set_var( "cart_item_option", "" );

    $sum = $sum + $price;
    $min_quantity = $Quantity;

    if ( !(is_bool( $min_quantity ) and !$min_quantity) and
         $RequireQuantity and $min_quantity == 0 )
        $can_checkout = false;
    $t->parse( "cart_item", "cart_item_tpl", true );
        
    $i++;
}


// shipping cost and VAT
$type = new eZShippingType( );
$shippingType =& $type->defaultType();
$shippingCost = $cart->shippingCost( $shippingType );

$currency->setValue( $shippingCost );
$t->set_var( "shipping_sum", $locale->format( $currency ) );

// calculate the vat of the shiping
$shippingVAT = $cart->shippingVAT( $shippingType );

if ( $PricesIncludeVAT == "enabled" )
{
    $currency->setValue( $sum + $shippingVAT + $shippingCost );
    $t->set_var( "cart_sum", $locale->format( $currency ) );
    $t->set_var( "price_ex_vat", "" );
    $t->parse( "price_inc_vat", "price_inc_vat_tpl" ); 
}
else
{
    $currency->setValue( $sum + $shippingCost );
    $t->set_var( "cart_sum", $locale->format( $currency ) );
    $t->set_var( "price_inc_vat", "" );
    $t->parse( "price_ex_vat", "price_ex_vat_tpl" ); 
}


$currency->setValue( $sum + $shippingCost );
$t->set_var( "cart_sum", $locale->format( $currency ) );

$currency->setValue( $totalVAT + $shippingVAT);
$t->set_var( "cart_vat_sum", $locale->format( $currency ) );

$t->set_var( "cart_checkout", "" );
if ( count( $items ) > 0 )
    $t->parse( "cart_checkout", "cart_checkout_tpl" );

$t->set_var( "cart_checkout_button", "" );
if ( $can_checkout )
    $t->parse( "cart_checkout_button", "cart_checkout_button_tpl" );


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

