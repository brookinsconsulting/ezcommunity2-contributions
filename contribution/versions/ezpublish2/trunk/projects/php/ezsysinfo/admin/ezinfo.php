<?php
// 
// $Id: ezinfo.php,v 1.1 2001/11/01 16:12:13 sascha Exp $
//
// Created on: <21-Apr-2001 12:11:59 bf>
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


include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezpublish.php" );

include_once( "ezsysinfo/classes/ezsysinfo.php" );


$ini =& INIFile::globalINI();
$Language =& $ini->read_var( "eZSysinfoMain", "Language" );

$t = new eZTemplate( "ezsysinfo/admin/" . $ini->read_var( "eZSysinfoMain", "AdminTemplateDir" ),
                     "ezsysinfo/admin/intl/", $Language, "ezinfo.php" );


$t->set_file( "ez_info_tpl", "ezinfo.tpl" );

$t->setAllStrings();

$t->set_var( "server", $ini->read_var( "site", "Server" ) );
$t->set_var( "db", $ini->read_var( "site", "Database" ) );
$t->set_var( "databaseImplementation", $ini->read_var( "site", "DatabaseImplementation" ));
    
$t->set_var( "ezpublish_version", eZPublish::version() );

$t->pparse( "output", "ez_info_tpl" );
    


?>


