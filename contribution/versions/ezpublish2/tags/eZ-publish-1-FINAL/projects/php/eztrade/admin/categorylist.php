<?
// 
// $Id: categorylist.php,v 1.11 2000/11/01 09:24:18 ce-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Sep-2000 14:56:11 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/categorylist/",
                     "eztrade/admin/intl/", $Language, "categorylist.php" );

$t->setAllStrings();

$t->set_file( array(
    "category_list_page_tpl" => "categorylist.tpl"
    ) );

// path
$t->set_block( "category_list_page_tpl", "path_item_tpl", "path_item" );

// category
$t->set_block( "category_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// product
$t->set_block( "category_list_page_tpl", "product_list_tpl", "product_list" );
$t->set_block( "product_list_tpl", "product_item_tpl", "product_item" );

$category = new eZProductCategory(  );
$category->get( $ParentID );

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList = $category->getByParent( $category );

// categories
$i=0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );

    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();
    

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->set_var( "category_description", $categoryItem->description() );

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// products
$productList = $category->products();

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "product_list", "" );
foreach ( $productList as $product )
{
    $t->set_var( "product_name", $product->name() );

    $price = new eZCurrency( $product->price() );
    
    $t->set_var( "product_price", $locale->format( $price ) );
    $t->set_var( "product_id", $product->id() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "product_item", "product_item_tpl", true );
    $i++;
}

if ( count( $productList ) > 0 )    
    $t->parse( "product_list", "product_list_tpl" );
else
    $t->set_var( "product_list", "" );

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "category_list_page_tpl" );

?>
