<?php
// 
// $Id: pendinglist.php,v 1.1 2001/08/16 09:56:22 ce Exp $
//
// Created on: <15-Aug-2001 16:30:02 ce>
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

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfptool.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$Locale = new eZLocale( $Language );
$AdminListLimit = $ini->read_var( "eZRfpMain", "AdminListLimit" );
$languageIni = new INIFIle( "ezrfp/admin/intl/" . $Language . "/pendinglist.php.ini", false );

if( isset( $DeleteRfps ) )
{
    if ( count ( $RfpArrayID ) != 0 )
    {
        foreach( $RfpArrayID as $TRfpID )
        {
            if( eZObjectPermission::hasPermission( $TRfpID, "rfp_rfp", 'w' )
                || eZRfp::isAuthor( eZUser::currentUser(), $TRfpID ) )
            {
                $rfp = new eZRfp( $TRfpID );

                // get the category to redirect to
                $rfpID = $rfp->id();

                $categoryArray = $rfp->categories();
                $categoryIDArray = array();
                foreach ( $categoryArray as $cat )
                {
                    $categoryIDArray[] = $cat->id();
                }
                $categoryID = $rfp->categoryDefinition();
                $categoryID = $categoryID->id();

                // clear the cache files.
                eZRfpTool::deleteCache( $TRfpID, $categoryID, $categoryIDArray );
                $rfp->delete();
            }
        }
    }
}

$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "pendinglist.php" );

$t->setAllStrings();

$t->set_file( array(
    "pending_list_page_tpl" => "pendinglist.tpl"
    ) );


// rfp
$t->set_block( "pending_list_page_tpl", "rfp_list_tpl", "rfp_list" );
$t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );

$t->set_block( "rfp_item_tpl", "rfp_is_published_tpl", "rfp_is_published" );
$t->set_block( "rfp_item_tpl", "rfp_not_published_tpl", "rfp_not_published" );
$t->set_block( "rfp_item_tpl", "rfp_edit_tpl", "rfp_edit" );


// prev/next
$t->set_block( "pending_list_page_tpl", "previous_tpl", "previous" );
$t->set_block( "pending_list_page_tpl", "next_tpl", "next" );

$t->set_var( "site_style", $SiteStyle );


// set the offset/limit
if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = $AdminListLimit;

// rfps
$rfp = new eZRfp();

$rfpList =& $rfp->rfps( "time", "pending",  $Offset, $Limit );
$rfpCount = $rfp->rfpCount( "pending" );

$i=0;
$t->set_var( "rfp_list", "" );


foreach ( $rfpList as $rfp )
{
    if( eZObjectPermission::hasPermission( $rfp->id(), "rfp_rfp", 'r') ||
        eZRfp::isAuthor( eZUser::currentUser(), $rfp->id() ) )
    {
        if ( $rfp->name() == "" )
            $t->set_var( "rfp_name", "&nbsp;" );
        else
            $t->set_var( "rfp_name", $rfp->name() );

        $t->set_var( "rfp_id", $rfp->id() );

        if ( $rfp->isPublished() == true )
        {
            $t->parse( "rfp_is_published", "rfp_is_published_tpl" );
            $t->set_var( "rfp_not_published", "" );        
        }
        else
        {
            $t->set_var( "rfp_is_published", "" );
            $t->parse( "rfp_not_published", "rfp_not_published_tpl" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        
        if( eZObjectPermission::hasPermission( $rfp->id(), "rfp_rfp", 'w') ||
            eZRfp::isAuthor( eZUser::currentUser(), $rfp->id() ) )
        {
            $t->parse( "rfp_edit", "rfp_edit_tpl", false );
        }
        else
            $t->set_var( "rfp_edit", "" );


        $t->parse( "rfp_item", "rfp_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $rfpCount, $AdminListLimit, $Offset, "pending_list_page_tpl" );


if ( count( $rfpList ) > 0 )    
    $t->parse( "rfp_list", "rfp_list_tpl" );
else
    $t->set_var( "rfp_list", "" );


$t->pparse( "output", "pending_list_page_tpl" );


?>
