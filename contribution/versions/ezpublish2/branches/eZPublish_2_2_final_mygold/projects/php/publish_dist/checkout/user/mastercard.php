<?
// 
// $Id: mastercard.php,v 1.1.2.1 2001/11/22 09:52:40 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <08-Feb-2001 13:49:48 bf>
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

$Language = $ini->read_var( "eZTradeMain", "Language" );

if ( $Action == "Verify" )
{
    $ValidThru = $ExpireMonth . $ExpireYear;
    
    //   $xmlSpecifics = "IC_BLZ=\"$BlzCode\" IC_KTO_NR=\"$AccountNR\"";
    $xmlSpecifics = "IC_PAN=\"$CCNumber\" IC_VALID_THRU=\"$ValidThru\"";
    $cardType = "MCARD";
    include( "checkout/user/card.php" );
}

$t = new eZTemplate( "checkout/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "checkout/user/intl/", $Language, "mastercard.php" );

$t->set_file( "mastercard_tpl", "mastercard.tpl" );

$t->set_block( "mastercard_tpl", "error_tpl", "error" );


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

$t->set_var( "order_id", $PreOrderID );
$t->set_var( "payment_type", $PaymentType );


$t->set_var( "cvc2_number", $CVC2Value );

$t->set_var( "card_number", $CCNumber );
$t->set_var( "valid_thru", $ValidThru );

$t->pparse( "output", "mastercard_tpl" );


?>
