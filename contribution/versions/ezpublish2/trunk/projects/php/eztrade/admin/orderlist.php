<?
// 
// $Id: orderlist.php,v 1.4 2000/10/24 19:03:13 bf-cvs Exp $
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

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/orderlist/",
                     "eztrade/admin/intl/", $Language, "orderlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "order_list_tpl" => "orderlist.tpl",
    ) );

$t->set_block( "order_list_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_item_list_tpl", "order_item_tpl", "order_item" );

$order = new eZOrder();
$orderArray = $order->getAll();

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
$i=0;
foreach ( $orderArray as $order )
{
    if (( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
        
    $t->set_var( "order_id", $order->id() );
    $status = $order->initialStatus( );
    $dateTime = $status->altered();
    
    $t->set_var( "order_date", $locale->format( $dateTime ) );

    $status = $order->lastStatus( );
    $dateTime = $status->altered();
    
    $t->set_var( "altered_date", $locale->format( $dateTime ) );

    $statusType = $status->type();
    
    $t->set_var( "order_status", $statusType->name() );

    $currency->setValue( $order->totalPrice()  );
    $t->set_var( "order_price", $locale->format( $currency ) );
    
    
    $t->parse( "order_item", "order_item_tpl", true );
    $i++;
}

$t->parse( "order_item_list", "order_item_list_tpl" );

//  $statusType = new eZOrderStatusType( );
//  $statusType->setName( "Undefined" );
//  $statusType->store();

$t->pparse( "output", "order_list_tpl" );

?>
