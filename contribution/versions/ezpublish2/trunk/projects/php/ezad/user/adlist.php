<?
// 
// $Id: adlist.php,v 1.8 2000/12/01 10:01:47 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <25-Nov-2000 15:44:37 bf>
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


// NOTE: this page does not use templates due to speed.
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezad/classes/ezad.php" );
include_once( "ezad/classes/ezadcategory.php" );
include_once( "ezad/classes/ezadview.php" );

$category = new eZAdCategory( $CategoryID );

// fetch the user if any
$user =& eZUser::currentUser();

// ads
$adList =& $category->ads( "time" );

foreach ( $adList as $ad )
{
    $adID = $ad->id();

    $image =& $ad->image();

    // ad image
    if ( $image )
    {
        $imgSRC =& $image->filePath();

        $imgWidth =& $image->width();
        $imgHeight =& $image->height();
    }

    // store the view statistics
    $view = new eZAdView();
    $view->setAd( $ad );
    $view->setUser( $user );
    $view->setVisitorIP( $REMOTE_ADDR );
    $view->setPrice( $ad->viewPrice() );
    $view->store();

	print( "<a target=\"_blank\" href=\"/ad/goto/$adID/\"><img src=\"$imgSRC\" width=\"$imgWidth\" height=\"$imgHeight\" border=\"0\" alt=\"\" /></a><br />" );
}


?>
