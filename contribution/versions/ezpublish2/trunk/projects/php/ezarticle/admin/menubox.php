<?
// 
// $Id: menubox.php,v 1.8 2001/01/23 15:26:30 bf Exp $
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

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "templates/" . $SiteStyle,
                     "ezarticle/admin/intl", $Language, "menubox.php" );

$t->set_file( array(
    "menu_box_tpl" => "menubox.tpl"
    ) );

$t->set_block( "menu_box_tpl", "menu_item_tpl", "menu_item" );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "module_dir", $module_dir );

$menuItems = array(
    array( "/article/archive/", "{intl-archive}" ),
    array( "/article/categoryedit/new/", "{intl-new_category}" ),
    array( "/article/articleedit/new/", "{intl-new_article}" )
    );

foreach ( $menuItems as $menuItem )
{
    $t->set_var( "target_url", $menuItem[0]  );
    $t->set_var( "name", $menuItem[1]  );

    $t->parse( "menu_item", "menu_item_tpl", true );
}

$t->setAllStrings();
$t->pparse( "output", "menu_box_tpl" );
    

?>
