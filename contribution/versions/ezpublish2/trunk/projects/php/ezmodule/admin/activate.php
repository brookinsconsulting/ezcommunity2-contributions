<?php
// 
// $Id: activate.php,v 1.1 2001/04/11 14:32:28 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <11-Apr-2001 15:07:58 amos>
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

include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$all_modules = $ini->read_array( "site", "EnabledModules" );

include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();

$modules =& $preferences->variableArray( "EnabledModules" );
$single_module = $preferences->variable( "SingleModule" ) == "enabled";

if ( $single_module )
{
    $modules = array( $ModuleName );
}
else
{
    switch( $ModuleName )
    {
        case "all":
        {
            $modules = $all_modules;
            break;
        }
        case "none":
        {
            $modules = array();
            break;
        }
        default:
        {
            if ( $Activate )
            {
                $modules = array_unique( array_merge( $modules, $ModuleName ) );
            }
            else
            {
                $modules = array_diff( $modules, array( $ModuleName ) );
            }
        }
    }
}
$preferences->setVariable( "EnabledModules", $modules );

//      $uri =& $GLOBALS["REQUEST_URI"];
//      $uri = eZHTTPTool::removeVariable( $uri, "ToggleMenu" );
eZHTTPTool::header( "Location: /" );
exit();

?>
