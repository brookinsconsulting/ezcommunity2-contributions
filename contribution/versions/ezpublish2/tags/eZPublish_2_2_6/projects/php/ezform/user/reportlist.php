<?php
//
// $Id: reportlist.php,v 1.1 2002/01/18 14:05:57 jhe Exp $
//
// Created on: <18-Jan-2002 14:39:26 jhe>
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

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "reportlist.php" );

$t->setAllStrings();

$t->set_file( "form_report_tpl", "reportlist.tpl" );

$t->set_block( "form_report_tpl", "report_item_tpl", "report_item" );

$reports = eZFormReport::getAll();

foreach ( $reports as $report )
{
    $t->set_var( "report_id", $report->id() );
    $t->set_var( "report_name", $report->name() );

    $t->parse( "report_item", "report_item_tpl", true );
}

$t->pparse( "output", "form_report_tpl" );

?>
