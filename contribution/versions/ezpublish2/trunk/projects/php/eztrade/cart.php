<?php
// 
// $Id: cart.php,v 1.2 2000/09/27 12:17:13 bf-cvs Exp $
//
// Definition of eZCompany class
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "ezsession/classes/ezsession.php" );


if ( $Action == "AddToBasket" )
{
    print( "add" );

    $product = new eZProduct( $ProductID );

    $options = $product->options();

    foreach ( $options as $option )
    {
        print( $option->id() . "<br>" );
        
        $optionID = $option->id();
        $tmpVar = "Option_" . $optionID;
        echo $$tmpVar . "<br>";
    }
    

    
//      foreach ( $OptionArray as $item )
//      {
//          print( $item );
        
//      }
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/cart/",
                     $DOC_ROOT . "/intl/", $Language, "cart.php" );

$t->setAllStrings();

$t->set_file( array(
    "cart_page" => "cart.tpl",
    "cart_item" => "cartitem.tpl"
    ) );

$cart = new eZCart();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

// get the cart or create it
$cart = $cart->getBySession( $session );
if ( !$cart )
{
    print( "creating a cart" );
    $cart = new eZCart();
    $cart->setSession( $session );

    $cart->store();
}

// fetch the cart items
$items = $cart->items();
if  ( $items )
{
    foreach ( $items as $item )
    {
        $product = $item->product();
        
        $t->set_var( "product_name", $product->name() );
        
        $t->parse( "cart_item_list", "cart_item", true );        
    }
} 


$t->pparse( "output", "cart_page" );

?>
