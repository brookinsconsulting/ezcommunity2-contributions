<?php
/*!
    $Id: eztemplate.php,v 1.3 2000/09/01 14:18:28 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <02-Aug-2000 22:14:20 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "classes/template.inc" );
include_once( "classes/class.INIFile.php" );

class eZTemplate extends Template {

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
