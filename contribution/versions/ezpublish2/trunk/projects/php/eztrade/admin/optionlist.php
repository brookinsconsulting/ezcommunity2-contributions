<?
// 
// $Id: optionlist.php,v 1.1 2000/09/20 08:31:24 bf-cvs Exp $
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
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );
include_once( $DOC_ROOT . "/classes/ezproduct.php" );
include_once( $DOC_ROOT . "/classes/ezoption.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/optionlist/",
                     $DOC_ROOT . "/admin/intl/", $Language, "optionlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "option_list_page" => "optionlist.tpl",
    "option_item" => "optionitem.tpl"
    ) );

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );

$options = $product->options();

foreach ( $options as $option )
{

    $t->parse( "option_list", "option_item", true );    
}

$t->set_var( "product_id", $ProductID );

$t->pparse( "output", "option_list_page" );

?>
