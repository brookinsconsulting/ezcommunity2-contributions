<?php
// 
// $Id: monthview.php,v 1.4 2001/01/12 18:51:35 gl Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Dec-2000 14:09:56 bf>
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

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl/", $Language, "monthview.php" );

$t->set_file( "month_view_page_tpl", "monthview.tpl" );

$t->setAllStrings();


$t->set_block( "month_view_page_tpl", "month_tpl", "month" );
$t->set_block( "month_tpl", "week_tpl", "week" );
$t->set_block( "month_tpl", "week_day_tpl", "week_day" );
$t->set_block( "week_tpl", "day_tpl", "day" );
$t->set_block( "day_tpl", "appointment_tpl", "appointment" );

   
$datetime = new eZDateTime( );

if ( $Year != "" && $Month != "" )
{
    $datetime->setYear( $Year );
    $datetime->setMonth( $Month );
}
else
{
    $Year = $datetime->year();
    $Month = $datetime->month();
}

$t->set_var( "month_name", $locale->monthName( $datetime->monthName(), false ) );
$t->set_var( "month_number", $Month );
$t->set_var( "year_number", $Year );
$t->set_var( "week", "" );



// draw the week day header. Using 2001 because it starts on a monday.
$dTime = new eZDateTime();
$dTime->setYear( 2001 );
$dTime->setMonth( 1 );

for ( $week_day=1; $week_day<=7; $week_day++ )
{
    $dTime->setDay( $week_day );
    $t->set_var( "week_day_name", $locale->dayName( $dTime->dayName(), false ) );

    $t->parse( "week_day", "week_day_tpl", true );
}

$tmpDate = new eZDate();
$tmpAppointment = new eZAppointment();

for ( $week=0; $week<6; $week++ )
{
    $t->set_var( "day", "" );

    if ( ( ( $week * 7 ) - $firstDay + 1 ) < ( $datetime->daysInMonth()  ) )
    {        
        $datetime->setDay( 1 );
        $firstDay = $datetime->dayOfWeek();

        for ( $day=1; $day<=7; $day++ )
        {
            $currentDay = $day + ( $week * 7 ) - $firstDay + 1;

            if ( ( ( $day + ( $week * 7 ) )  >= $firstDay ) &&
                 ( $currentDay <= $datetime->daysInMonth() ) )
            {
                // this month
                $datetime->setDay( $currentDay );

                // fetch the appointments for today
                $tmpDate->setYear( $datetime->year() );
                $tmpDate->setMonth( $datetime->month() );
                $tmpDate->setDay( $datetime->day() );

                $appointments = $tmpAppointment->getByDate( $tmpDate );
                $t->set_var( "appointment", "" );
                foreach ( $appointments as $appointment )
                {
                    $start = $appointment->date();
                    $t->set_var( "appointment_id", $appointment->id() );
                    $t->set_var( "start_time",  eZDateTime::addZero( $start->hour() ) . ":" . eZDateTime::addZero( $start->minute() ) );
                    
                    $t->parse( "appointment", "appointment_tpl", true );
                }
                        
                
                if ( $day <= 5 )
                    $t->set_var( "td_class", "bglight" );
                else
                    $t->set_var( "td_class", "bgdark" );

                $t->set_var( "day_number", $currentDay );
                $t->set_var( "day_number", $currentDay  );
            }
            else
            {
                // prevous month
                if ( ( $currentDay <= $datetime->daysInMonth() ) )
                {
                    $prevMonth = $datetime;

                    if ( $datetime->month() == 1 )
                    {
                        $prevMonth->setYear( $datetime->year() - 1 );
                        $prevMonth->setMonth( 12 );     
                    }
                    else
                    {
                        $prevMonth->setMonth( $datetime->month() - 1 );
                    }

                    $t->set_var( "appointment", "" );

                    $prevMonth->setDay( $prevMonth->daysInMonth() - $firstDay + $day + 1 );
                    
                    $t->set_var( "day_number", $prevMonth->day() );
                }
                else
                {
                    // next month
                    $nextMonth = $datetime;

                    $t->set_var( "appointment", "" );
                    
                    if ( $datetime->month() == 12 )
                    {
                        $nextMonth->setYear( $datetime->year() + 1 );
                        $nextMonth->setMonth( 1 );     
                    }
                    else
                    {
                        $nextMonth->setMonth( $datetime->month() + 1 );
                    }

                    $nextMonth->setDay( ( 7 - ( ( $firstDay + $datetime->daysInMonth() ) % 7 ) - 6)  + $day );
                    
                    $t->set_var( "day_number", $nextMonth->day() );
                }
                
                $t->set_var( "td_class", "bgdark" );                

            }
            $t->parse( "day", "day_tpl", true );            
        }
    }
    $t->parse( "week", "week_tpl", true );
}
$t->parse( "month", "month_tpl", true );

// next previous values.
$t->set_var( "next_year_number", $Year );
$t->set_var( "prev_year_number", $Year );

if ( $Month == 12 )
{
    $t->set_var( "next_month_number", 1 );
    $t->set_var( "next_year_number", $Year + 1 );
}
else
{
    $t->set_var( "next_month_number", $Month + 1 );
}

if ( $Month == 1 )
{
    $t->set_var( "prev_month_number", 12 );
    $t->set_var( "prev_year_number", $Year - 1 );    
}
else
{
    $t->set_var( "prev_month_number", $Month - 1 );    
}

$t->pparse( "output", "month_view_page_tpl" );

?>
