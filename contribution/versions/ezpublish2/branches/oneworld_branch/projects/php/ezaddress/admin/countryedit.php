<?php
//
// $Id: countryedit.php,v 1.4.10.1 2002/06/03 15:03:13 pkej Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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


include_once( "ezaddress/classes/ezcountry.php" );
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZAddressMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZAddressMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

$language_file = "country.php";
$item_type = new eZCountry( $CountryID );

$page_path = "/address/country";

if ( isset( $ItemArrayID ) and is_array( $ItemArrayID ) )
{
    $item_types = array();
    foreach( $ItemArrayID as $item_id )
    {
        $item_types[] = new eZCountry( $item_id );
    }
    
    include( "ezaddress/admin/typeedit.php" ); 
}

if( empty( $HTTP_REFERER ) )
{
    if( empty( $BackUrl ) )
    {
        $back_command = "$page_path/list";
    }
    else
    {
        $back_command = $BackUrl;
    }
}
else
{
    $back_command = $HTTP_REFERER;
}


if( $Action == "insert" or $Action == "update" )
{
    $item_type->setName( $ItemName );
    $item_type->setISO( $ItemISO );
    $item_type->setParentID( $ItemParentID );

    if ( $ItemHasVAT = "on" )
    {
        $item_type->setHasVAT( true );
    }
    else
    {
        $item_type->setHasVAT( false );
    }

    $item_type->store();

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list/" );
}


$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZAddressMain", "AdminTemplateDir" ), $DOC_ROOT . "admin/intl", $Language, $language_file );
$t->setAllStrings();

$item_error = true;
$t->set_file( "country_edit_page", "countryedit.tpl" );

$t->set_block( "country_edit_page", "country_edit_tpl", "country_edit" );
$t->set_block( "country_edit_tpl", "value_tpl", "value" );



if( $Action == "edit" )
{
    $action_value = "update";
    $item_error = false;
    
    $ItemID = $item_type->id();
    $ItemName = $item_type->name();
    $ItemISO = $item_type->iso();
    $ItemHasVAT = $item_type->hasVAT();
    $ItemParentID = $item_type->parentID();
}

if( $Action == "new" )
{
    $action_value = "insert";
    $ItemParentID = 0;

    $item_error = false;
}

$t->set_var( "no_line_item", "" );
$t->set_var( "line_item", "" );

$t->set_var( "item_id", $ItemID );
$t->set_var( "item_name", $ItemName );
$t->set_var( "item_iso", $ItemISO );



if ( $ItemHasVAT == true || $ItemHasVAT == "on" )
{
    $t->set_var( "item_has_vat", "checked" );
}
else
{
    $t->set_var( "item_has_vat", "" );
}


$treeArray = eZCountry::getTree( 0, 0, -1, 0, -1, false );

foreach( $treeArray as $country )
{
    $t->set_var( "option_value", $country["ID"] );
    $t->set_var( "option_name", $country["Name"] );

    if ( $ItemParentID == $country["ID"] )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    if ( $country["Level"] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $country["Level"] ) );
    else
        $t->set_var( "option_level", "" );

    $t->parse( "value", "value_tpl", true );
}

$t->set_var( "back_url", $back_command );
$t->set_var( "item_back_command", $back_command );

$t->parse( "country_edit", "country_edit_tpl", true );

$t->set_var( "form_path", $page_path );
$t->set_var( "action_value", $action_value );
$t->pparse( "output", "country_edit_page" );


?>
