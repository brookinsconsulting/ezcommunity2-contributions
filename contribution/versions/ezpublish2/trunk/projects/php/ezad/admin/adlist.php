<?
// 
// $Id: adlist.php,v 1.1 2000/11/25 11:40:51 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <22-Nov-2000 21:08:34 bf>
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

include_once( "ezad/classes/ezad.php" );
include_once( "ezad/classes/ezadcategory.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZAdMain", "Language" );

$t = new eZTemplate( "ezad/admin/" . $ini->read_var( "eZAdMain", "AdminTemplateDir" ),
                     "ezad/admin/intl/", $Language, "adlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "ad_list_page_tpl" => "adlist.tpl"
    ) );


// path
$t->set_block( "ad_list_page_tpl", "path_item_tpl", "path_item" );

// category
$t->set_block( "ad_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// ad
$t->set_block( "ad_list_page_tpl", "ad_list_tpl", "ad_list" );
$t->set_block( "ad_list_tpl", "ad_item_tpl", "ad_item" );
$t->set_block( "ad_item_tpl", "ad_is_published_tpl", "ad_is_published" );
$t->set_block( "ad_item_tpl", "ad_not_published_tpl", "ad_not_published" );

$category = new eZAdCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList = $category->getByParent( $category, true );


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


// ads
$adList = $category->ads( "time", true, true );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "ad_list", "" );
foreach ( $adList as $ad )
{
    if ( $ad->name() == "" )
        $t->set_var( "ad_name", "&nbsp;" );
    else
        $t->set_var( "ad_name", $ad->name() );

    $t->set_var( "ad_id", $ad->id() );

    if ( $ad->isPublished() == true )
    {
        $t->parse( "ad_is_published", "ad_is_published_tpl" );
        $t->set_var( "ad_not_published", "" );        
    }
    else
    {
        $t->set_var( "ad_is_published", "" );
        $t->parse( "ad_not_published", "ad_not_published_tpl" );
    }

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "ad_item", "ad_item_tpl", true );
    $i++;
}

if ( count( $adList ) > 0 )    
    $t->parse( "ad_list", "ad_list_tpl" );
else
    $t->set_var( "ad_list", "" );


$t->pparse( "output", "ad_list_page_tpl" );






?>
