<?php
// 
// $Id: typeedit.php,v 1.9 2001/09/05 11:55:50 jhe Exp $
//
// Created on: <20-Dec-2000 18:24:06 gl>
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

if ( isSet( $Cancel ) )
{
    Header( "Location: /calendar/typelist/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezlog.php" );

// $ini = new INIFIle( "site.ini" );
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZCalendarMain", "Language" );
$LanguageIni = new INIFile( "ezcalendar/admin/intl/" . $Language . "/typeedit.php.ini", false );

include_once( "ezcalendar/classes/ezappointment.php" );
include_once( "ezcalendar/classes/ezappointmenttype.php" );

if ( $Action == "Insert" )
{
    $type = new eZAppointmentType();
    $type->setName( $Name );
    $type->setDescription( $Description );

    if ( $ParentID != 0 && $ParentID != $type->id() )
    {
        $type->setParent( new eZAppointmentType( $ParentID ) );
    }

    $type->store();

    eZHTTPTool::header( "Location: /calendar/typelist/" );
    exit();
}

if ( $Action == "Update" )
{
    $type = new eZAppointmentType( $TypeID );
    $type->setName( $Name );
    $type->setDescription( $Description );

    if ( $ParentID != $type->id() )
    {
        $type->setParent( new eZAppointmentType( $ParentID ) );
    }

    $type->store();

    eZHTTPTool::header( "Location: /calendar/typelist/" );
    exit();
}

if ( $Action == "Delete" )
{
    if ( count( $TypeArrayID ) != 0 )
    {
        foreach ( $TypeArrayID as $TypeID )
        {
            $type = new eZAppointmentType( $TypeID );
            $typeName = $type->name();

            $type->delete();

            eZLog::writeNotice( "Appointment Type deleted: $typeName from IP: $REMOTE_ADDR" );
        }
        eZHTTPTool::header( "Location: /calendar/typelist/" );
        exit();
    }
}


$t = new eZTemplate( "ezcalendar/admin/" . $ini->read_var( "eZCalendarMain", "AdminTemplateDir" ),
                     "ezcalendar/admin/intl/", $Language, "typeedit.php" );

$t->setAllStrings();

$t->set_file( array( "type_edit_tpl" => "typeedit.tpl" ) );

$t->set_block( "type_edit_tpl", "parent_item_tpl", "parent_item" );


$t->set_var( "parent_is_selected", "selected" );

if ( $Action == "Edit" )
{
    $type = new eZAppointmentType( $TypeID );
    if ( $type->parentID() != 0 )
        $t->set_var( "parent_is_selected", "" );
}
else
{
    $type = new eZAppointmentType();
}

$t->set_var( "parent_name", "No parent" );
$t->set_var( "parent_id", "0" );
$t->parse( "parent_item", "parent_item_tpl", true );


$typeList = $type->getTree();
foreach ( $typeList as $typeSubList )
{
    $typeItem = $typeSubList[0];
    $typeLevel = $typeSubList[1];
    $indent = "";

    if ( $typeLevel > 1 )
        $indent =  str_repeat( "&nbsp;&nbsp;", $typeLevel - 1 );

    $t->set_var( "parent_name", $indent . $typeItem->name() );
    $t->set_var( "parent_id", $typeItem->id() );
    $t->set_var( "parent_is_selected", "" );

    if ( $Action == "Edit" && $type->parentID() == $typeItem->id() )
        $t->set_var( "parent_is_selected", "selected" );

    if ( $type->id() != $typeItem->id() )
        $t->parse( "parent_item", "parent_item_tpl", true );
}

if ( $Action == "Edit" )
{
    $t->set_var( "header", $LanguageIni->read_var( "strings", "edit_appointment_type" ) );
    $t->set_var( "name_value", $type->name() );
    $t->set_var( "description_value", $type->description() );
    $t->set_var( "type_id", $type->id() );
    $t->set_var( "action_value", "Update" );
}
else
{
    $t->set_var( "header", $LanguageIni->read_var( "strings", "new_appointment_type" ) );
    $t->set_var( "description_value", "" );
    $t->set_var( "name_value", "" );
    $t->set_var( "type_id", "" );
    $t->set_var( "action_value", "Insert" );
}


$t->pparse( "output", "type_edit_tpl" );

?>
