<?php
//
// $Id: orderstats.php,v 1.1.2.1 2002/04/16 10:44:08 ce Exp $
//
// Created on: <19-Sep-2000 10:56:05 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ), 
                     "eztrade/admin/intl/", $Language, "orderstats.php" );
		     
$t->set_file( "orderstats_tpl", "orderstats.tpl" );
		     
$t->setAllStrings();
    
$t->set_block( "orderstats_tpl", "std_output_tpl", "std_output" );		     
$t->set_block( "std_output_tpl", "row_tpl", "row" );
$t->set_block( "std_output_tpl", "no_data_tpl", "no_data" );		     
$t->set_block( "orderstats_tpl", "get_by_month_tpl", "get_by_month" );		     
$t->set_block( "orderstats_tpl", "cumu_output_tpl", "cumu_output" );
$t->set_block( "cumu_output_tpl", "cumulated_tpl", "cumulated" );

$t->set_var( "checked", "" );

// Start Create Selection by Month
//
$i = 7;    //start stat month;
$j = 2001; //start stat year;
$by_month = array();

while( mktime( 0,0,0,$i,1,$j ) < mktime() ):
    $by_month[] = date("m/y", mktime( 1,1,1,$i,1,$j ) );
    if ( $i % 12 == 0 )
    {
        $j++;
        $i = 0;
    }
    $i++;
endwhile;

$i = 1;
foreach ( $by_month as $month )
{
    if ( substr( $month, 0, 2 ) % 12 == 0  )
        $t->set_var( "tr", "</tr><tr>" );
    else
        $t->set_var( "tr", "" );
    $t->set_var( "by_month", $month );
    $t->set_var( "by_month_link", urlencode( $month ) );
    $t->parse( "get_by_month", "get_by_month_tpl", true );    
    $i++;
}
// 
// End Create Selection by Month

if ( isset( $ByMonth ) )
{
    $month_to_show = urldecode( $ByMonth );
    $StartDay = 1; 
    $StartMonth = substr( $month_to_show, 0, 2 ); 
    $StartYear = "20".substr( $month_to_show, 3, 2 );
    $EndDay = 31;
    $EndMonth = substr( $month_to_show, 0, 2 );
    $EndYear = "20".substr( $month_to_show, 3, 2 );
}

if ( !isset( $StartDay ) )
    $StartDay = 1;
if ( !isset( $StartMonth ) )
    $StartMonth = date("n");
if ( !isset( $StartYear ) )
    $StartYear = date("Y");
if ( !isset( $EndDay ) )
    $EndDay = date("d");
if ( !isset( $EndMonth ) )
    $EndMonth = date("n");
if ( !isset( $EndYear ) )
    $EndYear = date("Y");

makeSelect( 1, 31, "start_day", $StartDay, 1, $t );
makeSelect( 1, 31, "end_day", $EndDay, date("d"), $t );
makeSelect( 1, 12, "start_month", $StartMonth, date("n"), $t );
makeSelect( 1, 12, "end_month", $EndMonth, date("n"), $t );
makeSelect( 2001, date("Y"), "start_year", $StartYear, date("Y"), $t );
makeSelect( 2001, date("Y"), "end_year", $EndYear, date("Y"), $t );

$start = mktime( 0,0,0, $StartMonth, $StartDay, $StartYear );
$end   = mktime( 23,59,59, $EndMonth, $EndDay, $EndYear );

$orders = new eZOrder;
$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$all_orders = $orders->getAll( $offset=0, $limit= $orders->getTotalCount() );

if ( !isset( $ShowCumulated ) )
    $ShowCumulated == false;
    
    
if ( $ShowCumulated == true )
{
    $t->set_var( "checked", "checked" );
    $recent_orders = array();
    $i = 0;
    foreach( $all_orders as $order )
    {
	$datetime  = $order->date();
        if ( $datetime->timestamp() >= $start and $datetime->timestamp() <= $end )
	{
	    $recent_orders[$i]["Date"] = $datetime->day().".".$datetime->month().".".$datetime->year();
    	    $recent_orders[$i]["Amount"] = $order->totalPriceIncVAT() + $order->shippingCharge();
	    $i++;
        }    
    }

    $order_array = array();
    for ( $i = 0; $i < count( $recent_orders ); $i++)
    {
	if ( $i > 0 and $recent_orders[$i]["Date"] == $recent_orders[$i-1]["Date"] )
	{
	    $order_array[$recent_orders[$i]["Date"]] += $recent_orders[$i]["Amount"];
	}
	else
	{
    	    $order_array[$recent_orders[$i]["Date"]] = $recent_orders[$i]["Amount"];
	}
    }
    
    $total = array_sum( $order_array );
    
    $i = 0;
    foreach( $order_array as $key => $value )
    {
        $i % 2 == 0 ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
	$currency->setValue( $value );
        $t->set_var( "amount", $locale->format( $currency ) );
        $t->set_var( "period", $key );
	
	if ( round( $value/$total, 2 ) * 100 == 0 )
	    $width = 1;
	else
	    $width = round( $value/$total, 2 ) * 100;
	
	$t->set_var( "width", $width );
	$t->parse( "cumulated", "cumulated_tpl", true );
	$i++;
    }
    $currency->setValue( $total );
    $t->set_var( "order_sum", $locale->format( $currency ) );
    $t->parse( "cumu_output", "cumu_output_tpl" );
    $t->set_var( "std_output", "" );
}		
else
{
    $ordersum = 0;
    $i = 0;
    foreach ( $all_orders as $order )
    {
	$datetime = $order->date();
        if ( $datetime->timestamp() >= $start and $datetime->timestamp() <= $end )
	{
    	    $i % 2 == 0 ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
	    $user = new eZUser( $order->UserID );
	    $t->set_var( "count", $i+1 );
    	    $t->set_var( "user_id", $user->id() );
	    $t->set_var( "surname", $user->firstname() );
	    $t->set_var( "lastname", $user->lastName() );
	    $orderstats = new eZOrder( $order->id() );
	    $t->set_var( "id", $orderstats->id() );
	    $t->set_var( "date", $locale->format( $datetime ) );
	    $currency->setValue( $orderstats->totalPriceIncVAT() + $orderstats->shippingCharge() );
	    $t->set_var( "price", $locale->format( $currency ) );
	    $ordersum += $orderstats->totalPriceIncVAT() + $orderstats->shippingCharge();
	    $t->parse( "row", "row_tpl", true );
	    $i++;
	    $t->set_var( "no_data", "" );
	}
    
	if ($i == 0 )
	{
	    $t->parse( "no_data", "no_data_tpl" );
	    $t->set_var( "std_output", "" );
	}
    }
    $t->set_var( "cumu_output", "" );    
    $t->parse( "std_output", "std_output_tpl" );
}
/*
echo "<pre>";
print_r( $order_array );
echo "</pre>";
*/
$currency->setValue( $ordersum );
$t->set_var( "order_sum", $locale->format( $currency ) );

$t->pparse( "out", "orderstats_tpl" );

function makeSelect( $start, $end, $tpl_name, $form_name, $selected, $t )
{
    global $t;
    $t->set_block( "orderstats_tpl", $tpl_name."_tpl", $tpl_name );
    
    for ( $i = $start; $i <= $end; $i++ )
    {
	$t->set_var( $tpl_name."_value", $i );
	$t->set_var( $tpl_name."_name", $i );
    
	if ( isset( $form_name ) )
    	    if ( $i == $form_name )
		$t->set_var( "selected", "selected" );
	    else
		$t->set_var( "selected", "" );
	else
    	    if ( $i == $selected )
        	$t->set_var( "selected", "selected" );
    	    else
		$t->set_var( "selected", "" );    
    
	$t->parse( $tpl_name, $tpl_name."_tpl", true );
    }	   
}										    

?>
