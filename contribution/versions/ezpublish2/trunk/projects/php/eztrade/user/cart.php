<?php
// 
// $Id: cart.php,v 1.2 2000/10/22 10:46:20 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Sep-2000 11:57:49 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

include_once( "ezuser/classes/ezuser.php" );

$ini = new INIFIle( "site.ini" );

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
    print( "creating a cart" );
    $cart = new eZCart();
    $cart->setSession( $session );
    
    $cart->store();
}

//  $cart->delete();

if ( $Action == "AddToBasket" )
{
    print( "add<br>" );

    $product = new eZProduct( $ProductID );

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

    Header( "Location: /trade/cart/" );
    
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


// fetch the cart items
$items = $cart->items( );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
    
$i = 0;
$sum = 0.0;
foreach ( $items as $item )
{
    $product = $item->product();

    $image = $product->thumbnailImage();

    $thumbnail =& $image->requestImageVariation( 35, 35 );        

    $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
    $t->set_var( "product_image_width", $thumbnail->width() );
    $t->set_var( "product_image_height", $thumbnail->height() );
    $t->set_var( "product_image_caption", $image->caption() );

    $currency->setValue( $product->price() );

    $sum += $product->price();
    $t->set_var( "product_name", $product->name() );
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
        $t->set_var( "option_value", $value->name() );
            
        $t->parse( "cart_item_option", "cart_item_option_tpl", true );
    }
        
    $t->parse( "cart_item", "cart_item_tpl", true );
        
    $i++;
}

$shippingCost = 100.0;
$currency->setValue( $shippingCost );
$t->set_var( "shipping_cost", $locale->format( $currency ) );

$sum += $shippingCost;
$currency->setValue( $sum );
$t->set_var( "cart_sum", $locale->format( $currency ) );

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

