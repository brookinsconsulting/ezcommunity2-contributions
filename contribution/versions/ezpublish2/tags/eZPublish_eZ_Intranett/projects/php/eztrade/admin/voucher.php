<?php
// 
// $Id: voucher.php,v 1.1 2001/08/02 12:05:03 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Jun-2001 11:03:23 ce>
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
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezhttptool.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

if ( isSet ( $OK ) )
{
    $Action = "Insert";
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "voucher.php" );

$t->set_file( array( "voucher_edit_tpl" => "voucher.tpl" ) );

$t->set_block( "voucher_edit_tpl", "value_tpl", "value" );
$t->set_block( "voucher_edit_tpl", "multiple_value_tpl", "multiple_value" );

$t->set_block( "voucher_edit_tpl", "vat_select_tpl", "vat_select" );
$t->set_block( "voucher_edit_tpl", "shipping_select_tpl", "shipping_select" );
$t->set_block( "voucher_edit_tpl", "quantity_item_tpl", "quantity_item" );

$t->set_block( "voucher_edit_tpl", "price_group_list_tpl", "price_group_list" );
$t->set_block( "price_group_list_tpl", "price_groups_item_tpl", "price_groups_item" );
$t->set_block( "price_groups_item_tpl", "price_group_header_item_tpl", "price_group_header_item" );
$t->set_block( "price_groups_item_tpl", "price_group_item_tpl", "price_group_item" );
$t->set_block( "price_group_list_tpl", "price_groups_no_item_tpl", "price_groups_no_item" );

$t->setAllStrings();


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezvattype.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );

if ( $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );


    if ( is_numeric( $VoucherID ) )
        $voucher = new eZProduct( $VoucherID);
    else
        $voucher = new eZProduct();
    $voucher->setName( $Name );
    $voucher->setDescription( $Description );
    $voucher->setBrief(  $Brief );

    // Mark the product as a voucher.
    $voucher->setProductType( 2 );

    $vattype = new eZVATType( $VATTypeID );
    $voucher->setVATType( $vattype );

    $shippingGroup = new eZShippingGroup( $ShippingGroupID );
    $voucher->setShippingGroup( $shippingGroup );    
    
    if ( $ShowPrice == "on" )
    {
        $voucher->setShowPrice( true );
    }
    else
    {
        $voucher->setShowPrice( false );
    }

    if ( $Active == "on" )
    {
        $voucher->setShowProduct( true );
    }
    else
    {
        $voucher->setShowProduct( false );
    }

    $voucher->setDiscontinued( $Discontinued == "on" );

    if ( $IsHotDeal == "on" )
    {
        $voucher->setIsHotDeal( true );
    }
    else
    {
        $voucher->setIsHotDeal( false );
    }
    
    $voucher->setPrice( $Price );
    
    $voucher->store();

    if ( $ShowQuantity )
    {
        $voucher->setTotalQuantity( is_numeric( $Quantity ) ? $Quantity : false );
    }

//      eZPriceGroup::removePrices( $ProductID, -1 );
    $count = max( count( $PriceGroup ), count( $PriceGroupID ) );
    for ( $i = 0; $i < $count; $i++ )
    {
        if ( is_numeric( $PriceGroupID[$i] ) and $PriceGroup[$i] != "" )
        {
            eZPriceGroup::addPrice( $voucher->id(), $PriceGroupID[$i], $PriceGroup[$i] );
        }
    }

    // add a product to the categories
    $category = new eZProductCategory( $CategoryID );
    $category->addProduct( $voucher );

    $voucher->setCategoryDefinition( $category );

    if ( count( $CategoryArray ) > 0 )
    foreach ( $CategoryArray as $categoryItem )
    {
        if ( $categoryItem != $CategoryID )
        {
            $category = new eZProductCategory( $categoryItem );
            $category->addProduct( $voucher );
        }
    }


    $voucherID = $voucher->id();

    $categoryArray = $voucher->categories();
    $categoryIDArray = array();
    foreach ( $categoryArray as $cat )
    {
        $categoryIDArray[] = $cat->id();
    }    

    if( isset( $AddItem ) )
    {
        switch( $ItemToAdd )
        {
            // add options
            case "Option":
            {
                eZHTTPTool::header( "Location: /trade/productedit/optionlist/$productID/" );
                exit();
            }
            break;

            // add images
            case "Image":
            {
                eZHTTPTool::header( "Location: /trade/productedit/imagelist/$productID/" );
                exit();
            }
            break;

            // attribute
            case "Attribute":
            {
                eZHTTPTool::header( "Location: /trade/productedit/attributeedit/$productID/" );
                exit();
            }
            break;

            // attribute
            case "ModuleLinker":
            {
                eZHTTPTool::header( "Location: /trade/productedit/link/list/$productID/" );
                exit();
            }
            break;
        }
    }
    
    // preview
    if( isset ( $Preview ))
    {
        eZHTTPTool::header( "Location: /trade/productedit/productpreview/$productID/" );
        exit();
    }

    // get the category to redirect to
    $category = $voucher->categoryDefinition( );
    $categoryID = $category->id();
    
    eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID" );
    exit();
}

if ( $Action == "Cancel" )
{
    if ( isset( $VoucherID ) )
    {
        $voucher = new eZProduct( $VoucherID );
        $category = $voucher->categoryDefinition( );
        $categoryID = $category->id();
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /trade/categorylist/parent/" );
        exit();
    }
}

               
$t->set_var( "brief_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "keywords_value", "" );
$t->set_var( "product_nr_value", "" );
$t->set_var( "price_value", "" );

$t->set_var( "showprice_checked", "" );
$t->set_var( "showproduct_checked", "" );
$t->set_var( "discontinued_checked", "" );
$t->set_var( "is_hot_deal_checked", "" );

$t->set_var( "external_link", "" );

$t->set_var( "action_value", "insert" );


$VatType = false;
// edit
if ( $Action == "Edit" )
{
    $voucher = new eZProduct();
    $voucher->get( $voucherID );

    $t->set_var( "name_value", $voucher->name() );
    $t->set_var( "keywords_value", $voucher->keywords() );
    $t->set_var( "product_nr_value", $voucher->productNumber() );
    $t->set_var( "price_value", $voucher->price() );
    $t->set_var( "brief_value", $voucher->brief() );
    $t->set_var( "description_value", $voucher->description() );
    
    $t->set_var( "external_link", $voucher->externalLink() );
    
    $t->set_var( "action_value", "update" );
    $t->set_var( "product_id", $voucher->id() );

    if ( $voucher->showPrice() == true )
        $t->set_var( "showprice_checked", "checked" );

    if ( $voucher->showProduct() == true )
        $t->set_var( "showproduct_checked", "checked" );

    if ( $voucher->discontinued() == true )
        $t->set_var( "discontinued_checked", "checked" );

    if ( $voucher->isHotDeal() == true )
        $t->set_var( "is_hot_deal_checked", "checked" );

    $VatType =& $voucher->vatType();

    $Quantity = $voucher->totalQuantity();

    $prices = eZPriceGroup::prices( $voucherID );
    $PriceGroup = array();
    $PriceGroupID = array();
    foreach( $prices as $price )
    {
        $PriceGroup[] = $price["Price"];
        $PriceGroupID[] = $price["PriceID"];
    }
    $VatType =& $voucher->vatType();    
    $ShippingGroup =& $voucher->shippingGroup();    
}

$category = new eZProductCategory();
$categoryArray = $category->getTree( );

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        $defCat = $voucher->categoryDefinition( );
        
        if ( $voucher->existsInCategory( $catItem[0] ) &&
             ( $defCat->id() != $catItem[0]->id() ) )
        {
            $t->set_var( "multiple_selected", "selected" );
        }
        else
        {
            $t->set_var( "multiple_selected", "" );
        }

        if ( $defCat->id() == $catItem[0]->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
        $t->set_var( "multiple_selected", "" );
    }    
    
//      if ( $Action == "Edit" )
//      {
//          if ( $voucher->existsInCategory( $catItem ) )
//              $t->set_var( "selected", "selected" );
//          else
//              $t->set_var( "selected", "" );
//      }
//      else
//      {
//              $t->set_var( "selected", "" );
//      }
    
    $t->set_var( "option_value", $catItem[0]->id() );
    $t->set_var( "option_name", $catItem[0]->name() );

    if ( $catItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $t->parse( "value", "value_tpl", true );
    $t->parse( "multiple_value", "multiple_value_tpl", true );    
}

// show the VAT values

$vat = new eZVATType();
$vatTypes = $vat->getAll();

foreach ( $vatTypes as $type )
{
    if ( $VatType  and  ( $VatType->id() == $type->id() ) )
    {
        $t->set_var( "vat_selected", "selected" );
    }
    else
    {
        $t->set_var( "vat_selected", "" );
    }
        
    $t->set_var( "vat_id", $type->id() );
    $t->set_var( "vat_name", $type->name() . " (" . $type->value() . ")%" );

    $t->parse( "vat_select", "vat_select_tpl", true );
}

// show shipping groups

$group = new eZShippingGroup();

$groups =& $group->getAll();

foreach ( $groups as $group )
{
    if ( $ShippingGroup and $ShippingGroup->id() == $group->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->set_var( "shipping_group_id", $group->id() );
    
    $t->set_var( "shipping_group_name", $group->name() );

    $t->parse( "shipping_select", "shipping_select_tpl", true );
}

// Show quantity
$t->set_var( "quantity_item", "" );
$t->set_var( "quantity_value", $Quantity );
if ( $ShowQuantity )
{
    $t->parse( "quantity_item", "quantity_item_tpl" );
}

// Show price groups

$t->set_var( "price_group_list", "" );
$t->set_var( "price_groups_item", "" );
$t->set_var( "price_groups_no_item", "" );

if ( $ShowPriceGroups )
{
    $price_groups = eZPriceGroup::getAll();
    $count = max( count( $PriceGroup ), count( $PriceGroupID ) );
    $NewPriceGroup = array();
    for ( $i = 0; $i < $count; $i++ )
    {
        $NewPriceGroup[$PriceGroupID[$i]] = $PriceGroup[$i];
    }
    $prices = array();
    $price_ids = array();
    $price_names = array();
    foreach( $price_groups as $price_group )
    {
        $price_id = $price_group->id();
        $prices[] = $NewPriceGroup[$price_id];
        $price_ids[] = $price_id;
        $price_names[] = $price_group->name();
    }
    $PriceGroup = $prices;
    $PriceGroupID = $price_ids;
    $t->set_var( "price_group_header_item", "" );
    $t->set_var( "price_group_item", "" );
    for ( $i = 0; $i < count( $PriceGroup ); $i++ )
    {
        $t->set_var( "price_group_name", $price_names[$i] );
        $t->parse( "price_group_header_item", "price_group_header_item_tpl", true );

        $t->set_var( "price_group_value", $PriceGroup[$i] );
        $t->set_var( "price_group_id", $PriceGroupID[$i] );
        $t->parse( "price_group_item", "price_group_item_tpl", true );
    }
    if ( count( $price_groups ) > 0 )
    {
        $t->parse( "price_groups_item", "price_groups_item_tpl" );
        $t->parse( "price_group_list", "price_group_list_tpl" );
    }
//    else
//        $t->parse( "price_groups_no_item", "price_groups_no_item_tpl" );
}

    if ( $ShippingGroup  and  ( $ShippingGroup->id() == $group->id() ) )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    
    $t->set_var( "shipping_group_id", $group->id() );
    
    $t->set_var( "shipping_group_name", $group->name() );


$t->pparse( "output", "voucher_edit_tpl" );

?>
