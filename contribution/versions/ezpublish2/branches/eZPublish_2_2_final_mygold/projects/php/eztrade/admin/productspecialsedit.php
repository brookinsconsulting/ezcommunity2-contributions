<?php
//
//
// Sascha Frinken <sf@mygold.com>
// Copyright (C) 2002 MyGold.com.  All rights reserved.
//
// This file is available at developer.ez.no. It is part of the 3rd party
// extension trade productspecials from the ez publish (publishing software).
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
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The GNU General Public License is also available online at:
//
// http://www.gnu.org/copyleft/gpl.html
//

include_once( "classes/eztemplate.php" );
include_once( "eztrade/classes/ezproductspecials.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ), 
                       "eztrade/admin/" . "intl", $Language, "productspecialsedit.php" );

$t->setAllStrings();

$t->set_file( "product_specials_edit_page_tpl", "productspecialsedit.tpl" );

$t->set_block( "product_specials_edit_page_tpl", "form_tpl", "form" );
$t->set_block( "product_specials_edit_page_tpl", "result_tpl", "result" );
$t->set_block( "product_specials_edit_page_tpl", "error_tpl", "error" );

$t->set_var( "form", "" );
$t->set_var( "result", "" );
$t->set_var( "error", "" );

// Edit Existing Record
if ( isset( $SpecialID ) and !isset( $Update )  )
{
    $special = $special = new eZProductSpecial( $SpecialID );
    
    $t->set_var( "special_name", $special->getSpecialName() );
    $t->set_var( "description", $special->getDescription() );
    $t->set_var( "product_numbers", $special->getProductNumbers() );
    $t->set_var( "special_id", $SpecialID );
    $t->parse( "form", "form_tpl" );
}

// Show empty Form to fetch Data for a new Record
if ( !isset( $SpecialID ) and !isset( $Update ) )
{
    $t->set_var( "special_name", "" );
    $t->set_var( "description", "" );    
    $t->set_var( "product_numbers", "" );
    $t->set_var( "special_id", "" );
    $t->parse( "form", "form_tpl" );    
}

// Store new or updated record
if( $Update )
{
    $store_error = false;
    
    if ( isset( $SpecialID ) and $SpecialID != "" )
    {
        $special = $special = new eZProductSpecial( $SpecialID );
    	$special->setSpecialName( $SpecialName );
	$special->setDescription( $Description );
    	$special->setProductNumbers( $ProductNumbers );		
    	
	if ( !$special->store() )
	{
	    $store_error = true;
	}
    }
    else
    {
        $special = $special = new eZProductSpecial();
    	$special->setSpecialName( $SpecialName );
	$special->setDescription( $Description );
    	$special->setProductNumbers( $ProductNumbers );		
	
	if ( !$special->store() )
	{
	    $store_error = true;
	}
    }
    
    if ( $store_error )
    {
        $t->parse( "error", "error_tpl" );
    }
    else
    {
	$t->parse( "result", "result_tpl" );
    }
}

$t->pparse( "output", "product_specials_edit_page_tpl" );


?>
