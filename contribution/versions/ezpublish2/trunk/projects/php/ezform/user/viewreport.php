<?php
//
// $Id: viewreport.php,v 1.1 2002/01/21 11:29:57 jhe Exp $
//
// Created on: <21-Jan-2002 09:40:52 jhe>
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
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformtable.php" );
include_once( "ezform/classes/ezformreport.php" );
include_once( "ezform/classes/ezformrenderer.php" );

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "viewreport.php" );

$t->setAllStrings();

$t->set_file( "view_report_tpl", "viewreport.tpl" );

$t->set_var( "report_id", $ReportID );

$report = new eZFormReport( $ReportID );
$form = $report->form();

$renderer =& new eZFormRenderer( $form );

$t->set_var( "form_name", $form->name() );

$output = $renderer->renderResult( $ReportID, true, true );

$t->set_var( "form", $output );

$t->pparse( "output", "view_report_tpl" );

?>
