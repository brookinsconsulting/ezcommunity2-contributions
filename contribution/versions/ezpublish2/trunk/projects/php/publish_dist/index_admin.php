<?php
// 
// $Id: index_admin.php,v 1.15 2001/08/08 12:34:52 jhe Exp $
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

// Tell PHP where it can find our files.
if ( file_exists( "sitedir.ini" ) )
{
    include_once( "sitedir.ini" );
}

// TODO: This needs a better analysis
if ( isset( $siteDir ) and $siteDir != "" )
{
    $includePath = ini_get( "include_path" );
    $includePath .= ":" . $siteDir;
    ini_set( "include_path", $includePath );
 
    // For non-virtualhost, non-rewrite setup
    if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_NAME, $regs ) )
    {
        $wwwDir = $regs[1];
        $index = $regs[2];
    }
 
    // Remove url parameters
    if ( ereg( "^$wwwDir$index(.+)", $REQUEST_URI, $req ) )
    {
        $REQUEST_URI = $req[1];
    }
    else
    {
        $REQUEST_URI = "/";
    }
}
else
{
    // Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs );
    $REQUEST_URI = $regs[1];
 
    $wwwDir = "";
    $index = "";
}
    
// Start the buffer cache
ob_start();

$UsePHPSessions = false;

if ( $UsePHPSessions == true )
{
    // start session handling
    session_start();
} 

// settings for sessions
// max timeout is set to 48 hours
ini_alter("session.gc_maxlifetime", "172800");
ini_alter("session.entropy_file","/dev/urandom"); 
ini_alter("session.entropy_length", "512");  

ini_alter("session.cache_expire", "172800");

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/template.inc" );
include_once( "classes/ezmenubox.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezmodule/classes/ezmodulehandler.php" );

include_once( "classes/ezhttptool.php" );

// File functions
include_once( "classes/ezfile.php" );

$ini =& INIFile::globalINI();
$GlobalSiteIni =& $ini;

//  $session =& eZSession::globalSession();
//  $session->fetch();
//  print( "<pre>" . $session->hash() . "</pre>" );

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

$SiteStyle =& $ini->read_var( "site", "SiteStyle" );

$GLOBALS["DEBUG"] = true;

// Remove url parameters
// ereg( "([^?]+)", $REQUEST_URI, $regs ) ;
// $REQUEST_URI = $regs[1];

$url_array =& explode( "/", $REQUEST_URI );


$user =& eZUser::currentUser();
if ( $user )
{
    if ( $url_array[1] == "help" )
    {
        $HelpMode  = "enabled";
        
        include( "admin/help_header.php" );
    }
    else
    {
        // html header
        if ( $PrintableVersion == "enabled" )
        {        
            include( "admin/print_header.php" );
        }
        else
        {
            include( "admin/header.php" );
        }
    }
              
    
    require( "ezuser/admin/admincheck.php" );
    
    if ( !( $HelpMode == "enabled" ) )
    {
        include_once( "ezsession/classes/ezpreferences.php" );
        $preferences = new eZPreferences();

        $site_modules = $ini->read_array( "site", "EnabledModules" );
        $modules =& eZModuleHandler::active();

        $uri =& $GLOBALS["REQUEST_URI"];

        if ( $PrintableVersion != "enabled" )
        {
            if ( !empty( $GLOBALS["ToggleMenu"] ) )
            {
                foreach( $modules as $module )
                {
                    $module_dir = strtolower( $module );
                    if ( $GLOBALS["ToggleMenu"] == $module_dir )
                    {
                        eZModuleHandler::toggle( $module_dir );
                        $uri = eZHTTPTool::removeVariable( $uri, "ToggleMenu" );
                        eZHTTPTool::header( "Location: $uri" );
                        exit;
                    }
                }
            }

            $moved_module = false;
            eZModuleHandler::moveUp( $modules, $GLOBALS["MoveUp"], $moved_module );
            if ( !$moved_module )
            {
                eZModuleHandler::moveDown( $modules, $GLOBALS["MoveDown"], $moved_module );
            }

            $uri = eZHTTPTool::removeVariable( $uri, "MoveUp" );
            $uri = eZHTTPTool::removeVariable( $uri, "MoveDown" );

            if ( $moved_module )
            {
                $preferences->setVariable( "EnabledModules", $modules );
                eZHTTPTool::header( "Location: $uri" );
                exit;
            }

            // draw modules
            foreach ( $modules as $module )
            {
                if ( !empty( $module ) )
                {
                    $module_dir =& strtolower( $module );
                    unset( $menuItems );
                    include( "$module_dir/admin/menubox.php" );
                    if ( isset( $menuItems ) )
                        eZMenuBox::createBox( $module, $module_dir, "admin",
                        $SiteStyle, $menuItems, true, false,
                        "$module_dir/admin/menubox.php", false, true );
                }
            }
        }

        // parse the URI
        $page = "";
    
    
        // send the URI to the right decoder
        $page = "ez" . $url_array[1] . "/admin/datasupplier.php";
        // set the module logo
        $moduleName =& $url_array[1];

        if ( $moduleName == "" )
            $moduleName = "user";


        if ( $PrintableVersion != "enabled" )
        {
            // break the column an draw a horizontal line
            include( "admin/separator.php" );
        }

        if ( eZFile::file_exists( $page ) )
        {
            include( $page );
        }
        else
        {
            include( "ezuser/admin/welcome.php" );
        }
    }
    else
    {
        // show the help page

        $helpFile = "ez" . $url_array[2] . "/admin/help/". $Language . "/" . $url_array[3] . "_" . $url_array[4] . ".hlp";

        if ( eZFile::file_exists( $helpFile ) )
        {
            include( $helpFile );
        }
        else
        {
            print( "help file not found" );

        }
    }

    if ( $HelpMode == "enabled" )
    {
        include( "admin/help_footer.php" );
    }
    else
    {
        // html footer
        if ( $PrintableVersion == "enabled" )
        {
            include( "admin/print_footer.php" );
        }
        else
        {
            include( "admin/footer.php" );
        }
    }    
}
else
{
    include( "admin/loginheader.php" );
    
    if ( $moduleName == "" )
        $moduleName = "user";

    $LoginSeparator = true;

    if ( $REQUEST_URI == "/" )
    {
        $REQUEST_URI = "/user/login";
        $url_array =& explode( "/", $REQUEST_URI );
    }

    // parse the URI
    $page = "";

    // send the URI to the right decoder
    $page = "ezuser/admin/datasupplier.php";

    if ( eZFile::file_exists( $page ) )
    {
        include( $page );
    }

    // html footer
    include( "admin/loginfooter.php" );
}


// close the database connection.
$db =& eZDB::globalDatabase();
$db->close();

print( $db->isA() );

// flush the buffer cache
ob_end_flush();
?>

