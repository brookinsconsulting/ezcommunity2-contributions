<?php
//
// $Id: advancedsearch.php,v 1.1 2001/11/23 18:14:04 bf Exp $
//
// Created on: <20-Nov-2001 17:23:58 bf>
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
include_once( "ezdatamanager/classes/ezdataitem.php" );
include_once( "ezarticle/classes/ezarticlegenerator.php" );

if ( isset( $SelectType ) )
{
    $ItemTypeID = $NewItemTypeID;
}


$Language = $ini->read_var( "eZDataManagerMain", "Language" );

$t = new eZTemplate( "ezdatamanager/user/" . $ini->read_var( "eZDataManagerMain", "TemplateDir" ),
                     "ezdatamanager/user/intl", $Language, "advancedsearch.php" );

$locale = new eZLocale( $Language );

$t->set_file( "advanced_search_tpl", "advancedsearch.tpl" );
$t->set_block( "advanced_search_tpl", "item_type_option_tpl", "item_type_option" );
$t->set_block( "advanced_search_tpl", "item_value_list_tpl", "item_value_list" );
$t->set_block( "item_value_list_tpl", "item_value_tpl", "item_value" );
$t->set_block( "advanced_search_tpl", "item_list_tpl", "item_list" );
$t->set_block( "item_list_tpl", "item_tpl", "item" );


$t->setAllStrings();

$type = new eZDataType();
$types =& $type->getAll();

$t->set_var( "item_name", $ItemNameText );
$t->set_var( "item_value_list", "" );

foreach ( $types as $type )
{
    if ( $ItemTypeID > 0 )
    {

        if ( $ItemTypeID == $type->id() )            
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );        
    }
    else
        $t->set_var( "selected", "" );

    $t->set_var( "type_id", $type->id() );
    $t->set_var( "type_name", $type->name() );

    $t->parse( "item_type_option", "item_type_option_tpl", true );
}

if ( $NewItemTypeID > 0 )
{
    $t->set_var( "data_type_value", "" );

    $t->set_var( "item_name", $ItemNameText );
    $t->set_var( "item_type_id", $NewItemTypeID );

    $dataType = new eZDataType( $NewItemTypeID );
    $dataTypeItems =& $dataType->typeItems();

    $generator = new eZArticleGenerator();

    foreach ( $dataTypeItems as $dataTypeItem )
    {
        $t->set_var( "data_type_value", $TypeItemText[$dataTypeItem->id()] );

        $t->set_var( "data_type_name", $dataTypeItem->name() );
        $t->set_var( "data_type_id", $dataTypeItem->id() );

        $t->parse( "item_value", "item_value_tpl", true );
    }
    $t->parse( "item_value_list", "item_value_list_tpl" );
}

// show result
$t->set_var( "item_list", "" );

if ( isset( $Search ) )
{
    $valueItems =& eZDataItem::search( array( "ItemName" => $ItemNameText,
                                              "ItemTypeArray" => $TypeItemText )
                                              );
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
    
        $t->set_var( "item_result_name", $item->name() );
        $t->set_var( "item_result_id", $item->id() );
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

$t->pparse( "output", "advanced_search_tpl" );

?>
