<?php
// 
// $Id: checkout.php,v 1.4 2000/10/03 09:45:18 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Sep-2000 15:52:08 bf>
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "eztrade/classes/ezorder.php" );
include_once( "eztrade/classes/ezorderitem.php" );
include_once( "eztrade/classes/ezorderoptionvalue.php" );

include_once( "ezsession/classes/ezsession.php" );


$cart = new eZCart();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

// get the cart or create it
$cart = $cart->getBySession( $session, "Cart" );
if ( !$cart )
{
    print( "ERROR: no cart." );
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/checkout/",
                     $DOC_ROOT . "/intl/", $Language, "checkout.php" );

$t->setAllStrings();

$t->set_file( array(
    "checkout_tpl" => "checkout.tpl"
    ) );

//  $t->set_block( "cart_page", "cart_header_tpl", "cart_header" );


// create a new order
$order = new eZOrder();
$user = eZUser::currentUser();
$order->setUser( $user );
$order->setAddress( 42 );
$order->setShippingCharge( 120.0 );
$order->store();

// fetch the cart items
$items = $cart->items( $CartType );

foreach ( $items as $item )
{
    $product = $item->product();
    print( $product->name() . "<br>" );

    // create a new order item
    $orderItem = new eZOrderItem();
    $orderItem->setOrder( $order );
    $orderItem->setProduct( $product );
    $orderItem->setCount( $item->count() );
    $orderItem->setPrice( $product->price() );
    $orderItem->store();

    $optionValues =& $item->optionValues();

    $t->set_var( "cart_item_option", "" );
    foreach ( $optionValues as $optionValue )
    {
        $option =& $optionValue->option();
        $value =& $optionValue->optionValue();

        $orderOptionValue = new eZOrderOptionValue();
        $orderOptionValue->setOrderItem( $orderItem );
        $orderOptionValue->setOptionName( $option->name() );
        $orderOptionValue->setValueName( $value->name() );
        $orderOptionValue->store();
        
        print( "&nbsp;&nbsp;" . $option->name() . " " . $value->name() . "<br>");
    }    
}

$cart->clear();

$t->pparse( "output", "checkout_tpl" );

?>
