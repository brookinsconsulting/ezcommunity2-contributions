<?
// 
// $Id: optionlist.php,v 1.7 2000/11/01 09:11:11 ce-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Sep-2000 10:18:33 bf>
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
include_once( "eztrade/classes/ezoption.php" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/optionlist/",
                     "eztrade/admin/intl/", $Language, "optionlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "option_list_tpl" => "optionlist.tpl"
    ) );

$t->set_block( "option_list_tpl", "option_tpl", "option" );

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );

$options = $product->options();

if ( !$options )
{
    $noitem = new INIFIle( "eztrade/admin/intl/" . $Language . "/optionlist.php.ini", false );
    $t->set_var( "option", $noitem->read_var( "strings", "no_option" ) );

}

$i=0;
foreach ( $options as $option )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    $t->set_var( "option_name", $option->name() );
    $t->set_var( "option_id", $option->id() );
    $t->set_var( "product_id", $ProductID );

    $t->parse( "option", "option_tpl", true );
    $i++;
}

$t->set_var( "product_id", $ProductID );

$t->pparse( "output", "option_list_tpl" );

?>
