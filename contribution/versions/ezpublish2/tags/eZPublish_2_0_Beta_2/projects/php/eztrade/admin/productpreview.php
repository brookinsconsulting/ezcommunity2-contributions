<?
// 
// $Id: productpreview.php,v 1.12 2000/12/21 13:00:29 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <22-Sep-2000 16:13:32 bf>
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezoption.php" );

$t = new eZTemplate( "eztrade/admin/". $ini->read_var( "eZTradeMain", "AdminTemplateDir" ) . "/productpreview/",
                     "eztrade/admin/intl/", $Language, "productpreview.php" );

$t->setAllStrings();

$t->set_file( array( "product_preview_tpl" => "productpreview.tpl"
                     ) );

$t->set_block( "product_preview_tpl", "image_tpl", "image" );
$t->set_block( "product_preview_tpl", "main_image_tpl", "main_image" );
$t->set_block( "product_preview_tpl", "option_tpl", "option" );
$t->set_block( "option_tpl", "value_tpl", "value" );

$t->set_block( "product_preview_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

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


// attribute list
$type = $product->type();
if ( $type )    
{
    $attributes = $type->attributes();

    foreach ( $attributes as $attribute )
    {
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );
        $t->set_var( "attribute_value", $attribute->value( $product ) );
        
        $t->parse( "attribute", "attribute_tpl", true );
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
$t->set_var( "product_number", $product->productNumber() );
$t->set_var( "product_price", $product->price() );


$t->pparse( "output", "product_preview_tpl" );

?>
