<?php
//
// $Id: search.php,v 1.1 2001/11/21 14:49:02 bf Exp $
//
// Created on: <21-Nov-2001 12:42:41 bf>
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

$Language = $ini->read_var( "eZDataManagerMain", "Language" );

$t = new eZTemplate( "ezdatamanager/user/" . $ini->read_var( "eZDataManagerMain", "TemplateDir" ),
                     "ezdatamanager/user/intl", $Language, "search.php" );

$t->set_file( "search_page_tpl", "search.tpl" );

$t->set_block( "search_page_tpl", "item_list_tpl", "item_list" );
$t->set_block( "item_list_tpl", "item_tpl", "item" );

$t->setAllStrings();

$t->set_var( "search_text", $SearchText );

$t->set_var( "item_list", "" );

if ( isset( $SearchText ) )
{
    $t->set_var( "search", "" );

    $valueItems =& eZDataItem::search( $SearchText );
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

$t->pparse( "output", "search_page_tpl" );

?>
