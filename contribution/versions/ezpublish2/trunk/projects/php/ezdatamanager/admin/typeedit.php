<?php
//
// $Id: typeedit.php,v 1.4 2002/02/09 15:06:29 br Exp $
//
// Created on: <20-Nov-2001 15:04:53 bf>
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

include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

include_once( "ezdatamanager/classes/ezdatatype.php" );
include_once( "ezdatamanager/classes/ezdatatypeitem.php" );

function parseType( &$t, $value )
{
    $t->set_var( "string", "" );
    $t->set_var( "relation", "" );
    $t->set_var( "type_relation_list", "" );

    switch ( $value )
    {
        case "1":
        {
            $t->set_var( "string", "selected" );
        }
        break;
        case "2":
        {
            $t->set_var( "relation", "selected" );
            
        }
        break;
    }
}

function parseRelation( &$t, $relationID )
{
    $t->set_var( "type_relation_list", "" );
    $t->set_var( "type_relation_item", "" );
    $type = new eZDataType( );
    $types =& $type->getAll();
    foreach ( $types as $type )
    {
        $t->set_var( "select_type_name", $type->name() );
        $t->set_var( "select_type_id", $type->id() );

        if ( $type->id() == $relationID )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
        
        $t->parse( "type_relation_item", "type_relation_item_tpl", true );
        $i++;
    }
    $t->parse( "type_relation_list", "type_relation_list_tpl" );
}


if ( isset( $DeleteItems ) )
{
    foreach ( $DeleteItemArray as $itemID )
    {
        $item = new eZDataTypeItem( $itemID );
        $item->delete();
    }

    eZHTTPTool::header( "Location: /datamanager/typeedit/" . $TypeID );
    exit();    
}


if ( isset( $Store ) || isset( $NewItem ) || isSet( $Update ) )
{
    if ( $TypeID > 0 )
    {
        $type = new eZDataType( $TypeID );
    }
    else
    {
        $type = new eZDataType( );
    }

    $i=0;
    foreach ( $ItemIDArray as $itemID )
    {
        $item = new eZDataTypeItem( $itemID );
        $item->setName( $ItemName[$i] );
        $item->setDataType( $type );
        $item->setItemType( $EditItemTypeIDArray[$i] );

        // if the type is a relation (item type = 2)
        if ( $EditItemTypeIDArray[$i] == 2 )
        {
            $relationIDString = "TypeRelationID_$itemID";
//            print( $$relationIDString . ",");
            $relationID = $$relationIDString;
            $item->setRelation( $relationID );
        }
        
        $item->store();

        $i++;
    }

    $type->setName( $TypeName );
    $type->store();

    if (  isset( $NewItem ) )
    {
        $item = new eZDataTypeItem( );
        $item->setName( "" );
        $item->setDataType( $type );
        $item->setItemType( $NewItemTypeID );
        $item->store();

        eZHTTPTool::header( "Location: /datamanager/typeedit/" . $type->id() );
        exit();
    }
    else if ( !isSet( $Update ) )
    {
        eZHTTPTool::header( "Location: /datamanager/typelist/" );
        exit();
    }
}

$Language = $ini->read_var( "eZDataManagerMain", "Language" );

$t = new eZTemplate( "ezdatamanager/admin/" . $ini->read_var( "eZDataManagerMain", "AdminTemplateDir" ),
                     "ezdatamanager/admin/intl", $Language, "typeedit.php" );

$locale = new eZLocale( $Language );

$t->set_file( "type_edit_tpl", "typeedit.tpl" );
$t->set_block( "type_edit_tpl", "type_item_list_tpl", "type_item_list" );
$t->set_block( "type_item_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "type_item_tpl", "type_relation_list_tpl", "type_relation_list" );
$t->set_block( "type_relation_list_tpl", "type_relation_item_tpl", "type_relation_item" );
$t->setAllStrings();

$t->set_var( "type_item", "" );
$t->set_var( "type_id", "" );
$t->set_var( "type_name", "" );
$t->set_var( "type_item_list", "" );

if ( $TypeID > 0 )
{
    // if this is the first parsing of the site.
    if ( count( $EditItemTypeIDArray ) > 0 )
    {
        // fetch the items from the form variables.
        $t->set_var( "type_id", $TypeID );
        $t->set_var( "type_name", $TypeName );

        for ( $i=0; $i < count( $EditItemTypeIDArray ); $i++ )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_class", "bgdark" );
            }
            
            $t->set_var( "item_id", $ItemIDArray[$i] );
            $t->set_var( "item_name", $ItemName[$i] );

            parseType( $t, $EditItemTypeIDArray[$i] );

            if ( $EditItemTypeIDArray[$i] == 2 )
            {
                $relationIDString = "TypeRelationID_" . $ItemIDArray[$i];
                $relationID = $$relationIDString;
                parseRelation( $t, $relationID );
            }
            $t->parse( "type_item", "type_item_tpl", true );
        }
    }
    else
    {
        // fetch the items from the database.
        $type = new eZDataType( $TypeID );
        
        $t->set_var( "type_id", $type->id() );
        $t->set_var( "type_name", $type->name() );
        
        $items =& $type->typeItems();
        $i = 0;
        foreach ( $items as $item )
        {

            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_class", "bgdark" );
            }

            $t->set_var( "item_id", $item->id() );
            $t->set_var( "item_name", $item->name() );

            $itemTypeID = $item->itemType();
            parseType( $t, $itemTypeID );

            if ( $itemTypeID == 2 )
            {
                $relationID = $item->relationID();
                parseRelation( $t, $relationID );
            }
           
            $t->parse( "type_item", "type_item_tpl", true );
            $i++;
        }
    }
    $t->parse( "type_item_list", "type_item_list_tpl" );
}

$t->pparse( "output", "type_edit_tpl" );

?>
