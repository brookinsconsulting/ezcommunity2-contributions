<?php
// 
// $Id: ezinifile.php,v 1.3 2001/11/14 08:19:26 jhe Exp $
//
// Definition of eZINIFile class
//
// Created on: <25-Oct-2001 10:10:08 bf>
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
//! Provided functionality to access and write .ini style configuration files
/*!
  
  \code
  // include the file
  include_once( "classes/ezinifile.php" );

  $ini = new eZINIFile( "site.ini" );

  // get a variable from the file.
  $iniVar = $ini->readVar( "BlockName", "Variable" );
    
  
  \endcode
*/

class eZINIFile
{
    /*!
      Initialization of object;
     */
    function eZINIFile( $fileName, $useCache = true )
    {
        if ( $fileName == "" )
            $fileName = "site.ini";
            
        $this->FileName = $fileName;
        if ( $useCache )
            $this->loadCache();
        else
            $this->parse();
    }

    /*!
      \private
      Will load a cached version of the ini file if it exists.
    */
    function loadCache()
    {
        include_once( "classes/ezfile.php" );
        
        $cachedFile = "classes/cache/" . md5( eZFile::realpath( $this->FileName ) ) . ".php";
        
        // check for modifications
        $cacheTime = eZFile::filemtime( $cachedFile );
        $origTime = eZFile::filemtime( $this->FileName );
        $overrideTime = eZFile::filemtime( "override/" . $this->FileName );
        $appendTime = eZFile::filemtime( "override/" . $this->FileName . ".append" );

        $loadCache = false;
        if ( eZFile::file_exists( $cachedFile ) )
        {
            $loadCache = true;
            if ( $cacheTime < $origTime )
                $loadCache = false;
            if ( eZFile::file_exists( "override/" . $this->FileName ) and $cacheTime < $overrideTime )
                $loadCache = false;
            if ( eZFile::file_exists( "override/" . $this->FileName . ".append" ) and $cacheTime < $appendTime )
                $loadCache = false;
        }

        if ( $loadCache )
        {
            include( $cachedFile );
        }
        else
        {
            $this->parse();
            // save the data to a cached file
            $buffer = "";
            $i = 0;
            if ( is_array( $this->BlockValues ) )
            {
                reset( $this->BlockValues );
                while ( list( $groupKey, $groupVal ) = each ( $this->BlockValues ) )
                {
                    reset( $groupVal );
                    while ( list( $key, $val ) = each ( $groupVal ) )
                    {
                        $tmpVal = str_replace( "\"", "\\\"", $val );

                        $buffer .= "\$Array_" . $i . "[\"$key\"] = \"$tmpVal\";\n";
                    }

                    $buffer .= "\$this->BlockValues[\"$groupKey\"] =& \$Array_" . $i . ";\n";
                    $i++;
                }
                $buffer = "<?php\n" . $buffer . "\n?>";

                $fp = eZFile::fopen( $cachedFile, "w+" );        
                fwrite ( $fp, $buffer );
                fclose( $fp );
            }
        }
    }

    /*!
      \private
      Will parse the INI file and store the variables in the variable $this->BlockValues
     */
    function &parse( )
    {
        $lines =& file( $this->FileName );

        $currentBlock = "";
        foreach ( $lines as $line )
        {
            // check for new block
            if ( preg_match("#^\[(.+)\]\s*$#", $line, $newBlockNameArray ) )
            {
                $newBlockName = $newBlockNameArray[1];
                $currentBlock = strToLower( $newBlockName );        
            }

            // check for variable
            if ( preg_match("#^([^=]+)=(.+)$#", $line, $valueArray ) )
            {
                $varName = $valueArray[1];
                $varValue = $valueArray[2];
                
                $this->BlockValues[$currentBlock][$varName] = $varValue;
            }
        }

        return $ret;
    }
    

    /*!
      Saves the file to disk.
      If filename is given the file is saved with that name if not the current name is used.
    */
    function &save( $fileName=false )
    {
        include_once( "classes/ezfile.php" );
        if ( $fileName )
            $fp = eZFile::fopen( $fileName, "w+");
        else
            $fp = eZFile::fopen( $this->FileName, "w+");

        $output = ""; 
        while ( list( $blockKey, $blockValue ) = each ( $this->BlockValues ) )
        {
            $output .= "[$blockKey]\n";
            while ( list( $varKey, $varValue ) = each ( $blockValue ) )
            {
                $output .= "$varKey=$varValue\n";
            }            
        }        
        
        fwrite ( $fp, $output );
        fclose( $fp );
    }
    
    /*!
      Reads a variable from the ini file.
      false is returned if the variable was not found.
    */
    function &readVariable( $blockName, $varName )
    {
        $blockName = strToLower( $blockName );
        $ret = $this->BlockValues[$blockName][$varName];

        return $ret;
    }

    /*!
      Checks if a variable is set. Returns true if the variable exists, false if not.
    */
    function &hasVar( $blockName, $varName )
    {
        return isSet( $this->BlockValues[$blockName][$varName] );
    }
    
    /*!
      Reads a variable from the ini file. The variable
      will be returned as an array. ; is used as delimiter.
     */
    function &readVariableArray( $blockName, $varName )
    {
        $blockName = strToLower( $blockName );
        $ret =& explode( ";", $this->BlockValues[$blockName][$varName] );

        return $ret;
    }
    
    /*!
      Fetches a variable group and returns it as an associative array.
     */
    function &readGroup( $blockName )
    {
        $blockName = strToLower( $blockName );        
        $ret = $this->BlockValues[$blockName];

        return $ret;
    }

    
    /*!
      Sets an INI file variable.
     */
    function &setVariable( $blockName, $varName, $varValue )
    {
        $this->BlockValues[$blockName][$varName] = $varValue;
    }
   

    /// Variable to store the ini file values.
    var $BlockValues;

    /// Stores the filename
    var $FileName;
}

?>



