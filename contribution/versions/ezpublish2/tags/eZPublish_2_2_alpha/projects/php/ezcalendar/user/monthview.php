<?php
// 
// $Id: monthview.php,v 1.29 2001/08/17 13:35:58 jhe Exp $
//
// Created on: <27-Dec-2000 14:09:56 bf>
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
include_once( "classes/ezlog.php" );
include_once( "classes/ezlocale.php" );

include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdate.php" );

include_once( "ezcalendar/classes/ezappointment.php" );
include_once( "ezcontact/classes/ezconsultation.php" );
include_once( "eztodo/classes/eztodo.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$Locale = new eZLocale( $Language );

$user =& eZUser::currentUser();
$session =& eZSession::globalSession();
$session->fetch();

if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( isSet( $GetByUserID ) )
{
    $userID = $GetByUserID;
}

if ( ( $session->variable( "ShowOtherCalenderUsers" ) == false ) ||
     ( isSet( $GetByUser ) ) )
{
    $session->setVariable( "ShowOtherCalenderUsers", $GetByUserID );
}

$appOwnerUser = new eZUser( $session->variable( "ShowOtherCalenderUsers" ) );

$date = new eZDate();

if ( $Year != "" && $Month != "" )
{
    $date->setYear( $Year );
    $date->setMonth( $Month );
}
else
{
    $Year = $date->year();
    $Month = $date->month();
}

$session->setVariable( "Year", $Year );
$session->setVariable( "Month", $Month );

$zMonth = addZero( $Month );
$isMyCalendar = ( $userID && $userID == $GetByUserID ) ? "-private" : "";
$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl", $Language, "monthview.php",
                     "default", "ezcalendar" . "/user", "$Year-$zMonth-$GetByUserID" . $isMyCalendar );

$t->set_file( "month_view_page_tpl", "monthview.tpl" );

//{
//    print( "cached<br />" );
//    print( $t->cache() );
//}
//else
{
//    print( "not cached<br />" );
    $t->setAllStrings();

    $t->set_block( "month_view_page_tpl", "user_item_tpl", "user_item" );
    $t->set_block( "month_view_page_tpl", "month_tpl", "month" );
    $t->set_block( "month_tpl", "week_tpl", "week" );
    $t->set_block( "month_tpl", "week_day_tpl", "week_day" );
    $t->set_block( "week_tpl", "day_tpl", "day" );
    $t->set_block( "day_tpl", "public_appointment_tpl", "public_appointment" );
    $t->set_block( "day_tpl", "private_appointment_tpl", "private_appointment" );
    $t->set_block( "day_tpl", "public_consultation_tpl", "public_consultation" );
    $t->set_block( "day_tpl", "public_todo_tpl", "public_todo" );
    $t->set_block( "public_consultation_tpl", "public_consultation_company_tpl", "public_consultation_company" );
    $t->set_block( "public_consultation_tpl", "public_consultation_person_tpl", "public_consultation_person" );
                   
    $t->set_var( "month_name", $Locale->monthName( $date->monthName(), false ) );
    $t->set_var( "month_number", $Month );
    $t->set_var( "year_number", $Year );
    $t->set_var( "week", "" );

    // Draw the week day header.
    $headerDate = new eZDate();
    $headerDate->setYear( 2001 );
    if ( $Locale->mondayFirst() )
    {
        // January 2001 starts on a Monday
        $headerDate->setMonth( 1 );
    }
    else
    {
        // April 2001 starts on a Sunday
        $headerDate->setMonth( 4 );
    }

    for ( $week_day = 1; $week_day <= 7; $week_day++ )
    {
        $headerDate->setDay( $week_day );
        $t->set_var( "week_day_name", $Locale->dayName( $headerDate->dayName( $Locale->mondayFirst() ), false ) );

        $t->parse( "week_day", "week_day_tpl", true );
    }

    $today = new eZDate();
    $tmpDate = new eZDate();
    $tmpAppointment = new eZAppointment();

    for ( $week = 0; $week < 6; $week++ )
    { 
        $t->set_var( "day", "" );

        if ( ( ( $week * 7 ) - $firstDay + 1 ) < ( $date->daysInMonth()  ) )
        {        
            $date->setDay( 1 );
            $firstDay = $date->dayOfWeek( $Locale->mondayFirst() );

            for ( $day = 1; $day <= 7; $day++ )
            {
                $currentDay = $day + ( $week * 7 ) - $firstDay + 1;

                // this month
                if ( ( ( $day + ( $week * 7 ) )  >= $firstDay ) &&
                     ( $currentDay <= $date->daysInMonth() ) )
                {
                    $date->setDay( $currentDay );

                    // fetch the appointments for today
                    $tmpDate->setYear( $date->year() );
                    $tmpDate->setMonth( $date->month() );
                    $tmpDate->setDay( $date->day() );
                    $appointments =& $tmpAppointment->getByDate( $tmpDate, $appOwnerUser, true );

                    $t->set_var( "public_appointment", "" );
                    $t->set_var( "private_appointment", "" );
                    $t->set_var( "public_consultation", "" );
                    $t->set_var( "public_todo", "" );

                    foreach ( $appointments as $appointment )
                    {
                        $t->set_var( "appointment_id", $appointment->id() );
                        $t->set_var( "start_time", $Locale->format( $appointment->startTime(), true ) );
                        $t->set_var( "stop_time", $Locale->format( $appointment->stopTime(), true ) );

                        if ( $appointment->isPrivate() == false || $userID == $appointment->userID() )
                        {
                            $t->parse( "public_appointment", "public_appointment_tpl", true );
                        }
                        else
                        {
                            $t->parse( "private_appointment", "private_appointment_tpl", true );
                        }
                    }

                    // fetch the consultations for today
                    $endDate = new eZDateTime();
                    $endDate->setTimeStamp( $tmpDate->timeStamp() + 86400 );
                    $consultations =& eZConsultation::findConsultationsByDate( $userID, $tmpDate, $endDate );
                    foreach ( $consultations as $consultation )
                    {
                        $t->set_var( "consultation_id", $consultation->id() );

                        $company_id = $consultation->company( $userID );
                        if ( $company_id )
                        {
                            $company = new eZCompany( $company_id );
                            $t->set_var( "consultation_desc", $consultation->shortDescription() );
                            $t->set_var( "consultation_company", $company->name() );
                            $t->set_var( "company_id", $company_id );
                            $t->parse( "public_consultation", "public_consultation_company_tpl", true );
                        }
                        else
                        {
                            $person_id = $consultation->person( $userID );
                            if ( $person_id )
                            {
                                $person = new eZPerson( $person_id );
                                $t->set_var( "consultation_desc", $consultation->shortDescription() );
                                $t->set_var( "consultation_person", $person->lastName() . " " .  $person->firstName() );
                                $t->set_var( "person_id", $person_id );
                                $t->parse( "public_consultation", "public_consultation_person_tpl", true );
                            }
                        }
                    }

                    // fetch todos for today
                    if ( !$userID )
                        $todos = array();
                    else
                        $todos =& eZTodo::getByDate( $userID, $tmpDate );
                    foreach ( $todos as $todo )
                    {
                        $t->set_var( "todo_id", $todo->id() );
                        $t->set_var( "todo_desc", $todo->name() );
                        $t->parse( "public_todo", "public_todo_tpl", true );
                    }
                                        
                    // set special colours for today and weekend
                    if ( $tmpDate->equals( $today ) )
                    {
                        $t->set_var( "td_class", "bgcurrent" );
                    }
                    else if ( $day == 7 )
                    {
                        $t->set_var( "td_class", "bgweekend" );
                    }
                    else if ( $day == 6 )
                    {
                        if ( $Locale->mondayFirst() == true )
                            $t->set_var( "td_class", "bgweekend" );
                        else
                            $t->set_var( "td_class", "bglight" );
                    }
                    else if ( $day == 1 )
                    {
                        if ( $Locale->mondayFirst() == false )
                            $t->set_var( "td_class", "bgweekend" );
                        else
                            $t->set_var( "td_class", "bglight" );
                    }
                    else
                    {
                        $t->set_var( "td_class", "bglight" );
                    }

                    $t->set_var( "day_number", $currentDay );
                    $t->set_var( "month_number", $Month );
                    $t->set_var( "year_number", $Year );
                }
                else   // previous or next month
                {
                    $prevNextDate = new eZDate( $date->year(), $date->month(), $date->day() );

                    // prevous month
                    if ( ( $currentDay <= $date->daysInMonth() ) )
                    {
                        $t->set_var( "public_appointment", "" );
                        $t->set_var( "private_appointment", "" );

                        if ( $date->month() == 1 )
                        {
                            $prevNextDate->setYear( $date->year() - 1 );
                            $prevNextDate->setMonth( 12 );     
                        }
                        else
                        {
                            $prevNextDate->setMonth( $date->month() - 1 );
                        }

                        $prevNextDate->setDay( $prevNextDate->daysInMonth() - $firstDay + $day + 1 );
                        $t->set_var( "day_number", $prevNextDate->day() );
                        $t->set_var( "month_number", $prevNextDate->month() );
                        $t->set_var( "year_number", $prevNextDate->year() );

                        $t->set_var( "appointment", "" );
                    }
                    else
                    {
                        // next month
                        $t->set_var( "public_appointment", "" );
                        $t->set_var( "private_appointment", "" );

                        if ( $date->month() == 12 )
                        {
                            $prevNextDate->setYear( $date->year() + 1 );
                            $prevNextDate->setMonth( 1 );     
                        }
                        else
                        {
                            $prevNextDate->setMonth( $date->month() + 1 );
                        }

                        $tmp = ( $firstDay + $date->daysInMonth() ) % 7;
                        if ( $tmp == 0 )
                            $tmp = 7;

                        $prevNextDate->setDay( ( 7 - $tmp - 6 ) + $day );
                        $t->set_var( "day_number", $prevNextDate->day() );
                        $t->set_var( "month_number", $prevNextDate->month() );
                        $t->set_var( "year_number", $prevNextDate->year() );

                        $t->set_var( "appointment", "" );
                    }
                    $t->set_var( "td_class", "bgdark" );
                    if ( $prevNextDate->equals( $today ) )
                        $t->set_var( "td_class", "bgcurrent" );
                }
                $t->parse( "day", "day_tpl", true );
            }
        }
        $t->parse( "week", "week_tpl", true );
    }
    $t->parse( "month", "month_tpl", true );

    // User list
    $user = new eZUser();
    $user_array =& $user->getAll();
    foreach ( $user_array as $userItem )
    {
        $t->set_var( "user_id", $userItem->id() );
        $t->set_var( "user_firstname", $userItem->firstName() );
        $t->set_var( "user_lastname", $userItem->lastName() );

        if ( $appOwnerUser->id() == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }

        $t->parse( "user_item", "user_item_tpl", true );
    }


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


    $t->storeCache( "output", "month_view_page_tpl", true );
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