<?php
// 
// $Id: eztemplate.php,v 1.6 2000/09/15 13:47:28 bf-cvs Exp $
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
        $this->intlDir = $intlDir;
        $this->language = $language;
        $this->phpFile = $phpFile;
        $this->Template( $templateDir );
//        print( $intlDir . "/" . $language . "/" . $phpFile . ".ini" );
        $ini = new INIFile( $intlDir . "/" . $language . "/" . $phpFile . ".ini", false );

        $this->TextStrings = $ini->read_group( "strings" );
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
    
    var $TextStrings;    
}

?>
