<?
// 
// $Id: menubox.php,v 1.4 2001/07/12 08:23:05 bf Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <10-May-2001 14:51:43 ce>
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

// Supply $menuItems to get a menubox

$menuItems = array(
    array( "/sitemanager/section/list/", "{intl-section_list}" ),
    array( "/sitemanager/cache/", "{intl-cache_admin}" ),
    array( "/sitemanager/file/list/", "{intl-file_list}" ),
    array( "/sitemanager/siteconfig/", "{intl-site_config}" )
//    array( "/sitemanager/sqladmin/query/", "{intl-sql_admin}" )
    );

?>
