<?php
//
// Created on: <27-May-2002 15:06:36 jhe>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "classes/ezhttptool.php" );
if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /calendar/typelist/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezcalendar/classes/ezcalendar.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZCalendarMain", "Language" );

if ( $Action == "insert" )
{
    $cal = new eZCalendar();
    $cal->setName( $Name );

    $cal->store();

    foreach ( $Groups as $groupID )
    {
        eZObjectPermission::setPermission( $groupID, $cal->id(), "calendar_calendar", 'w' );
    }

    eZHTTPTool::header( "Location: /calendar/archive/" );
    exit();
}

if ( $Action == "update" )
{
    $cal = new eZCalendar( $CalendarID );
    $cal->setName( $Name );

    $cal->store();
    eZObjectPermission::removePermissions( $cal->id(), "calendar_calendar", 'w' );
    foreach ( $Groups as $groupID )
    {
        eZObjectPermission::setPermission( $groupID, $cal->id(), "calendar_calendar", 'w' );
    }

    eZHTTPTool::header( "Location: /calendar/archive/" );
    exit();
}

if ( $Action == "delete" )
{
    if ( count( $CalendarArrayID ) > 0 )
    {
        foreach ( $CalendarArrayID as $CalID )
        {
            $cal = new eZCalendar( $CalID );
            $calName = $cal->name();

            $cal->delete();

            eZLog::writeNotice( "Calendar deleted: $calName from IP: $REMOTE_ADDR" );
        }
    }
    eZHTTPTool::header( "Location: /calendar/archive/" );
    exit();
}

$t = new eZTemplate( "ezcalendar/admin/" . $ini->read_var( "eZCalendarMain", "AdminTemplateDir" ),
                     "ezcalendar/admin/intl/", $Language, "calendaredit.php" );

$t->setAllStrings();

$t->set_file( "calendar_edit_tpl", "calendaredit.tpl" );
$t->set_block( "calendar_edit_tpl", "group_element_tpl", "group_element" );

$t->set_var( "group_element", "" );

$userGroups =& eZUserGroup::getAll();

if ( $Action == "edit" )
{
    $t->set_var( "action_type", "update" );
    $cal = new eZCalendar( $CalendarID );
    
    $t->set_var( "calendar_id", $cal->id() );
    $t->set_var( "calendar_name", $cal->name() );
    $groupsID = eZObjectPermission::getGroups( $CalendarID, "calendar_calendar", 'w' , false );

    foreach ( $userGroups as $group )
    {
        $t->set_var( "group_id", $group->id() );
        $t->set_var( "group_name", $group->name() );
        $t->set_var( "selected", in_array( $group->id(), $groupsID ) ? "selected" : "" );

        $t->parse( "group_element", "group_element_tpl", true );
    }
}
else
{
    $t->set_var( "action_type", "insert" );
    $t->set_var( "calendar_id", "" );
    $t->set_var( "calendar_name", "" );
    foreach ( $userGroups as $group )
    {
        $t->set_var( "group_id", $group->id() );
        $t->set_var( "group_name", $group->name() );
        $t->set_var( "selected", "" );

        $t->parse( "group_element", "group_element_tpl", true );
    }
}

$t->pparse( "output", "calendar_edit_tpl" );

?>
