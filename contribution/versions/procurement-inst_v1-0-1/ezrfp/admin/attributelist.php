<?php
// 
// $Id: attributelist.php,v 1.4.2.1 2003/01/14 16:20:19 br Exp $
//
// Created on: <05-Jun-2001 13:07:24 pkej>
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
include_once( "classes/ezhttptool.php" );

include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfpattribute.php" );
include_once( "ezrfp/classes/ezrfptype.php" );
include_once( "ezrfp/classes/ezrfptool.php" );

$rfp = new eZRfp( $RfpID );
$category = $rfp->categoryDefinition( );
$CategoryID = $category->id();

if( isset( $DeleteSelected ) )
{
    $count = count( $TypeArrayID );

    for( $i = 0; $i < $count; $i++ )
    {
        $type = new eZRfpType( $TypeArrayID[$i] );
        $rfp->deleteAttributesByType( $type );
    }
    eZRfpTool::deleteCache( $RfpID, $CategoryID, $CategoryArray );
}

if( isset( $NewType ) )
{

     $types = $rfp->types();
     $typeExists = false;

     foreach ( $types as $type )
     {
         if( $type->id() == $TypeID )
         {
             $typeExists = true;
         }
     }

     if( !$typeExists )
     {
         $type = new eZRfpType( $TypeID );
         $attributes = $type->attributes();

         foreach( $attributes as $attribute )
         {
             $attribute->setValue( $rfp, htmlspecialchars( "" ) );
         }
     } 
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );

$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "attribute.php" );

$t->setAllStrings();

$t->set_file( array(
    "arttribute_list_page_tpl" => "attributelist.tpl"
    ) );

$t->set_block( "arttribute_list_page_tpl", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "arttribute_list_page_tpl", "type_list_select_tpl", "type_list_select" );
$t->set_block( "arttribute_list_page_tpl", "no_types_select_item_tpl", "no_types_select_item" );
$t->set_block( "type_list_select_tpl", "type_item_select_tpl", "type_item_select" );
$t->set_block( "arttribute_list_page_tpl", "no_types_item_tpl", "no_types_item" );


$types = $rfp->types();

$typeCount = count( $types );

if( $typeCount > 0 )
{
    $i=0;
    foreach( $types as $type )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        $t->set_var( "type_id", $type->id() );
        $t->set_var( "type_name", $type->name() );
        $t->parse( "type_item", "type_item_tpl", true );
        $i++;
    }
    $t->parse( "type_list", "type_list_tpl" );
    $t->set_var( "no_types_item", "" );
}
else
{
    $t->parse( "no_types_item", "no_types_item_tpl" );
    $t->set_var( "type_list", "" );
}

$types =& eZRfpType::getAll();
$t->set_var( "selected", "" );
$t->set_var( "type_id", "" );
$t->set_var( "type_name", "" );

$typeCount = count( $types );

if( $typeCount > 0 )
{
    foreach( $types as $type )
    {
        $t->set_var( "type_id", $type->id() );
        $t->set_var( "type_name", $type->name() );
        
        $t->parse( "type_item_select", "type_item_select_tpl", true );
    }


    $t->parse( "type_list_select", "type_list_select_tpl" );
    $t->set_var( "no_types_select_item", "" );
}
else
{
    $t->parse( "no_types_select_item", "no_types_select_item_tpl" );
    $t->set_var( "type_list_select", "" );
}

$typeCount = count( $types );



$t->set_var( "rfp_name", $rfp->name() );
$t->set_var( "rfp_id", $rfp->id() );

$t->set_var( "site_style", $SiteStyle );

$t->pparse( "output", "arttribute_list_page_tpl" );

?>
