<?php
//
// Created on: <27-May-2002 14:13:02 jhe>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezcalendar/classes/ezcalendar.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZCalendarMain", "Language" );

$t = new eZTemplate( "ezcalendar/admin/" . $ini->read_var( "eZCalendarMain", "AdminTemplateDir" ),
                     "ezcalendar/admin/intl/", $Language, "calendarlist.php" );

$t->setAllStrings();

$t->set_file( "calendar_list_tpl", "calendarlist.tpl" );

$t->set_block( "calendar_list_tpl", "calendar_item_tpl", "calendar_item" );
$t->set_var( "calendar_item", "" );

$calendarList =& eZCalendar::getAll( true );
$i = 0;

foreach ( $calendarList as $calendar )
{
    $t->set_var( "calendar_id", $calendar->id() );
    $t->set_var( "calendar_name", $calendar->name() );
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->parse( "calendar_item", "calendar_item_tpl", true );
    $i++;
}

$t->pparse( "output", "calendar_list_tpl" );

?>
