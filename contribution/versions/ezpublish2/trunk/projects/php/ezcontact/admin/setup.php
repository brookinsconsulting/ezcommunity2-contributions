<?php
// 
// $Id: setup.php,v 1.4 2001/06/28 08:14:53 bf Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <25-Jan-2001 00:58:23 amos>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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


include_once( "classes/ezmenubox.php" );
include_once( "classes/INIFile.php" );

$ini =& $GlobalSiteIni;
$SiteStyle =& $ini->read_var( "site", "SiteStyle" );

$menuItems = array(
    array( "/contact/consultationtype/list/", "{intl-consultationtypelist}" ),
    array( "/contact/projecttype/list/", "{intl-projecttypelist}" )
    );

eZMenuBox::createBox( "eZContact", "ezcontact", "admin", $SiteStyle, $menuItems,
                      true, "menuitems.tpl", false, true );

?>
