<?
// 
// $Id: adlist.php,v 1.5 2000/11/29 14:39:30 bf-cvs Exp $
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "ezad/classes/ezad.php" );
include_once( "ezad/classes/ezadcategory.php" );
include_once( "ezad/classes/ezadview.php" );

// no need to include this is is included in the index file.
//  $ini = new INIFIle( "site.ini" );


$Language = $ini->read_var( "eZAdMain", "Language" );

$t = new eZTemplate( "ezad/user/" . $ini->read_var( "eZAdMain", "TemplateDir" ),
                     "ezad/user/intl/", $Language, "adlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "ad_list_page_tpl" => "adlist.tpl"
    ) );


// ad
$t->set_block( "ad_list_page_tpl", "ad_list_tpl", "ad_list" );
$t->set_block( "ad_list_tpl", "ad_item_tpl", "ad_item" );


$category = new eZAdCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );


// fetch the user if any
$user =& eZUser::currentUser();


// ads
$adList =& $category->ads( "time" );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "ad_list", "" );
foreach ( $adList as $ad )
{
    if ( $ad->name() == "" )
        $t->set_var( "ad_name", "&nbsp;" );
    else
        $t->set_var( "ad_name", $ad->name() );

    $t->set_var( "ad_id", $ad->id() );

    $image =& $ad->image();

    // ad image
    if ( $image )
    {
        $t->set_var( "image_src",  $image->filePath() );
        $t->set_var( "image_width", $image->width() );
        $t->set_var( "image_height", $image->height() );
        $t->set_var( "image_file_name", $image->originalFileName() );
    }

    // store the view statistics
    $view = new eZAdView();
    $view->setAd( $ad );
    $view->setUser( $user );
    $view->setVisitorIP( $REMOTE_ADDR );
    $view->setPrice( 0.4 );
    $view->store();

        
    $t->parse( "ad_item", "ad_item_tpl", true );
    $i++;
}

if ( count( $adList ) > 0 )    
    $t->parse( "ad_list", "ad_list_tpl" );
else
    $t->set_var( "ad_list", "" );


$t->pparse( "output", "ad_list_page_tpl" );






?>
