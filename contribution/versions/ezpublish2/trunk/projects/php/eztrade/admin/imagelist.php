<?
// 
// $Id: imagelist.php,v 1.8 2000/10/29 12:40:41 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 10:32:19 bf>
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

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );


$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "imagelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_list_page_tpl" => "imagelist.tpl"
    ) );

$t->set_block( "image_list_page_tpl", "image_tpl", "image" );

$product = new eZProduct( $ProductID );


$thumbnail = $product->thumbnailImage();
$main = $product->mainImage();

$t->set_var( "product_name", $product->name() );

$images = $product->images();

$i=0;
$t->set_var( "image", "" );
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

    $t->set_var( "main_image_checked", "" );
    if ( $main != 0 )
    {
        if ( $main->id() == $image->id() )
        {
            $t->set_var( "main_image_checked", "checked" );
        }
    }

    $t->set_var( "thumbnail_image_checked", "" );
    if ( $thumbnail != 0 )
    {
        if ( $thumbnail->id() == $image->id() )
        {
            $t->set_var( "thumbnail_image_checked", "checked" );
        }
    }
    
    $t->set_var( "image_number", $i + 1 );

    $t->set_var( "image_name", $image->caption() );
    $t->set_var( "image_id", $image->id() );
    $t->set_var( "product_id", $ProductID );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_url", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height",$variation->height() );
    
//      $t->set_var( "image_url", $image->filePath() );

    $t->parse( "image", "image_tpl", true );
    
    $i++;
}


$t->set_var( "product_id", $product->id() );

$t->pparse( "output", "image_list_page_tpl" );

?>
