<?php
//
// $Id: biglist.php,v 1.1.2.6 2002/04/11 07:55:14 ce Exp $
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
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

include_once( "classes/ezcachefile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

function &bigList( $CategoryID, $ParentID, $Offset=0, $module, $parse=false )
{
    $bigListCacheFile = new eZCacheFile( "eztrade/cache/biglist/",
					 array_merge( "list", $CategoryID, $ParentID, $Offset ),
					 "cache", "," );
    if ( $bigListCacheFile->exists() )
    {
	return $bigListCacheFile->contents();
    }
    else
    {
	global $ini, $IntlDir, $Language;

    $GlobalSectionID = eZProductCategory::sectionIDStatic( $CategoryID );
	$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
			     "eztrade/user/intl/", $Language, "categorytreelist.php" );

	$t->set_file( "category_list_page_tpl", "biglist.tpl", $GlobalSectionID );

	$t->set_block( "category_list_page_tpl", "category_list_tpl", "category_list" );
	$t->set_block( "category_list_page_tpl", "navigator_top_tpl", "navigator_top" );
	$t->set_block( "category_list_tpl", "top_category_tpl", "top_category" );
	$t->set_block( "category_list_page_tpl", "product_item_tpl", "product_item" );
	$t->set_block( "category_list_page_tpl", "category_item_tpl", "category_item" );

	$t->setAllStrings();

	if ( !isSet( $Limit ) or !is_numeric( $Limit ) )
	    $Limit = 20;
	if ( !isSet( $Offset ) or !is_numeric( $Offset ) )
	    $Offset = 0;

    $category = new eZProductCategory(  );
	$category->get( $CategoryID );

	$categoryList =& $category->getByParent( $category, "name", 100 );
        if ( is_object ( $categoryList[0] ) == false )
        {
            eZHTTPTool::header( "Location: /trade/productlist/$CategoryID/" );
            exit();
        } 
	if ( !isSet( $ParentID ) or !is_numeric( $ParentID ) )
    {
        if ( trim( $categoryList[0]->name() ) == "" )
        {
            $ParentID = $categoryList[1]->id();
        }
        else
        {
            $ParentID = $categoryList[0]->id();
        }
    }

	$sub = new eZProductCategory(  );
	$sub->get( $ParentID );


	$t->set_var( "parent_id", $ParentID );
	$t->set_var( "product_list", "" );
	$t->set_var( "product_item", "" );
	$t->set_var( "category_item", "" );
	$t->set_var( "module", "$module" );

	$products =& $sub->activeProducts( $sub->sortMode(), $Offset, $Limit );
	$TotalTypes =& $sub->productCount( $sub->sortMode() );
	if ( $TotalTypes > 0 )
	{
// sub categories
	    foreach ( $products as $product )
	    {
		$t->set_var( "product_id", $product->id() );
		$t->set_var( "product_name", $product->name() );
		$t->parse( "product_item", "product_item_tpl", true );
	    }
	}
	else
	{
	    $db =& eZDB::globalDatabase();
	    $db->array_query( $category_array, "SELECT ID, Name FROM eZTrade_Category WHERE Parent='$ParentID' ORDER BY Name", array( "Limit" => $Limit, "Offset" => $Offset ) );
	    $db->query_single( $count, "SELECT COUNT(ID) as Count FROM eZTrade_Category WHERE Parent='$ParentID'" );
	    $TotalTypes = $count["Count"];
	    if ( count ( $category_array ) > 0 )
	    {
		foreach ( $category_array as $categoryItem )
		{
		    $t->set_var( "category_id", $categoryItem["ID"] );
		    $t->set_var( "category_name", $categoryItem["Name"] );
		    $t->parse( "category_item", "category_item_tpl", true );
		}
	    }
	}

	eZList::drawNavigator( $t, $TotalTypes, $Limit, $Offset, "navigator_top_tpl" );
	$buttom =& $t->parse( "navigator_top", "navigator_top_tpl" );
	$t->set_var( "navigator_buttom", "$buttom" );

// categories
	foreach ( $categoryList as $categoryItem )
	{
	    $t->set_var( "top_category_id", $categoryItem->id() );
	    $t->set_var( "top_category_name", $categoryItem->name() );

	    $subItemList =& $category->getByParent( $categoryItem );
	    $t->parse( "top_category", "top_category_tpl", true );
	}

	if ( count( $categoryList ) == 0 )
	{
	    $t->set_var( "category_list", "" );
	}
	else
	{
	    $buttom =& $t->parse( "category_list", "category_list_tpl" );
	    $t->set_var( "list_buttom", "$buttom" );
	}

	$output =& $t->parse( "output", "category_list_page_tpl" );
//	$bigListCacheFile->store( $output );
    if ( $parse )
    {
        $t->pparse( "output", "category_list_page_tpl" );
    }
    else
        return $output;
    }
}
?>
