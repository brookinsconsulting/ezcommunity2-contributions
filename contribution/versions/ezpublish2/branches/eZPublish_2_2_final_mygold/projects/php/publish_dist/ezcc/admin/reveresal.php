<?
// 
// $Id: reveresal.php,v 1.1.2.1 2001/11/15 16:25:01 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Apr-2001 14:29:10 bf>
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

$error = false;
$reversal_done = false;
$error_array = array();

if ( $Action == "Reversal" )
{
    if ( $RefID == "" or $Amount == "" )
    {
        $error_array[] = "input_error";
    }
    else
    {
        $reversal_done = true;
        $dateTime = new eZDateTime();
        $date = $dateTime->year() . $dateTime->addZero( $dateTime->month() ) .  $dateTime->addZero( $dateTime->day() );
        $time = $dateTime->addZero( $dateTime->hour() ) . $dateTime->addZero( $dateTime->minute() ) .  $dateTime->addZero( $dateTime->second() );

        $taID = md5( microtime() );
        $refID = $RefID;
        $ammount = $Amount;

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> <ICMessage IC_TA_REF_ID=\"$refID\" IC_SHOP_ID=\"65 019\" IC_SHOP_TA_ID=\"$taID\" IC_TA_TYPE=\"119\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_AMOUNT=\"$ammount\" IC_CURRENCY=\"280\" IC_PROCESSING_CODE=\"1\" />";

        $execString = "checkout/socket.pl " . EscapeShellArg( $xml );

//      print( "<b>Sending: </b><br>" . htmlspecialchars( $xml ) . "<br><br>" );
    
        $ret = system( $execString );
    
//      print( "<br />" );
    
//    print(  "<b>Receiving: </b><br>" . htmlspecialchars( $ret ) );

        $domtree =& qdom_tree( $ret );

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
                }
        }

        if ( $RC_CODE == "00" )
        {
            $PaymentSuccess = "true";
            $ClearingError = false;
        
        }
        else
        {
            $PaymentSuccess = "false";
            $ClearingError = true;
//              if ( $RC_CODE != "" )
//              {
//                  $session->setVariable( "PaymentTry", $tryNr + 1 );
//              }
        }
    }
}

$t = new eZTemplate( "ezcc/admin/" . $ini->read_var( "eZCCMain", "AdminTemplateDir" ),
                     "ezcc/admin/intl/", $Language, "reversal.php" );

$t->set_file( "reversal_tpl", "reversal.tpl" );

$t->set_block( "reversal_tpl", "error_tpl", "error" );
$t->set_block( "error_tpl", "input_error_tpl", "input_error" );
$t->set_block( "error_tpl", "gateway_error_tpl", "gateway_error" );

$t->set_block( "reversal_tpl", "success_tpl", "success" );

$t->set_var( "success", "" );
if ( $ClearingError == true )
{
    $t->set_var( "error_text", $RC_TEXT );
    $t->set_var( "error_code", $RC_CODE );
    $error = true;
    $error_array[] = "gateway_error";
}
else if ( $reversal_done )
{
    $t->parse( "success", "success_tpl" );
}

$t->set_var( "error", "" );
if ( $error = true )
{
    $t->set_var( "input_error", "" );
    $t->set_var( "gateway_error", "" );
    foreach( $error_array as $error_item )
    {
        $t->parse( $error_item, $error_item . "_tpl" );
    }
    $t->parse( "error", "error_tpl" );
}

$t->setAllStrings();


$t->pparse( "output", "reversal_tpl" );


?>
