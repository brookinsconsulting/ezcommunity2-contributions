<?php
//
// $Id: wishlist.php,v 1.9 2000/12/19 12:19:52 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Oct-2000 18:09:45 bf>
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
    Header( "Location: /trade/customerlogin/?RedirectURL=/trade/wishlist/" );
    exit();
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


    // check if a product like this is already in the basket.
    // if so-> add the count value.

    $productAddedToWishlist = false;
    {
        // fetch the cart items
        $items = $wishlist->items( );

        foreach ( $items as $item )
        {
            $productItem =  $item->product();
            // the same product
            if ( ( $ProductID == $productItem->id() ) && ( $productAddedToWishlist == false ) )
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
                        $productAddedToWishlist = true;
                    }
                }
                else
                { // product without options
                    if ( count( $OptionValueArray ) == 0 )
                    {
                        $item->setCount( $item->count() + 1 );
                        $item->store();
                        $productAddedToWishlist = true;
                    }
                }
            }
        }
    }
    
    if ( $productAddedToWishlist == false )
    {
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
    }

    Header( "Location: /trade/wishlist/" );

    exit();
}

if ( $Action == "MoveToCart" )
{
    $wishListItem = new eZWishListItem( );
    if ( $wishListItem->get( $WishListItemID ) )
    {
        $wishListItem->moveToCart();
        $wishListItem->delete();
    }

    Header( "Location: /trade/wishlist/" );
    exit();
}

if ( $Action == "RemoveFromWishlist" )
{
    $wishListItem = new eZWishListItem( );
    if ( $wishListItem->get( $WishListItemID ) )
    {
        $wishListItem->delete();
    }

    Header( "Location: /trade/wishlist/" );

    exit();
}


if ( $Action == "Refresh" )
{
    $i=0;
    if ( count( $WishlistIDArray ) > 0 )
    foreach ( $WishlistIDArray as $wishlistID )
    {
        $wishlistItem = new eZWishlistItem( $wishlistID );
        $wishlistItem->setCount( $WishlistCountArray[$i] );
        $wishlistItem->store();
        $i++;
    }
}


$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "wishlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "wishlist_page_tpl" => "wishlist.tpl"
    ) );


$t->set_block( "wishlist_page_tpl", "empty_wishlist_tpl", "empty_wishlist" );

$t->set_block( "wishlist_page_tpl", "wishlist_image_tpl", "wishlist_image" );


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

    $t->set_var( "wishlist_item_id", $item->id() );

    $image = $product->thumbnailImage();

    if ( $image )
    {
        $thumbnail =& $image->requestImageVariation( 35, 35 );

        $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
        $t->set_var( "product_image_width", $thumbnail->width() );
        $t->set_var( "product_image_height", $thumbnail->height() );
        $t->set_var( "product_image_caption", $image->caption() );
        $t->parse( "wishlist_image", "wishlist_image_tpl" );
    }
    else
    {
        $t->set_var( "wishlist_image", "&nbsp;" );
    }

    $currency->setValue( $product->price() * $item->count() );

    $sum += $product->price();
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );

    $t->set_var( "wishlist_item_count", $item->count() );
    
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

$shippingCost = $ini->read_var( "eZTradeMain", "ShippingCost" );

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

