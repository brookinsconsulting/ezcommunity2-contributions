<?php
// 
// $Id: header.php,v 1.39 2001/10/12 10:52:58 sascha Exp $
//
// Created on: <23-Jan-2001 16:06:07 bf>
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


include_once( "ezmodule/classes/ezmodulehandler.php" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezpublish.php" );


$ini =& INIFile::globalINI();
$Language =& $ini->read_var( "eZUserMain", "Language" );
$Locale = new eZLocale( $Language );
$iso = $Locale->languageISO();
//$site_modules = $ini->read_array( "site", "EnabledModules" );
$site_modules = eZModuleHandler::all();
include_once( "ezmodule/classes/ezmodulehandler.php" );

$ModuleTab = eZModuleHandler::activeTab();

include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();
$modules =& eZModuleHandler::active();
// $modules =& $preferences->variableArray( "EnabledModules" );
$single_module = eZModuleHandler::useSingleModule();

$t = new eZTemplate( "admin/templates/" . $SiteStyle,
                     "admin/intl/", $Language, "header.php" );


$t->set_file( "header_tpl", "header.tpl" );


$t->set_block( "header_tpl", "module_list_tpl", "module_list" );
$t->set_block( "module_list_tpl", "module_item_tpl", "module_item" );
$t->set_block( "module_list_tpl", "module_control_tpl", "module_control" );
$t->set_block( "header_tpl", "menu_tpl", "menu_item" );

$SiteURL =& $ini->read_var( "site", "SiteURL" );

$user =& eZUser::currentUser();

if ( $user )
{
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
}
else
{
    $t->set_var( "first_name", "" );
    $t->set_var( "last_name", "" );
}


$t->set_var( "site_url", $SiteURL );

$t->set_var( "site_style", $SiteStyle );

$t->set_var( "module_name", $moduleName );

$t->set_var( "charset", $iso );

$t->set_var( "module_list", "" );
$t->set_var( "module_item", "" );
$t->set_var( "module_control", "" );
$uri = $GLOBALS["REQUEST_URI"];
$t->set_var( "ref_url", $uri );

if ( $ModuleTab == true )
{
    foreach( $site_modules as $site_module )
    {
        $module = strtolower( $site_module );
        if ( eZFile::file_exists( $module ) )
        {
            if ( $single_module )
            {
                $t->set_var( "module_action", "activate" );
            }
            else
            {
                $t->set_var( "module_action", in_array( $site_module, $modules ) ? "deactivate" : "activate" );
            }
            $t->set_var( "ez_module_name", $site_module );
            $t->set_var( "ez_dir_name", $module );
            $moduleSettingName = $site_module . "Main";
            $moduleLanguage = $ini->read_var( $moduleSettingName, "Language" );
            if ( !$moduleLanguage )
                $moduleLanguage = $Lanugage;
            $lang_file = new INIFile( "$module/admin/intl/$moduleLanguage/menubox.php.ini" );
            $mod_name = $lang_file->read_var( "strings", "module_name" );
            $t->set_var( "module_name", $mod_name );
            $t->parse( "module_item", "module_item_tpl", true );
        }
    }
    if ( !$single_module )
        $t->parse( "module_control", "module_control_tpl" );
    $t->parse( "module_list", "module_list_tpl" );
}

$t->setAllStrings();

$t->set_var( "module_count", count ( $modules ) );
$t->set_var( "ezpublish_version", eZPublish::version() );

$t->set_var( "menu_item", "" );

$moduletab = $ini->read_var( "site", "ModuleTab" );

if ( ( $moduletab == "enabled" ) && ( count ( $modules ) != 0 ) )
{
	$t->parse( "menu_item", "menu_tpl" );
}


$t->pparse( "output", "header_tpl" );
    

?>
