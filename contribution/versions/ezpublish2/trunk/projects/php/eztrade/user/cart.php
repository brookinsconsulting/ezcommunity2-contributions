<?php
// 
// $Id: cart.php,v 1.22 2001/03/09 11:45:35 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 11:57:49 bf>
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

include_once( "classes/ezhttptool.php" );

// checkout
if ( isset( $DoCheckOut ) )
{
    eZHTTPTool::header( "Location: /trade/customerlogin/" );
    exit();
}


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

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

//  $cart->delete();

if ( $Action == "AddToBasket" )
{
    $product = new eZProduct( $ProductID );

    // check if a product like this is already in the basket.
    // if so-> add the count value.

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
        $cartItem = new eZCartItem();

        $cartItem->setProduct( $product );
        $cartItem->setCart( $cart );

        $cartItem->store();

        if ( count( $OptionValueArray ) > 0 )
        {
            $i = 0;
            foreach ( $OptionValueArray as $value )
            {
                $option = new eZOption( $OptionIDArray[$i] );
                $optionValue = new eZOptionValue( $value );
                
                $cartOption = new eZCartOptionValue();
                $cartOption->setCartItem( $cartItem );
                $cartOption->setOption( $option );
                $cartOption->setOptionValue( $optionValue );

                $cartOption->store();
                
                $i++;
            }
        }
    }
    
//      Header( "Location: /trade/cart/" );
//      exit();
}

if ( $Action == "Refresh" )
{
    $i=0;
    if ( count( $CartIDArray ) > 0 )
    foreach ( $CartIDArray as $cartID )
    {
        $cartItem = new eZCartItem( $cartID );
        $cartItem->setCount( $CartCountArray[$i] );
        $cartItem->store();
        $i++;
    }
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

$t->set_file( array(
    "cart_page_tpl" => "cart.tpl"
    ) );


$t->set_block( "cart_page_tpl", "cart_checkout_tpl", "cart_checkout" );
$t->set_block( "cart_page_tpl", "empty_cart_tpl", "empty_cart" );


$t->set_block( "cart_page_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );

$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_tpl", "cart_image_tpl", "cart_image" );


// fetch the cart items
$items = $cart->items( );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
    
$i = 0;
$sum = 0.0;
$totalVAT = 0.0;
foreach ( $items as $item )
{
    $t->set_var( "cart_item_id", $item->id() );
    
    $product = $item->product();
    
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
    
    $price = $product->price() * $item->count();
    
    $currency->setValue( $price );

    $sum += $price;
    
    $totalVAT += $product->vat() * $item->count();
    
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );

    $t->set_var( "cart_item_count", $item->count() );
    
    $t->set_var( "product_price", $locale->format( $currency ) );

    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $optionValues =& $item->optionValues();

    $t->set_var( "cart_item_option", "" );
    foreach ( $optionValues as $optionValue )
    {
        $option =& $optionValue->option();
        $value =& $optionValue->optionValue();
                 
        $t->set_var( "option_name", $option->name() );

        $descriptions = $value->descriptions();
        $t->set_var( "option_value", $descriptions[0] );

        // get the value price if exists
        $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup,
                                             $option->id(), $value->id() );
        
        if ( $price )
        {
            $found_price = true;
            $price = new eZCurrency( $price );
        }

        // if not fetch the standard price
        if ( !$found_price )
        {
            $price = new eZCurrency( $value->price() );
        }

        $t->parse( "cart_item_option", "cart_item_option_tpl", true );
    }


        
    $t->parse( "cart_item", "cart_item_tpl", true );
        
    $i++;
}


$currency->setValue( $sum );
$t->set_var( "cart_sum", $locale->format( $currency ) );
$currency->setValue( $totalVAT );
$t->set_var( "cart_vat_sum", $locale->format( $currency ) );

if ( count( $items ) > 0 )
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

