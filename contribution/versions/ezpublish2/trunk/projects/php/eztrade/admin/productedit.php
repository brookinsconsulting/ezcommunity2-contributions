<?php
// 
// $Id: productedit.php,v 1.47 2001/03/21 13:39:22 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Sep-2000 10:56:05 bf>
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


function deleteCache( $ProductID, $CategoryID, $CategoryArray, $Hotdeal )
{
    if ( get_class( $ProductID ) == "ezproduct" )
    {
        $CategoryID =& $ProductID->categoryDefinition( false );
        $CategoryArray =& $ProductID->categories( false );
        $Hotdeal = $ProductID->isHotDeal();
        $ProductID = $ProductID->id();
    }

    $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, $CategoryID ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    $files = eZCacheFile::files( "eztrade/cache/", array( "productlist",
                                                          array_merge( $CategoryID, $CategoryArray ) ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
    if ( $Hotdeal )
    {
        $files = eZCacheFile::files( "eztrade/cache/", array( "hotdealslist", NULL ),
                                     "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
    }
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowModuleLinker = $ini->read_var( "eZTradeMain", "ShowModuleLinker" ) == "true";

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezvattype.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );

if ( isset( $SubmitPrice ) )
{
    for ( $i = 0; $i < count( $ProductEditArrayID ); $i++ )
    {
        if ( $Price[$i] != "" and is_numeric( $Price[$i] ) )
        {
            $product = new eZProduct( $ProductEditArrayID[$i] );
            $product->setPrice( $Price[$i] );
            $product->store();
          deleteCache( $product, false, false, false );
        }
    }
    if ( isset( $Query ) )
        eZHTTPTool::header( "Location: /trade/search/$Offset/$Query" );
    else
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$CategoryID/$Offset" );
    exit();
}

if ( isset ( $DeleteProducts ) )
{
    $Action = "DeleteProducts";
}

if ( $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );


    $product = new eZProduct();
    $product->setName( $Name );
    $product->setDescription( $Description );
    $product->setBrief(  $Brief );
    $product->setKeywords( $Keywords );
    $product->setProductNumber( $ProductNumber );

    $product->setExternalLink( $ExternalLink );

    $vattype = new eZVATType( $VATTypeID );
    $product->setVATType( $vattype );

    $shippingGroup = new eZShippingGroup( $ShippingGroupID );
    $product->setShippingGroup( $shippingGroup );    
    
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


    if ( $IsHotDeal == "on" )
    {
        $product->setIsHotDeal( true );
    }
    else
    {
        $product->setIsHotDeal( false );
    }
    
    $product->setPrice( $Price );
    
    $product->store();

    if ( $ShowQuantity )
    {
        $product->setTotalQuantity( is_numeric( $Quantity ) ? $Quantity : false );
    }

//      eZPriceGroup::removePrices( $ProductID, -1 );
    $count = max( count( $PriceGroup ), count( $PriceGroupID ) );
    for ( $i = 0; $i < $count; $i++ )
    {
        if ( is_numeric( $PriceGroupID[$i] ) and $PriceGroup[$i] != "" )
        {
            eZPriceGroup::addPrice( $product->id(), $PriceGroupID[$i], $PriceGroup[$i] );
        }
    }

    // add a product to the categories
    $category = new eZProductCategory( $CategoryID );
    $category->addProduct( $product );

    $product->setCategoryDefinition( $category );

    if ( count( $CategoryArray ) > 0 )
    foreach ( $CategoryArray as $categoryItem )
    {
        if ( $categoryItem != $CategoryID )
        {
            $category = new eZProductCategory( $categoryItem );
            $category->addProduct( $product );
        }
    }


    $productID = $product->id();

    $categoryArray = $product->categories();
    $categoryIDArray = array();
    foreach ( $categoryArray as $cat )
    {
        $categoryIDArray[] = $cat->id();
    }    

    // clear the cache files.
    deleteCache( $ProductID, $CategoryID, $categoryIDArray, $product->isHotDeal() );

    // add options
    if ( isset( $Option ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/optionlist/$productID/" );
        exit();
    }

    // add images
    if ( isset( $Image ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/imagelist/$productID/" );
        exit();
    }
    
    // preview
    if ( isset( $Preview ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/productpreview/$productID/" );
        exit();
    }
   
    // attribute
    if ( isset( $Attribute ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/attributeedit/$productID/" );
        exit();
    }
        

    // get the category to redirect to
    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
    eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID" );
    exit();
}

if ( $Action == "Update" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );

    $product = new eZProduct();
    $product->get( $ProductID );
    $was_hotdeal = $product->isHotDeal();
    $product->setName( $Name );
    $product->setDescription( $Description );
    $product->setBrief( $Brief );
    $product->setKeywords( $Keywords  );
    $product->setProductNumber( $ProductNumber );
    $product->setExternalLink( $ExternalLink );

    $vattype = new eZVATType( $VATTypeID );
    $product->setVATType( $vattype );

    $shippingGroup = new eZShippingGroup( $ShippingGroupID );
    $product->setShippingGroup( $shippingGroup );    
    
    
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

    if ( $IsHotDeal == "on" )
    {
        $product->setIsHotDeal( true );
    }
    else
    {
        $product->setIsHotDeal( false );
    }
    
    
    $product->setPrice( $Price );
    
    $product->store();

    if ( $ShowQuantity )
    {
        $product->setTotalQuantity( is_numeric( $Quantity ) ? $Quantity : false );
    }

    eZPriceGroup::removePrices( $ProductID, -1 );
    $count = max( count( $PriceGroup ), count( $PriceGroupID ) );
    for ( $i = 0; $i < $count; $i++ )
    {
        if ( is_numeric( $PriceGroupID[$i] ) and $PriceGroup[$i] != "" )
        {
            eZPriceGroup::addPrice( $ProductID, $PriceGroupID[$i], $PriceGroup[$i] );
        }
    }

    $productID = $product->id();

    // Calculate which categories are new and which are unused
    $old_maincategory = $product->categoryDefinition();
    $old_categories = array_merge( $old_maincategory->id(), $product->categories( false ) );
    $old_categories = array_unique( $old_categories );

    $new_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );

    $remove_categories = array_diff( $old_categories, $new_categories );
    $add_categories = array_diff( $new_categories, $old_categories );

    foreach ( $remove_categories as $categoryItem )
    {
        eZProductCategory::removeProduct( $product, $categoryItem );
    }

    // add a product to the categories
    $category = new eZProductCategory( $CategoryID );
    $product->setCategoryDefinition( $category );

    foreach ( $add_categories as $categoryItem )
    {
        eZProductCategory::addProduct( $product, $categoryItem );
    }

    // clear the cache files.
    deleteCache( $ProductID, $CategoryID, $old_categories, $was_hotdeal or $product->isHotDeal() );

    // add options
    if ( isset( $Option ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/optionlist/$productID/" );
        exit();
    }

    // add images
    if ( isset( $Image ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/imagelist/$productID/" );
        exit();
    }
    
    // preview
    if ( isset( $Preview ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/productpreview/$productID/" );
        exit();
    }

    // attribute
    if ( isset( $Attribute ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/attributeedit/$productID/" );
        exit();
    }    

    // module link
    if ( isset( $ModuleLinker ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/link/list/$productID/" );
        exit();
    }    

    // get the category to redirect to
    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
    eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID" );
    exit();
}

if ( $Action == "Cancel" )
{
    if ( isset( $ProductID ) )
    {
        $product = new eZProduct( $ProductID );
        $category = $product->categoryDefinition( );
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

if ( $Action == "DeleteProducts" )
{
    if ( count ( $ProductArrayID ) != 0 )
    {
        foreach( $ProductArrayID as $ProductID )
        {
            $product = new eZProduct();
            $product->get( $ProductID );

            $categories = $product->categories();

            $categoryArray = $product->categories();
            $categoryIDArray = array();
            foreach ( $categoryArray as $cat )
            {
                $categoryIDArray[] = $cat->id();
            }    
    

            // clear the cache files.
            deleteCache( $ProductID, $CategoryID, $categoryIDArray, $product->isHotDeal() );

            $category = $product->categoryDefinition( );
            $categoryID = $category->id();
    
            $product->delete();

            eZPriceGroup::removePrices( $ProductID, -1 );
        }
    }

    if ( isset( $Query ) )
        eZHTTPTool::header( "Location: /trade/search/$Offset/$Query" );
    else
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID/$Offset" );
    exit();
}

if ( $Action == "Delete" )
{
    $product = new eZProduct();
    $product->get( $ProductID );

    $categories = $product->categories();

    $categoryArray = $product->categories();
    $categoryIDArray = array();
    foreach ( $categoryArray as $cat )
    {
        $categoryIDArray[] = $cat->id();
    }    

    // clear the cache files.
    deleteCache( $ProductID, $CategoryID, $categoryIDArray, $product->isHotDeal() );

    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
    $product->delete();

    eZPriceGroup::removePrices( $ProductID, -1 );
    
    eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "productedit.php" );


$t->set_file( array( "product_edit_tpl" => "productedit.tpl" ) );

$t->set_block( "product_edit_tpl", "value_tpl", "value" );
$t->set_block( "product_edit_tpl", "multiple_value_tpl", "multiple_value" );

$t->set_block( "product_edit_tpl", "module_linker_button_tpl", "module_linker_button" );

$t->set_block( "product_edit_tpl", "vat_select_tpl", "vat_select" );
$t->set_block( "product_edit_tpl", "shipping_select_tpl", "shipping_select" );
$t->set_block( "product_edit_tpl", "quantity_item_tpl", "quantity_item" );

$t->set_block( "product_edit_tpl", "price_group_list_tpl", "price_group_list" );
$t->set_block( "price_group_list_tpl", "price_groups_item_tpl", "price_groups_item" );
$t->set_block( "price_groups_item_tpl", "price_group_header_item_tpl", "price_group_header_item" );
$t->set_block( "price_groups_item_tpl", "price_group_item_tpl", "price_group_item" );
$t->set_block( "price_group_list_tpl", "price_groups_no_item_tpl", "price_groups_no_item" );

$t->setAllStrings();
               
$t->set_var( "brief_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "keywords_value", "" );
$t->set_var( "product_nr_value", "" );
$t->set_var( "price_value", "" );

$t->set_var( "showprice_checked", "" );
$t->set_var( "showproduct_checked", "" );
$t->set_var( "is_hot_deal_checked", "" );

$t->set_var( "external_link", "" );

$t->set_var( "action_value", "insert" );


$VatType = false;
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

    if ( $product->isHotDeal() == true )
        $t->set_var( "is_hot_deal_checked", "checked" );

    $VatType =& $product->vatType();

    $Quantity = $product->totalQuantity();

    $prices = eZPriceGroup::prices( $ProductID );
    $PriceGroup = array();
    $PriceGroupID = array();
    foreach( $prices as $price )
    {
        $PriceGroup[] = $price["Price"];
        $PriceGroupID[] = $price["PriceID"];
    }
    $VatType =& $product->vatType();    
    $ShippingGroup =& $product->shippingGroup();    
}

$category = new eZProductCategory();
$categoryArray = $category->getTree( );

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        $defCat = $product->categoryDefinition( );
        
        if ( $product->existsInCategory( $catItem[0] ) &&
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
//          if ( $product->existsInCategory( $catItem ) )
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
    $t->set_var( "shipping_group_id", $group->id() );
    
    $t->set_var( "shipping_group_name", $group->name() );

    $t->parse( "shipping_select", "shipping_select_tpl", true );
}

// Show quantity
$t->set_var( "quantity_item", "" );
$t->set_var( "quantity_value", $Quantity );
if ( $ShowQuantity )
    $t->parse( "quantity_item", "quantity_item_tpl" );

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

$t->set_var( "module_linker_button", "" );
if ( $ShowModuleLinker )
    $t->parse( "module_linker_button", "module_linker_button_tpl" );

$t->pparse( "output", "product_edit_tpl" );

?>
