<?
// 
// $Id: eventedit.php,v 1.36 2001/03/12 13:55:47 fh Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
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

include_once( "classes/ezhttptool.php" );

$URL = split( "/", $REQUEST_URI );

if( is_numeric( $URL[8] ) )
	$masterGroupID = $URL[8];
else
	$masterGroupID = 0;

if ( isSet( $DeleteEvents ) )
{
    $Action = "DeleteEvents";
}


if ( isSet( $GoDay ) )
{
    include_once( "classes/ezdate.php" );

    $session =& eZSession::globalSession();
    $session->fetch();

    $year = $session->variable( "Year" );
    $month = $session->variable( "Month" );
    $day = $session->variable( "Day" );

    $date = new eZDate( $year, $month, $day );
    if ( $date->daysInMonth() < $day )
        $day = $date->daysInMonth();

    eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
    exit();
}
else if ( isSet( $GoMonth ) )
{
    $session =& eZSession::globalSession();
    $session->fetch();

    $year = $session->variable( "Year" );
    $month = $session->variable( "Month" );

    eZHTTPTool::header( "Location: /groupeventcalendar/monthview/$year/$month/" );
    exit();
}
else if ( isSet( $GoYear ) )
{
    $session =& eZSession::globalSession();
    $session->fetch();

    $year = $session->variable( "Year" );

    eZHTTPTool::header( "Location: /groupeventcalendar/yearview/$year/" );
    exit();
}
else if ( isSet( $GoToday ) )
{
    $today = new eZDate();

    $year = addZero( $today->year() );
    $month = addZero( $today->month() );
    $day = addZero( $today->day() );

    eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
    exit();
}
else if ( isSet( $GoNew ) )
{
    $today = new eZDate();

    $year = addZero( $today->year() );
    $month = addZero( $today->month() );
    $day = addZero( $today->day() );

    eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/new/$year/$month/$day/" );
    exit();
}


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeditor.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$SiteDesign = $ini->read_var( "site", "SiteDesign" );
$Language = $ini->read_var( "eZGroupEventCalendarMain", "Language" );
$StartTimeStr = $ini->read_var( "eZGroupEventCalendarMain", "DayStartTime" );
$StopTimeStr = $ini->read_var( "eZGroupEventCalendarMain", "DayStopTime" );
$timeSelect = $ini->read_var( "eZGroupEventCalendarMain", "TwelveHourSelect" );

$Locale = new eZLocale( $Language );

$user = eZUser::currentUser();

if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( $Action == "New"  )
{
    $event = new eZGroupEvent();
}
else
    $event = new eZGroupEvent( $EventID );

//We don't need to record this
//$session->setVariable( "ShowOtherCalenderGroups", $groupID );


$t = new eZTemplate( "ezgroupeventcalendar/user/" . $ini->read_var( "eZGroupEventCalendarMain", "TemplateDir" ),
                     "ezgroupeventcalendar/user/intl/", $Language, "eventedit.php" );

$t->set_file( "event_edit_tpl", "eventedit.tpl" );

$t->setAllStrings();

$t->set_block( "event_edit_tpl", "user_error_tpl", "user_error" );
$t->set_block( "user_error_tpl", "no_user_error_tpl", "no_user_error" );
$t->set_block( "user_error_tpl", "wrong_user_error_tpl", "wrong_user_error" );
$t->set_block( "user_error_tpl", "wrong_user_error_new_tpl", "wrong_user_error_new" );

$t->set_block( "event_edit_tpl", "no_error_tpl", "no_error" );
$t->set_block( "no_error_tpl", "title_error_tpl", "title_error" );
$t->set_block( "no_error_tpl", "group_error_tpl", "group_error" );

$t->set_block( "no_error_tpl", "start_hour_item_tpl", "start_hour_item" );
$t->set_block( "no_error_tpl", "start_minute_item_tpl", "start_minute_item" );
$t->set_block( "no_error_tpl", "stop_hour_item_tpl", "stop_hour_item" );
$t->set_block( "no_error_tpl", "stop_minute_item_tpl", "stop_minute_item" );
$t->set_block( "no_error_tpl", "start_ampm_radio_tpl", "start_ampm_radio" );
$t->set_block( "no_error_tpl", "stop_ampm_radio_tpl", "stop_ampm_radio" );

$t->set_block( "no_error_tpl", "start_time_error_tpl", "start_time_error" );
$t->set_block( "no_error_tpl", "stop_time_error_tpl", "stop_time_error" );
$t->set_block( "no_error_tpl", "value_tpl", "value" );
$t->set_block( "no_error_tpl", "month_tpl", "month" );
$t->set_block( "no_error_tpl", "day_tpl", "day" );
$t->set_block( "no_error_tpl", "year_tpl", "year" );
$t->set_block( "no_error_tpl", "group_name_edit_tpl", "group_name_edit" );
$t->set_block( "no_error_tpl", "group_name_new_tpl", "group_name_new" );
$t->set_block( "group_name_new_tpl", "group_item_tpl", "group_item" );

//history bar block
$t->set_block( "event_edit_tpl", "group_history_tpl", "group_history" );
$t->set_block( "event_edit_tpl", "edit_history_tpl", "edit_history" );
$t->set_block( "event_edit_tpl", "new_history_tpl", "new_history" );

$t->set_var( "sitedesign", $SiteDesign );

$t->set_var( "group_history", "" );

//print the group name in the history bar if a group is selected
if( $masterGroupID != 0 )
{
	$group = new eZUserGroup( $masterGroupID );

	$t->set_var( "group_print_name", $group->name() );
	$t->set_var( "group_print_id", $group->id() );
	$t->parse( "group_history", "group_history_tpl", true );
}
elseif ( $event->groupID() && $masterGroupID == 0 )
{
	$group = new eZUserGroup( $event->groupID() );

	$t->set_var( "group_print_name", $group->name() );
	$t->set_var( "group_print_id", $group->id() );
	$t->parse( "group_history", "group_history_tpl", true );
}
else
	$t->set_var( "group_history", "" );


$t->set_var( "edit_history", "" );
$t->set_var( "new_history", "" );

$t->set_var( "group_name_edit", "" );
$t->set_var( "group_name_new", "" );



// no user logged on
if( $userID == false )
{
    $t->set_var( "no_error", "" );
    $t->set_var( "wrong_user_error", "" );

    $t->parse( "no_user_error", "no_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );

    $groupError = true;
	$errorPrint = true;
}

//set the group to a non valid group
$groupID = "-1";

// Groups that should not use be used with the calendar
$noShowGroup = new eZGroupNoShow();

// Determine editing permissions
$permission = new eZGroupEditor();
$editor     = false;

if ( $user == true ) 
{
	//if the user has root access, return all the groups else just return the group they are a member of
	if( $user->hasRootAccess() == true || eZPermission::checkPermission( $user, "eZGroupEventCalendar", "WriteToRoot" ) )
	{
		$groups = new eZUserGroup();
		$groupsList = $groups->getAll( true );
		
		$editor = true;
	}
	else
		$groupsList = $user->groups();

	if( $Action == "New" )
	{
		$Group = $masterGroupID;
	}
	else
	{
		$Group = $event->groupID();
	}

	//Determin if the user has editing permissions
	if( $editor == true )
	{
		$groupID = $Group;
	}
	elseif( $permission->hasEditPermission( $user->id(), $Group ) == true && $editor == false )
	{
		$editor = true;
		$groupID = $Group;
	}
	elseif( $permission->getByGroup( $Group ) == false && $editor == false )
	{
		foreach( $groupsList as $groups )
		{
			if( $permission->groupHasEditor( $groups->id() ) == false && $noShowGroup->groupEntry( $groups->id() ) == false )
			{
				if( $Group != 0 && $Group )
				{
					$editor = true;
					$groupID = $Group;
					break;
				}
				elseif( $Group == 0 )
				{
					$editor = true;
					$groupID = $Group;
					break;				
				}
			}
		}
	}
}

if ( ($Action == "New" || $Action == "Insert" || $Action == "Update" || $Action == "Edit" ) && $groupsList )
{
	$groupError = true;
	foreach ( $groupsList as $groups )
	{
		if( $Action == "New" )
		{
			if( ($masterGroupID == $groups->id() || $masterGroupID == 0) && $editor == true  )
			{
				$groupError = false;
				break;
			}
		}

		if( $Action == "Edit" )
		{
			if( $event->groupID() == $groups->id() && $editor == true  )
			{
				$groupError = false;
				break;
			}
		}

		if( $Action == "Insert" || $Action == "Update")
		{
			if( ($StoreByGroupID == $groups->id() || $StoreByGroupID == 0) && $editor == true )
			{
				$groupError = false;
				break;
			}
		}
	}
}


if ( $event->groupID() )
	$session->setVariable( "ShowOtherCalenderGroups", $event->groupID() );
elseif( $masterGroupID != 0 && !isset( $EventID ) )
	$session->setVariable( "ShowOtherCalenderGroups", $masterGroupID );


// only the specified group member is allowed to edit or delete an event
if ( $Action == "Edit" && $groupError == true )
{
    $t->set_var( "no_error", "" );
    $t->set_var( "no_user_error", "" );

    $t->parse( "wrong_user_error", "wrong_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );

    $groupError = true;
}


if ( $Action == "DeleteEvents" )
{
	//initialize the error array
	$error = array();

	//get the date from the first event to be deleted
	$tmpAppointment = new eZGroupEvent( $eventArrayID[0]);
    $datetime = $tmpAppointment->dateTime();

    if ( count ( $eventArrayID ) != 0 )
    {
		foreach( $eventArrayID as $ID )
        {
			array_push( $error, $ID );
			$event = new eZGroupEvent( $ID );
			foreach ( $groupsList as $groups )
			{
				//If the user has a matching group set their group id to the matching group else there group id will be -1.
				if( $event->groupID() == $groups->id() )
				{
					$dump = array_pop( $error );
					$event->delete();
					exec("/home/httpd/vhosts/ezpublish2.mcotest.umsystem.edu/publish/secure_clearcache.sh");
					break;
				}
			}  
		}
	}


    // user not allowed to delete this appointment
	if( count( $error ) > 0 )
    {
		$t->set_var( "no_error", "" );
		$t->set_var( "no_user_error", "" );

		$t->parse( "wrong_user_error", "wrong_user_error_tpl" );
		$t->parse( "user_error", "user_error_tpl" );
		$t->pparse( "output", "appointment_edit_tpl" );

		$groupError = true;
    }
	else
	{ 
		$year = addZero( $datetime->year() );
		$month = addZero( $datetime->month() );
		$day = addZero( $datetime->day() );
		//deleteCache( "default", $Language, $year, $month, $day, $groupID );

		eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
		exit();
	}
}


// Allowed format for start and stop time:
// 14 14:30 14:0 1430
// the : can be replaced with any non number character
if ( ($Action == "Insert" || $Action == "Update")  && $groupError == false )
{
    if ( isSet( $Cancel ) )
    {
        $event = new eZGroupEvent( $EventID );
        $dt = $event->dateTime();
        $year = $dt->year();
        $month = $dt->month();
        $day = $dt->day();
        eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
        exit();
    }

    $user = eZUser::currentUser();
    if ( $user )
    {
        $type = new eZGroupEventType( $TypeID );

        if ( $Action == "Update" )
            $event = new eZGroupEvent( $EventID );
        else
            $event = new eZGroupEvent();

        $event->setDescription( $Description );
        $event->setType( $type );
        $event->setPriority( $Priority );

        if ( $IsPrivate == "on" )
            $event->setIsPrivate( true );
        else
            $event->setIsPrivate( false );

        if ( $Name != "" )
        {
            $event->setName( $Name );
        }
        else
        {
            $TitleError = true;
        }

		if ( $StoreByGroupID != 0 )
        {
			$group = new eZUserGroup( $StoreByGroupID );
            $event->setGroup( $group );
        }
        else
        {
            $GroupInsertError = true;
        }

        // start/stop time for the day
        $dayStartTime = new eZTime();
        $dayStopTime = new eZTime();

        if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $dayStartArray ) )
        {
            $hour = $dayStartArray[2];
            $dayStartTime->setHour( $hour );

            $min = $dayStartArray[3];
            $dayStartTime->setMinute( $min );

            $dayStartTime->setSecond( 0 );
        }

        if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $dayStopArray ) )
        {
            $hour = $dayStopArray[2];
            $dayStopTime->setHour( $hour );

            $min = $dayStopArray[3];
            $dayStopTime->setMinute( $min );

            $dayStopTime->setSecond( 0 );
        }

        // start/stop time for the appointment
        $startTime = new eZTime();
        $stopTime = new eZTime();

        $startTime->setSecond( 0 );
        $stopTime->setSecond( 0 );

        if ( $IsAllDay == "on" )
        {
            $starthour = $dayStartArray[2];
			$startmin  = $dayStartArray[3];
			$stophour  = $dayStopArray[2];
			$stopmin   = $dayStopArray[3];

            $startTime->setHour( $starthour );
            $startTime->setMinute( $startmin  );
            $stopTime->setHour( $stophour );
            $stopTime->setMinute( $stopmin  );
		}
        else
		{
			if ( $Start_Hour != '' && $Start_Minute != '' )
			{
				$hour = $Start_Hour;
				$hour = convertToTwentyFour( $hour, $Start_AM_PM );
				settype( $hour, "integer" );
				$startHour = $hour;

				$startTime->setHour( $hour );

				$min = $Start_Minute;
				settype( $min, "integer" );

				$startTime->setMinute( $min );
			}
			else
			{
				$StartTimeError = true;
			}

			if ( $Stop_Hour != '' && $Stop_Minute != '')
			{
				$hour = $Stop_Hour;
				$hour = convertToTwentyFour( $hour, $Stop_AM_PM );
				settype( $hour, "integer" );
				
				if( $hour > $startHour )
				{
					$stopTime->setHour( $hour );

					$min = $Stop_Minute;
					settype( $min, "integer" );

					$stopTime->setMinute( $min );
				}
				else
					$StopTimeError = true;
			}
			else
			{
				$StopTimeError = true;
			}
		}

        $datetime = new eZDateTime( $Year, $Month, $Day );
        $datetime->setSecondsElapsedHMS( $startTime->hour(), $startTime->minute(), 0 );

        $event->setDateTime( $datetime );


        if ( $stopTime->isGreater( $startTime, true ) )
        {
            $StopTimeError = true;
        }
        else
        {
            $duration = new eZTime( $stopTime->hour() - $startTime->hour(),
                                    $stopTime->minute() - $startTime->minute() );

            $event->setDuration( $duration );
        }

        if ( $TitleError == false && $GroupInsertError == false && $StartTimeError == false && $StopTimeError == false )
        {
            $event->store();
			exec("/home/httpd/vhosts/ezpublish2.mcotest.umsystem.edu/publish/secure_clearcache.sh");

            $year = addZero( $datetime->year() );
            $month = addZero( $datetime->month() );
            $day = addZero( $datetime->day() );
            deleteCache( "default", $Language, $year, $month, $day, $groupID );

            eZHTTPTool::header( "Location: /groupeventcalendar/dayview/$year/$month/$day/" );
            exit();
        }
        else
        {
            $t->set_var( "name_value", $event->name() );
            $t->set_var( "description_value", $event->description() );

			$t->set_var( "group_name_new", "" );
			if ( $user )
			{
				include_once( "ezuser/classes/ezusergroup.php" );

				$t->set_var( "group_item", "" );

				// build the group drop down list
				foreach( $groupsList as $groups )
				{
					if( $noShowGroup->groupEntry( $groups->id() ) == false )
					{
						$t->set_var( "group_member_name", $groups->name() );
						$t->set_var( "group_member_id", $groups->id() );

						if ( $groups->id() == $StoreByGroupID )
							$t->set_var( "group_is_selected", "selected" );
						else
							$t->set_var( "group_is_selected", "" );

						$t->parse( "group_item", "group_item_tpl", true );
					}
				}

				$t->parse( "group_name_new", "group_name_new_tpl", true );
			}

			$t->parse( "new_history", "new_history_tpl", true );

            if ( $event->isPrivate() )
                $t->set_var( "is_private", "checked" );
            else
                $t->set_var( "is_private", "" );

			$eventStartTime =& $event->startTime();
			$startHour		= ( addZero( $eventStartTime->hour() ) );
			$startMinute	= ( addZero( $eventStartTime->minute() ) );

			$eventStopTime  =& $event->stopTime();
			$stopHour		= ( addZero( $eventStopTime->hour() ) );
			$stopMinute		= ( addZero( $eventStopTime->minute() ) );

			if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $dayStartArray ) )
			{
				$dayStarthour = $dayStartArray[2];
				$dayStartMin  = $dayStartArray[3];
			}

			if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $dayStopArray ) )
			{
				$dayStopHour = $dayStopArray[2];
				$dayStopMin  = $dayStopArray[3];
			}

			$t->set_var( "is_all_day", "" );
			if( $startHour = $dayStarthour && $startMinute == $dayStartMin && $stopHour == $dayStopHour && $stopMinute == $dayStopMin )
				$t->set_var( "is_all_day", "checked" );

			$minuteInterval = $ini->read_var( "eZGroupEventCalendarMain", "MinutesSelectInterval" );

			$minute_array    = array();
			for( $i=0; $i<60; $i = $i + $minuteInterval )
			{
				$i = addZero( $i );
				array_push ($minute_array, "$i");
			}

			if( $timeSelect == "enabled" )
			{
				$hour_array   = array('01','02','03','04','05','06','07','08','09','10','11','12');

				if( $startHour >= 12 )
				{
					$t->set_var( "start_am", "unchecked" );
					$t->set_var( "start_pm", "checked" );
				}
				else
				{
					$t->set_var( "start_pm", "unchecked" );
					$t->set_var( "start_am", "checked" );
				}

				if( $stopHour >= 12 )
				{
					$t->set_var( "stop_am", "unchecked" );
					$t->set_var( "stop_pm", "checked" );
				}
				else
				{
					$t->set_var( "stop_pm", "unchecked" );
					$t->set_var( "stop_am", "checked" );
				}

				$startHour = convertToTwelve( $startHour );
				$stopHour = convertToTwelve( $stopHour );

				$t->parse( "start_ampm_radio", "start_ampm_radio_tpl" );
				$t->parse( "stop_ampm_radio", "stop_ampm_radio_tpl" ); 
			}
			else
			{
				$t->set_var( "start_ampm_radio", "" );
				$t->set_var( "stop_ampm_radio", "" ); 
				$hour_array   = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
			}


			foreach( $hour_array as $hour )
			{
				$t->set_var( "is_start_hour_selected", $hour == $startHour ? "selected" : "" );
				$t->set_var( "start_hour", $hour );
				$t->parse( "start_hour_item", "start_hour_item_tpl", true );

				$t->set_var( "is_stop_hour_selected", $hour == $stopHour ? "selected" : "" );
				$t->set_var( "stop_hour", $hour );
				$t->parse( "stop_hour_item", "stop_hour_item_tpl", true );
			}

			foreach( $minute_array as $minute )
			{
				$t->set_var( "is_start_minute_selected", $minute == $startMinute ? "selected" : "" );
				$t->set_var( "start_minute", $minute );
				$t->parse( "start_minute_item", "start_minute_item_tpl", true );

				$t->set_var( "is_stop_minute_selected", $minute == $stopMinute ? "selected" : "" );
				$t->set_var( "stop_minute", $minute );
				$t->parse( "stop_minute_item", "stop_minute_item_tpl", true );
			}
        }
    }
}

$t->set_var( "user_error", "" );

if ( ($Action == "Insert" || $Action == "Update")  && $groupError == true )
{
	$t->set_var( "no_error", "" );
    $t->set_var( "no_user_error", "" );

    $t->parse( "wrong_user_error", "wrong_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );
}

if ( $Action == "Update" && $groupError == false )
{
    $t->set_var( "name_value", $Name );
    $t->set_var( "description_value", $Description );
    $t->set_var( "start_value", $Start );
    $t->set_var( "stop_value", $Stop );

    $typeID = $TypeID;

    $t->set_var( "0_selected", "" );
    $t->set_var( "1_selected", "" );
    $t->set_var( "2_selected", "" );

    if ( $Priority == 0 )
        $t->set_var( "0_selected", "selected" );
    else if ( $Priority == 1 )
        $t->set_var( "1_selected", "selected" );
    else if ( $Priority == 2 )
        $t->set_var( "2_selected", "selected" );

    if ( $IsPrivate == "on" )
        $t->set_var( "is_private", "checked" );
    else
        $t->set_var( "is_private", "" );

    $t->set_var( "action_value", $Action );
    $t->set_var( "appointment_id", $EventID );
}


$today = new eZDate();
$tmpdate = new eZDate( $Year, $Month, $Day );


$t->set_var( "edit", "" );

if ( $Action == "Edit" && $groupError == false )
{
    $event = new eZGroupEvent( $EventID );
    $t->set_var( "name_value", $event->name() );
    $t->set_var( "event_id", $event->id() );
    $t->set_var( "description_value", $event->description() );

	include_once("ezuser/classes/ezusergroup.php" );
	$group = new eZUserGroup( $groupID );
	
	//$t->set_var( "group_name", $group->name() );
	//$t->set_var( "group_id", $group->id() );
	//$t->parse( "group_name_edit", "group_name_edit_tpl", true );


	// build the group drop down list
	$noshow_array = $noShowGroup->getAll();
	$t->set_var( "group_item", "" );

	foreach( $groupsList as $groups )
	{
		if( $noShowGroup->groupEntry( $groups->id() ) == false )
		{
			$t->set_var( "group_member_id", $groups->id() );
			$t->set_var( "group_member_name", $groups->name() );

			if ( $groups->id() == $group->id() )
				$t->set_var( "group_is_selected", "selected" );
			else
				$t->set_var( "group_is_selected", "" );

			$t->parse( "group_item", "group_item_tpl", true );
		}
	}

	$t->parse( "group_name_new", "group_name_new_tpl", true );
	
	$t->set_var( "group_print_id", $group->id() );
	$t->set_var( "group_print_name", $group->name() );
	$t->set_var( "event_title", $event->name() );

	//set the history bar data
	$t->parse( "edit_history", "edit_history_tpl", true );

    $type =& $event->type();
    $typeID = $type->id();

    $date  = $event->dateTime();
	$year  = $date->year();
    $month = $date->month();
    $day   = $date->day();

    $tmpdate = new eZDate( $year, $month, $day );
	
	$startTime   =& $event->startTime();
	$startHour   = ( addZero( $startTime->hour() ) );
	$startMinute = ( addZero( $startTime->minute() ) );

	$stopTime    =& $event->stopTime();
	$stopHour    = ( addZero( $stopTime->hour() ) );
	$stopMinute  = ( addZero( $stopTime->minute() ) );

	if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StartTimeStr, $dayStartArray ) )
    {
		$dayStarthour = $dayStartArray[2];
		$dayStartMin  = $dayStartArray[3];
    }

    if ( preg_match( "#(^([0-9]{1,2})[^0-9]{0,1}([0-9]{0,2})$)#", $StopTimeStr, $dayStopArray ) )
    {
		$dayStopHour = $dayStopArray[2];
		$dayStopMin  = $dayStopArray[3];
    }

	$t->set_var( "is_all_day", "" );
	$t->set_var( "is_start_hour_selected", "" );
	$t->set_var( "is_start_minute_selected", "" );
	$t->set_var( "is_stop_hour_selected", "" );
	$t->set_var( "is_stop_minute_selected", "" );
	$t->set_var( "start_ampm_radio", "" );
	$t->set_var( "stop_ampm_radio", "" );
	$allDay = false;

	if( $startHour == $dayStarthour && $startMinute == $dayStartMin && $stopHour == $dayStopHour && $stopMinute == $dayStopMin )
	{
		$allDay = true;
		$t->set_var( "is_all_day", "checked" );
	}

	$minuteInterval = $ini->read_var( "eZGroupEventCalendarMain", "MinutesSelectInterval" );

	$minute_array    = array();
	for( $i=0; $i<60; $i = $i + $minuteInterval )
	{
		$i = addZero( $i );
		array_push ($minute_array, "$i");
	}

	if( $timeSelect == "enabled" )
	{
		$hour_array   = array('01','02','03','04','05','06','07','08','09','10','11','12');

		if( $startHour >= 12 )
		{
			$t->set_var( "start_am", "unchecked" );
			$t->set_var( "start_pm", "checked" );
		}
		else
		{
			$t->set_var( "start_pm", "unchecked" );
			$t->set_var( "start_am", "checked" );
		}

		if( $stopHour >= 12 )
		{
			$t->set_var( "stop_am", "unchecked" );
			$t->set_var( "stop_pm", "checked" );
		}
		else
		{
			$t->set_var( "stop_pm", "unchecked" );
			$t->set_var( "stop_am", "checked" );
		}

		$startHour = convertToTwelve( $startHour );
		$stopHour = convertToTwelve( $stopHour );

		$t->parse( "start_ampm_radio", "start_ampm_radio_tpl" );
		$t->parse( "stop_ampm_radio", "stop_ampm_radio_tpl" ); 
	}
	else
	{
		$t->set_var( "start_ampm_radio", "" );
		$t->set_var( "stop_ampm_radio", "" ); 
		$hour_array   = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
	}

	foreach( $hour_array as $hour )
	{
		if( $allDay == false && $hour == $startHour )
			$t->set_var( "is_start_hour_selected", "selected" );
		else
			$t->set_var( "is_start_hour_selected", "" );

		$t->set_var( "start_hour", $hour );
		$t->parse( "start_hour_item", "start_hour_item_tpl", true );


		if( $allDay == false && $hour == $stopHour )
			$t->set_var( "is_stop_hour_selected", "selected" );
		else
			$t->set_var( "is_stop_hour_selected", "" );

		$t->set_var( "stop_hour", $hour );
		$t->parse( "stop_hour_item", "stop_hour_item_tpl", true );
	}

	foreach( $minute_array as $minute )
	{
		if( $allDay == false && $minute == $startMinute )
			$t->set_var( "is_start_minute_selected", "selected" );
		else
			$t->set_var( "is_start_minute_selected", "" );

		$t->set_var( "start_minute", $minute );
		$t->parse( "start_minute_item", "start_minute_item_tpl", true );

		if( $allDay == false && $minute == $stopMinute )
			$t->set_var( "is_stop_minute_selected", "selected" );
		else
			$t->set_var( "is_stop_minute_selected", "" );

		$t->set_var( "stop_minute", $minute );
		$t->parse( "stop_minute_item", "stop_minute_item_tpl", true );
	}

    $t->set_var( "0_selected", "" );
    $t->set_var( "1_selected", "" );
    $t->set_var( "2_selected", "" );

    if ( $event->priority() == 0 )
        $t->set_var( "0_selected", "selected" );
    else if ( $event->priority() == 1 )
        $t->set_var( "1_selected", "selected" );
    else if ( $event->priority() == 2 )
        $t->set_var( "2_selected", "selected" );

    $dt =& $event->dateTime();
	$today = new eZDate();

	$tempYear = addZero( $today->year() );
    //$tempYear = $tmpdate->year();
	$yearsPrint = $ini->read_var( "eZGroupEventCalendarMain", "YearsPrint" );
	for( $i=1; $i<=$yearsPrint; $i++ )
	{
		if( $dt->year() == $tempYear )
		{
			$t->set_var( "year_value", $tempYear );
			$t->set_var( "is_year_selected", "selected" );
			$i--;
		}
		else
		{
			$t->set_var( "year_value", $tempYear );
			$t->set_var( "is_year_selected", "" );
		}

		$tempYear++;
		$t->parse( "year", "year_tpl", true );
	}

    if ( $event->isPrivate() )
        $t->set_var( "is_private", "checked" );
    else
        $t->set_var( "is_private", "" );

    $t->set_var( "action_value", "update" );
}


// print out error messages
if ( $TitleError == true )
{
    $t->parse( "title_error", "title_error_tpl" );
	$t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "title_error", "" );

if ( $GroupInsertError == true )
{
    $t->parse( "group_error", "group_error_tpl" );
	$t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "group_error", "" );

if ( $StartTimeError == true )
{
    $t->parse( "start_time_error", "start_time_error_tpl" );
	$t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "start_time_error", "" );

if ( $StopTimeError == true )
{
    $t->parse( "stop_time_error", "stop_time_error_tpl" );
	$t->set_var( "action_value", "insert" );
}
else
    $t->set_var( "stop_time_error", "" );




if ( $Action == "New" && $groupError == false )
{
	$user = eZUser::currentUser();

	$t->set_var( "group_name_new", "" );
	if ( $user )
	{
		include_once( "ezuser/classes/ezusergroup.php" );

		$t->set_var( "group_item", "" );

		// build the group drop down list

			$noshow_array = $noShowGroup->getAll();

		foreach( $groupsList as $groups )
		{
			if( $noShowGroup->groupEntry( $groups->id() ) == false )
			{
				$t->set_var( "group_member_id", $groups->id() );
				$t->set_var( "group_member_name", $groups->name() );

				if ( $groups->id() == $masterGroupID )
					$t->set_var( "group_is_selected", "selected" );
				else
					$t->set_var( "group_is_selected", "" );

				$t->parse( "group_item", "group_item_tpl", true );
			}
		}

		$t->parse( "group_name_new", "group_name_new_tpl", true );
	}

	$t->parse( "new_history", "new_history_tpl", true );

    $t->set_var( "action_value", "insert" );
    $t->set_var( "appointment_id", "new" );
    $t->set_var( "name_value", "" );
    $t->set_var( "description_value", "" );
    $t->set_var( "is_private", "" );
    $t->set_var( "start_value", "" );
    $t->set_var( "stop_value", "" );
	$t->set_var( "is_all_day", "" );

    $t->set_var( "0_selected", "" );
    $t->set_var( "1_selected", "" );
    $t->set_var( "2_selected", "" );

	$priority = $ini->read_var( "eZGroupEventCalendarMain", "Priority" );

    if ( $priority== 0 )
        $t->set_var( "0_selected", "selected" );
    else if ( $priority == 1 )
        $t->set_var( "1_selected", "selected" );
    else if ( $priority == 2 )
        $t->set_var( "2_selected", "selected" );

    if ( $Year != 0 )
        $year = $Year;
    else
        $year = $today->year();

    if ( $Month != 0 )
        $month = $Month;
    else
        $month = $today->month();

    if ( $Day != 0 )
        $day = $Day;
    else
        $day = $today->day();

    $tmpdate = new eZDate( $year, $month, $day );

	$minuteInterval = $ini->read_var( "eZGroupEventCalendarMain", "MinutesSelectInterval" );

	$minute_array    = array();
	for( $i=0; $i<60; $i = $i + $minuteInterval )
	{
		$i = addZero( $i );
		array_push ($minute_array, "$i");
	}

	if ( $StartTime != 0 )
	{
		$startHour   = substr( $StartTime, 0, 2);
		$startMinute = substr( $StartTime, 2, 3);
	}
	else
	{
		$startHour   = '';
		$startMinute = '';
	}

	if( $timeSelect == "enabled" )
	{
		$hour_array   = array('01','02','03','04','05','06','07','08','09','10','11','12');

		if( $startHour >= 12 )
		{
			$t->set_var( "start_am", "unchecked" );
			$t->set_var( "start_pm", "checked" );
		}
		else
		{
			$t->set_var( "start_pm", "unchecked" );
			$t->set_var( "start_am", "checked" );
		}

		$startHour = convertToTwelve( $startHour );

		$t->parse( "start_ampm_radio", "start_ampm_radio_tpl" );
		$t->parse( "stop_ampm_radio", "stop_ampm_radio_tpl" ); 
	}
	else
	{
		$t->set_var( "start_ampm_radio", "" );
		$t->set_var( "stop_ampm_radio", "" ); 
		$hour_array   = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
	}

    $t->set_var( "is_start_hour_selected", "" );
	$t->set_var( "is_start_minute_selected", "" );
	$t->set_var( "is_stop_hour_selected", "" );
	$t->set_var( "is_stop_minute_selected", "" );

	foreach( $hour_array as $hour )
	{
		if ( $StartTime != 0 )
			$t->set_var( "is_start_hour_selected", $hour == $startHour ? "selected" : "" );
		$t->set_var( "start_hour", $hour );
		$t->parse( "start_hour_item", "start_hour_item_tpl", true );

		$t->set_var( "stop_hour", $hour );
		$t->parse( "stop_hour_item", "stop_hour_item_tpl", true );
	}

	foreach( $minute_array as $minute )
	{
		if ( $StartTime != 0 )
			$t->set_var( "is_start_minute_selected", $minute == $startMinute ? "selected" : "" );
		$t->set_var( "start_minute", $minute );
		$t->parse( "start_minute_item", "start_minute_item_tpl", true );

		$t->set_var( "stop_minute", $minute );
		$t->parse( "stop_minute_item", "stop_minute_item_tpl", true );
	}
}
elseif( $Action == "New" && $groupError == true && $errorPrint == false )
{
	$t->set_var( "no_error", "" );
    $t->set_var( "no_user_error", "" );

    $t->parse( "wrong_user_error", "wrong_user_error_tpl" );
    $t->parse( "user_error", "user_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );

}

// print the event types
$type = new eZGroupEventType();
$typeList =& $type->getTree();

foreach ( $typeList as $type )
{
    if ( $type[1] > 1 )
        $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $type[1] - 1 ) );
    else
        $t->set_var( "option_level", "" );

    if ( $typeID )
    {	
        if ( $typeID == $type[0]->id() )
        {
            $t->set_var( "type_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "type_is_selected", "" );
        }
    }
	else
	{
		$t->set_var( "type_is_selected", "" );
	}
    
    $t->set_var( "option_name", $type[0]->name() );
    $t->set_var( "option_value", $type[0]->id() );
    
    $t->parse( "value", "value_tpl", true );
}

// set day combobox
$daysInMonth = $tmpdate->daysInMonth();
//for ( $i=1; $i<=$daysInMonth; $i++ )
for ( $i=1; $i<=31; $i++ )
{
    if ( $tmpdate->day() == $i )
	{
		$t->set_var( "day_value", $i );
        $t->set_var( "selected", "selected" );
	}
    else
        $t->set_var( "selected", "" );

    if ( $Action == "Edit" && $groupError == false )
    {
        if ( $dt->day() == $i )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    
    $t->set_var( "day_id", $i );
    $t->set_var( "day_name", $i );

    $t->parse( "day", "day_tpl", true );
}

// set month combobox
$month = $tmpdate->month();
for ( $i=1; $i<13; $i++ )
{
    if ( $month == $i )   // don't use $tmpdate->month() since it gets changed
	{
		$t->set_var( "month_value", $i );
        $t->set_var( "selected", "selected" );
	}
    else
        $t->set_var( "selected", "" );

    if ( $Action == "Edit" && $groupError == false )
    {
        if ( $dt->month() == $i )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }

    $tmpdate->setMonth( $i );
    $t->set_var( "month_id", $i );
    $t->set_var( "month_name", $Locale->monthName( $tmpdate->monthName() ) );

    $t->parse( "month", "month_tpl", true );
}

if ( $Action != "Edit" )
{
    $t->set_var( "year_value", $tmpdate->year() );
	$t->set_var( "is_year_selected", "selected" );
	$t->parse( "year", "year_tpl", true );
	$yearsPrint = $ini->read_var( "eZGroupEventCalendarMain", "YearsPrint" );
	for( $i=1; $i<=$yearsPrint; $i++ )
	{
		$t->set_var( "year_value", $tmpdate->year() + $i );
		$t->set_var( "is_year_selected", "" );
		$t->parse( "year", "year_tpl", true );
	}
}

if ( $groupError == false )
{
    $t->parse( "no_error", "no_error_tpl" );
    $t->pparse( "output", "event_edit_tpl" );
}


// deletes the dayview cache file for a given day
function deleteCache( $siteStyle, $language, $year, $month, $day, $groupID )
{
    unlink( "ezgroupeventcalendar/user/cache/dayview.tpl-$siteStyle-$language-$year-$month-$day-$groupID.cache" );
    unlink( "ezgroupeventcalendar/user/cache/monthview.tpl-$siteStyle-$language-$year-$month-$groupID.cache" );
    unlink( "ezgroupeventcalendar/user/cache/dayview.tpl-$siteStyle-$language-$year-$month-$day-$groupID-private.cache" );
    unlink( "ezgroupeventcalendar/user/cache/monthview.tpl-$siteStyle-$language-$year-$month-$groupID-private.cache" );
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

function convertToTwelve( $value )
{
	switch( $value )
	{
		case "13" :
		{
			$ret = 1;
		}
		break;

		case "14" :
		{
			$ret = 2;
		}
		break;

		case "15" :
		{
			$ret = 3;
		}
		break;

		case "16" :
		{
			$ret = 4;
		}
		break;

		case "17" :
		{
			$ret = 5;
		}
		break;

		case "18" :
		{
			$ret = 6;
		}
		break;

		case "19" :
		{
			$ret = 7;
		}
		break;

		case "20" :
		{
			$ret = 8;
		}
		break;

		case "21" :
		{
			$ret = 9;
		}
		break;

		case "22" :
		{
			$ret = 10;
		}
		break;

		case "23" :
		{
			$ret = 11;
		}
		break;

		case "00" :
		{
			$ret = 12;
		}
		break;

		default :
		{
			$ret = $value;
		}
		break;
	}

	$ret = addZero( $ret );

	return $ret;
}

function convertToTwentyFour( $value, $period )
{
	switch( $value )
	{
		case "01" :
		{
			if( $period == "pm" )
				$ret = 13;
			else
				$ret = $value;
		}
		break;

		case "02" :
		{
			if( $period == "pm" )
				$ret = 14;
			else
				$ret = $value;
		}
		break;

		case "03" :
		{
			if( $period == "pm" )
				$ret = 15;
			else
				$ret = $value;
		}
		break;

		case "04" :
		{
			if( $period == "pm" )
				$ret = 16;
			else
				$ret = $value;
		}
		break;

		case "05" :
		{
			if( $period == "pm" )
				$ret = 17;
			else
				$ret = $value;
		}
		break;

		case "06" :
		{
			if( $period == "pm" )
				$ret = 18;
			else
				$ret = $value;
		}
		break;

		case "07" :
		{
			if( $period == "pm" )
				$ret = 19;
			else
				$ret = $value;
		}
		break;

		case "08" :
		{
			if( $period == "pm" )
				$ret = 20;
			else
				$ret = $value;
		}
		break;

		case "09" :
		{
			if( $period == "pm" )
				$ret = 21;
			else
				$ret = $value;
		}
		break;

		case "10" :
		{
			if( $period == "pm" )
				$ret = 22;
			else
				$ret = $value;
		}
		break;

		case "11" :
		{
			if( $period == "pm" )
				$ret = 23;
			else
				$ret = $value;
		}
		break;

		case "12" :
		{
			if( $period == "pm" )
			{
				$ret = 12;
			}
			else
			{
				$ret = 0;
			}
		}
		break;

		default :
		{
			$ret = $value;
		}
		break;
	}

	$ret = addZero( $ret );

	return $ret;
}
?>
