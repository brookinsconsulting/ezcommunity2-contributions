<?
// 
// $Id: orderedit.php,v 1.2 2000/10/03 14:09:48 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <30-Sep-2000 13:03:13 bf>
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

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezorder.php" );

include_once( "eztrade/classes/ezorderstatustype.php" );

if ( $Action == "newstatus" )
{
    $status = new eZOrderStatus();
    
    // store the status
    $statusType = new eZOrderStatusType( $StatusID );


    $status = new eZOrderStatus();
    $status->setType( $statusType );
    $status->setComment( $StatusComment );
    $status->setOrderID( $OrderID );

    $user = eZUser::currentUser();

    $status->setAdmin( $user );
    $status->store();            

    Header( "Location: /trade/orderlist/" );
    exit();
}

if ( $Action == "delete" )
{
    $order = new eZOrder( $OrderID );
    $order->delete();
    
    Header( "Location: /trade/orderlist/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/orderedit/",
                     "eztrade/admin/intl/", $Language, "orderedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "order_edit_tpl" => "orderedit.tpl",
    ) );

$t->set_block( "order_edit_tpl", "order_status_option_tpl", "order_status_option" );

$t->set_block( "order_edit_tpl", "order_status_history_tpl", "order_status_history" );

$t->set_block( "order_edit_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_item_list_tpl", "order_item_tpl", "order_item" );

$t->set_block( "order_item_tpl", "order_item_option_tpl", "order_item_option" );



$order = new eZOrder( $OrderID );

// fetch the order items
$items = $order->items( $OrderType );

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

    $t->set_var( "order_item_option", "" );
    foreach ( $optionValues as $optionValue )
    {
                 
        $t->set_var( "option_name", $optionValue->optionName() );
        $t->set_var( "option_value", $optionValue->valueName() );
            
        $t->parse( "order_item_option", "order_item_option_tpl", true );
    }
        
    $t->parse( "order_item", "order_item_tpl", true );
        
    $i++;
}

$shippingCost = 100.0;
$currency->setValue( $shippingCost );
$t->set_var( "shipping_cost", $locale->format( $currency ) );

$sum += $shippingCost;
$currency->setValue( $sum );
$t->set_var( "order_sum", $locale->format( $currency ) );

$statusType = new eZOrderStatusType();
$statusTypeArray = $statusType->getAll();
foreach ( $statusTypeArray as $status )
{
    $t->set_var( "option_name", $status->name() );
    $t->set_var( "option_id", $status->id() );
    $t->parse( "order_status_option", "order_status_option_tpl", true );
}


$historyArray = $order->statusHistory();
$i=0;
foreach ( $historyArray as $history )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    
    $statusType = $history->type();
    $t->set_var( "status_date", $locale->format( $history->altered() ) );
    $t->set_var( "status_name", $statusType->name() );
    $t->set_var( "status_comment", $history->comment() );
    $t->parse( "order_status_history", "order_status_history_tpl", true );
    $i++;
}

$t->set_var( "order_id", $order->id() );

$t->parse( "order_item_list", "order_item_list_tpl" );

$t->pparse( "output", "order_edit_tpl" );

?>
