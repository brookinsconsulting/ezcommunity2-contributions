<?php
//
// $Id: typelist.php,v 1.1 2001/11/21 14:49:02 bf Exp $
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

if ( isset( $DeleteTypes ) )
{
    foreach ( $TypeIDArray as $typeID )
    {
        $type = new eZDataType( $typeID );
        $type->delete(); 
    }
    eZHTTPTool::header( "Location: /datamanager/typelist/" . $TypeID );
    exit();    
}     

if ( isset( $DeleteItems ) )
{
    foreach ( $ItemIDArray as $itemID )
    {
        $item = new eZDataItem( $itemID );
        $item->delete(); 
    }
    eZHTTPTool::header( "Location: /datamanager/typelist/" . $TypeID );
    exit();        
}     

$Language = $ini->read_var( "eZDataManagerMain", "Language" );

$t = new eZTemplate( "ezdatamanager/admin/" . $ini->read_var( "eZDataManagerMain", "AdminTemplateDir" ),
                     "ezdatamanager/admin/intl", $Language, "typelist.php" );

$t->set_file( "type_list_page_tpl", "typelist.tpl" );
$t->set_block( "type_list_page_tpl", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_tpl", "type" );

$t->set_block( "type_list_page_tpl", "item_list_tpl", "item_list" );
$t->set_block( "item_list_tpl", "item_tpl", "item" );

$t->setAllStrings();

$t->set_var( "current_type_name", "" );
$t->set_var( "current_type_id", "" );

$type = new eZDataType( );
$types =& $type->getAll();

$i=0;
foreach ( $types as $type )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->set_var( "type_name", $type->name() );
    $t->set_var( "type_id", $type->id() );
    $t->parse( "type", "type_tpl", true );
    $i++;
}

if ( count( $types ) > 0 )    
    $t->parse( "type_list", "type_list_tpl" );
else
    $t->set_var( "type_list", "" );


$t->set_var( "item_list", "" );

if ( $TypeID > 0 )
{
    $t->set_var( "type_list", "" );

    $type = new eZDataType( $TypeID );

    $t->set_var( "current_type_name", $type->name() );
    $t->set_var( "current_type_id", $type->id() );
    
    $valueItems =& $type->items();
    $i=0;
    foreach ( $valueItems as $item )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
    
        $t->set_var( "item_name", $item->name() );
        $t->set_var( "item_id", $item->id() );
        $t->parse( "item", "item_tpl", true );
        $i++;
    }

    if ( count( $valueItems ) > 0 )
    {
        $t->parse( "item_list", "item_list_tpl" );
    }
    else
        $t->set_var( "item_list", "" );
} 

$t->pparse( "output", "type_list_page_tpl" );

?>
