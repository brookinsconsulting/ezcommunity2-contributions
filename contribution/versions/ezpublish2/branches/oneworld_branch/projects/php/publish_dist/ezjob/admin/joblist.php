<?php
//
// $Id: joblist.php,v 1.1.2.1 2002/06/04 11:23:49 br Exp $
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <24-May-2002 10:36:50 br>
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

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZJobMain", "Language" );

$t = new eZTemplate( "ezjob/admin/" . $ini->read_var( "eZJobMain", "AdminTemplateDir" ),
                     "ezjob/admin/intl", $Language, "joblist.php" );
$t->set_file( "job_list_tpl", "joblist.tpl" );
$t->setAllStrings();

$t->set_block( "job_list_tpl", "name_list_tpl", "name_list" );
$t->set_block( "name_list_tpl", "name_item_tpl", "name_item" );

$t->set_var( "name_list", "" );
$t->set_var( "offset", "" );

$t->pparse( "output", "job_list_tpl" );

?>
