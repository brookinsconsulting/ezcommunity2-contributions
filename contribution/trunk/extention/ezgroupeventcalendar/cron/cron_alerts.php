#!/usr/local/bin/php -q
<?
// 
// $Id: cron_alert.php,v 1.14.2.8 2004/09/29 05:58:00 ghb Exp $
//
// Created on: <29-Sep-2004 05:56:00 ghb>
// 
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 2001-2004 Brookins Consulting.  All rights reserved.
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

//------------------------------------------------
// Index Placement Header
// Find out, where our files are.
//------------------------------------------------

set_time_limit( 0 );

if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_FILENAME, $regs ) )
    $siteDir = $regs[1];
elseif ( ereg( "(.*/)([^\/]+\.php)/?", $PHP_SELF, $regs ) )
    $siteDir = $DOCUMENT_ROOT . $regs[1];
else
//	$siteDir = "./";
$siteDir = "/home/web/ezcommunity.net/html/";
$serverDateCMD = "/bin/date";
$serverCalendarCronLog = 'bin/logs/calendar_even_notification_cron.log';

if ( substr( php_uname(), 0, 7) == "Windows" )
    $separator = ";";
else
    $separator = ":";

$includePath = ini_get( "include_path" );
if ( trim( $includePath ) != "" )
    $includePath .= $separator . $siteDir;
else
    $includePath = $siteDir;
ini_set( "include_path", $includePath );

// print( $includePath );
// print( $siteDir . "\n\n" );

//------------------------------------------------

 include_once( "classes/INIFile.php" );
 $ini = new INIFile( "site.ini" );
 $GlobalSiteIni =& $ini;

//------------------------------------------------
// Send Email Event Notification

 include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
 include_once( "ezgroupeventcalendar/classes/ezgroupeventcategory.php" );

 $events = new eZGroupEvent();
 $eventList = $events->getAll();

 foreach( $eventList as $events )
 {
   // $events =& $event->getAll();
   // print( "event: " .  $event->name()."\n" );

   $events->notification();
 }
//------------------------------------------------

//------------------------------------------------
// print date to log file (basic trigger log)

print("\n");
$today = date("F j, Y, g:i a"); 

//------------------------------------------------
// print pre-rc extended logging output

// print('outputing date to file via cron . . .' );
// print("\n" .' creating /home/ladiva/public_html/event_cron_log.txt' ."\n".' date: '. $today . "\n");


//------------------------------------------------
// insert date stamp into event cron log
// date represents date stamp for each date this 
// cron schedualed module feature triger runs.

// What other information would be usefull here.
// Emailed Users List Names, Email Adresses, UserID, Date, EventID
  
system( $serverDateCMD .' >> '. $siteDir . $serverCalendarCronLog);

// print("#---------------------------------------------- \n");

//------------------------------------------------
// print / output log text
// system('/usr/bin/cat /home/ladiva/public_html/event_cron_log.txt');
//------------------------------------------------

?>
