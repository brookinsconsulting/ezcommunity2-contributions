<?php
// 
// $Id: productedit.php,v 1.15 2000/10/29 10:24:25 bf-cvs Exp $
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

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );


if ( $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );


    $product = new eZProduct();
    $product->setName( strip_tags( $Name ) );
    $product->setDescription( strip_tags( $Description ) );
    $product->setBrief( strip_tags( $Brief ) );
    $product->setKeywords( strip_tags( $Keywords ) );
    $product->setProductNumber( strip_tags( $ProductNumber ) );

    $product->setExternalLink( strip_tags( $ExternalLink ) );

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

    // add a product to the category
    $parentCategory->addProduct( $product );

    $productID = $product->id();
    
    // add options
    if ( isset( $Option ) )
    {
        Header( "Location: /trade/productedit/optionlist/$productID/" );
        exit();
    }

    // add images
    if ( isset( $Image ) )
    {
        Header( "Location: /trade/productedit/imagelist/$productID/" );
        exit();
    }
    
    // preview
    if ( isset( $Preview ) )
    {
        Header( "Location: /trade/productedit/productpreview/$productID/" );
        exit();
    }

    // get the category to redirect to
    $categories = $product->categories();    
    $categoryID = $categories[0]->id();
    
    Header( "Location: /trade/categorylist/parent/$categoryID" );
    exit();
}

if ( $Action == "Update" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );

    $product = new eZProduct();
    $product->get( $ProductID );
    $product->setName( strip_tags( $Name ) );
    $product->setDescription( strip_tags( $Description ) );
    $product->setBrief( strip_tags( $Brief ) );
    $product->setKeywords( strip_tags( $Keywords ) );
    $product->setProductNumber( strip_tags( $ProductNumber  ) );

    $product->setExternalLink( strip_tags( $ExternalLink ) );
    
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

    // remove current category assignments and add a product to the category
    $product->removeFromCategories();
    $parentCategory->addProduct( $product );


    // add options
    if ( isset( $Option ) )
    {
        Header( "Location: /trade/productedit/optionlist/$productID/" );
        exit();
    }

    // add images
    if ( isset( $Image ) )
    {
        Header( "Location: /trade/productedit/imagelist/$productID/" );
        exit();
    }
    
    // preview
    if ( isset( $Preview ) )
    {
        Header( "Location: /trade/productedit/productpreview/$productID/" );
        exit();
    }

    // get the category to redirect to
    $categories = $product->categories();    
    $categoryID = $categories[0]->id();
    
    Header( "Location: /trade/categorylist/parent/$categoryID" );
    exit();
}

if ( $Action == "Cancel" )
{
    print( "id:" .$ProductID );
    $product = new eZProduct( $ProductID );

    $categories = $product->categories();

    $categoryID = $categories[0]->id();

    Header( "Location: /trade/categorylist/parent/$categoryID" );
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

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/productedit/",
                     "eztrade/admin/intl/", $Language, "productedit.php" );


$t->set_file( array( "product_edit_tpl" => "productedit.tpl" ) );

$t->set_block( "product_edit_tpl", "value_tpl", "value" );


$t->setAllStrings();
               
$t->set_var( "brief_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "keywords_value", "" );
$t->set_var( "product_nr_value", "" );
$t->set_var( "price_value", "" );

$t->set_var( "showprice_checked", "" );
$t->set_var( "showproduct_checked", "" );
$t->set_var( "inherit_options_checked", "" );

$t->set_var( "external_link", "" );

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
    
    $t->set_var( "external_link", $product->externalLink() );
    
    $t->set_var( "action_value", "update" );
    $t->set_var( "product_id", $product->id() );

    if ( $product->showPrice() == true )
        $t->set_var( "showprice_checked", "checked" );

    if ( $product->showProduct() == true )
        $t->set_var( "showproduct_checked", "checked" );

    if ( $product->inheritOptions() == true )
        $t->set_var( "inherit_options_checked", "checked" );
}

$category = new eZProductCategory();
$categoryArray = $category->getAll( );

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        if ( $product->existsInCategory( $catItem ) )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
    }
    else
    {
            $t->set_var( "selected", "" );
    }
    
    $t->set_var( "option_value", $catItem->id() );
    $t->set_var( "option_name", $catItem->name() );

    $t->parse( "value", "value_tpl", true );    
}


$t->pparse( "output", "product_edit_tpl" );

?>
