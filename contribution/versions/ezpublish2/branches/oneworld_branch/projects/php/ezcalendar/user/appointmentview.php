<?php
// 
// $Id: appointmentview.php,v 1.18.10.1 2002/06/04 11:57:56 jhe Exp $
//
// Created on: <08-Jan-2001 11:53:05 bf>
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
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezcalendar/classes/ezappointment.php" );
include_once( "ezcalendar/classes/ezappointmenttype.php" );
include_once( "ezcalendar/classes/ezcalendar.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl/", $Language, "appointmentview.php" );

$t->set_file( "appointment_view_tpl", "appointmentview.tpl" );

$t->setAllStrings();

$t->set_block( "appointment_view_tpl", "error_tpl", "error" );
$t->set_block( "appointment_view_tpl", "view_tpl", "view" );
$t->set_block( "view_tpl", "public_tpl", "public" );
$t->set_block( "view_tpl", "private_tpl", "private" );
$t->set_block( "view_tpl", "low_tpl", "low" );
$t->set_block( "view_tpl", "normal_tpl", "normal" );
$t->set_block( "view_tpl", "high_tpl", "high" );
$t->set_block( "view_tpl", "edit_appointment_tpl", "edit_appointment" );

$t->set_var( "edit_appointment", "" );

$user =& eZUser::currentUser();
$session =& eZSession::globalSession();
$session->fetch();

$t->set_var( "view", "" );
if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( $GetByUserID == false )
{
    $GetByUserID = $userID;
}

if ( ( $session->variable( "ShowOtherCalendarUsers" ) == false ) || ( isSet( $GetByUser ) ) )
{
    $session->setVariable( "ShowOtherCalendarUsers", $GetByUserID );
}

$appointment = new eZAppointment( $AppointmentID );

$appointmentType = $appointment->type();
$datetime = $appointment->dateTime();

$t->set_var( "calendar_id", $CalID );
$t->set_var( "appointment_id", $appointment->id() );
$t->set_var( "appointment_title", $appointment->name() );
$t->set_var( "appointment_type", $appointmentType->name() );
$t->set_var( "appointment_date", $locale->format( $datetime->date() ) );
$t->set_var( "appointment_starttime", $locale->format( $appointment->startTime(), true ) );
$t->set_var( "appointment_stoptime", $locale->format( $appointment->stopTime(), true ) );
$t->set_var( "appointment_description", $appointment->description() );

$calID = $appointment->calID();

if ( $appointment->isPrivate() == true )
{
    $t->parse( "private", "private_tpl" );
    $t->set_var( "public", "" );
}
else
{
    $t->parse( "public", "public_tpl" );
    $t->set_var( "private", "" );
}
switch ( $appointment->priority() )
{
    case 0:
    {
        $t->parse( "low", "low_tpl" );
        $t->set_var( "normal", "" );
        $t->set_var( "high", "" );
    }
    break;
    case 1:
    {
        $t->parse( "normal", "normal_tpl" );
        $t->set_var( "low", "" );
        $t->set_var( "high", "" );
    }
    break;
    case 2:
    {
        $t->parse( "high", "high_tpl" );
        $t->set_var( "low", "" );
        $t->set_var( "normal", "" );
    }
    break;
}

$calendar = new eZCalendar( $CalID );
$groupsID = eZObjectPermission::getGroups( $CalID, "calendar_calendar", 'w' , false );
$showAdd = false;
if ( get_class( $user ) == "ezuser" )
    $userGroups =& $user->groups( false );
else
    $userGroups = array();

foreach ( $groupsID as $addGroup )
{
    if ( in_array( $addGroup, $userGroups ) )
        $showAdd = true;
}

if ( $showAdd )
    $t->parse( "edit_appointment", "edit_appointment_tpl" );
else
    $t->set_var( "edit_appointment", "" );

$t->set_var( "error", "" );

$t->parse( "view", "view_tpl" );

$t->pparse( "output", "appointment_view_tpl" );

?>
