<?
// 
// $Id: cutover.php,v 1.1.2.1 2001/11/15 16:25:01 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Apr-2001 12:15:32 bf>
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

$cutover_done = false;
if ( $Action == "Cutover" )
{
    $cutover_done = true;
    $dateTime = new eZDateTime();
    $date = $dateTime->year() . $dateTime->addZero( $dateTime->month() ) .  $dateTime->addZero( $dateTime->day() );
    $time = $dateTime->addZero( $dateTime->hour() ) . $dateTime->addZero( $dateTime->minute() ) .  $dateTime->addZero( $dateTime->second() );

    $taID = md5( microtime() );


    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <ICMessage IC_SHOP_ID=\"65 019\" IC_SHOP_TA_ID=\"$taID\" IC_TA_TYPE=\"910\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_PROCESSING_CODE=\"1\" IC_CLEARING_INFO=\"0,280,\"/>";

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
         <ICMessage IC_AMOUNT=\"1\" >";
    
    $execString = "checkout/socket.pl " . EscapeShellArg( $xml);

    print( "<b>Sending: </b><br>" . htmlspecialchars( $xml ) . "<br><br>" );
    
    $ret = system( $execString, $ret_var );
    
     print( "<br />" );
    
    print(  "<b>Receiving: </b><br>" . htmlspecialchars( $ret ) );

    $domtree =& qdom_tree( $ret );

    $RC_CODE = "";
    $success = false;
print( "<pre>" );
print_r( $domtree );
print( "</pre>" );
    if ( $domtree and $ret != "" )
    {
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
    }
    else
    {
        if ( $ret_var != 0 )
            $RC_TEXT = "Error when executing checkout/socket.pl";
        else
            $RC_TEXT = "Could not parse received xml";
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
//          if ( $RC_CODE != "" )
//          {
//              $session->setVariable( "PaymentTry", $tryNr + 1 );
//          }
    }
}

$t = new eZTemplate( "ezcc/admin/" . $ini->read_var( "eZCCMain", "AdminTemplateDir" ),
                     "ezcc/admin/intl/", $Language, "cutover.php" );

$t->set_file( "cutover_tpl", "cutover.tpl" );

$t->set_block( "cutover_tpl", "error_tpl", "error" );
$t->set_block( "cutover_tpl", "success_tpl", "success" );

$t->set_var( "success", "" );

$t->set_var( "error", "" );
if ( $ClearingError == true )
{
    $t->set_var( "error_text", $RC_TEXT );
    $t->set_var( "error_code", $RC_CODE );
    $t->parse( "error", "error_tpl" );
}
else if ( $cutover_done )
{
    $t->parse( "success", "success_tpl" );
}

$t->setAllStrings();

$t->pparse( "output", "cutover_tpl" );


?>
