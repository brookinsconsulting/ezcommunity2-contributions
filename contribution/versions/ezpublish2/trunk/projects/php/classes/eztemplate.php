<?php
// 
// $Id: eztemplate.php,v 1.14 2001/01/12 16:07:23 bf Exp $
//
// Definition of eZTemplate class
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/template.inc" );
include_once( "classes/INIFile.php" );

//!! eZCommon
//! The eZTemplate class provides template functions. In regard to locale information.
/*!
  This class provides functions for using templates with internationalized language.
  Template variables which start with intl- are looked up in the language file and
  replaced with text in the described language.
    
*/

class eZTemplate extends Template
{

    /*!
      Constructs a new eZTemplate object.
    */
    function eZTemplate( $templateDir = "", $intlDir = "", $language = "", $phpFile = "" )
    {
        #echo "eZTemplate::eZTemplate( \$templateDir = $templateDir, \$intlDir = $intlDir, \$language = $language, \$phpFile = $phpFile )<br>";
        $this->intlDir =& $intlDir;
        $this->language =& $language;
        $this->phpFile =& $phpFile;
        $this->Template( $templateDir );

        $languageFile = $intlDir . "/" . $language . "/" . $phpFile . ".ini";
        if ( file_exists( $languageFile ) )
        {        
            $this->ini = new INIFile( $intlDir . "/" . $language . "/" . $phpFile . ".ini", false );
            $this->TextStrings = $this->ini->read_group( "strings" );
        }
        else
        {
            print( "<br><b>Error: language file, $languageFile, could not be found.</b><br>" );
        }
    }

    /*!
      Sets all internationalisations.
    */
    function setAllStrings()
    {
        for ( $i = 0; $i < count ( $this->TextStrings ); $i++ )
        {
            $tmp = each( $this->TextStrings );
            
            $this->set_var( "intl-" . $tmp[0], $tmp[1] );
            #echo "intl-" . $tmp[0] . " = " . $tmp[1] . "<br>";
        }
    }
    
    /*!
    */
    function set_var2($varname, $value = "")
    {
        Template::set_var($varname, $value = "");        
        if (!is_array($varname))
        {
            if (!empty($varname))
            {
                if ($this->debug)
                {
                    print "scalar: set *$varname* to *$value*<br>\n";
                }
            }
            $this->varkeys[$varname] = "/".$this->varname($varname)."/";
            $this->varvals[$varname] = $value;
        }
        else
        {
            reset($varname);
            while(list($k, $v) = each($varname))
            {
                if (!empty($k))
                {
                    if ($this->debug)
                    {
                        print "array: set *$k* to *$v*<br>\n";
                    }
                }

                $this->varkeys[$k] = "/".$this->varname($k)."/";
                $this->varvals[$k] = $v;
                }
        }
    }

    /*!
      Returns a reference to the ini file object.
    */
    function &ini()
    {
        return $this->ini;
    }
    
    var $TextStrings;
    var $ini;    
}

?>
