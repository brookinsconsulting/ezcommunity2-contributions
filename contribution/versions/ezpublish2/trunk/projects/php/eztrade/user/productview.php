<?
// 
// $Id: productview.php,v 1.2 2000/10/22 10:46:20 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <24-Sep-2000 12:20:32 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezoption.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "productview.php" );

$t->setAllStrings();

$t->set_file( array(
    "product_view_tpl" => "productview.tpl"
    ) );


$t->set_block( "product_view_tpl", "path_tpl", "path" );
$t->set_block( "product_view_tpl", "image_tpl", "image" );
$t->set_block( "product_view_tpl", "main_image_tpl", "main_image" );
$t->set_block( "product_view_tpl", "option_tpl", "option" );
$t->set_block( "option_tpl", "value_tpl", "value" );

$category = new eZProductCategory(  );
$category->get( $CategoryID );

$pathArray =& $category->path();

$t->set_var( "category_path", "" );
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
    $variation = $mainImage->requestImageVariation( 250, 250 );
    
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

$t->set_var( "title_text", $product->name() );
$t->set_var( "intro_text", $product->brief() );
$t->set_var( "description_text", nl2br( $product->description() ) );


$images = $product->images();

$i=0;
$t->set_var( "image_list", "" );
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
    
        $t->set_var( "image_caption", $image->caption() );
        $t->set_var( "image_id", $image->id() );
        $t->set_var( "product_id", $ProductID );

        $variation = $image->requestImageVariation( 150, 150 );
    
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

$t->set_var( "product_id", $product->id() );

$locale = new eZLocale( $Language );
$price = new eZCurrency( $product->price() );

$t->set_var( "product_number", $product->productNumber() );
$t->set_var( "product_price", $locale->format( $price ) );

if ( $GenerateStaticPage == "true" )
{
    $cachedFile = "eztrade/cache/productview," .$ProductID . "," . $CategoryID .".cache";
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse($target, "product_view_tpl" );
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
