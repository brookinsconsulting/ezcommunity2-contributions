<?php
// 
// $Id: ezmenubox.php,v 1.5 2001/01/24 10:01:26 bf Exp $
//
// Definition of eZMenuBox class
//
// Jan Borsodi <jb@ez.no>
// Created on: <23-Jan-2001 17:47:58 amos>
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

//!! eZCommon
//! The eZMenuBox class creates menuboxes on the fly
/*!
  Example:
  \code
  $menuItems = array( array( "/module/modulelist/", "{intl-modulelist}" ) );
  eZMenuBox::createBox( "eZModule", "ezmodule", "admin", "standard", $menuItems );
  // or
  print( eZMenuBox::createBox( "eZModule", "ezmodule", "admin", "standard", $menuItems, false ) );
  \endcode

*/

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

class eZMenuBox
{
    function eZMenuBox()
    {
    }

    /*!
      Creates a menubox with:
      $ModuleName used for looking up language in the site.ini file,
      $module_dir used for locating translation files,
      $place where in the module, either "admin" or "user"
      $SiteStyle used for setting the look of the boxes,
      $menuItems contains the menu items and
      $print print the box if true else return the box as text.
    */

    function createBox( $ModuleName, $module_dir, $place, $SiteStyle, &$menuItems, $print = true )
    {
        include_once( "ezsession/classes/ezpreferences.php" );
        $preferences = new eZPreferences();
        
        $ini =& $GLOBALS["GlobalSiteIni"];

        $Language = $ini->read_var( $ModuleName . "Main", "Language" );

        $menuStatus =& $preferences->variable( $module_dir . "_status" );


        if ( $GLOBALS["ToggleMenu"] == $module_dir )
        {
            if ( $menuStatus == "open" )
            {
                $preferences->setVariable( $module_dir . "_status", "closed" );
            }
            else
            {
                $preferences->setVariable( $module_dir . "_status", "open" );                
            }
            
            $menuStatus =& $preferences->variable( $module_dir . "_status" );
        }
        
        if ( !$menuStatus )
        {
            $menuStatus = "open";
        }

        $t = new eZTemplate( "templates/" . $SiteStyle,
                             $module_dir . "/$place/intl", $Language, "menubox.php",
                              $SiteStyle, $module_dir . "/$place", $menuStatus );

        if ( $t->hasCache() )
        {
            print $t->cache();
            return true;
        }

        $t->setAllStrings();

        if ( $menuStatus == "open" )
        {
            $t->set_file( array(
                "menu_box_tpl" => "menubox.tpl"
                ) );
            
            $t->set_block( "menu_box_tpl", "menu_item_tpl", "menu_item" );
            $t->set_block( "menu_item_tpl", "menu_item_link_tpl", "menu_item_link" );
            $t->set_block( "menu_item_tpl", "menu_item_break_tpl", "menu_item_break" );

            $uri = $GLOBALS["REQUEST_URI"];
            $uri = "/";
            $uri =& eZHTTPTool::addVariable( $uri, "ToggleMenu", $module_dir );
            $t->set_var( "request_uri", $uri );
            
            
            $t->set_var( "site_style", $SiteStyle );
            $t->set_var( "module_dir", $module_dir );
            
            foreach ( $menuItems as $menuItem )
            {
                $t->set_var( "menu_item_link", "" );
                $t->set_var( "menu_item_break", "" );
                $error = false;
                if ( is_array( $menuItem ) )
                {
                    $t->set_var( "target_url", $menuItem[0]  );
                    $t->set_var( "name", $t->translate( $menuItem[1] ) );

                    $t->parse( "menu_item_link", "menu_item_link_tpl", true );
                }
                else if ( is_string( $menuItem ) )
                {
                    switch( $menuItem )
                    {
                        case "break":
                        {
                            $t->parse( "menu_item_break", "menu_item_break_tpl", true );
                            break;
                        }
                        default:
                        {
                            $error = true;
                        }
                    }
                }
                else
                {
                    $error = true;
                }

                if ( $error )
                {
                    print( "<h1>Unknown menubox item, \"" );
                    print_r( $menuItem );
                    print( "\"</h1><br />" );
                }
                $t->parse( "menu_item", "menu_item_tpl", true );
            }
        }
        else
        {
            $t->set_file( array(
                "menu_box_tpl" => "menubox_closed.tpl"
                ) );

            $t->set_var( "site_style", $SiteStyle );
            $t->set_var( "module_dir", $module_dir );
            
//              $uri = $GLOBALS["REQUEST_URI"];
            $uri = "/";
            $uri =& eZHTTPTool::addVariable( $uri, "ToggleMenu", $module_dir );
            $t->set_var( "request_uri", $uri );
        }
        
        
        return $t->storeCache( "output", "menu_box_tpl", $print );
    }
};

?>
