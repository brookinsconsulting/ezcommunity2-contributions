<?
// 
// $Id: typeedit.php,v 1.3 2001/03/01 14:06:26 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <20-Dec-2000 18:24:06 bf>
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


if ( isset( $Cancel ) )
{
    Header( "Location: /trade/typelist/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproducttype.php" );
include_once( "eztrade/classes/ezproductattribute.php" );

if ( $Action == "Insert" )
{
    $type = new eZProductType();
    $type->setName( $Name );
    $type->setDescription( $Description );

    $type->store();

    Header( "Location: /trade/typelist/" );
    exit();
}

if ( $Action == "Update" )
{

    $type = new eZProductType( $TypeID );
    $type->setName( $Name );
    $type->setDescription( $Description );

    $type->store();

    // update attributes
    $i =0;
    if ( count( $AttributeName ) > 0 )
    {
        foreach ( $AttributeName as $attribute )
        {
            $att = new eZProductAttribute( $AttributeID[$i] );
            $att->setName( $attribute );
            $att->store();            
            
            $i++;
        }
    }


    if ( isset( $NewAttribute ) )
    {
        $attribute = new eZProductAttribute();
        $attribute->setType( $type );
        $attribute->setName( "New attribute" );
        $attribute->store();
        
        $Action = "Edit";        
    }
    else
    {
        if ( isset( $UpdateValues ) )
        {
            $Action = "Edit";
        }
        else
        {
            Header( "Location: /trade/typelist/" );
            exit();
        }
    }
}

if ( $Action == "Delete" )
{
    $type = new eZProductType();
    $type->get( $TypeID );

    $type->delete();
    
    Header( "Location: /trade/typelist/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "typeedit.php" );

$t->setAllStrings();

$t->set_file( array( "type_edit_tpl" => "typeedit.tpl" ) );


$t->set_block( "type_edit_tpl", "value_tpl", "value" );

$t->set_block( "type_edit_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$type = new eZProductType();

$typeArray = $type->getAll( );

$t->set_var( "attribute_list", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "type_id", "" );
$t->set_var( "action_value", "Insert" );

// edit
if ( $Action == "Edit" )
{
    $type = new eZProductType();
    $type->get( $TypeID );

    $t->set_var( "name_value", $type->name() );
    $t->set_var( "description_value", $type->description() );
    $t->set_var( "action_value", "Update" );
    $t->set_var( "type_id", $type->id() );


    $attributes = $type->attributes();

    foreach ( $attributes as $attribute )
    {
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );
        
        $t->parse( "attribute", "attribute_tpl", true );
    }

    if ( count( $attributes ) > 0 )
    {
        $t->parse( "attribute_list", "attribute_list_tpl", true );
    }
    else
    {
        $t->set_var( "attribute_list", "", true );
    }
    
}


$t->pparse( "output", "type_edit_tpl" );

?>
