<?php
// 
// $Id: userlist.php,v 1.1 2001/08/29 19:12:43 fh Exp $
//
// Created on: <28-Aug-2001 15:02:02 fh>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" );

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/admin/intl/", $Language, "userlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "user_list_page_tpl" => "userlist.tpl"
    ) );

$t->set_block( "user_list_page_tpl", "category_item_tpl", "category_item" );
$t->set_var( "category_item", "" );

$t->set_block( "user_list_page_tpl", "address_tpl", "address" );
$t->set_block( "address_tpl", "address_item_tpl", "address_item" );
$t->set_var( "address", "" );

$t->set_block( "user_list_page_tpl", "no_subscribers_tpl", "no_subscribers" );
$t->set_var( "no_subscribers", "" );

// some logic to set the CategoryID right. (we can get it both from url and from the list)
if( is_numeric( $ListID ) )
    $CategoryID = $ListID;

$categories = eZBulkMailCategory::getAll();
foreach( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );
    if( $CategoryID == $category->id() )
        $t->set_var( "category_selected", "selected" );
    else
        $t->set_var( "category_selected", "" );
        
    $t->parse( "category_item", "category_item_tpl", true );
}

if( $CategoryID != 0 )
{
    // list normal subscribers..
    $i = 0;
    $addresses = eZBulkMailCategory::subscribers( true, $CategoryID );
    foreach( $addresses as $address )
    {
        $t->set_var( "subscriber_address", $address->eMail() );
        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
        $t->parse( "address_item", "address_item_tpl", true );
        $i++;
    }

    // list users forced by group addition

    if( $i > 0 )
        $t->parse( "address", "address_tpl", false );
    else
        $t->parse( "no_subscribers", "no_subscribers_tpl", false );
}

$t->pparse( "output", "user_list_page_tpl" );
?>
