<?php 
// 
// $Id: INIFile.php,v 1.40 2001/08/16 07:33:55 bf Exp $
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
//! The INIFile class provides .ini file functions.
/*!
  The INI file class supports comments which starts with a # and stops at the end of the line,
  this means that one cannot use these characters in groups, keys or values.

  The INI file can also read MS-DOS text files,
  which has an extra carriage return to signal an end of line.

  \code
  include_once( "classes/INIFile.php" );
  $ini = new INIFile( "site.ini" );

  $PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );

  $arrayTest = $ini->read_array( "site", "ArrayTest" );

  foreach ( $arrayTest as $test )
  {
    print( "test: ->$test<-<br>" );
  }  
  \endcode
*/

class INIFile
{ 

    /*!
      Constructs a new INIFile object.
    */
    function INIFile( $inifilename="", $write=false )
    {
        include_once( "classes/ezfile.php" );
        
        // echo "INIFile::INIFile( \$inifilename = $inifilename,\$write = $write )<br />\n";
        // $this->load_data( $inifilename, $write );

        $cachedFile = "classes/cache/" . md5(  realpath( $inifilename ) ) . ".php";

        
        // check for modifications
        $cacheTime = eZFile::filemtime( $cachedFile );
        $origTime = eZFile::filemtime( $inifilename );
        $overrideTime = eZFile::filemtime( "override/" . $inifilename );
        $appendTime = eZFile::filemtime( "override/" . $inifilename . ".append" );

        $loadCache = false;
        if ( eZFile::file_exists( $cachedFile ) )
        {
            $loadCache = true;
            if ( $cacheTime < $origTime )
                $loadCache = false;
            if ( eZFile::file_exists( "override/" . $inifilename ) and $cacheTime < $overrideTime )
                $loadCache = false;
            if ( eZFile::file_exists( "override/" . $inifilename . ".append" ) and $cacheTime < $appendTime )
                $loadCache = false;
        }


        if ( $loadCache )
        {
            include( $cachedFile );
        }
        else
        {
            $this->load_data( $inifilename, $write );
            // save the data to a cached file
            $buffer = "";
            $i = 0;
            reset( $this->GROUPS );        
            while ( list( $groupKey, $groupVal ) = each ( $this->GROUPS ) )
            {
                reset( $groupVal );
                while ( list( $key, $val ) = each ( $groupVal ) )
                {
                    $tmpVal = str_replace( "\"", "\\\"", $val );

                    $buffer .= "\$Array_". $i . "[\"$key\"] = \"$tmpVal\";\n";
                }

                $buffer .= "\$this->GROUPS[\"$groupKey\"] =& \$Array_". $i .";\n";
                $i++;
            }
            $buffer = "<?php\n" . $buffer . "\n?>";

            $fp = eZFile::fopen( $cachedFile, "w+" );        
            fwrite ( $fp, $buffer );
            fclose( $fp );
        }
        
    }

    function load_data( $inifilename = "",$write = true, $useoverride = true )
    {
        $this->WRITE_ACCESS = $write;
        if ( !empty($inifilename) )
        {
            if ( !eZFile::file_exists($inifilename) )
            { 
                $this->error( "This file ($inifilename) does not exist!"); 
            }
            else
            {
                $this->parse($inifilename);
            }
        }
        if ( $useoverride )
            $this->load_override_data( "override/" . $inifilename );
    }

    function load_override_data( $inifilename="" )
    {
        $appendfilename = $inifilename . ".append";
        if ( !empty($inifilename) and eZFile::file_exists($inifilename) )
        {
            $this->parse($inifilename, false );
        }
        else if ( !empty($appendfilename) and eZFile::file_exists($appendfilename) )
        {
            $this->parse($appendfilename, true );
        }
    }

    function file_exists( $inifilename )
    {
        return ( eZFile::file_exists( "override/$inifilename.append" ) or
                 eZFile::file_exists( "override/$inifilename" ) or
                 eZFile::file_exists( $inifilename ) );
    }

    /*!
      Parses the ini file.
    */
    function parse( $inifilename, $append = false )
    {
        $this->INI_FILE_NAME = $inifilename;

        $fp = eZFile::fopen( $inifilename, $this->WRITE_ACCESS ? "r+" : "r" );

        if ( !isset( $this->CURRENT_GROUP ) or !$append )
             $this->CURRENT_GROUP=false;
        if ( !isset( $this->GROUPS ) or !$append )
             $this->GROUPS=array();

        $contents =& fread($fp, eZFile::filesize($inifilename));
        $contents .= "\n";
        $ini_data =& split( "\n",$contents);

        for ( $i = 0; $i < count( $ini_data ); $i++ )
        {
            $data =& $ini_data[$i];
            // Remove MS-DOS Carriage return from end of line
            if ( ord( $data[strlen($data) - 1] ) == 13 )
                $data = substr( $data, 0, strlen($data) - 1 );
        }

        while( list($key, $data) = each($ini_data) ) 
        { 
            $this->parse_data($data); 
        }

        fclose( $fp ); 
    } 

    /*!
      Parses the variable.
    */
    function parse_data( $data )
    {
        // Remove comments from line
        if ( preg_match( "/([^#]*)#.*/", $data, $m ) )
        {
            $data = $m[1];
        }

        if( ereg( "\[([[:alnum:]]+)\]", $data, $out ) )
        {
            $this->CURRENT_GROUP = strtolower( $out[1] ); 
        } 
        else 
        {
            $split_data =& split( "=", $data );
            
            if ( !isset( $split_data[1] ) )
                $split_data[1] = "";
            $this->GROUPS[ $this->CURRENT_GROUP ][ $split_data[0] ] = $split_data[1]; 
        }
    }

    /*!
      Saves the ini file.
    */
    function save_data() 
    {
        $fp = eZFile::fopen($this->INI_FILE_NAME, "w");

        if ( empty($fp) ) 
        { 
            $this->Error( "Cannot create file $this->INI_FILE_NAME"); 
            return false; 
        } 
         
        $groups = $this->read_groups(); 
        $group_cnt = count($groups); 

        for($i=0; $i<$group_cnt; $i++) 
        { 
            $group_name = $groups[$i];
            if ( $i == 0 )
            {
                $res = sprintf( "[%s]\n",$group_name);
            }
            else
            {
                $res = sprintf( "\n[%s]\n",$group_name);
            }
            fwrite($fp, $res); 
            $group = $this->read_group($group_name); 
            for(reset($group); $key=key($group);next($group)) 
            { 
                $res = sprintf( "%s=%s\n",$key,$group[$key]); 
                fwrite($fp,$res); 
            } 
        } 
         
        fclose($fp); 
    } 

    /*!
      Returns the number of groups.
    */
    function get_group_count() 
    { 
        return count($this->GROUPS); 
    } 
     
    /*!
      Returns an array with the names of all the groups.
    */
    function read_groups() 
    { 
        $groups = array(); 
        for (reset($this->GROUPS);$key=key($this->GROUPS);next($this->GROUPS)) 
            $groups[]=$key; 
        return $groups; 
    } 

    /*!
      Checks if a group exists.
    */
    function group_exists( $group_name )
    {
        $group_name = strtolower( $group_name );
        $group =& $this->GROUPS[$group_name];
        if (empty($group)) return false; 
        else return true; 
    } 

    /*!
      Returns an associative array of the variables in one group.
    */
    function read_group($group) 
    {
        $group = strtolower( $group );
        $group_array =& $this->GROUPS[$group]; 
        if(!empty($group_array))  
            return $group_array; 
        else  
        { 
            $this->Error( "Group $group does not exist"); 
            return false; 
        } 
    } 
     
    /*!
      Adds a new group to the ini file.
    */
    function add_group($group_name) 
    {
        $group_name = strtolower( $group_name );
        $new_group = $this->GROUPS[$group_name]; 
        if ( empty($new_group) ) 
        { 
            $this->GROUPS[$group_name] = array(); 
        } 
        else
        {
            $this->Error( "Group $group_name exists");
        }
    } 

    /*!
      Clears a group.
    */
    function empty_group($group_name) 
    {
        $group_name = strtolower( $group_name );
        unset( $this->GROUPS[$group_name] );
        $this->GROUPS[$group_name] = array();
    } 

    /*!
      Returns true if the group and variable exists.
    */
    function has_var( $group, $var_name )
    {
        $group = strtolower( $group );
        return isset( $this->GROUPS[$group] ) and isset( $this->GROUPS[$group][$var_name] );
    }

    /*!
      Reads a variable from a group.
    */
    function read_var( $group, $var_name )
    {
        $group = strtolower( $group );
        if ( !isset( $this->GROUPS[$group] ) or !isset( $this->GROUPS[$group][$var_name] ) )
        {
            $this->Error( "$var_name does not exist in $group");
            return false;
        }
        return $this->GROUPS[$group][$var_name];
    }

    /*!
      Reads a variable from a group and returns the result as an
      array of strings.

      The variable is splitted on ; characters.
    */
    function read_array( $group, $var_name )
    {
        if ( $this->has_var( $group, $var_name ) )
        {
            $var_value =& $this->read_var( $group, $var_name );
            if ( $var_value != "" )
            {
                $var_array =& explode( ";", $var_value );
            }
            else
            {
                $var_array = array();
            }
            return $var_array;
        }
        else
        {
            $this->Error( "array $var_name does not exist in $group");
            return false; 
        }
    }
     
    /*!
      Sets a variable in a group.
    */
    function set_var( $group, $var_name, $var_value )
    {
        $group = strtolower( $group );
        $this->GROUPS[$group][$var_name] = $var_value;
    }     


    /*!
      Prints the error message.
    */
    function error($errmsg) 
    { 
        $this->ERROR = $errmsg; 
        echo  "Error:" . $this->ERROR . "<br>\n"; 
        return; 
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

    var $INI_FILE_NAME =  ""; 
    var $ERROR =  ""; 
    var $GROUPS = array();
    var $CURRENT_GROUP =  "";
    var $WRITE_ACCESS = ""; 
    
} 

?>
