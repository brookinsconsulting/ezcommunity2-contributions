<?php
//
// $Id: headlines.php,v 1.18.2.8 2003/06/02 10:46:23 jhe Exp $
//
// Created on: <30-Nov-2000 14:35:24 bf>
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
include_once( "classes/ezcachefile.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$ImageDir = $ini->read_var( "eZRfpMain", "ImageDir" );

if ( !function_exists( "createHeadlinesMenu" )  )
{
    function createHeadlinesMenu( $menuCacheFile=false )
        {
            global $ini;
            global $Language;
            global $GlobalSiteDesign;
            global $CategoryID;
            global $Limit;


            include_once( "ezrfp/classes/ezrfpcategory.php" );
            include_once( "ezrfp/classes/ezrfp.php" );
            include_once( "ezrfp/classes/ezrfprenderer.php" );

            $t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                                 "ezrfp/user/intl/", $Language, "headlines.php" );

            $t->setAllStrings();

            $t->set_file( array(
                              "rfp_list_page_tpl" => "headlines.tpl"
                              ) );


// product
            $t->set_block( "rfp_list_page_tpl", "rfp_list_tpl", "rfp_list" );
            $t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );
            $t->set_block( "rfp_item_tpl", "current_image_item_tpl", "current_image_item" );


// image dir
            $t->set_var( "image_dir", $ImageDir );

            if ( !isset( $Limit ) )
            {
                $Limit = 10;
            }

            if ( !isset( $HeadlineOffset ) )
            {
                $HeadlineOffset = 0;
            }

            $category = new eZRfpCategory( $CategoryID );

            if ( $CategoryID == 0 )
            {
                // do not set offset for the main page news
                // always sort by publishing date is the merged category
                $rfp = new eZRfp();
                $rfpList =& $rfp->rfps( "time", false, $HeadlineOffset, $Limit );
                $rfpCount = $rfp->rfpCount( false );
            }
            else
            {
                $rfpList =& $category->rfps( $category->sortMode(), false, true, $HeadlineOffset, $Limit );
                $rfpCount = $category->rfpCount( false, true  );
            }


// should we allow currentuser to go get rfps with permissions or should we not??
//$rfpList = $category->rfps( $SortMode, false, true, 0, 5 );

            $locale = new eZLocale( $Language );
            $i=0;
            $t->set_var( "rfp_list", "" );
            foreach ( $rfpList as $rfp )
            {
                $t->set_var( "category_id", $CategoryID );

                $t->set_var( "rfp_id", $rfp->id() );
                $t->set_var( "rfp_name", $rfp->name() );

                // category image/icon
                $catDef = $rfp->categoryDefinition();

                $image =& $catDef->image();

                $t->set_var( "current_image_item", "" );

                if ( ( get_class( $image ) == "ezimage" ) && ( $image->id() != 0 ) )
                {
                    $imageWidth =& $ini->read_var( "eZRfpMain", "CategoryImageWidth" );
                    $imageHeight =& $ini->read_var( "eZRfpMain", "CategoryImageHeight" );

                    $variation =& $image;

                    $imageURL = $variation->filePath( );
                    $imageWidth =& $variation->width();
                    $imageHeight =& $variation->height();
                    $imageCaption =& $image->caption();

                    $t->set_var( "current_image_width", $imageWidth );
                    $t->set_var( "current_image_height", $imageHeight );
                    $t->set_var( "current_image_url", $imageURL );
                    $t->set_var( "current_image_caption", $imageCaption );
                    $t->parse( "current_image_item", "current_image_item_tpl" );
                }
                else
                {
                    $t->set_var( "current_image_item", "" );
                }

                $published =& $rfp->published();
                $date =& $published->date();

                $t->set_var( "rfp_published", $locale->format( $date ) );

                if ( ( $i % 2 ) == 0 )
                {
                    $t->set_var( "td_class", "bglight" );
                    $t->set_var( "td_alt", "1" );
                }
                else
                {
                    $t->set_var( "td_class", "bgdark" );
                    $t->set_var( "td_alt", "2" );
                }

                if ( $rfp->linkText() != "" )
                {
                    $t->set_var( "rfp_link_text", $rfp->linkText() );
                }
                else
                {
                    $t->set_var( "rfp_link_text", "more" );
                }

                $t->parse( "rfp_item", "rfp_item_tpl", true );
                $i++;
            }

            if ( count( $rfpList ) > 0 )
                $t->parse( "rfp_list", "rfp_list_tpl" );
            else
                $t->set_var( "rfp_list", "" );

            if ( get_class( $menuCacheFile ) == "ezcachefile" )
            {
                $output =& $t->parse( $target, "rfp_list_page_tpl" );
                $menuCacheFile->store( $output );
                print( $output );
            }
            else
            {
                $t->pparse( "output", "rfp_list_page_tpl" );
            }
        }
}

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $user =& eZUser::currentUser();
    $groupstr = "";
    if ( get_class( $user ) == "ezuser" )
    {
        $groupIDArray =& $user->groups( false );
        sort( $groupIDArray );
        $first = true;
        foreach ( $groupIDArray as $groupID )
        {
            $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
            $first = false;
        }
    }
    else
        $user = 0;

    $menuCacheFile = new eZCacheFile( "ezrfp/cache",
                                      array( "menubox_headlines", $GlobalSiteDesign, $CategoryID, $groupstr ),
                                      "cache", "," );

    if ( $menuCacheFile->exists() )
    {
        print( $menuCacheFile->contents() );
    }
    else
    {
        createHeadlinesMenu( $menuCacheFile );
    }
}
else
{
    createHeadlinesMenu();
}

?>
