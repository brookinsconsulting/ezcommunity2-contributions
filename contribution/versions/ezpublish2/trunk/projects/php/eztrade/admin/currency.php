<?
// 
// $Id: currency.php,v 1.2 2001/02/26 11:57:24 bf Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <23-Feb-2001 16:27:56 bf>
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
include_once( "classes/ezhttptool.php" );

include_once( "eztrade/classes/ezproductcurrency.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZTradeMain", "Language" );


if ( $Action == "Store" )
{
    $i=0;
    foreach ( $CurrencyID as $id )
    {
        $str = "CurrencyPrefix_" . $id;
        $prefixArray = $$str;
        
        $currency = new eZProductCurrency( $id );
        $currency->setName( $CurrencyName[$i] );
        $currency->setSign( $CurrencySign[$i] );
        $currency->setValue( $CurrencyValue[$i] );
        
        if ( $prefixArray[0] == 1 )
            $currency->setPrefixSign( true );
        else
            $currency->setPrefixSign( false );
            
        $currency->store();
        $i++;
    }

}

if ( $Action == "AddCurrency" )
{
    $currency = new eZProductCurrency( );
    $currency->store();
}


if ( $Action == "DeleteSelected" )
{
    foreach ( $DeleteID as $id )
    {
        $currency = new eZProductCurrency( $id );
        $currency->delete();        
    }
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "currency.php" );

$t->setAllStrings();

$t->set_file( array( "currency_tpl" => "currency.tpl" ) );

$t->set_block( "currency_tpl", "currency_item_tpl", "currency_item" );



$currency = new eZProductCurrency( );
$currencies =& $currency->getAll();

$i=0;
foreach ( $currencies as $currency )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->set_var( "currency_id", $currency->id() );
    $t->set_var( "currency_name", $currency->name() );
    $t->set_var( "currency_sign", $currency->sign() );
    $t->set_var( "currency_value", $currency->value() );
    
    if ( $currency->prefixSign() )
    {
        $t->set_var( "currency_prefixed", "checked" );
        $t->set_var( "currency_not_prefixed", "" );
    }
    else
    {
        $t->set_var( "currency_prefixed", "" );
        $t->set_var( "currency_not_prefixed", "checked" );
    }
    
    $t->parse( "currency_item", "currency_item_tpl", true );
    $i++;
}



$t->pparse( "output", "currency_tpl" );

?>
