<?php
/*!
    $Id: eztemplate.php,v 1.5 2000/09/08 13:17:17 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <02-Aug-2000 22:14:20 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "classes/template.inc" );
include_once( "classes/INIFile.php" );


//!! eZCommon
//! The eZTemplate class provides template functions. In regard to locale information.
/*!
  
*/

class eZTemplate extends Template
{

    var $TextStrings;

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

    function setAllStrings()
    {
        for ( $i = 0; $i < count ( $this->TextStrings ); $i++ )
        {
            $tmp = each( $this->TextStrings );

            $this->set_var( "intl-" . $tmp[0], $tmp[1] );
        }
    }
}

?>
