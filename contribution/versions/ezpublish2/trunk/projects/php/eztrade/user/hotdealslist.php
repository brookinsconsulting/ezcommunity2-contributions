<?
// 
// $Id: hotdealslist.php,v 1.5 2000/12/14 20:32:15 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <12-Nov-2000 19:34:40 bf>
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

$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$hotDealColumns  = $ini->read_var( "eZTradeMain", "HotDealColumns" );
$hotDealImageWidth  = $ini->read_var( "eZTradeMain", "HotDealImageWidth" );
$hotDealImageHeight  = $ini->read_var( "eZTradeMain", "HotDealImageHeight" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "hotdealslist.php" );

$t->set_file( "product_list_page_tpl", "hotdealslist.tpl" );

$t->set_block( "product_list_page_tpl", "price_tpl", "price" );

$t->set_block( "product_list_page_tpl", "product_list_tpl", "product_list" );
$t->set_block( "product_list_tpl", "product_tpl", "product" );

$t->set_block( "product_tpl", "product_image_tpl", "product_image" );



$t->setAllStrings();

$product = new eZProduct(  );


// products
$productList =& $product->hotDealProducts();

$locale = new eZLocale( $Language );
$i=0;
foreach ( $productList as $product )
{
    if ( ( $i % $hotDealColumns ) == 0 )
    {
        $t->set_var( "begin_tr", "<tr>" );
        $t->set_var( "end_tr", "" );        
    }
    else
    {
        $t->set_var( "begin_tr", "" );
        $t->set_var( "end_tr", "</tr>" );
    }
    
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_intro_text", $product->brief() );

    $image = $product->thumbnailImage();

    if  ( $image )
    {
        $thumbnail =& $image->requestImageVariation( 100, 100 );

        $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
        $t->set_var( "product_image_width", $thumbnail->width() );
        $t->set_var( "product_image_height", $thumbnail->height() );
        $t->set_var( "product_image_caption", $image->caption() );
        $t->parse( "product_image", "product_image_tpl" );
    }
    else
    {
        $t->set_var( "product_image", "" );
    }
    
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
    
    $defCat = $product->categoryDefinition();
    $t->set_var( "category_id", $defCat->id() );

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
    $cachedFile = "eztrade/cache/hotdealslist.cache";
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
