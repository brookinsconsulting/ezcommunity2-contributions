<?
// 
// $Id: elv.php,v 1.1.2.1 2002/04/16 10:44:08 ce Exp $
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
include_once( "classes/ezlog.php" );
include_once( "ezcc/classes/ezcclog.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

if ( $Action == "Verify" )
{
    // add CC verification here

    $dateLog = new eZDate();
    $timeLog = new eZTime();
     
    $date = $dateLog->year() . $dateLog->addZero( $dateLog->month() ) .  $dateLog->addZero( $dateLog->day() );
    $time = date( "His" );

    $tryNr = $session->variable( "PaymentTry" ) +100 ;
    setType( $tryNr, "integer" );
    $taID = $PreOrderID . "#" . $tryNr;
    
    $Amount = $Amount * 100;
    
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> <ICMessage IC_SHOP_ID=\"65 019\" IC_SHOP_TA_ID=\"$taID\" IC_BLZ=\"$BlzCode\" IC_KTO_NR=\"$AccountNR\" IC_TA_TYPE=\"110\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_AMOUNT=\"$Amount\" IC_CURRENCY=\"978\" IC_PROCESSING_CODE=\"1\" IC_SHOP_CUSTOM1=\"$PreOrderID\" />";
    $execString = "checkout/socket.pl " . EscapeShellArg( $xml);

    print( "<b>Sending: </b><br>" . htmlspecialchars( $xml ) . "<br><br>" );
    
    $ret = system( $execString );
    
    print(  "<b>Receiving: </b><br>" . htmlspecialchars( $ret ) );

    $domtree =& qdom_tree( $ret );

    if ( strlen( $BlzCode ) != 8 )
        $BLZ_ERROR = true;
    else
        $BLZ_ERROR = false;

    $RC_CODE = "";
    $success = false;
    foreach ( $domtree->children as $child )
    {
        if ( count( $child->attributes )> 0 )
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

    // Write to log file
    $logFile = "checkout/log/checkout.log";

    $log = new eZCCLog();
    $log->setType( "ELV" );
    $log->setPreOrderID( $PreOrderID );
    $log->setTaID( $TA_ID );
    $log->setDate( $dateLog );
    $log->setTime( $timeLog );
    $log->setAmount( $Amount );
    if ( $RC_CODE != "00" )
        $log->setStatus( 3 );
    else
        $log->setStatus( 0 );
    $log->setRcCode( $RC_CODE );
    $log->setRcText( $RC_TEXT );
    $log->setBLZ( $BlzCode );
    $log->setAcctNR( $AccountNR );
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
            $session->setVariable( "PaymentTry", $tryNr + 11 );
        }
    }
}

$t = new eZTemplate( "ezcc/admin/" . $ini->read_var( "eZCCMain", "TemplateDir" ),
                     "ezcc/admin/intl/", $Language, "elv.php" );
		     

$t->set_file( "elv_tpl", "elv.tpl" );

$t->set_block( "elv_tpl", "error_tpl", "error" );

$t->set_var( "amount", $Amount/100 );
$t->set_var( "blz", $BlzCode );
$t->set_var( "pre_order_id", $PreOrderID );
$t->set_var( "act_nr", $AccountNR );

if ( $ClearingError == true )
{
    if ( $BLZ_ERROR == true )
        $RC_TEXT = "Eine Bankleitzahl ist immer 8-stellig, bitte korrigieren Sie Ihre Eingabe.";

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

$t->set_var( "account_nr", $AccountNR );
$t->set_var( "blz_code", $BlzCode );
$t->set_var( "charge_total", $ChargeTotal );


$t->pparse( "output", "elv_tpl" );


?>
