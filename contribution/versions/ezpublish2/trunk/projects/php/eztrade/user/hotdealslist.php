<?
// 
// $Id: hotdealslist.php,v 1.1 2000/11/12 18:19:11 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Sep-2000 14:46:20 bf>
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

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "hotdealslist.php" );

$t->set_file( "product_list_page_tpl", "hotdealslist.tpl" );

$t->set_block( "product_list_page_tpl", "price_tpl", "price" );

$t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );
$t->set_block( "product_list_tpl", "product_tpl", "product" );



$t->setAllStrings();

$category = new eZProductCategory(  );
$category->get( $CategoryID );


// products
$productList =& $category->activeProducts();

$locale = new eZLocale( $Language );
$i=0;
foreach ( $productList as $product )
{
    $t->set_var( "product_id", $product->id() );


    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_intro_text", $product->brief() );
    
    if ( $product->showPrice() == true  )
    {
        $price = new eZCurrency( $product->price() );
        $t->set_var( "product_price", $locale->format( $price ) );        
        $t->parse( "price", "price_tpl" );
    }
    else
    {
        $t->set_var( "price", "" );
    }
    

    
    $t->set_var( "category_id", $category->id() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "product", "product_tpl", true );
    $i++;
}

if ( count( $productList ) > 0 )
{
    $t->parse( "product_list", "product_list_tpl" );
}
else
{
    $t->set_var( "product_list", "" );
}




if ( $GenerateStaticPage == "true" )
{
    $cachedFile = "eztrade/cache/productlist," . $CategoryID .".cache";
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse( $target, "product_list_page_tpl" );
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "product_list_page_tpl" );
}


?>
