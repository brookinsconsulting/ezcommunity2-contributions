<?
// 
// $Id: productview.php,v 1.15 2001/01/29 17:17:41 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <24-Sep-2000 12:20:32 bf>
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
include_once( "classes/eztexttool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZTradeMain", "Language" );

$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );

$MainImageWidth = $ini->read_var( "eZTradeMain", "MainImageWidth" );
$MainImageHeight = $ini->read_var( "eZTradeMain", "MainImageHeight" );

$SmallImageWidth = $ini->read_var( "eZTradeMain", "SmallImageWidth" );
$SmallImageHeight = $ini->read_var( "eZTradeMain", "SmallImageHeight" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezoption.php" );

if ( !isset( $IntlDir ) )
    $IntlDir = "eztrade/user/intl";
if ( !isset( $IniFile ) )
    $IniFile = "productview.php";

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     $IntlDir, $Language, $IniFile );

$t->setAllStrings();

if ( !isset( $productview ) )
    $productview = "productview.tpl";

if ( isset( $template_array ) and isset( $variable_array ) and
     is_array( $template_array ) and is_array( $variable_array ) )
{
    $standard_array = array( "product_view_tpl" => $productview );
    $temp_arr = array_merge( $standard_array, $template_array );
    $t->set_file( $temp_arr );
    $t->set_file_block( $template_array );
    if ( isset( $block_array ) and is_array( $block_array ) )
        $t->set_block( $block_array );
    $t->parse( $variable_array );
}
else
{
    $t->set_var( "extra_product_info", "" );
    $t->set_file( "product_view_tpl", $productview );
}

//  $t->set_file( array(
//      "product_view_tpl" => "productview.tpl"
//      ) );

$t->set_block( "product_view_tpl", "price_tpl", "price" );
$t->set_block( "product_view_tpl", "add_to_cart_tpl", "add_to_cart" );

$t->set_block( "product_view_tpl", "path_tpl", "path" );
$t->set_block( "product_view_tpl", "image_tpl", "image" );
$t->set_block( "product_view_tpl", "main_image_tpl", "main_image" );
$t->set_block( "product_view_tpl", "option_tpl", "option" );
$t->set_block( "option_tpl", "value_tpl", "value" );
$t->set_block( "product_view_tpl", "external_link_tpl", "external_link" );

$t->set_block( "product_view_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$t->set_block( "product_view_tpl", "numbered_page_link_tpl", "numbered_page_link" );
$t->set_block( "product_view_tpl", "print_page_link_tpl", "print_page_link" );

if ( !isset( $ModuleName ) )
    $ModuleName = "trade";
if ( !isset( $ModuleList ) )
    $ModuleList = "productlist";
if ( !isset( $ModuleView ) )
    $ModuleView = "productview";
if ( !isset( $ModulePrint ) )
    $ModulePrint = "productprint";

$t->set_var( "module", $ModuleName );
$t->set_var( "module_list", $ModuleList );
$t->set_var( "module_view", $ModuleView );
$t->set_var( "module_print", $ModulePrint );

$category = new eZProductCategory(  );
$category->get( $CategoryID );

$pathArray =& $category->path();


$t->set_var( "path", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path", "path_tpl", true );
}

$product = new eZProduct( $ProductID );

$mainImage = $product->mainImage();
if ( $mainImage )
{
    $variation = $mainImage->requestImageVariation( $MainImageWidth, $MainImageHeight );
    
    $t->set_var( "main_image_id", $mainImage->id() );
    $t->set_var( "main_image_uri", "/" . $variation->imagePath() );
    $t->set_var( "main_image_width", $variation->width() );
    $t->set_var( "main_image_height", $variation->height() );
    $t->set_var( "main_image_caption", $mainImage->caption() );

    $mainImageID = $mainImage->id();

    $t->parse( "main_image", "main_image_tpl" );    
}
else
{
    $t->set_var( "main_image", "" );    
}


if ( $CapitalizeHeadlines == "enabled" )
{
    include_once( "classes/eztexttool.php" );
    $t->set_var( "title_text", eZTextTool::capitalize( $product->name() ) );
}
else
{        
    $t->set_var( "title_text", $product->name() );
} 
$t->set_var( "intro_text", $product->brief() );
$t->set_var( "description_text", eZTextTool::nl2br( $product->description() ) );

$images = $product->images();

$i=0;
$t->set_var( "image", "" );
foreach ( $images as $image )
{
    if ( $image->id() != $mainImageID )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
    
        $t->set_var( "image_name", $image->name() );

        $t->set_var( "image_caption", eZTextTool::nl2br( $image->caption() ) );
        $t->set_var( "image_id", $image->id() );
        $t->set_var( "product_id", $ProductID );

        $variation = $image->requestImageVariation( $SmallImageWidth, $SmallImageHeight );
    
        $t->set_var( "image_url", "/" .$variation->imagePath() );
        $t->set_var( "image_width", $variation->width() );
        $t->set_var( "image_height", $variation->height() );
    
        $t->parse( "image", "image_tpl", true );
    
        $i++;
    }
}

$options = $product->options();

$t->set_var( "option", "" );

foreach ( $options as $option )
{
    $values = $option->values();

    $valueText = "";
    $t->set_var( "value", "" );    
    foreach ( $values as $value )
    {
        $valueText .= $value->name() . "\n";
        $id = $value->id();
        
        $t->set_var( "value_name", $value->name() );
        $t->set_var( "value_id", $value->id() );
        
        $t->parse( "value", "value_tpl", true );    
    }

    $t->set_var( "option_name", $option->name() );
    $t->set_var( "option_description", $option->description() );
    $t->set_var( "option_id", $option->id() );
    $t->set_var( "product_id", $ProductID );

    $t->parse( "option", "option_tpl", true );    
}

// attribute list
$type = $product->type();
if ( $type )    
{
    $attributes = $type->attributes();

    $i=0;
    foreach ( $attributes as $attribute )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "begin_tr", "<tr>" );
            $t->set_var( "end_tr", "" );        
        }
        else
        {
            $t->set_var( "begin_tr", "" );
            $t->set_var( "end_tr", "</tr>" );
        }
        
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );
        $t->set_var( "attribute_value", $attribute->value( $product ) );
        
        $t->parse( "attribute", "attribute_tpl", true );
        $i++;
    }
}

if ( count ( $attributes ) > 0 )
{
    $t->parse( "attribute_list", "attribute_list_tpl" );
}
else
{
    $t->set_var( "attribute_list", "" );
}


$t->set_var( "product_id", $product->id() );

if ( trim( $product->externalLink() ) != "" )
{
    $t->set_var( "external_link_url", "http://" . $product->externalLink() );
    $t->parse( "external_link", "external_link_tpl" );
}
else
{
    $t->set_var( "external_link", "" );
}

$locale = new eZLocale( $Language );

$t->set_var( "product_number", $product->productNumber() );

if ( $product->showPrice() == true  )
{
    $price = new eZCurrency( $product->price() );
    $t->set_var( "product_price", $locale->format( $price ) );
    $t->parse( "price", "price_tpl" );
    $t->parse( "add_to_cart", "add_to_cart_tpl" );
}
else
{
    $t->set_var( "price", "" );
    $t->set_var( "add_to_cart", "" );
}

if ( $PrintableVersion == "enabled" )
{
    $t->parse( "numbered_page_link", "numbered_page_link_tpl" );
    $t->set_var( "print_page_link", "" );
}
else
{
    $t->parse( "print_page_link", "print_page_link_tpl" );
    $t->set_var( "numbered_page_link", "" );
}

if ( isset( $func_array ) and is_array( $func_array ) )
{
    foreach( $func_array as $func )
    {
        $func( $t, $ProductID );
    }
}

if ( $GenerateStaticPage == "true" )
{
    $cachedFile = "eztrade/cache/productview," .$ProductID . "," . $CategoryID .".cache";
    $template_var = "product_view_tpl";
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse($target, $template_var );
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "product_view_tpl" );
}


?>
