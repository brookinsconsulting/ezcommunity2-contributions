<?php
// 
// $Id: adstatistics.php,v 1.9 2001/04/30 16:04:47 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Nov-2000 11:47:03 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezimagefile.php" );
include_once( "classes/ezlog.php" );

include_once( "classes/ezdatetime.php" );

include_once( "ezad/classes/ezad.php" );
include_once( "ezad/classes/ezadcategory.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZAdMain", "Language" );
$ImageDir = $ini->read_var( "eZAdMain", "ImageDir" );

$t = new eZTemplate( "ezad/admin/" . $ini->read_var( "eZAdMain", "AdminTemplateDir" ),
                     "ezad/admin/intl/", $Language, "adstatistics.php" );

$t->setAllStrings();

$t->set_file( array(
    "ad_edit_page_tpl" => "adstatistics.tpl"
    ) );

$t->set_block( "ad_edit_page_tpl", "image_tpl", "image" );
$t->set_block( "ad_edit_page_tpl", "html_item_tpl", "html_item" );


$ad = new eZAd( $AdID );

$t->set_var( "ad_title", $ad->name() );
$t->set_var( "ad_description", $ad->description() );
$t->set_var( "ad_url", $ad->url() );
$t->set_var( "ad_id", $ad->id() );

$t->set_var( "ad_view_count", $ad->viewCount() );
$t->set_var( "ad_click_count", $ad->clickCount() );

$clickRevenue =& $ad->totalClickRevenue();

$viewRevenue =& $ad->totalViewRevenue();

$t->set_var( "ad_view_revenue", $clickRevenue );
$t->set_var( "ad_click_revenue", $viewRevenue );

$t->set_var( "ad_total_revenue", $clickRevenue + $viewRevenue );

$view_count = $ad->viewCount();

if ( is_numeric( $view_count ) and $view_count != 0 )
{
    $t->set_var( "ad_click_percent", ( $ad->clickCount() / $view_count ) * 100 );
}
else
{
    $t->set_var( "ad_click_percent", ( 0 ) );
}

if ( $ad->isActive() == true )
{
    $t->set_var( "ad_is_active", "checked" );
}
else
{
    $t->set_var( "ad_is_active", "" );
}


if ( $ad->useHTML() )
{
    $t->set_var( "image", "" );
    
    $t->set_var( "html_banner", $ad->htmlBanner() );
    $t->parse( "html_item", "html_item" );

}    
else
{
    $image = $ad->image();
    
    if ( $image )
    {
        $t->set_var( "image_src",  $image->filePath() );
        $t->set_var( "image_alt", $image->caption() );
        $t->set_var( "image_width", $image->width() );
        $t->set_var( "image_height", $image->height() );
        $t->set_var( "image_file_name", $image->originalFileName() );

        $t->set_var( "html_item", "" );

        $t->parse( "image", "image_tpl" );
    }
}


$t->pparse( "output", "ad_edit_page_tpl" );

?>

