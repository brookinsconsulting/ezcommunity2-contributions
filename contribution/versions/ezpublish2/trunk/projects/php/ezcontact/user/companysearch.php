<?php
//
// $Id: companysearch.php,v 1.6 2001/07/20 12:01:51 jakobn Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

/*
    Searches for companies.
*/
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

$ini =& INIFIle::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );
$errorIni = new INIFIle( "ezcontact/user/intl/" . $Language . "/search.php.ini", false );

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezcontact/user/intl/", $Language, "search.php" );
$t->set_file( array(
    "search" => "companysearch.tpl" ) );

$t->set_block( "search", "search_box_tpl", "search_box" );
$t->set_block( "search", "advanced_search_box_tpl", "advanced_search_box" );
$t->set_block( "advanced_search_box_tpl", "category_option_tpl", "category_option" );
$t->set_block( "search", "search_results_tpl", "search_results" );
$t->set_block( "search_results_tpl", "result_item_tpl", "result_item" );
$t->set_block( "result_item_tpl", "result_category_tpl", "result_category" );
$t->set_block( "search", "no_results_tpl", "no_results" );

$t->set_var( "search_box", "" );
$t->set_var( "advanced_search_box", "" );
$t->set_var( "search_results", "" );
$t->set_var( "result_item", "" );
$t->set_var( "category_option", "" );
$t->set_var( "no_results", "" );
$t->set_var( "result_category", "" );

$t->set_var( "search_text", "$SearchText" );

$Action = "new";
$results = "false";

if( $SearchObject == "company" )
{
    $Action = "search";
}

if( !empty( $SearchText ) )
{
    $Action = "search";
}

if( !empty( $AdvancedSearch ) )
{
    $Action = "advanced";
    $t->set_var( "advanced_search", "true" );
}

if( $Action == "new" )
{
    $t->parse( "search_box", "search_box_tpl" );
}

$company = new eZCompany();

if( $Action == "search" )
{
    
    $companyArray = $company->search( $SearchText );
    
    $count = count( $companyArray );
    
    if( $count > 0 )
    {
        $results = true;
    }
    
    $t->parse( "search_box", "search_box_tpl" );
}

function byParent( $inParentID, $indent, $maxLevel = 3 )
{
    global $t;
    global $CategoryArray;
    
    $type = new eZCompanyType();
    $typeArray = $type->getByParentID( $inParentID );
    
    $count = count( $typeArray );
    
    if( $indent > $maxLevel )
    {
        $indent == $maxLevel;
    }
    $indentLine = str_pad( $indentLine, $indent * 6, "&nbsp;" );
    
    foreach( $typeArray as $ct )
    {
        $CategoryID = $ct->id();
        $t->set_var( "category_id", $CategoryID );
        $t->set_var( "category_value", $indentLine . $ct->name() );
        $t->set_var( "category_selected", "" );
        
        if( is_array( $CategoryArray ) )
        foreach( $CategoryArray as $Category )
        {
            if( $CategoryID == $Category )
            {
                $t->set_var( "category_selected", "selected" );
            }
        }
        
        $t->parse( "category_option", "category_option_tpl", true );
        byParent( $ct->id(), $indent + 1 );
    }
}

if( $Action == "advanced" )
{
    
    byParent( $ParentID, 0 );
    
    $companyArray = array();
    $count = count( $CategoryArray );

    if( $count )
    {
        foreach( $CategoryArray as $Category )
        {
            $companyArray = array_merge( $companyArray, $company->searchByCategory( $Category, $SearchText ) );
        }
        $companyArray = array_unique( $companyArray );
        $results = true;
    }
    
    $t->parse( "advanced_search_box", "advanced_search_box_tpl" );
}

if( $results == true )
{
    $count = count( $companyArray );
    $t->set_var( "results", $count );
    $i;
    foreach( $companyArray as $company )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "item_color", "bglight" );
        else
            $t->set_var( "item_color", "bgdark" );
        $i++;
        $t->set_var( "item_name", $company->name() );
        $t->set_var( "item_id", $company->id() );
        $t->set_var( "item_description", $company->comment() );
        $t->set_var( "item_view_path", "/contact/company/view" );
        $t->set_var( "item_delete_path", "/contact/company/delete" );
        $t->set_var( "item_edit_path", "/contact/company/edit" );
        
        $categoryArray = $company->categories( $company->id() );
        
        $t->set_var( "result_category", "" );
        
        foreach( $categoryArray as $category )
        {
            $t->set_var( "item_category_name", $category->name() );
            $t->set_var( "item_category_id", $category->id() );
            $t->set_var( "item_category_view_path", "/contact/company/list" );
            $t->parse( "result_category", "result_category_tpl", true );
        }
        
        $t->parse( "result_item", "result_item_tpl", true );
    }
    
    if( $count > 0 )
    {
        $t->parse( "search_results", "search_results_tpl" );
    }
    else
    {
        $t->parse( "no_results", "no_results_tpl" );
    }
}

$t->setAllStrings();

$t->pparse( "output", "search");
