<?php
// 
// $Id: visa.php,v 1.6 2001/07/20 11:42:02 jakobn Exp $
//
// Created on: <08-Feb-2001 14:11:48 ce>
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
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezcctool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

$checkVar = eZHTTPTool::getVar( "a" );

if ( $checkVar == true )
{
    // add clearing code here
//    if ( eZCCTool::checkCC( $CCNumber, $ExpierMonth, $ExpierYear ) )
    $PaymentSuccess = "true";
}

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "visa.php" );

$t->set_file( "visa_tpl", "visa.tpl" );

$t->setAllStrings();

// $ChargeTotal is the value to charge the customer with

$user = eZUser::currentUser();

$t->set_var( "f", $ini->read_var( "eZCCMain", "PID" ) );
$t->set_var( "l", $ini->read_var( "eZCCMain", "Language" ) );
$t->set_var( "m", $ini->read_var( "eZCCMain", "VendorID" ) );
$t->set_var( "d", $ini->read_var( "eZCCMain", "Currency" ) );
$t->set_var( "p", $ini->read_var( "eZCCMain", "p" ) );
$t->set_var( "i", $ChargeTotal );

$t->set_var( "email", $user->email() );
$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );
$t->set_var( "referer_url", $GLOBALS["HTTP_REFERER"] );
$t->set_var( "card_type", 1 );

$t->set_var( "order_id", $PreOrderID );
$t->set_var( "payment_type", $PaymentType );

$t->pparse( "output", "visa_tpl" );
?>
