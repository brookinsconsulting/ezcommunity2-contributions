<?
// 
// $Id: menubox.php,v 1.16 2001/06/01 13:33:11 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Oct-2000 17:53:46 bf>
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
    array( "/article/unpublished/", "{intl-unpublished}" ),
    array( "/article/archive/", "{intl-archive}" ),
    array( "/article/sitemap/", "{intl-sitemap}" ),
    array( "/article/categoryedit/new/", "{intl-new_category}" ),
    array( "/article/topiclist/", "{intl-topiclist}" ),
    array( "/article/articleedit/new/", "{intl-new_article}" ),
    array( "/article/type/list", "{intl-list_type}" ),
    array( "/article/type/edit", "{intl-new_type}" )
    );

?>
