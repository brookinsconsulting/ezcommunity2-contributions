<?php 
// 
// $Id: INIFile.php,v 1.51 2001/11/14 08:19:26 jhe Exp $
//
// Implements a simple INI-file parser
//
// Based upon class.INIfile.php by Mircho Mirev <mircho@macropoint.com>
//
// Created on: <09-Jun-2001 07:18:20 bf>
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

//!! eZCommon
//! NOTE: This file is obsolete. Use ezinifile.php instead.
/*!
  Obsolete.  
*/

include_once( "classes/ezinifile.php" );

class INIFile
{ 
    /*!
      Constructs a new INIFile object.
    */
    function INIFile( $inifilename = "", $useCache = true )
    {
        $this->INIFileObject = new eZINIFile( $inifilename, $useCache );
    }

    /*!
    */
    function file_exists( $inifilename )
    {
        return ( eZFile::file_exists( "override/" . $inifilename . ".append" ) or
                 eZFile::file_exists( "override/" . $inifilename ) or
                 eZFile::file_exists( $inifilename ) );
    }

    /*!
      
    */
    function read_group( $group_name )
    {
        return $this->INIFileObject->readGroup( $group_name );
    }

    /*!
      Saves the ini file.
    */
    function save_data() 
    {
        $this->INIFileObject->save();
    }

    /*!
      Returns true if the group and variable exists.
    */
    function has_var( $group_name, $var_name )
    {
        $this->INIFileObject->hasVar( $group_name, $var_name );
    }
    
    /*!
      Reads a variable from a group.
    */
    function &read_var( $group_name, $var_name )
    {
        $var = $this->INIFileObject->readVariable( $group_name, $var_name );

        return $var;
    }

    /*!
      Reads a variable from a group and returns the result as an
      array of strings.

      The variable is splitted on ; characters.
    */
    function &read_array( $group_name, $var_name )
    {
        return $this->INIFileObject->readVariableArray( $group_name, $var_name );
    }
     
    /*!
      Sets a variable in a group.
    */
    function set_var( $group_name, $var_name, $var_value )
    {
        return $this->INIFileObject->setVariable( $group_name, $var_name, $var_value );
    }

    /*!
      \static
      Returns the global ini file for a given type. Normally the type is the site.ini INI object,
      loaded from the site.ini file. This can be overidden by supplying $type and $file.
      If the ini-file object does not exist it is created before returning.
    */
    function &globalINI( $type = "SiteIni", $file = "site.ini" )
    {
        $ini =& $GLOBALS["INI_$type"];

        if ( get_class( $ini ) != "inifile" )
        {
            $ini = new INIFile( $file );
        }
        return $ini;
    }

    /// INI file object
    var $INIFileObject;

} 
?>

