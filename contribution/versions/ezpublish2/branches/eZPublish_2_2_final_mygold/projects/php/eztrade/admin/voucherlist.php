<?php
// 
// $Id: voucherlist.php,v 1.2.4.2 2002/04/16 10:30:46 ce Exp $
//
// Created on: <20-Dec-2000 18:18:28 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezvoucher.php" );

if( isset( $Delete ) )
{
    foreach( $DeleteArrayID as $voucherID )
    {
        $voucher = new eZVoucher( $voucherID );
        $voucher->delete();
    }
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "voucherlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "voucher_list_page_tpl" => "voucherlist.tpl"
    ) );


// voucher
$t->set_block( "voucher_list_page_tpl", "voucher_list_tpl", "voucher_list" );
$t->set_block( "voucher_list_tpl", "voucher_item_tpl", "voucher_item" );
$t->set_block( "voucher_item_tpl", "voucher_is_available_tpl", "voucher_is_available" );
$t->set_block( "voucher_item_tpl", "voucher_is_not_available_tpl", "voucher_is_not_available" );

// next prvious
$t->set_block( "voucher_list_tpl", "previous_tpl", "previous" );
$t->set_block( "voucher_list_tpl", "next_tpl", "next" );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "search",  $QueryText );

if ( isSet( $URLQueryText ) )
{
    $QueryText = urldecode( $URLQueryText );
}

    
$t->set_var( "url_query_string", $QueryText );
    
$t->set_var( "previous", "" );
$t->set_var( "next", "" );
    
if ( !isSet( $OrderBy ) )
    $OrderBy = "Date";
	
if ( !isSet( $Offset ) )
    $Offset = 0;
	    
if ( !isSet( $Limit ) )
    $Limit = 15;
		
$t->set_var( "current_offset", $Offset );
		

$t->set_var( "site_style", $SiteStyle );

if ( isSet( $QueryText ) )
{
    $voucherlist = eZVoucher::search( $QueryText, $Offset, $Limit );
    $total_count = eZVoucher::getSearchCount( $QueryText );
}
else
{
    $voucherlist = eZVoucher::getAll( $Limit, $Offset );
    $total_count = eZVoucher::count();
}

// categories
$i=0;
$t->set_var( "voucher_list", "" );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

foreach ( $voucherlist as $voucherItem )
{
    $voucherInfo =& $voucherItem->information();
    $t->set_var( "voucher_key", $voucherItem->keyNumber() );
    $t->set_var( "voucher_receiver", $voucherInfo->toName() );
    $t->set_var( "voucher_id", $voucherItem->id() );

    $t->set_var( "voucher_created", $locale->format( $voucherItem->created() ) );

    $currency->setValue( $voucherItem->price() );
    $t->set_var( "voucher_price", $locale->format( $currency ) );

    $currency->setValue( $voucherItem->price() );
    $t->set_var( "voucher_price", $locale->format( $currency ) );

    if ( $voucherItem->isAvailable() )
    {
        $t->set_var( "voucher_is_not_available", "" );
        $t->parse( "voucher_is_available", "voucher_is_available_tpl" );
    }
    else
    {
        $t->set_var( "voucher_is_available", "" );
        $t->parse( "voucher_is_not_available", "voucher_is_not_available_tpl" );
    }

    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    
    $t->parse( "voucher_item", "voucher_item_tpl", true );
    $i++;
}

if ( count( $voucherlist ) > 0 )    
    $t->parse( "voucher_list", "voucher_list_tpl" );
else
    $t->set_var( "voucher_list", "" );


eZList::drawNavigator( $t, $total_count, $Limit, $Offset, "voucher_list_page_tpl" );


$t->pparse( "output", "voucher_list_page_tpl" );

?>
