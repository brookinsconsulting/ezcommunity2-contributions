<?php
// 
// $Id: eztemplate.php,v 1.8 2000/10/02 11:58:14 bf-cvs Exp $
//
// Definition of eZCompany class
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
  replaced with text in the desired language.
    
*/

class eZTemplate extends Template
{

    /*!
      Constructs a new eZTemplate object.
    */
    function eZTemplate( $templateDir, $intlDir = "", $language = "", $phpFile = "" )
    {
        $this->intlDir =& $intlDir;
        $this->language =& $language;
        $this->phpFile =& $phpFile;
        $this->Template( $templateDir );
//        print( $intlDir . "/" . $language . "/" . $phpFile . ".ini" );
        $this->ini = new INIFile( $intlDir . "/" . $language . "/" . $phpFile . ".ini", false );

        $this->TextStrings = $this->ini->read_group( "strings" );
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
    
    
    
    var $TextStrings;
    var $ini;    
}

?>
