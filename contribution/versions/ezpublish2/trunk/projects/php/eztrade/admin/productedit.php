<?
// 
// $Id: productedit.php,v 1.2 2000/09/20 12:58:04 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Sep-2000 10:56:05 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproduct.php" );
include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );


if ( $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );


    $product = new eZProduct();
    $product->setName( $Name );
    $product->setDescription( $Description );
    $product->setBrief( $Brief );
    $product->setKeywords( $Keywords );
    $product->setProductNumber( $ProductNumber );

    if ( $ShowPrice == "on" )
    {
        $product->setShowPrice( true );
    }
    else
    {
        $product->setShowPrice( false );
    }

    if ( $Active == "on" )
    {
        $product->setShowProduct( true );
    }
    else
    {
        $product->setShowProduct( false );
    }

    if ( $InheritOptions == "on" )
    {
        $product->setInheritOptions( true );
    }
    else
    {
        $product->setInheritOptions( false );
    }
    
    $product->setPrice( $Price );
    
    $product->store();

    $parentCategory->addProduct( $product );

    $productID = $product->id();
    // add options
    if ( isset( $Option ) )
    {
        Header( "Location: /trade/productedit/optionlist/$productID/" );
        exit();
    }

    Header( "Location: /trade/categorylist/" );
    exit();
}

if ( $Action == "Update" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );

    $product = new eZProduct();
    $product->get( $ProductID );
    $product->setName( $Name );
    $product->setDescription( $Description );
    $product->setBrief( $Brief );
    $product->setKeywords( $Keywords );
    $product->setProductNumber( $ProductNumber );

    if ( $ShowPrice == "on" )
    {
        $product->setShowPrice( true );
    }
    else
    {
        $product->setShowPrice( false );
    }

    if ( $Active == "on" )
    {
        $product->setShowProduct( true );
    }
    else
    {
        $product->setShowProduct( false );
    }

    if ( $InheritOptions == "on" )
    {
        $product->setInheritOptions( true );
    }
    else
    {
        $product->setInheritOptions( false );
    }
    
    $product->setPrice( $Price );
    
    $product->store();

    $productID = $product->id();
    // add options
    if ( isset( $Option ) )
    {
        Header( "Location: /trade/productedit/optionlist/$productID/" );
        exit();
    }
    
    Header( "Location: /trade/categorylist/" );
    exit();
}

if ( $Action == "Delete" )
{
    $product = new eZProduct();
    $product->get( $ProductID );
    $product->delete();    
    
    Header( "Location: /trade/categorylist/" );
    exit();
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/productedit/",
                     $DOC_ROOT . "/admin/intl/", $Language, "productedit.php" );

$t->setAllStrings();

$t->set_file( array( "product_edit_page" => "productedit.tpl",
                      "option_item" => "optionitem.tpl") );

$t->set_var( "brief_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "keywords_value", "" );
$t->set_var( "product_nr_value", "" );

$t->set_var( "showprice_checked", "" );
$t->set_var( "active_checked", "" );
$t->set_var( "inherit_options_checked", "" );

$t->set_var( "action_value", "insert" );

// edit
if ( $Action == "Edit" )
{
    $product = new eZProduct();
    $product->get( $ProductID );
    
    $t->set_var( "name_value", $product->name() );
    $t->set_var( "keywords_value", $product->keywords() );
    $t->set_var( "product_nr_value", $product->productNumber() );
    $t->set_var( "price_value", $product->price() );
    $t->set_var( "brief_value", $product->brief() );
    $t->set_var( "description_value", $product->description() );
    $t->set_var( "action_value", "update" );
    $t->set_var( "product_id", $product->id() );

    if ( $product->showPrice() == true )
        $t->set_var( "showprice_checked", "checked" );

    if ( $product->showProduct() == true )
        $t->set_var( "active_checked", "checked" );

    if ( $product->inheritOptions() == true )
        $t->set_var( "inherit_options_checked", "checked" );
}

$category = new eZProductCategory();
$categoryArray = $category->getAll( );

foreach ( $categoryArray as $catItem )
{
    $t->set_var( "option_value", $catItem->id() );
    $t->set_var( "option_name", $catItem->name() );

    $t->parse( "option_values", "option_item", true );    
}


$t->pparse( "output", "product_edit_page" );

?>
