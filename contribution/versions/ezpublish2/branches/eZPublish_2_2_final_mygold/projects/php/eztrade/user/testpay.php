<?php
//
// $Id: testpay.php,v 1.1.2.1 2002/06/10 09:57:18 ce Exp $
//
// Created on: <02-Feb-2001 17:42:45 bf>
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

$PaymentSuccess = "false";
$VerifySuccess = "false";

// Checks if the payment is valid
if ( $Action == "Verify" )
{
    if ( $VerifyData["CCNumber"] == "321" )
        $VerifySuccess = "true";
}

// Do the payment
if ( $Action == "Pay" )
{
    $VerifyData = $session->arrayValue( "VerifyData" );
    if ( $VerifyData["CCNumber"] == "321" )
    {
        $PaymentSuccess = "true";
    }
    else
    {
        $PaymentSuccess = "false";
    }
}

?>
