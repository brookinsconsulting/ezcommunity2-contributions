<?php
//
// $Id: itemview.php,v 1.1 2001/11/21 14:49:02 bf Exp $
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

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezqdomrenderer.php" );

$Language = $ini->read_var( "eZDataManagerMain", "Language" );

$t = new eZTemplate( "ezdatamanager/user/" . $ini->read_var( "eZDataManagerMain", "TemplateDir" ),
                     "ezdatamanager/user/intl", $Language, "itemview.php" );

$locale = new eZLocale( $Language );

$t->set_file( "item_edit_tpl", "itemview.tpl" );
$t->set_block( "item_edit_tpl", "item_value_list_tpl", "item_value_list" );
$t->set_block( "item_value_list_tpl", "item_value_tpl", "item_value" );

$t->setAllStrings();

$type = new eZDataType();
$types =& $type->getAll();

$t->set_var( "item_id", $ItemID );

$t->set_var( "item_name", $ItemName );
$t->set_var( "item_value_list", "" );

if ( $ItemID > 0 )
{
    $t->set_var( "data_type_value", "" );

    $item = new eZDataItem( $ItemID );
    $t->set_var( "item_name", $item->name() );

    $dataType =& $item->dataType();
    $dataTypeItems =& $dataType->typeItems();

    $article = new eZArticle();
    $renderer = new eZQDomRenderer( $article );
    foreach ( $dataTypeItems as $dataTypeItem )
    {     
        $article->setContents( $item->itemValue( $dataTypeItem ) );
        
        $t->set_var( "data_type_value", $renderer->renderIntro() );

        $t->set_var( "data_type_name", $dataTypeItem->name() );
        $t->set_var( "data_type_id", $dataTypeItem->id() );

        $t->parse( "item_value", "item_value_tpl", true );
    }
    $t->parse( "item_value_list", "item_value_list_tpl" );
}

$t->pparse( "output", "item_edit_tpl" );

?>
