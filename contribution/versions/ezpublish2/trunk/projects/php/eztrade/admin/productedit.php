<?php
// 
// $Id: productedit.php,v 1.27 2001/01/24 18:54:44 bf Exp $
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

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
    $dir = dir( "eztrade/cache/" );
    while( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "productview,(.*),.*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $productID )
                {
                    unlink( "eztrade/cache/" . $entry );
                }
            }

            
            if ( ereg( "productlist,(.*)\..*", $entry, $regArray  ) )
            {
                if ( in_array( $regArray[1], $categoryIDArray )  )
                {
                    unlink( "eztrade/cache/" . $entry );
                }
            }            

            if ( ereg( "hotdealslist.cache", $entry, $regArray  ) )
            {
                unlink( "eztrade/cache/" . $entry );
            }            
            
        } 
    }
    $dir->close();
    
    
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

    // remove current category assignments and add a product to the category
    $product->removeFromCategories();

    // add a product to the categories
    $category = new eZProductCategory( $CategoryID );
    $category->addProduct( $product );
    
    $product->setCategoryDefinition( $category );
    
    foreach ( $CategoryArray as $categoryItem )
    {
        if ( $categoryItem != $CategoryID )
        {
            $category = new eZProductCategory( $categoryItem );
            $category->addProduct( $product );
        }
    }

    $categoryArray = $product->categories();
    $categoryIDArray = array();
    foreach ( $categoryArray as $cat )
    {
        $categoryIDArray[] = $cat->id();
    }    

    // clear the cache files.
    $dir = dir( "eztrade/cache/" );
    while( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "productview,(.*),.*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $ProductID )
                {
                    unlink( "eztrade/cache/" . $entry );
                }
            }

            if ( ereg( "productlist,(.*)\..*", $entry, $regArray  ) )
            {
                if ( in_array( $regArray[1], $categoryIDArray )  )
                {
                    unlink( "eztrade/cache/" . $entry );
                }
            }

            if ( ereg( "hotdealslist.cache", $entry, $regArray  ) )
            {
                unlink( "eztrade/cache/" . $entry );
            }            
            
        } 
    }
    $dir->close();


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
            $dir = dir( "eztrade/cache/" );
            while( $entry = $dir->read() )
            { 
                if ( $entry != "." && $entry != ".." )
                {
                    if ( ereg( "productview,(.*),.*", $entry, $regArray  ) )
                    {
                        if ( $regArray[1] == $productID )
                        {
                            unlink( "eztrade/cache/" . $entry );
                        }
                    }
            
                    if ( ereg( "productlist,(.*)\..*", $entry, $regArray  ) )
                    {
                        if ( in_array( $regArray[1], $categoryIDArray )  )
                        {
                            unlink( "eztrade/cache/" . $entry );
                        }
                    }

                    if ( ereg( "hotdealslist.cache", $entry, $regArray  ) )
                    {
                        unlink( "eztrade/cache/" . $entry );
                    }            
                } 
            }
            $dir->close();

            $category = $product->categoryDefinition( );
            $categoryID = $category->id();
    
            $product->delete();
        }
    }

    Header( "Location: /trade/categorylist/parent/$categoryID/" );
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
    $dir = dir( "eztrade/cache/" );
    while( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "productview,(.*),.*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $productID )
                {
                    unlink( "eztrade/cache/" . $entry );
                }
            }
            
            if ( ereg( "productlist,(.*)\..*", $entry, $regArray  ) )
            {
                if ( in_array( $regArray[1], $categoryIDArray )  )
                {
                    unlink( "eztrade/cache/" . $entry );
                }
            }

            if ( ereg( "hotdealslist.cache", $entry, $regArray  ) )
            {
                unlink( "eztrade/cache/" . $entry );
            }            
        } 
    }
    $dir->close();

    $category = $product->categoryDefinition( );
    $categoryID = $category->id();
    
    $product->delete();
    
    Header( "Location: /trade/categorylist/parent/$categoryID/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/productedit/",
                     "eztrade/admin/intl/", $Language, "productedit.php" );


$t->set_file( array( "product_edit_tpl" => "productedit.tpl" ) );

$t->set_block( "product_edit_tpl", "value_tpl", "value" );
$t->set_block( "product_edit_tpl", "multiple_value_tpl", "multiple_value" );



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


$t->pparse( "output", "product_edit_tpl" );

?>
