<?php
//
// $Id: itemedit.php,v 1.4 2002/02/10 11:55:29 bf Exp $
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

include_once( "ezuser/classes/ezusergroup.php" );


if ( isset( $Store ) )
{
    $item = new eZDataItem( $ItemID );

    $item->setName( $ItemName );
    $item->setOwnerGroup( $ItemOwnerGroupID );
    
    $item->store();
    
    $dataType =& $item->dataType();
    $dataTypeItems =& $dataType->typeItems();

    $generator = new eZArticleGenerator();
    foreach ( $dataTypeItems as $dataTypeItem )
    {
        unset( $contentsArray );

        switch ( $ItemValueTypeID[$dataTypeItem->id()] )
        {
            case "1" :
            {
                $contentsArray[] = $ItemValueArray[$dataTypeItem->id()];
                $contents = $generator->generateXML( $contentsArray );
                $item->setItemValue( $dataTypeItem, $contents );
                
            }break;

            case "2" :
            {
                $item->setItemValue( $dataTypeItem, $ItemValueArray[$dataTypeItem->id()] );
            }break;
        }
        


    }

    eZHTTPTool::header( "Location: /datamanager/typelist/" . $dataType->id() );
    exit();    
}

if ( isset( $SelectType ) )
{
    if ( $ItemID > 0 )
    {
        $item = new eZDataItem( $ItemID );
    }
    else
    {
        $item = new eZDataItem( );
    }
    $item->setName( $ItemName );
    
    $type = new eZDataType( $NewItemTypeID );
    $item->setDataType( $type );
    $item->store();

    eZHTTPTool::header( "Location: /datamanager/itemedit/" . $item->id() );
    exit();    
}

$Language = $ini->read_var( "eZDataManagerMain", "Language" );

$t = new eZTemplate( "ezdatamanager/admin/" . $ini->read_var( "eZDataManagerMain", "AdminTemplateDir" ),
                     "ezdatamanager/admin/intl", $Language, "itemedit.php" );

$locale = new eZLocale( $Language );

$t->set_file( "item_edit_tpl", "itemedit.tpl" );
$t->set_block( "item_edit_tpl", "item_type_option_tpl", "item_type_option" );
$t->set_block( "item_edit_tpl", "item_owner_group_tpl", "item_owner_group" );
$t->set_block( "item_edit_tpl", "item_value_list_tpl", "item_value_list" );
$t->set_block( "item_value_list_tpl", "item_value_tpl", "item_value" );

$t->set_block( "item_value_tpl", "text_item_tpl", "text_item" );
$t->set_block( "item_value_tpl", "relation_item_tpl", "relation_item" );
$t->set_block( "relation_item_tpl", "relation_item_value_tpl", "relation_item_value" );


$t->setAllStrings();

$type = new eZDataType();
$types =& $type->getAll();

$t->set_var( "item_id", $ItemID );

$t->set_var( "item_name", $ItemName );
$t->set_var( "item_value_list", "" );

foreach ( $types as $type )
{
    if ( $ItemID > 0 )
    {
        $item = new eZDataItem( $ItemID );
        $tType =& $item->dataType();

        if ( $tType->id() == $type->id() )            
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

$item = new eZDataItem( $ItemID );


// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

foreach ( $groupList as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    if ( $ItemID > 0 )
    {
        if ( $item->ownerGroup( false ) == $group->id() )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );        
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->parse( "item_owner_group", "item_owner_group_tpl", true );
}

if ( $ItemID > 0 )
{
    $t->set_var( "data_type_value", "" );

    $item = new eZDataItem( $ItemID );
    $t->set_var( "item_name", $item->name() );

    $dataType =& $item->dataType();
    $dataTypeItems =& $dataType->typeItems();

    $generator = new eZArticleGenerator();

    foreach ( $dataTypeItems as $dataTypeItem )
    {
        $t->set_var( "item_value_type_id", $dataTypeItem->itemType() );

        $t->set_var( "text_item", "" );
        $t->set_var( "relation_item", "" );
        switch ( $dataTypeItem->itemType() )
        {
            case "1" :
            {
                if ( trim( $item->itemValue( $dataTypeItem ) ) != "" )
                    $contentsArray = $generator->decodeXML( $item->itemValue( $dataTypeItem ) );
                else
                    $contentsArray[] = "";

                $t->set_var( "data_type_value", $contentsArray[0] );
                $t->set_var( "data_type_name", $dataTypeItem->name() );
                $t->set_var( "data_type_id", $dataTypeItem->id() );

                $t->parse( "text_item", "text_item_tpl" );

            }break;

            case "2" :
            {
                $value = $item->itemValue( $dataTypeItem );

                $t->set_var( "data_type_name", $dataTypeItem->name() );
                $t->set_var( "data_type_value", $contentsArray[0] );
                $t->set_var( "data_type_id", $dataTypeItem->id() );
                
                $dataItems = eZDataItem::getAll( $dataTypeItem->relationID() );

                $t->set_var( "relation_item_value", "" );
                foreach ( $dataItems as $dataItem )
                {
                    if ( $value == $dataItem->id() )
                        $t->set_var( "selected", "selected" );
                    else
                        $t->set_var( "selected", "" );
                    
                    $t->set_var( "relation_type_id", $dataItem->id() );                    
                    $t->set_var( "relation_type_name", $dataItem->name() );

                    $t->parse( "relation_item_value", "relation_item_value_tpl", true);                    
                }

                $t->parse( "relation_item", "relation_item_tpl" );

            }break;
            
        }

        $t->parse( "item_value", "item_value_tpl", true );
    }
    $t->parse( "item_value_list", "item_value_list_tpl" );
}

$t->pparse( "output", "item_edit_tpl" );

?>
