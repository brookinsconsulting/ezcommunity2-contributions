<?php
// 
// $Id: dayview.php,v 1.30 2001/01/27 22:13:58 gl Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <08-Jan-2001 12:48:35 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );

include_once( "ezcalendar/classes/ezappointment.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$StartTimeStr = $ini->read_var( "eZCalendarMain", "DayStartTime" );
$StopTimeStr = $ini->read_var( "eZCalendarMain", "DayStopTime" );
$IntervalStr = $ini->read_var( "eZCalendarMain", "DayInterval" );

$Locale = new eZLocale( $Language );

$user = eZUser::currentUser();
$session =& eZSession::globalSession();
$session->fetch();

if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( $GetByUserID == false )
{
    $GetByUserID = $userID;
}

if ( ( $session->variable( "ShowOtherCalenderUsers" ) == false ) || ( isSet( $GetByUser ) ) )
{
    $session->setVariable( "ShowOtherCalenderUsers", $GetByUserID );
}

$tmpUser = new eZUser( $session->variable( "ShowOtherCalenderUsers" ) );

$date = new eZDate();

if ( $Year != "" && $Month != "" && $Day != "" )
{
    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );
}
else
{
    $Year = $date->year();
    $Month = $date->month();
    $Day = $date->day();
}

$session->setVariable( "Year", $Year );
$session->setVariable( "Month", $Month );
$session->setVariable( "Day", $Day );

$zMonth = addZero( $Month );
$zDay = addZero( $Day );
$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl", $Language, "dayview.php",
                     "default", "ezcalendar" . "/user", "$Year-$zMonth-$zDay-$GetByUserID" );

$t->set_file( "day_view_page_tpl", "dayview.tpl" );

if ( $t->hasCache() )
{
//    print( "cached<br />" );
    print( $t->cache() );
}
else
{
//    print( "not cached<br />" );
    $t->setAllStrings();

    $t->set_block( "day_view_page_tpl", "user_item_tpl", "user_item" );
    $t->set_block( "day_view_page_tpl", "time_table_tpl", "time_table" );
    $t->set_block( "time_table_tpl", "no_appointment_tpl", "no_appointment" );
    $t->set_block( "time_table_tpl", "private_appointment_tpl", "private_appointment" );
    $t->set_block( "time_table_tpl", "public_appointment_tpl", "public_appointment" );
    $t->set_block( "public_appointment_tpl", "delete_check_tpl", "delete_check" );
    $t->set_block( "day_view_page_tpl", "week_tpl", "week" );
    $t->set_block( "week_tpl", "day_tpl", "day" );
    $t->set_block( "week_tpl", "empty_day_tpl", "empty_day" );

    $t->set_var( "month_number", $Month );
    $t->set_var( "year_number", $Year );
    $t->set_var( "day_number", $Day );
    $t->set_var( "long_date", $Locale->format( $date, false ) );


    $today = new eZDate();
    $tmpDate = new eZDate( $date->year(), $date->month(), $date->day() );
    $tmpAppointment = new eZAppointment();

    // fetch the appointments for the selected day
    $appointments =& $tmpAppointment->getByDate( $tmpDate, $tmpUser, true );


    // set start/stop and interval times
    $startTime = new eZTime();
    $stopTime = new eZTime();
    $interval = new eZTime();

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $startArray ) )
    {
        $hour = $startArray[2];
        $startTime->setHour( $hour );

        $min = $startArray[3];
        $startTime->setMinute( $min );

        $startTime->setSecond( 0 );
    }

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $stopArray ) )
    {
        $hour = $stopArray[2];
        $stopTime->setHour( $hour );

        $min = $stopArray[3];
        $stopTime->setMinute( $min );

        $stopTime->setSecond( 0 );
    }

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $IntervalStr, $intervalArray ) )
    {
        $hour = $intervalArray[2];
        $interval->setHour( $hour );

        $min = $intervalArray[3];
        $interval->setMinute( $min );

        $interval->setSecond( 0 );
    }


    // places appointments into columns, creates extra columns as necessary
    $numRows = 0;
    $numCols = 1;
    $tableCellsId = array();       // appointmend id for a cell
    $tableCellsRowSpan = array();  // rowspan for a cell
    $colTaken = array();           // number of non free rows in the current column, after the last appointment. 0 means col free.
    $emptyRows = array();          // number of empty rows in the current column, after the last appointment
    $appointmentDone = array();    // true when the appointment has been inserted into the table
    $tmpStartTime = new eZTime( $startTime->hour(), $startTime->minute(), $startTime->second() );

    while ( $tmpStartTime->isGreater( $stopTime ) == true )
    {
        $numRows++;
        $tableCellsId[$numRows-1] = array();
        $tableCellsRowSpan[$numRows-1] = array();

        // marks cells as taken, -1
        for ( $col=0; $col<$numCols; $col++ )
        {
            if ( $colTaken[$col] > 0 )
            {
                $tableCellsId[$numRows-1][$col] = -1;
            }
        }

        foreach ( $appointments as $appointment )
        {
            // if this appointment should be inserted into the table now
            if ( $appointmentDone[$appointment->id()] == false &&
            intersects( $appointment, $tmpStartTime, $tmpStartTime->add( $interval ) ) == true )
            {
                $foundFreeColumn = false;
                $col = 0;
                while ( $foundFreeColumn == false  )
                {
                    // the column is free, insert appointment here
                    if ( $tableCellsId[$numRows-1][$col] == 0 )
                    {
                        $tableCellsId[$numRows-1][$col] = $appointment->id();
                        $tableCellsRowSpan[$numRows-1][$col] = appointmentRowSpan( $appointment, $tmpStartTime, $interval );
                        $colTaken[$col] = $tableCellsRowSpan[$numRows-1][$col];
                        $appointmentDone[$appointment->id()] = true;
                        $foundFreeColumn = true;

                        // if we created a new column, mark leading empty spaces
                        if ( $col >= $numCols )
                            $emptyRows[$col] = $numRows - 1;

                        if ( $emptyRows[$col] > 0 )
                        {
                            $tableCellsId[ $numRows - 1 - $emptyRows[$col] ][$col] = -2;
                            $tableCellsRowSpan[ $numRows - 1 - $emptyRows[$col] ][$col] = $emptyRows[$col];
                            $emptyRows[$col] = 0;
                        }
                    }

                    // the column was not free, try the next one
                    $col++;
                    if ( $col > $numCols )
                        $numCols++;
                }
            }
        }

        // decrease/increase counts as we move down
        for ( $col=0; $col<$numCols; $col++ )
        {
            if ( $colTaken[$col] > 0 )
            {
                $colTaken[$col]--;
            }

            if ( $tableCellsId[$numRows-1][$col] == 0 )
            {
                $emptyRows[$col]++;
            }
        }

        $tmpStartTime = $tmpStartTime->add( $interval );
    }

    // mark remaining empty spaces as empty, -2
    for ( $col=0; $col<$numCols; $col++ )
    {
        if ( $emptyRows[$col] > 0 )
        {
            $tableCellsId[ $numRows - $emptyRows[$col] ][$col] = -2;
            $tableCellsRowSpan[ $numRows - $emptyRows[$col] ][$col] = $emptyRows[$col];
        }
    }


// debug contents table
//  print( "Rows: " . $numRows . "   Cols: " . $numCols . "<br />" );
//  print( "<table border=\"1\">" );
//  for ( $row=0; $row<$numRows; $row++ )
//  {
//      print( "<tr>" );
//      for ( $col=0; $col<$numCols; $col++ )
//      {
//          print( "<td>" . $tableCellsId[$row][$col] . " / " . $tableCellsRowSpan[$row][$col] . "</td>" );
//      }
//      print( "</tr>" );
//  }
//  print( "</table>" );


    // prints out the time table
    $emptyDone = false;
    $now = new eZTime();
    $nowSet = false;
    $row = 0;

    while ( $startTime->isGreater( $stopTime ) == true )
    {
        $t->set_var( "short_time", $Locale->format( $startTime, true ) );
        $t->set_var( "start_time", addZero( $startTime->hour() ) . addZero( $startTime->minute() ) );

        $drawnColumn = array();

        $t->set_var( "public_appointment", "" );
        $t->set_var( "private_appointment", "" );
        $t->set_var( "no_appointment", "" );
        $t->set_var( "delete_check", "" );

        for ( $col=0; $col<$numCols; $col++ )
        {
            $appointmentId = $tableCellsId[$row][$col];

            // an appointment
            if ( $appointmentId > 0 )
            {
                $appointment = new eZAppointment( $appointmentId );

                // a private appointment
                if ( $appointment->isPrivate() == true )
                {
                    $t->set_var( "td_class", "bgdark" );
                    $t->set_var( "rowspan_value", $tableCellsRowSpan[$row][$col] );

                    $t->parse( "private_appointment", "private_appointment_tpl", true );
                }
                // a public appointment
                else
                {
                    $t->set_var( "td_class", "bgdark" );
                    $t->set_var( "rowspan_value", $tableCellsRowSpan[$row][$col] );
                    $t->set_var( "appointment_id", $appointment->id() );
                    $t->set_var( "appointment_name", $appointment->name() );
                    $t->set_var( "appointment_description", $appointment->description() );
                    $t->set_var( "edit_button", "Edit" );

                    $t->parse( "delete_check", "delete_check_tpl" );
                    $t->parse( "public_appointment", "public_appointment_tpl", true );
                }
            }

            // an empty space
            else if ( $appointmentId == -2 )
            {
                $t->set_var( "td_class", "bglight" );
                $t->set_var( "rowspan_value", $tableCellsRowSpan[$row][$col] );

                $t->parse( "no_appointment", "no_appointment_tpl", true );
            }
        }

        $t->set_var( "td_class", "" );

// Mark current time with bgcurrent. Does not currently go well together with caching.
//        if ( $date->equals( $today ) && $nowSet == false &&
//        $startTime->isGreater( $now, true ) && $now->isGreater( $startTime->add( $interval ) ) )
//        {
//            $t->set_var( "td_class", "bgcurrent" );
//            $nowSet = true;
//        }

        $startTime = $startTime->add( $interval );
        $row++;

        $t->parse( "time_table", "time_table_tpl", true );
    }


    // User list
    $user = new eZUser();
    $user_array =& $user->getAll();

    foreach( $user_array as $userItem )
    {
        $t->set_var( "user_id", $userItem->id() );
        $t->set_var( "user_firstname", $userItem->firstName() );
        $t->set_var( "user_lastname", $userItem->lastName() );

        if ( $tmpUser->id() == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }

        $t->parse( "user_item", "user_item_tpl", true );
    }


    // set up top prev/next links
    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );

    // previous year link
    $date->setYear( $Year - 1 );
    if ( $date->month() == 2 && $date->daysInMonth() < $date->day() )
        $date->setDay( $date->daysInMonth() );

    $t->set_var( "1_year_number", $date->year() );
    $t->set_var( "1_month_number", $date->month() );
    $t->set_var( "1_day_number", $date->day() );

    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );

    // next year link
    $date->setYear( $Year + 1 );
    if ( $date->month() == 2 && $date->daysInMonth() < $date->day() )
        $date->setDay( $date->daysInMonth() );

    $t->set_var( "2_year_number", $date->year() );
    $t->set_var( "2_month_number", $date->month() );
    $t->set_var( "2_day_number", $date->day() );

    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );

    // previous month link
    if ( $date->month() == 1 )
    {
        $date->setMonth( 12 );
        $date->setYear( $Year - 1 );
    }
    else
        $date->setMonth( $date->month() - 1 );

    if ( $date->daysInMonth() < $date->day() )
        $date->setDay( $date->daysInMonth() );

    $t->set_var( "3_year_number", $date->year() );
    $t->set_var( "3_month_number", $date->month() );
    $t->set_var( "3_day_number", $date->day() );

    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );

    // next month link
    if ( $date->month() == 12 )
    {
        $date->setMonth( 1 );
        $date->setYear( $Year + 1 );
    }
    else
        $date->setMonth( $date->month() + 1 );

    if ( $date->daysInMonth() < $date->day() )
        $date->setDay( $date->daysInMonth() );

    $t->set_var( "4_year_number", $date->year() );
    $t->set_var( "4_month_number", $date->month() );
    $t->set_var( "4_day_number", $date->day() );

    $date->setYear( $Year );
    $date->setMonth( $Month );
    $date->setDay( $Day );


    // parse month table
    $t->set_var( "month_number", $date->month() );
    $t->set_var( "month_name", $Locale->monthName( $date->monthName(), false ) );

    $t->set_var( "week", "" );
    for ( $week=0; $week<6; $week++ )
    {
        $t->set_var( "day", "" );
        $t->set_var( "empty_day", "" );

        for ( $day=1; $day<=7; $day++ )
        {
            $date->setDay( 1 );
            $firstDay = $date->dayOfWeek( $Locale->mondayFirst() );

            $currentDay = $day + ( $week * 7 ) - $firstDay + 1;

            if ( ( ( $day + ( $week * 7 ) )  >= $firstDay ) &&
                 ( $currentDay <= $date->daysInMonth() ) )
            {
                $date->setDay( $currentDay );

                $t->set_var( "td_class", "bglight" );
                if ( $date->equals( $today ) )
                    $t->set_var( "td_class", "bgcurrent" );

                $t->set_var( "day_number", $currentDay );
                $t->parse( "day", "day_tpl", true );
            }
            else
            {
                $t->set_var( "td_class", "bglight" );                
                $t->parse( "day", "empty_day_tpl", true );
            }
        }
        $t->parse( "week", "week_tpl", true );
    }


    $t->storeCache( "output", "day_view_page_tpl", true );
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


// checks if an appointment intersects with a given time interval
function intersects( &$app, &$startTime, &$stopTime )
{
    $ret = false;
    $appStartTime =& $app->startTime();
    $appStopTime =& $app->stopTime();

    // appstart is between start and stop
    if ( $startTime->isGreater( $appStartTime, true ) == true &&
    $appStartTime->isGreater( $stopTime ) == true )
    {
        $ret = true;
    }
    // appstop is between start and stop
    else if ( $startTime->isGreater( $appStopTime ) == true &&
    $appStopTime->isGreater( $stopTime, true ) == true )
    {
        $ret = true;
    }
    // appstart is before start, and appstop is after stop
    else if ( $appStartTime->isGreater( $startTime ) == true &&
    $stopTime->isGreater( $appStopTime ) == true )
    {
        $ret = true;
    }

    return $ret;
}


//Adds a "0" in front of the value if it's below 10.
function addZero( $value )
{
    settype( $value, "integer" );
    $ret = $value;
    if ( $ret < 10 )
    {
        $ret = "0". $ret;
    }
    return $ret;
}


?>
