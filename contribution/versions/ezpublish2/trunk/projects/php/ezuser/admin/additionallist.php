<?php
//
// $Id: additionallist.php,v 1.1 2001/11/16 16:16:42 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <16-Nov-2001 13:58:37 ce>
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

//!! 
//! The class ||| does
/*!

*/

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$move_item = true;

include_once( "ezuser/classes/ezuseradditional.php" );

if ( ( isset( $Update ) ) or ( isset ( $New ) ) or ( isset ( $Delete ) ) )
{
    $i=0;
    foreach( $AdditionalArrayID as $additionalID )
    {
        $additional = new eZUserAdditional( $additionalID );
        $additional->setName( $Name[$i] );
        $additional->store();
        $i++;
    }
}

if ( isset( $New ) )
{
    $additional = new eZUserAdditional( );
    $additional->store();
}

if( isset( $Delete ) )
{
    foreach( $DeleteArrayID as $additionalID )
    {
        $additional = new eZUserAdditional( $additionalID );
        $additional->delete();
    }
}

if( $Action == "up" )
{
    $additional = new eZUserAdditional( $AdditionalID );
    $additional->moveUp();
}

if( $Action == "down" )
{
    $additional = new eZUserAdditional( $AdditionalID );
    $additional->moveDown();
}


$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl/", $Language, "additionallist.php" );

$t->setAllStrings();

$t->set_file( array(
    "additional_list_page_tpl" => "additionallist.tpl"
    ) );

$t->set_block( "additional_list_page_tpl", "additional_list_tpl", "additional_list" );
$t->set_block( "additional_list_tpl", "additional_item_tpl", "additional_item" );
$t->set_block( "additional_item_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "additional_item_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "additional_item_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "additional_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "additional_item_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "additional_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

$t->set_var( "no_item_move_down", "" );
$t->set_var( "no_item_separator", "" );
$t->set_var( "no_item_move_up", "" );
$t->set_var( "item_move_down", "" );
$t->set_var( "item_separator", "" );
$t->set_var( "item_move_up", "" );


$t->set_var( "site_style", $SiteStyle );

$additional = new eZUserAdditional(  );

$additionalList = $additional->getAll( );

$count = count ( $additionalList );
$i=0;
$t->set_var( "additional_list", "" );
foreach ( $additionalList as $additionalItem )
{
    $t->set_var( "additional_id", $additionalItem->id() );

    $t->set_var( "additional_name", $additionalItem->name() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    if ( $i > 0 && isset( $move_item ) )
    {
        $t->parse( "item_move_up", "item_move_up_tpl" );
    }
    else
    {
        $t->parse( "no_item_move_up", "no_item_move_up_tpl" );
    }
        
    if ( $i > 0 && $i < $count - 1 && isset( $move_item ) )
    {
        $t->parse( "item_separator", "item_separator_tpl" );
    }
    else
    {
        $t->parse( "no_item_separator", "no_item_separator_tpl" );
    }
        
    if ( $i < $count - 1 && isset( $move_item ) )
    {
        $t->parse( "item_move_down", "item_move_down_tpl" );
    }
    else
    {
        $t->parse( "no_item_move_down", "no_item_move_down_tpl" );
    }

    
    $t->parse( "additional_item", "additional_item_tpl", true );
    $i++;
}

if ( count( $additionalList ) > 0 )    
    $t->parse( "additional_list", "additional_list_tpl" );
else
    $t->set_var( "additional_list", "" );

$t->pparse( "output", "additional_list_page_tpl" );

?>
