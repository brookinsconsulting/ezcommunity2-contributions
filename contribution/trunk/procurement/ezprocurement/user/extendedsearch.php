<?php
// 
// $Id: extendedsearch.php,v 1.3 2001/07/19 12:19:21 jakobn Exp $
//
// Created on: <29-Mar-2001 11:15:24 amos>
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
include_once( "classes/ezlist.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "extendedsearch.php" );

$t->setAllStrings();

$t->set_file( "extended_search_tpl", "extendedsearch.tpl" );

$t->set_block( "extended_search_tpl", "search_item_tpl", "search_item" );
$t->set_block( "search_item_tpl", "category_item_tpl", "category_item" );

$t->set_block( "extended_search_tpl", "rfp_list_tpl", "rfp_list" );
$t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );

$contents =& eZRfp::shortContents();
$t->set_var( "category_item", "" );
foreach( $contents as $content )
{
    $t->set_var( "category_id", $content );
    $t->set_var( "category_name", $content );
    $t->set_var( "category_level", "" );
    $t->set_var( "category_selected", $Category == $content ? "selected" : "" );
    $t->parse( "category_item", "category_item_tpl", true );
}
$t->set_var( "search_text", $SearchText );
$t->set_var( "search_url_text", $SearchText == "" ? "+" : $SearchText );

$t->parse( "search_item", "search_item_tpl" );

$t->set_var( "rfp_list", "" );

if ( !is_numeric( $Offset ) )
    $Offset = 0;
if ( !is_numeric( $Max ) )
    $Max = 4;

if ( isset( $Search ) )
{
    $words = preg_split( "/[, ]+/", $SearchText );
    $keywords = array();
    foreach( $words as $word )
    {
        $keyword = strtolower( trim( $word ) );
        if ( $keyword != "" )
            $keywords[] = $keyword;
    }
    $rfps =& eZRfp::searchByShortContent( $Category, $keywords, $Offset, $Max );
    $t->set_var( "rfp_item", "" );
    $t->set_var( "category", $Category == "" ? "+" : $Category );
    foreach( $rfps as $rfp )
    {
        $t->set_var( "rfp_id", $rfp->id() );
        $t->set_var( "rfp_name", $rfp->name() );
        $t->set_var( "rfp_page", 1 );
        $cats =& $rfp->categories( false );
        $t->set_var( "rfp_category", $cats[0] );
        $t->parse( "rfp_item", "rfp_item_tpl", true );
    }
    $rfpCount = eZRfp::searchByShortContent( $Category, $keywords, true );

    eZList::drawNavigator( $t, $rfpCount, $Max, $Offset, "rfp_list_tpl" );

    $t->parse( "rfp_list", "rfp_list_tpl" );

}

$t->pparse( "output", "extended_search_tpl" );

?>
