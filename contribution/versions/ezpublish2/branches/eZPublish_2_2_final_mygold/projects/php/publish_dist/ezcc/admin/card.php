<?php
// 
// $Id: card.php,v 1.1.2.1 2002/04/16 10:44:08 ce Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <17-Apr-2001 14:11:54 amos>
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

// General code for creditcard validation
// Included by:
// visa.php, mastercard.php

include_once( "ezcc/classes/ezcclog.php" );
include_once( "classes/ezlog.php" );

// add CC verification here

$dateLog = new eZDate();
$timeLog = new eZTime();

$date = $dateLog->year() . $dateLog->addZero( $dateLog->month() ) .  $dateLog->addZero( $dateLog->day() );
$time = date("His");

$tryNr = $session->variable( "PaymentTry" );
setType( $tryNr, "integer" );
$taID = $OrderNumber . "#" . $tryNr;

//    print( $taID );
 
$ammount = $Amount * 100;
         

$ValidThru = $ExpireMonth . $ExpireYear;

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> <ICMessage IC_SHOP_ID=\"65 019\" IC_CVC2=\"$CVC2Value\" IC_SHOP_TA_ID=\"$taID\" $xmlSpecifics IC_TA_TYPE=\"110\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_AMOUNT=\"$ammount\" IC_CURRENCY=\"978\" IC_PROCESSING_CODE=\"1\" IC_SHOP_CUSTOM1=\"$OrderNumber\" />";

$execString = "checkout/socket.pl " . EscapeShellArg( $xml);

//print( "<b>Sending: </b><br>" . htmlspecialchars( $xml ) . "<br><br>" );

$ret = system( $execString );

//print(  "<b>Receiving: </b><br>" . htmlspecialchars( $ret ) );


$domtree =& qdom_tree( $ret );


$RC_CODE = "";
$success = false;
foreach ( $domtree->children as $child )
{
    if ( count( $child->attributes ) > 0 )
        foreach ( $child->attributes as $attribute )
        {
            if ( $attribute->name == "IC_RC_CODE" )
            {
                $RC_CODE = $attribute->content;
            }

            if ( $attribute->name == "IC_RC_TEXT" )
            {
                $RC_TEXT = $attribute->content;
            }
            if ( $attribute->name == "IC_TA_ID" )
            {
                $TA_ID = $attribute->content;
            }
        }
}


$log = new eZCCLog();
$log->setType( $cardType );
$log->setPreOrderID( $OrderNumber );
$log->setTaID( $TA_ID );
$log->setDate( $dateLog );
$log->setTime( $timeLog );
$log->setAmount( $ammount );

if ( $RC_CODE != "00" )
    $log->setStatus( 3 );
else
    $log->setStatus( 0 );


$log->setBLZ( 00000000 );
$log->setAcctNR( 000000000 );
$log->setRcCode( $RC_CODE );
$log->setRcText( $RC_TEXT );
$log->store();



if ( $RC_CODE == "00" )
{
    $PaymentSuccess = "true";
    $ClearingError = false;
}
else
{
    $PaymentSuccess = "false";
    $ClearingError = true;
    if ( $RC_CODE != "" )
    {
        $session->setVariable( "PaymentTry", $tryNr + 1 );
    }

}
    
//      print( $RC_CODE );
            
//      print( "<pre>" );
//      print_r( $domtree );
//      print( "</pre>" );    

?>
