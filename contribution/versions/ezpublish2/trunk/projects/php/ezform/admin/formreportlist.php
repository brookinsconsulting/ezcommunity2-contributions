<?php
//
// $Id: formreportlist.php,v 1.1 2002/01/18 14:05:57 jhe Exp $
//
// Created on: <17-Jan-2002 11:25:28 jhe>
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
include_once( "ezform/classes/ezformreport.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "formreportlist.php" );
$t->setAllStrings();

$t->set_file( "form_report_list_page_tpl", "formreportlist.tpl" );

$t->set_block( "form_report_list_page_tpl", "form_report_tpl", "form_report" );

$t->set_var( "form_report", "" );

$reports = eZFormReport::getAll();

$i = 0;
foreach ( $reports as $rep )
{
    $t->set_var( "td_class", ( $i % 2 == 0 ) ? "bglight" : "bgdark" );
    $t->set_var( "report_id", $rep->id() );
    $t->set_var( "report_name", $rep->name() );

    $t->parse( "form_report", "form_report_tpl" );
}

$t->pparse( "output", "form_report_list_page_tpl" );

?>
