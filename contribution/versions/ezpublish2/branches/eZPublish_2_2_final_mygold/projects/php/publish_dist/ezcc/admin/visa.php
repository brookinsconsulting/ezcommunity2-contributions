<?
// 
// $Id: visa.php,v 1.1.2.1 2002/04/16 10:44:08 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <02-Feb-2001 19:59:14 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezdatetime.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZCCMain", "Language" );

if ( $Action == "Verify" )
{
    $ValidThru = $ExpireMonth . $ExpireYear;
    
    $xmlSpecifics = "IC_PAN=\"$CCNumber\" IC_VALID_THRU=\"$ValidThru\"";
    $cardType = "VISA";
    include( "ezcc/admin/card.php" );

}

$t = new eZTemplate( "ezcc/admin/templates/mygold",  "ezcc/admin/intl", $Language, "visa.php" );

$t->set_file( "visa_tpl", "visa.tpl" );

$t->set_block( "visa_tpl", "error_tpl", "error" );


if ( $ClearingError == true )
{
    $t->set_var( "error_text", $RC_TEXT );
    $t->set_var( "error_code", $RC_CODE );
    $t->parse( "error", "error_tpl" );
}
else
{
    $t->set_var( "error", "" );
}

$t->setAllStrings();


$t->set_var( "amount", $Amount );
$t->set_var( "order_number", $OrderNumber );

$t->set_var( "order_id", $PreOrderID );
$t->set_var( "payment_type", $PaymentType );

$t->set_var( "cvc2_number", $CVC2Value );

$t->set_var( "card_number", $CCNumber );
$t->set_var( "valid_thru", $ValidThru );

$t->pparse( "output", "visa_tpl" );


?>
