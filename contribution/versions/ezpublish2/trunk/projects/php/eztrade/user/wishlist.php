<?php
// 
// $Id: wishlist.php,v 1.1 2000/10/22 10:33:23 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Oct-2000 18:09:45 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

print( "wishlist" );

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
include_once( "eztrade/classes/ezwishlist.php" );
include_once( "eztrade/classes/ezwishlistitem.php" );
include_once( "eztrade/classes/ezwishlistoptionvalue.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );


$wishlist = new eZWishlist();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

$user = eZUser::currentUser();

if ( !$user )
{
    print( "You must be a user for adding products to the wishlist.. Create one? >> add redirect." );
    print( "bf@ez.no <- fix :)." );
}

$wishlist = $wishlist->getByUser( $user );

if ( !$wishlist )
{
    print( "creating a wishlist" );
    $wishlist = new eZWishlist();
    $wishlist->setUser( $user );
    
    $wishlist->store();
}

//  $wishlist->delete();

if ( $Action == "AddToBasket" )
{
    print( "add<br>" );

    $product = new eZProduct( $ProductID );

    $wishlistItem = new eZWishlistItem();
    
    $wishlistItem->setProduct( $product );
    $wishlistItem->setWishlist( $wishlist );

    $wishlistItem->store();

    if ( count( $OptionValueArray ) > 0 )
    {
        $i = 0;
        foreach ( $OptionValueArray as $value )
        {
            
            $option = new eZOption( $OptionIDArray[$i] );
            $optionValue = new eZOptionValue( $value );
        
            $wishlistOption = new eZWishlistOptionValue();
            $wishlistOption->setWishlistItem( $wishlistItem );
            $wishlistOption->setOption( $option );
            $wishlistOption->setOptionValue( $optionValue );

            $wishlistOption->store();
        
            $i++;
        }
    }

    Header( "Location: /trade/wishlist/" );
    
    exit();
}

$t = new eZTemplate( "eztrade/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/intl/", $Language, "wishlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "wishlist_page_tpl" => "wishlist.tpl"
    ) );


$t->set_block( "wishlist_page_tpl", "empty_wishlist_tpl", "empty_wishlist" );


$t->set_block( "wishlist_page_tpl", "wishlist_item_list_tpl", "wishlist_item_list" );
$t->set_block( "wishlist_item_list_tpl", "wishlist_item_tpl", "wishlist_item" );
$t->set_block( "wishlist_item_tpl", "wishlist_item_option_tpl", "wishlist_item_option" );

// fetch the wishlist items
$items = $wishlist->items( );

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

    $t->set_var( "wishlist_item_option", "" );
    foreach ( $optionValues as $optionValue )
    {
        $option =& $optionValue->option();
        $value =& $optionValue->optionValue();
                 
        $t->set_var( "option_name", $option->name() );
        $t->set_var( "option_value", $value->name() );
            
        $t->parse( "wishlist_item_option", "wishlist_item_option_tpl", true );
    }
        
    $t->parse( "wishlist_item", "wishlist_item_tpl", true );
        
    $i++;
}

$shippingCost = 100.0;
$currency->setValue( $shippingCost );
$t->set_var( "shipping_cost", $locale->format( $currency ) );

$sum += $shippingCost;
$currency->setValue( $sum );
$t->set_var( "wishlist_sum", $locale->format( $currency ) );


if ( count( $items ) > 0 )
{
    $t->parse( "wishlist_item_list", "wishlist_item_list_tpl" );
    $t->set_var( "empty_wishlist", "" );    
}
else
{
    $t->parse( "empty_wishlist", "empty_wishlist_tpl" );    
    $t->set_var( "wishlist_item_list", "" );
}


$t->pparse( "output", "wishlist_page_tpl" );

?>

