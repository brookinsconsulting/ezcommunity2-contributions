<?php
/*!
    $Id: ezerror.php,v 1.4 2000/09/08 13:17:17 bf-cvs Exp $

    Author: Jo Henrik Endrerud <jhe@ez.no>
    
    Created on: <16-Aug-2000 14:10:00 jhe>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/


//!! eZCommon
//! The eZDB class provides database.
/*!
  
*/

class eZError
{
    function eZError( $language, $path = "intl/" )
    {
        $this->Language = $language;
        $this->Path = $path;
    }

    function getError( $message )
    {
        include_once( "classes/INIFile.php" );
        $ini = new INIFile( $this->Path . "/" . $this->Language . "/error.ini", false );
        return $ini->read_var( "Error", $message );
    }

    var $Language;
    var $Path;
}
        
