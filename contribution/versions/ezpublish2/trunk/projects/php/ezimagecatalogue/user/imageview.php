<?
// 
// $Id: imageview.php,v 1.2 2000/10/26 18:18:11 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Oct-2000 19:40:18 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezimagecatalogue/classes/ezimagevariation.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );


$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "imageview.php" );

$t->set_file( "image_view_tpl", "imageview.tpl" );

$t->setAllStrings();

$image = new eZImage( $ImageID );

$variation =& $image->requestImageVariation( $ini->read_var( "eZImageCatalogueMain", "ImageViewWidth" ),
                                             $ini->read_var( "eZImageCatalogueMain", "ImageViewHeight" ) );

$t->set_var( "referer_url", $RefererURL );

$t->set_var( "image_uri", "/" . $variation->imagePath() );
$t->set_var( "image_width", $variation->width() );
$t->set_var( "image_height", $variation->height() );
$t->set_var( "image_caption", $image->caption() );

$t->pparse( "output", "image_view_tpl" );


?>
