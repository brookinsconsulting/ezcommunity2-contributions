<?php
// 
// $Id: productspecialslist.php,v 1.1.2.1 2002/04/16 10:44:08 ce Exp $
//
// Created on: <22-Sep-2000 16:13:32 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezmodulelink.php" );
include_once( "classes/ezlinksection.php" );
include_once( "classes/ezlinkitem.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductspecials.php" );
include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "eztrade/admin/". $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "productspecialslist.php" );

$t->set_file( "product_specials_list_tpl", "productspecialslist.tpl"  );

$t->setAllStrings();

$t->set_block( "product_specials_list_tpl", "product_special_list_tpl", "product_special_list" );
$t->set_block( "product_specials_list_tpl", "no_specials_tpl", "no_specials" );
$t->set_block( "product_special_list_tpl", "product_numbers_tpl", "product_numbers" );


// Delete if required
if ( isset( $HTTP_POST_VARS["Delete"] ) and isset( $HTTP_POST_VARS["DeleteArray"] ) and count( $HTTP_POST_VARS["DeleteArray"] ) > 0 )
{
    foreach ( $HTTP_POST_VARS["DeleteArray"] as $delete_id )
    {
	$special = new eZProductSpecial( $delete_id );
	$special->delete();
    }
    
    eZHTTPTool::header( "Location: /trade/productspecialslist/" );
    exit();
}

$specials = new eZProductSpecial();
$specials = $specials->getAll();

if ( count( $specials ) > 0 )
{
    foreach ( $specials as $special )
    {
        $t->set_var( "special_name", $special->getSpecialName() );
	$t->set_var( "special_id", $special->getID() );
    
	$product_numbers = explode( ";", $special->getProductNumbers() );
    
	foreach ( $product_numbers as $product_number )
	{
    	    $t->set_var( "product_number", $product_number );    
	    $t->parse( "product_numbers", "product_numbers_tpl", true );

        }
	
	$t->set_var( "no_specials", "" );
	$t->parse( "product_special_list", "product_special_list_tpl", true );
	$t->set_var( "product_numbers", "" );
    }
}
else
{
    $t->set_var( "product_special_list", "" );
    $t->parse( "no_specials", "no_specials_tpl" );
}

$t->pparse( "output", "product_specials_list_tpl" );

?>
