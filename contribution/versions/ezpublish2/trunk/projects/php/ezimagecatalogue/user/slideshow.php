<?php
//
// $Id: slideshow.php,v 1.4 2001/07/09 10:55:31 jhe Exp $
//
// Definition of eZArticle class
//
// Jo Henrik Endrerud <jhe@ez.no>
// Created on: <25-Jun-2001 11:50:32 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "ezimagecatalogue/classes/ezslideshow.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );

$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "slideshow.php" );

$t->setAllStrings();

$t->set_file( "slideshow_tpl", "slideshow.tpl" );

$t->set_block( "slideshow_tpl", "image_tpl", "image" );
$t->set_block( "slideshow_tpl", "previous_tpl", "previous" );
$t->set_block( "slideshow_tpl", "next_tpl", "next" );

if ( $Position == "" )
    $Position = 0;


$slideshow = new eZSlideshow( $CategoryID, eZUser::currentUser(), $Position );
$image = $slideshow->image();

if ( !$image )
{
    $t->set_var( "image", "" );
}
else
{
    $variation =& $image->requestImageVariation( $ini->read_var( "eZImageCatalogueMain", "ImageViewWidth" ),
    $ini->read_var( "eZImageCatalogueMain", "ImageViewHeight" ) );

    $t->set_var( "image_uri", "/" . $variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    
    $t->parse( "image", "image_tpl" );
}

$current = $slideshow->currentPosition();
$t->set_var( "category", $CategoryID );

if ( $current > 0 )
{
    $t->set_var( "prev_image", $current - 1 );
    $t->parse( "previous", "previous_tpl" );
}
else
{
    $t->set_var( "previous", "" );
} 

if ( $current < ( $slideshow->size() - 1 ) )
{
    if ( is_numeric( $RefreshTimer ) )
    {
        $MetaRedirectLocation = "/imagecatalogue/slideshow/" . $CategoryID . "/" . ($current + 1) . "/" . $RefreshTimer . "/";
        $MetaRedirectTimer = $RefreshTimer;
    }
    $t->set_var( "next_image", $current + 1 );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
}

$t->pparse( "output", "slideshow_tpl" );

?>
