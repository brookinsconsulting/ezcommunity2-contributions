<?php
// 
// $Id: dayview.php,v 1.7 2001/01/16 17:13:16 gl Exp $
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
include_once( "classes/ezlocale.php" );

include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdate.php" );

include_once( "ezcalendar/classes/ezappointment.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$Locale = new eZLocale( $Language );

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
$t->set_var( "long_date", $Locale->format( $datetime->date(), false ) );

$tmpDate = new eZDate();
$tmpAppointment = new eZAppointment();

// fetch the appointments for the selected day
$tmpDate->setYear( $datetime->year() );
$tmpDate->setMonth( $datetime->month() );
$tmpDate->setDay( $datetime->day() );

$appointments = $tmpAppointment->getByDate( $tmpDate );

$appointmentColumns = array();
$rowSpanColumns = array();

$startTime = new eZTime( 8, 0, 0 );
$interval = new eZTime( 0, 30, 0 );
$stopTime = new eZTime( 18, 0, 0 );

// places appointments into columns, creates extra columns as necessary
foreach ( $appointments as $appointment )
{
    $aCount = 0;
    $foundFreeColumn = false;
    while ( $foundFreeColumn == false  )
    {
        if ( gettype( $appointmentColumns[$aCount] ) != "array" )
        {
            $appointmentColumns[$aCount] = array();
            $rowSpanColumns[$aCount] = 0;
        }

        if ( isFree( $appointmentColumns[$aCount], $appointment ) )
        {
            $foundFreeColumn = true;
            $appointmentColumns[$aCount][] = $appointment;
        }

        $aCount++;
    }
}

$numCols = count( $appointmentColumns );
$emptyDone = false;

// print out the time table
while ( $startTime->isGreater( $stopTime ) == true )
{
    $t->set_var( "hour_value", eZTime::addZero( $startTime->hour() ) );
    $t->set_var( "minute_value", eZTime::addZero( $startTime->minute() ) );

    $drawnColumn = array();
    $t->set_var( "appointment", "" );

    for ( $i=0; $i<$numCols; $i++ )
    {
        $drawnColumn[$i] = false;

        if ( $rowSpanColumns[$i] <= 1 )
        {
            foreach ( $appointmentColumns[$i] as $app )
            {
                // draw an appointment cell
                if ( intersects( $app, $startTime, $startTime->add( $interval ) ) )
                {
                    $rowSpanColumns[$i] = appointmentRowSpan( $app, $startTime, $interval );
                    $t->set_var( "td_class", "bgdark" );
                    $t->set_var( "rowspan_value", $rowSpanColumns[$i] );
                    $t->set_var( "appointment_id", $app->id() );
                    $t->set_var( "appointment_name", $app->name() );
                    $t->set_var( "appointment_description", $app->description() );
                    $t->set_var( "edit_button", "Edit" );
                    $t->set_var( "delete_button", "Delete" );

                    $t->parse( "appointment", "appointment_tpl", true );
                    $drawnColumn[$i] = true;
                }
            }

            // draw an empty cell
            if ( $drawnColumn[$i] == false )
            {
                $rowSpanColumns[$i] = emptyRowSpan( $appointmentColumns[$i], $startTime, $stopTime, $interval );
                $t->set_var( "td_class", "bglight" );
                $t->set_var( "rowspan_value", $rowSpanColumns[$i] );
                $t->set_var( "appointment_id", "" );
                $t->set_var( "appointment_name", "" );
                $t->set_var( "appointment_description", "" );
                $t->set_var( "edit_button", "" );
                $t->set_var( "delete_button", "" );

                $t->parse( "appointment", "appointment_tpl", true );
            }
        }
        else
        {
            $rowSpanColumns[$i]--;
        }
    }

    // if there are no appointments this day, draw a big empty cell
    if ( $numCols == 0 && $emptyDone == false )
    {
        $emptyArray = array();
        $rowSpanColumns[$i] = emptyRowSpan( $emptyArray, $startTime, $stopTime, $interval );
        $t->set_var( "td_class", "bglight" );
        $t->set_var( "rowspan_value", $rowSpanColumns[$i] );
        $t->set_var( "appointment_id", "" );
        $t->set_var( "appointment_name", "" );
        $t->set_var( "appointment_description", "" );
        $t->set_var( "edit_button", "" );
        $t->set_var( "delete_button", "" );

        $t->parse( "appointment", "appointment_tpl", true );

        $emptyDone = true;
    }

    $startTime = $startTime->add( $interval );

    $t->parse( "time_table", "time_table_tpl", true );
}


// returns the number of rows an appointment covers.
function appointmentRowSpan( &$appointment, &$startTime, &$interval )
{
    $ret = 0;
    $tmpTime = new eZTime( $startTime->hour(), $startTime->minute(), $startTime->second() );
    $aStop =& $appointment->stopTime();

    while ( $tmpTime->isGreater( $aStop ) )
    {
        $tmpTime = $tmpTime->add( $interval );
        $ret++;
    }

    return $ret;
}


// returns the number of empty rows before an appointment.
function emptyRowSpan( &$appointmentArray, &$startTime, &$stopTime, &$interval )
{
    $ret = 0;
    $tmpTime = new eZTime( $startTime->hour(), $startTime->minute(), $startTime->second() );
    $foundAppointment = false;

    while ( $foundAppointment == false && $tmpTime->isGreater( $stopTime ) == true )
    {
        $tmpTime = $tmpTime->add( $interval );
        $ret++;

        foreach ( $appointmentArray as $app )
        {
            if ( intersects( $app, $tmpTime, $tmpTime->add( $interval ) ) )
            {
                $foundAppointment = true;
            }
        }
    }

    return $ret;
}


// checks if the appointment crashes with other appointments in the array given
function isFree( &$appointmentArray, &$appointment )
{
    $ret = true;
    foreach( $appointmentArray as $app )
    {
        if ( intersects( $appointment, $app->startTime(), $app->stopTime() ) )
        {
            $ret = false;
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

// next previous values.
$t->set_var( "curr_month_number", $Month );
$t->set_var( "curr_day_number", $Day );

$t->set_var( "prev_year_number", $Year - 1 );
$t->set_var( "next_year_number", $Year + 1 );
$t->set_var( "prev_myear_number", $Year );
$t->set_var( "next_myear_number", $Year );

if ( $Month == 12 )
{
    $t->set_var( "next_month_number", 1 );
    $t->set_var( "next_myear_number", $Year + 1 );
}
else
{
    $t->set_var( "next_month_number", $Month + 1 );
}

if ( $Month == 1 )
{
    $t->set_var( "prev_month_number", 12 );
    $t->set_var( "prev_myear_number", $Year - 1 );    
}
else
{
    $t->set_var( "prev_month_number", $Month - 1 );    
}


$t->pparse( "output", "day_view_page_tpl" );

?>
