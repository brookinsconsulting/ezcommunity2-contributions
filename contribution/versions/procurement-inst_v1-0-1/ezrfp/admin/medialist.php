<?php
// 
// $Id: medialist.php,v 1.1 2001/07/25 12:32:20 ce Exp $
//
// Created on: <25-Jul-2001 11:02:48 ce>
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
include_once( "ezmediacatalogue/classes/ezmedia.php" );


$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "medialist.php" );

$t->setAllStrings();

$t->set_file( array(
    "media_list_page_tpl" => "medialist.tpl"
    ) );

$t->set_block( "media_list_page_tpl", "no_media_tpl", "no_media" );
$t->set_block( "media_list_page_tpl", "media_list_tpl", "media_list" );
$t->set_block( "media_list_tpl", "media_tpl", "media" );

$rfp = new eZRfp( $RfpID );

$session =& eZSession::globalSession();
$session->setVariable( "MediaListReturnTo", $REQUEST_URI );
$session->setVariable( "SelectMedia", "multi" );
$session->setVariable( "NameInBrowse", $rfp->name() );

$t->set_var( "rfp_name", $rfp->name() );

$t->set_var( "site_style", $SiteStyle );

if ( isSet ( $AddMedia ) )
{
    if ( count ( $MediaArrayID ) > 0 )
    {
        foreach( $MediaArrayID as $mediaID )
        {
            $media = new eZMedia( $mediaID );
            $rfp->addMedia( $media );
        }
    }
}

$media = $rfp->media();
if ( count( $media ) == 0 )
{
    $t->set_var( "media_list", "" );
    $t->parse( "no_media", "no_media_tpl", true );
}
else
{
    $t->set_var( "no_media", "" );

    $i=0;
    foreach ( $media as $media )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "media_number", $i + 1 );

        if ( $media->caption() == "" )
            $t->set_var( "media_name", "&nbsp;" );
        else
            $t->set_var( "media_caption", $media->caption() );

        $t->set_var( "media_name", $media->name() );
        $t->set_var( "media_description", $media->description() );
        $t->set_var( "media_id", $media->id() );
        $t->set_var( "rfp_id", $RfpID );

        $t->set_var( "media_url", $media->mediaPath() );
        $t->parse( "media", "media_tpl", true );

        $i++;
    }

    $t->parse( "media_list", "media_list_tpl", true );
}


$t->set_var( "rfp_id", $rfp->id() );

$t->pparse( "output", "media_list_page_tpl" );

?>
