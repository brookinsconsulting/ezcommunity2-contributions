<?php
// 
// $Id: imagelist.php,v 1.20.2.1 2003/01/07 13:46:19 br Exp $
//
// Created on: <21-Sep-2000 10:32:19 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZRfpMain", "Language" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfptool.php" );


$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "imagelist.php" );

$t->setAllStrings();

$t->set_file( "image_list_page_tpl", "imagelist.tpl" );

$t->set_block( "image_list_page_tpl", "no_images_tpl", "no_images" );
$t->set_block( "image_list_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );

$rfp = new eZRfp( $RfpID );

$session =& eZSession::globalSession();
$session->setVariable( "ImageListReturnTo", $REQUEST_URI );
$session->setVariable( "SelectImages", "multi" );
$session->setVariable( "NameInBrowse", $rfp->name() );

$thumbnail = $rfp->thumbnailImage();

$t->set_var( "rfp_name", $rfp->name() );

$t->set_var( "site_style", $SiteStyle );

if ( isSet( $AddImages ) )
{
    if ( count( $ImageArrayID ) > 0 )
    {
        foreach ( $ImageArrayID as $imageID )
        {
            $image = new eZImage( $imageID );
            $rfp->addImage( $image );
        }
    }
}

$images = $rfp->images( true, "Placement" );
// Start Add By kadooz !

/** move rfp pics up/down **/
if ( is_numeric( $MoveImageUp ) || is_numeric( $MoveImageDown ) )
{
    if ( is_numeric( $MoveImageUp ) )
    {
        $rfp->moveImageUp( $MoveImageUp );
    }

    if ( is_numeric( $MoveImageDown ) )
    {
        $rfp->moveImageDown( $MoveImageDown );
    }
    eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/$RfpID/" );
    exit();
}

// End Add By kadooz !

if ( count( $images ) == 0 )
{
    $t->set_var( "image_list", "" );
    $t->parse( "no_images", "no_images_tpl", true );
}
else
{
    $t->set_var( "no_images", "" );
 
    $i=0;
    foreach ( $images as $image )
    {
        $placement = $image["Placement"];
        $image = $image["Image"];

        
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "thumbnail_image_checked", "" );
        if ( $thumbnail != 0 )
        {
            if ( $thumbnail->id() == $image->id() )
            {
                $t->set_var( "thumbnail_image_checked", "checked" );
            }
        }

        $t->set_var( "image_number", $placement );

        if ( $image->caption() == "" )
            $t->set_var( "image_name", "&nbsp;" );
        else
            $t->set_var( "image_name", $image->caption() );
        $t->set_var( "image_id", $image->id() );
        $t->set_var( "rfp_id", $RfpID );

        $variation =& $image->requestImageVariation( 150, 150 );

        $t->set_var( "image_url", "/" .$variation->imagePath() );
        $t->set_var( "image_width", $variation->width() );
        $t->set_var( "image_height",$variation->height() );

        $t->parse( "image", "image_tpl", true );

        $i++;
    }

    $t->parse( "image_list", "image_list_tpl", true );
}


$t->set_var( "rfp_id", $rfp->id() );

$t->pparse( "output", "image_list_page_tpl" );

?>
