<?php
//
// $Id: typeedit.php,v 1.1 2001/11/21 14:49:02 bf Exp $
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


if ( isset( $Store ) || isset( $NewItem ) )
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
        $item->store();

        eZHTTPTool::header( "Location: /datamanager/typeedit/" . $type->id() );
        exit();
    }
    else
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

$t->setAllStrings();

$t->set_var( "type_id", "" );
$t->set_var( "type_name", "" );
$t->set_var( "type_item_list", "" );

if ( $TypeID > 0 )
{
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

        $t->parse( "type_item", "type_item_tpl", true );
        $i++;
    }
    if ( count( $items ) > 0 )
        $t->parse( "type_item_list", "type_item_list_tpl" );
    else
        $t->set_var( "type_item_list", "" );
}

$t->pparse( "output", "type_edit_tpl" );

?>
