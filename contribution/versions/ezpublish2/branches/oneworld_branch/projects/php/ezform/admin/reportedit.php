<?php
//
// $Id: reportedit.php,v 1.1 2002/01/18 14:05:57 jhe Exp $
//
// Created on: <17-Jan-2002 13:33:08 jhe>
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

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformreport.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "reportedit.php" );
$t->setAllStrings();

$t->set_file( "report_edit_tpl", "reportedit.tpl" );

$t->set_block( "report_edit_tpl", "form_element_tpl", "form_element" );

$t->set_var( "form_element", "" );

if ( $Action == "edit" )
{
    $report = new eZFormReport( $ReportID );
    $t->set_var( "report_id", $ReportID );
    $t->set_var( "report_name", $report->name() );
}
else if ( $Action == "new" )
{
    $report = new eZFormReport();
    $t->set_var( "report_id", "" );
    $t->set_var( "report_name", "" );
}
else if ( $Action == "store" )
{
    $report = new eZFormReport( $ReportID );
    $report->setName( $reportName );
    $report->setForm( $form );
    $report->store();
    $ReportID = $report->id();
    include_once( "classes/ezhttptool.php" );
    if ( isSet( $OK ) )
    {
        eZHTTPTool::header( "Location: /form/report/" );
        exit();
    }
    else if ( isSet( $Setup ) )
    {
        eZHTTPTool::header( "Location: /form/report/setup/$ReportID" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /form/report/edit/$ReportID" );
        exit();
    }
}

$forms = eZForm::getAll( 0, false );

foreach ( $forms as $form )
{
    $t->set_var( "form_id", $form->id() );
    $t->set_var( "form_name", $form->name() );
    $t->set_var( "selected", $report->form( false ) == $form->id() ? "selected" : "" );
    $t->parse( "form_element", "form_element_tpl", true );
}

$t->pparse( "output", "report_edit_tpl" );

?>
