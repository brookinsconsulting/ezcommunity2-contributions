<?php
// 
// $Id: sourcesiteedit.php,v 1.2 2000/12/06 12:48:36 ce-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Nov-2000 17:55:31 bf>
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZNewsFeedMain", "Language" );
$ImageDir = $ini->read_var( "eZNewsFeedMain", "ImageDir" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "eznewsfeed/classes/ezsourcesite.php" );

include_once( "eznewsfeed/classes/eznewscategory.php" );

if ( $Action == "Insert" )
{
    $sourcesite = new eZSourceSite();
    
    $sourcesite->setName( $SourceSiteName );
    $sourcesite->setURL( $SourceSiteURL );
    $sourcesite->setLogin( $SourceSiteLogin );
    $sourcesite->setPassword( $SourceSitePassword );
    $category = new eZNewsCategory( $CategoryID );
    $sourcesite->setCategory( $catgory );
    $sourcesite->setDecoder( $SourceSiteDecoder );

    if ( $SourceSiteIsActive == "on" )
    {
        $sourcesite->setIsActive( true );
    }
    else
    {
        $sourcesite->setIsActive( false  );
    }

    $sourcesite->store();

    Header( "Location: /newsfeed/archive/$CategoryID/" );
    exit();
}

if ( $Action == "Update" )
{
    print( "ohman" );
    $sourcesite = new eZSourceSite( $SourceSiteID );
    
    $sourcesite->setName( $SourceSiteName );
    $sourcesite->setURL( $SourceSiteURL );
    $sourcesite->setLogin( $SourceSiteLogin );
    $sourcesite->setPassword( $SourceSitePassword );
    $category = new eZNewsCategory( $CategoryID );
    $sourcesite->setCategory( $category );
    $sourcesite->setDecoder( $SourceSiteDecoder );

    if ( $SourceSiteIsActive == "on" )
    {
        $sourcesite->setIsActive( true );
    }
    else
    {
        $sourcesite->setIsActive( false  );
    }

    $sourcesite->store();

    Header( "Location: /newsfeed/archive/$CategoryID/" );
    exit();
}

if ( $Action == "Delete" )
{
    $sourcesite = new eZSourceSite( $SourceSiteID );
    $sourcesite->delete();

    Header( "Location: /newsfeed/archive/$CategoryID/" );
    exit();

}



$t = new eZTemplate( "eznewsfeed/admin/" . $ini->read_var( "eZNewsFeedMain", "AdminTemplateDir" ),
                     "eznewsfeed/admin/intl/", $Language, "sourcesiteedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "news_edit_page_tpl" => "sourcesiteedit.tpl"
    ) );

$t->set_block( "news_edit_page_tpl", "value_tpl", "value" );

if ( $Action == "New" )
{
    $t->set_var( "source_site_name_value", "" );
    $t->set_var( "source_site_id_value", "" );
    $t->set_var( "source_site_url_value", "" );
    $t->set_var( "source_site_login_value", "" );
    $t->set_var( "source_site_password_value", "" );
    $t->set_var( "source_site_decoder_value", "" );
    $t->set_var( "action_value", "Insert" );    
}



if ( $Action == "Edit" )
{
    $sourcesite = new eZSourceSite( $SourceSiteID );

    $t->set_var( "source_site_name_value", $sourcesite->name() );
    $t->set_var( "source_site_url_value", $sourcesite->url() );
    $t->set_var( "source_site_login_value", $sourcesite->login() );
    $t->set_var( "source_site_password_value", $sourcesite->password() );
    $t->set_var( "source_site_decoder_value", $sourcesite->decoder() );
    $t->set_var( "source_site_id", $sourcesite->id() );
    $t->set_var( "action_value", "update" );

    $category = $sourcesite->category();

    if ( $sourcesite->isActive() == true )
    {
        $t->set_var( "source_site_isactive_value", "checked" );
    }
    else
    {
        $t->set_var( "source_site_isactive", "" );
    }
  
}

// category select
$category = new eZNewsCategory();
$categoryArray = $category->getAll( );

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        if ( $category->ID() == $catItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
    }    
    
    $t->set_var( "option_value", $catItem->id() );
    $t->set_var( "option_name", $catItem->name() );

    $t->parse( "value", "value_tpl", true );    
}


$t->pparse( "output", "news_edit_page_tpl" );

?>

