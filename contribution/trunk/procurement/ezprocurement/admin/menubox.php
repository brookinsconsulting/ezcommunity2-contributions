<?php
// 
// $Id: menubox.php,v 1.22 2001/09/27 15:20:35 bf Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// Supply $menuItems to get a menubox

$menuItems = array(
    array( "/procurement/sitemap/", "{intl-rfp_list}" ),

//    array( "/procurement/archive/10", "{intl-rfp_list}" ),
//    array( "/procurement/archive/0", "{intl-archive}" ),
    array( "/procurement/edit/new/", "{intl-new_rfp}" ),
    array( "/user/ingroup/5", "{intl-user_list}" ),
    array( "/user/new", "{intl-new_user}" ),
    array( "/filemanager/map", "{intl-rfp_attachments}" ),
    array( "/filemanager/new", "{intl-rfp_new_file}" ),

//    array( "/procurement/categoryedit/new/", "{intl-new_category}" ),
    array( "/procurement/unpublished/", "{intl-unpublished}" ),
    // array( "/procurement/search/advanced", "{intl-search_advanced}" ),

    array( "/procurement/report", "{intl-rfp_report}" ),

    array( "/procurement/cache/clear", "{intl-rfp_cache}" )


  //  array( "/procurement/sitemap/", "{intl-sitemap}" ),
//    array( "/procurement/pendinglist/", "{intl-pending_list}" ),
//    array( "/procurement/topiclist/", "{intl-topiclist}" ),
//    array( "/procurement/type/list", "{intl-list_type}" ),
//    array( "/procurement/type/edit", "{intl-new_type}" ),
//    array( "/procurement/edit/new/", "{intl-new_widget}" ),
//    array( "/procurement/export", "{intl-export}" )
    );

?>
