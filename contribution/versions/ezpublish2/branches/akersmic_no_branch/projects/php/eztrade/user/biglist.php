<?php
//
// $Id: biglist.php,v 1.1.2.1 2002/02/05 15:30:55 ce Exp $
//
// Created on: <12-Dec-2000 14:43:08 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "categorytreelist.php" );

$t->set_file( "category_list_page_tpl", "biglist.tpl" );

$t->set_block( "category_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "top_category_tpl", "top_category" );
$t->set_block( "category_list_page_tpl", "product_item_tpl", "product_item" );


$t->setAllStrings();

$sub = new eZProductCategory(  );
$sub->get( $ParentID );

if ( !isSet( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 20;
if ( !isSet( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$t->set_var( "parent_id", $ParentID );

$products =& $sub->activeProducts( $sub->sortMode(), $Offset, $Limit );
$TotalTypes =& $sub->productCount( $sub->sortMode());

// sub categories
foreach ( $products as $product )
{
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->parse( "product_item", "product_item_tpl", true );
}
eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "category_list_page_tpl" );

$category = new eZProductCategory(  );
$category->get( $CategoryID );

$categoryList =& $category->getByParent( $category );

// categories
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "top_category_id", $categoryItem->id() );
    $t->set_var( "top_category_name", $categoryItem->name() );

    $subItemList =& $category->getByParent( $categoryItem );

//    $t->set_var( "level_1_category", "" );

    $t->parse( "top_category", "top_category_tpl", true );

}

if ( count( $categoryList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $t->parse( "category_list", "category_list_tpl" );
}

$t->pparse( "output", "category_list_page_tpl" );
?>

