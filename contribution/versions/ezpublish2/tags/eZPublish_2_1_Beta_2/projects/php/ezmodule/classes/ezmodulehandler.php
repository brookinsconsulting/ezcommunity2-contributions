<?php
// 
// $Id: ezmodulehandler.php,v 1.3 2001/04/19 07:32:32 jb Exp $
//
// Definition of eZModuleHandler class
//
// Jan Borsodi <jb@ez.no>
// Created on: <18-Apr-2001 13:23:01 amos>
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

//!! eZModule
//! The class eZModuleHandler allows for easy handling of modules
/*!
  Moving modules up and down, enabling/disabling and opening closing can
  easily be done by using this class. It also allows one to get all available modules
  as well as all active ones.

  All functions are static so one can use them without an object.

  \code
  // Returning the active modules
  $modules =& eZModuleHandler::active();
  foreach( $modules as $module )
  {
      print( "$module is active" );
  }

  // Opening a module menubox
  eZModuleHandler::setOpen( "eZTrade" );

  // Moving a module upwards
  $has_moved = false;
  eZModuleHandler::moveUp( $modules, "eZTrade", $has_moved );
  \endcode
*/

include_once( "ezsession/classes/ezpreferences.php" );

class eZModuleHandler
{

    /*!
      \static
      Returns true if the module tabbar is active for the current user.
    */
    function activeTab()
    {
        $preferences = new eZPreferences();
        $module_tab = eZModuleHandler::hasTab();
        $user_module_tab = $preferences->variable( "ModuleTab" );
        if ( $module_tab and !is_bool( $user_module_tab ) )
        {
            $module_tab = $user_module_tab == "enabled";
        }
        return $module_tab;
    }

    /*!
      \static
      Returns true if the module tabbar is activated in the site.ini.
    */
    function hasTab()
    {
        $ini =& INIFile::globalINI();
        $module_tab = $ini->read_var( "site", "ModuleTab" ) == "enabled";
        return $module_tab;
    }

    /*!
      \static
      Sets whether the module tabbar is active or not for the current user.
    */
    function setActiveTab( $active )
    {
        $preferences = new eZPreferences();
        $preferences->setVariable( "ModuleTab", $active ? "enabled" : "disabled" );
    }

    /*!
      \static
      Returns true if only a single module is show at a time.
    */
    function useSingleModule()
    {
        $preferences = new eZPreferences();
        $single_module = $preferences->variable( "SingleModule" );
        if ( !is_bool( $single_module ) )
        {
            $single_module =  $single_module == "enabled";
        }
        else
        {
            $single_module = true;
        }
        return $single_module;
    }

    /*!
      \static
      Returns all available modules as an array.
    */
    function &all()
    {
        $ini =& INIFile::globalINI();
        return $ini->read_array( "site", "EnabledModules" );
    }

    /*!
      \static
      Returns all active modules as an array.
    */
    function &active()
    {
        $ini =& INIFile::globalINI();
        $preferences = new eZPreferences();
        if ( !eZModuleHandler::activeTab() )
        {
            $modules =& $preferences->variableArray( "EnabledModules" );
            $site_modules =& eZModuleHandler::all();
            if ( $modules )
            {
                $modules = array_intersect( $modules, $site_modules );
                $extra_modules = array_diff( $site_modules, $modules );
                $modules = array_merge( $modules, $extra_modules );
                $modules = array_diff( $modules, array( "" ) );
            }
            else
            {
                $modules = array_diff( $site_modules, array( "" ) );
            }
            return $modules;
        }
        else
        {
            return $preferences->variableArray( "EnabledModules" );
        }
    }

    /*!
      \static
      Returns whether the module menubox is open or closed. True is open.
    */
    function isOpen( $module )
    {
        $preferences = new eZPreferences();
        $menuStatus =& $preferences->variable( strtolower( $module ) . "_status" );
        return $menuStatus == "open" || empty( $menuStatus );
    }

    /*!
      \static
      Sets the module menubox to be either open or closed, default is open.
    */
    function setOpen( $module, $open = true )
    {
        $preferences = new eZPreferences();
        $preferences->setVariable( strtolower( $module ) . "_status", $open ? "open" : "closed" );
    }

    /*!
      \static
      Toggles the menubox, if it's open it is closed otherwise opened.
      Returns true if menubox was opened, false otherwise.
      \sa setOpen
    */
    function toggle( $module )
    {
        $preferences = new eZPreferences();
        eZModuleHandler::setOpen( $module, !eZModuleHandler::isOpen( $module ) );
    }

    /*!
      \static
      Sets the active modules.
    */
    function setActive( $modules )
    {
        $preferences = new eZPreferences();
        $preferences->setVariable( "EnabledModules", $modules );
    }

    /*!
      \static
      Appends the module to the end of the modules list if not already present.
      The new list is returned.
    */
    function &append( $modules, $module )
    {
        return array_unique( array_merge( $modules, $module ) );
    }

    /*!
      \static
      Removes the module from the modules list.
      The new list is returned.
    */
    function &remove( $modules, $module )
    {
        return array_diff( $modules, array( $module ) );
    }

    /*!
      \static
      Tries to move the specified module one item up in the array,
      if the top is reached it is wrapped to the bottom.
      The variable moved_module is set to true if a move was done.
    */
    function moveUp( &$modules, $module, &$moved_module )
    {
        $module = strtolower( $module );
        reset( $modules );
        $i = 0;
        $moved_module = false;
        while( list( $key, $module_name ) = each( $modules ) )
        {
            $module_low =& strtolower( $module_name );
            if ( !empty( $module_name ) and $module == $module_low )
            {
                $pos = $i;
                if ( $i > 0 )
                {
                    $pos_above = $i - 1;
                    $module_above = $modules[$pos_above];
                    $modules[$pos_above] = $module_name;
                    $modules[$pos] = $module_above;
                    $moved_module = true;
                    break;
                }
                else
                {
                    $module_item = array_shift( $modules );
                    $modules = array_merge( $modules, $module_item );
                    $moved_module = true;
                    break;
                }
            }
            $i++;
        }
    }

    /*!
      \static
      Tries to move the specified module one item down in the array,
      if the bottom is reached it is wrapped to the top.
      The variable moved_module is set to true if a move was done.
    */
    function moveDown( &$modules, $module, &$moved_module )
    {
        $module = strtolower( $module );
        reset( $modules );
        $i = 0;
        $moved_module = false;
        while( list( $key, $module_name ) = each( $modules ) )
        {
            $module_low =& strtolower( $module_name );
            if ( !empty( $module_name ) and $module == $module_low )
            {
                $pos = $i;
                if ( $i < count( $modules ) - 1 )
                {
                    $pos_below = $i + 1;
                    $module_below = $modules[$pos_below];
                    $modules[$pos_below] = $module_name;
                    $modules[$pos] = $module_below;
                    $moved_module = true;
                    break;
                }
                else
                {
                    $module_item = array_pop( $modules );
                    $modules = array_merge( $module_item, $modules );
                    $moved_module = true;
                    break;
                }
            }
            $i++;
        }
    }
}

?>
