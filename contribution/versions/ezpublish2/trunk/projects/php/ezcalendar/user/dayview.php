<?php
// 
// $Id: dayview.php,v 1.1 2001/01/09 17:00:07 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <08-Jan-2001 12:48:35 bf>
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
include_once( "classes/ezlog.php" );

include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdate.php" );

include_once( "ezcalendar/classes/ezappointment.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZCalendarMain", "Language" );

$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl/", $Language, "dayview.php" );

$t->set_file( "day_view_page_tpl", "dayview.tpl" );

$t->setAllStrings();

$t->set_block( "day_view_page_tpl", "time_table_tpl", "time_table" );
$t->set_block( "time_table_tpl", "appointment_tpl", "appointment" );

$datetime = new eZDateTime( );

if ( $Year != "" && $Month != "" && $Day != "" )
{
    $datetime->setYear( $Year );
    $datetime->setMonth( $Month );
    $datetime->setDay( $Day );
}
else
{
    $Year = $datetime->year();
    $Month = $datetime->month();
    $Day = $datetime->day();
}

$t->set_var( "month_number", $Month );
$t->set_var( "year_number", $Year );
$t->set_var( "day_number", $Day );

$tmpDate = new eZDate();
$tmpAppointment = new eZAppointment();
$locale = new eZLocale( $Language );

// fetch the appointments for the selected day
$tmpDate->setYear( $datetime->year() );
$tmpDate->setMonth( $datetime->month() );
$tmpDate->setDay( $datetime->day() );

$appointments = $tmpAppointment->getByDate( $tmpDate );

$appointmentColumns = array();

foreach ( $appointments as $appointment )
{
    $aCount = 0;
    $foundFreeColumn = false;
    while ( $foundFreeColumn == false  )
    {
        if ( gettype( $appointmentColumns[$aCount] ) != "array" )
            $appointmentColumns[$aCount] = array();

        if ( isFree( $appointmentColumns[$aCount], $appointment ) )
        {
            $foundFreeColumn = true;
            $appointmentColumns[$aCount][] = $appointment;

        }
        
        $aCount++;
    }
}

// print out the time table

$startTime = new eZTime( 8, 0, 0 );
$interval = new eZTime( 0, 30, 0 );
$stopTime = new eZTime( 18, 0, 0 );

$numCols = count( $appointmentColumns );

//  print( $numCols );

while ( $startTime->isGreater( $stopTime ) == true )
{
    $t->set_var( "hour_value", eZTime::addZero( $startTime->hour() ) );
    $t->set_var( "minute_value", eZTime::addZero( $startTime->minute() ) );


    $drawnColumn = array();
    $t->set_var( "appointment", "" );
    // draw the column appointments or a space
    for ( $i=0; $i<$numCols; $i++ )
    {
        $drawnColumn[$i] = false;
        foreach ( $appointmentColumns[$i] as $app )
        {
            if ( intersects( $app, $startTime, $startTime->add( $interval ) ) )
            {
                $t->set_var( "td_class", "bgdark" );
                
                $t->set_var( "appointment_id", $app->id() );
                $t->set_var( "appointment_name", $app->name() );

                $t->parse( "appointment", "appointment_tpl", true );
                $drawnColumn[$i] = true;
            }
        }

        if ( $drawnColumn[$i] == false )
        {
            $t->set_var( "td_class", "bglight" );
            $t->set_var( "appointment_id", "" );
            $t->set_var( "appointment_name", "" );
            
            $t->parse( "appointment", "appointment_tpl", true );
        }
    }
    
    $startTime = $startTime->add( $interval );

    $t->parse( "time_table", "time_table_tpl", true );
}



// checks if the appointment crashes with other appointments
// in the array given
function isFree( &$appointmentArray, &$appointment )
{
    $ret = true;
    foreach( $appointmentArray as $app )
    {
        if ( intersects( $appointment, $app->startTime(), $app->stopTime() ) )
        {
            $ret = false;
//              print( "inter" . $appointment->id() . " " . $app->id() . "<br>" );
        }
        else
        {
//              print( "not inter" . $appointment->id() . " " . $app->id() . "<br>" );
        }
    }
    return $ret;
}


// checks if an appointment intersects with a time interval
function intersects( &$app, &$startTime, &$stopTime )
{
    $ret = false;
    $aStart =& $app->startTime();
    $aStop =& $app->stopTime();

    if ( $aStart->isGreater( $startTime ) == true &&
    $startTime->isGreater( $aStop ) == true )
    {
        $ret = true;
    }

    if ( $aStart->isGreater( $stopTime ) == true &&
    $stopTime->isGreater( $aStop ) == true )
    {
        $ret = true;
    }

    if ( $startTime->isGreater( $aStart ) == true &&
    $aStop->isGreater( $stopTime ) == true )
    {
        $ret = true;
    }

    // 
    if ( $aStart->isGreater( $startTime, true ) == true &&
    $stopTime->isGreater( $aStop, true ) == true )
    {
        $ret = true;
    }

    return $ret;
}


$t->pparse( "output", "day_view_page_tpl" );

?>
