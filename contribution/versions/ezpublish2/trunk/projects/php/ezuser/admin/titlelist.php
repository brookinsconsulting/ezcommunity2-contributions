<?php
// 
// $Id: titlelist.php,v 1.1 2001/10/26 12:30:49 bf Exp $
//
// Created on: <26-Oct-2001 13:33:01 bf>
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

include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

include_once( "ezuser/classes/eztitle.php" );



if ( isset( $NewTitle ) )
{
    $title = new eZTitle( );
    $title->store();    
}

if ( isset( $DeleteTitle ) )
{
    if ( count( $DeleteIDArray )  > 0 )
    foreach ( $DeleteIDArray as $id )
    {
        eZTitle::delete( $id );
    }
}


if ( ( isset( $Store ) ) || ( isset ( $NewTitle ) ) ||( isset ( $DeleteTitle ) ) )
{
    $i=0;

    if ( count( $IDArray )  > 0 )
    foreach ( $IDArray as $id )
    {
        $title = new eZTitle( $id );
        $title->setName( $Name[$i] );
        $title->store();

        $i++;
    }

}

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl", $Language, "titlelist.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "title_page_tpl", "titlelist.tpl" );

$t->setAllStrings();

$t->set_block( "title_page_tpl", "title_list_tpl", "title_list" );
$t->set_block( "title_list_tpl", "title_item_tpl", "title_item" );

$t->set_var( "title_item", "" );

$title = new eZTitle( );

$titleArray =& $title->getAll();

$i=0;
if ( count ( $titleArray ) > 0 )
{
    foreach ( $titleArray as $title )
    {
        $t->set_var( "id", $title->id() );
        $t->set_var( "title_name", $title->name() );
        
        $t->parse( "title_item", "title_item_tpl", true );

    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    $i++;
	}
    
}
$t->parse( "title_list", "title_list_tpl" );

$t->pparse( "output", "title_page_tpl" );

?>
