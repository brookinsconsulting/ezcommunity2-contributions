<?
// 
// $Id: productpreview.php,v 1.1 2000/09/22 14:37:06 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <22-Sep-2000 16:13:32 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproduct.php" );
include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/productpreview/",
                     $DOC_ROOT . "/admin/intl/", $Language, "productpreview.php" );

$t->setAllStrings();

$t->set_file( array( "product_preview_page" => "productpreview.tpl",
                      "image_item" => "imageitem.tpl") );


$product = new eZProduct( $ProductID );

$t->set_var( "title_text", $product->name() );
$t->set_var( "intro_text", $product->brief() );
$t->set_var( "description_text", $product->description() );



$images = $product->images();

$i=0;
foreach ( $images as $image )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->set_var( "image_name", $image->caption() );
    $t->set_var( "image_id", $image->id() );
    $t->set_var( "product_id", $ProductID );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_url", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    
    $t->parse( "image_list", "image_item", true );
    
    $i++;
}

$t->set_var( "product_id", $product->id() );


$t->pparse( "output", "product_preview_page" );

?>
