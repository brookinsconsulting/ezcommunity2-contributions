<?php
// 
// $Id: importnews.php,v 1.8 2001/02/01 13:03:04 th Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <16-Nov-2000 13:02:19 bf>
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

include_once( "eznewsfeed/classes/eznews.php" );
include_once( "eznewsfeed/classes/eznewscategory.php" );
include_once( "eznewsfeed/classes/eznewsimporter.php" );
include_once( "eznewsfeed/classes/ezsourcesite.php" );

include_once( "classes/ezdatetime.php" );

// fetch one site
if ( $Action == "Fetch" )
{
    $site = new eZSourceSite( $SourceSiteID );

    print( "importing news from :" .  $site->url() );
    
    $newsImporter = new eZNewsImporter( $site->decoder(),
                                        $site->url(),
                                        $site->category(),
                                        $site->login(),
                                        $site->password(),
                                        $site->autoPublish() );
    $newsImporter->importNews();
}

// fetch every site
if ( $Action == "ImportNews" )
{
    $sourceSite = new eZSourceSite();
    
    $sourceSiteList = $sourceSite->getAll();
    
    foreach ( $sourceSiteList as $site )
    {
        unset( $newsImporter );
        $newsImporter = new eZNewsImporter( $site->decoder(),
                                            $site->url(),
                                            $site->category(),
                                            $site->login(),
                                            $site->password(),
                                            $site->autoPublish() );
        $newsImporter->importNews();
    }    
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZNewsfeedMain", "Language" );

$t = new eZTemplate( "eznewsfeed/admin/" . $ini->read_var( "eZNewsfeedMain", "AdminTemplateDir" ),
                     "eznewsfeed/admin/intl/", $Language, "importnews.php" );

$t->setAllStrings();

$t->set_file( array(
    "import_news_tpl" => "importnews.tpl"
    ) );

$t->set_block( "import_news_tpl", "source_site_list_tpl", "source_site_list" );
$t->set_block( "source_site_list_tpl", "source_site_tpl", "source_site" );

$t->set_var( "site_style", $SiteStyle );

//  $newsCategory = new eZNewsCategory( 7 );

$sourceSite = new eZSourceSite();


//  $sourceSite->setName( "Linux.com" );
//  $sourceSite->setDecoder( "rdf" );
//  $sourceSite->setURL( "http://www.linux.com/mrn/front_page.rss" );
//  //  $sourceSite->setLogin( "seanexftp" );
//  //  $sourceSite->setPassword( "20-nye-fisk" );
//  $sourceSite->setCategory( $newsCategory );
//  $sourceSite->store();


$sourceSiteList = $sourceSite->getAll();

$i=0;
foreach ( $sourceSiteList as $site )
{
    $t->set_var( "source_site_id", $site->id() );
    $t->set_var( "source_site_name", $site->name() );
    $t->set_var( "source_site_url", $site->url() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $i++;
    $t->parse( "source_site", "source_site_tpl", true );
}

$t->parse( "source_site_list", "source_site_list_tpl" );

$sourceSite = new eZSourceSite();

//  $newsImporter = new eZNewsImporter( "nyheter.no" );
//  $newsImporter->importNews();

//  $newsImporter = new eZNewsImporter( "freshmeat.net" );
//  $newsImporter->importNews();

$t->pparse( "output", "import_news_tpl" );

?>
