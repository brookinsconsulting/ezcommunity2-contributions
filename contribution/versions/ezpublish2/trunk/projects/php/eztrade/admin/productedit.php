<?php
// 
// $Id: productedit.php,v 1.34 2001/02/22 14:28:45 jb Exp $
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

function deleteCache( $ProductID, $CategoryID, $CategoryArray )
{
    if ( get_class( $ProductID ) == "ezproduct" )
    {
        $CategoryID =& $ProductID->categoryDefinition( false );
        $CategoryArray =& $ProductID->categories( false );
        $ProductID = $ProductID->id();
    }

    $files = eZCacheFile::files( "eztrade/cache/", array( "productview", $ProductID, $CategoryID ),
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
    $files = eZCacheFile::files( "eztrade/cache/", array( "hotdealslist" ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }
}

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezvattype.php" );

if ( isset( $SubmitPrice ) )
{
    for ( $i = 0; $i < count( $ProductEditArrayID ); $i++ )
    {
        if ( $Price[$i] != "" and is_numeric( $Price[$i] ) )
        {
            $product = new eZProduct( $ProductEditArrayID[$i] );
            $product->setPrice( $Price[$i] );
            $product->store();
            deleteCache( $product, false, false );
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
    deleteCache( $ProductID, $CategoryID, $categoryIDArray );

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
   
    // attribute
    if ( isset( $Attribute ) )
    {
        Header( "Location: /trade/productedit/attributeedit/$productID/" );
        exit();
    }
        

    // get the category to redirect to
    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
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

    $vattype = new eZVATType( $VATTypeID );
    $product->setVATType( $vattype );    
    
    
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
    deleteCache( $ProductID, $CategoryID, $old_categories );

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

    // attribute
    if ( isset( $Attribute ) )
    {
        Header( "Location: /trade/productedit/attributeedit/$productID/" );
        exit();
    }    

    // get the category to redirect to
    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
    Header( "Location: /trade/categorylist/parent/$categoryID" );
    exit();
}

if ( $Action == "Cancel" )
{
    if ( isset( $ProductID ) )
    {
        $product = new eZProduct( $ProductID );
        $category = $product->categoryDefinition( );
        $categoryID = $category->id();
        Header( "Location: /trade/categorylist/parent/$categoryID" );
        exit();
    }
    else
    {
        Header( "Location: /trade/categorylist/parent/" );
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
            deleteCache( $ProductID, $CategoryID, $categoryIDArray );

            $category = $product->categoryDefinition( );
            $categoryID = $category->id();
    
            $product->delete();
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
    deleteCache( $ProductID, $CategoryID, $categoryIDArray );

    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
    $product->delete();
    
    Header( "Location: /trade/categorylist/parent/$categoryID/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "productedit.php" );


$t->set_file( array( "product_edit_tpl" => "productedit.tpl" ) );

$t->set_block( "product_edit_tpl", "value_tpl", "value" );
$t->set_block( "product_edit_tpl", "multiple_value_tpl", "multiple_value" );

$t->set_block( "product_edit_tpl", "vat_select_tpl", "vat_select" );



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



$t->pparse( "output", "product_edit_tpl" );

?>
