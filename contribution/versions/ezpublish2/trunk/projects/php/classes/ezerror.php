<?php
/*!
    $Id: ezerror.php,v 1.1 2000/08/16 12:35:09 jhe-cvs Exp $

    Author: Jo Henrik Endrerud <jhe@ez.no>
    
    Created on: <16-Aug-2000 14:10:00 jhe>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
class eZError
{
    var $Language;
    var $Path;

    function eZError( $language, $path = "intl/" )
    {
        $this->Language = $language;
        $this->Path = $path;
    }

    function getError( $message )
    {
        include_once( "class.INIFile.php" );
        $ini = new INIFile( $this->Path . "/" . $this->Language . "/error.ini", false );
        return $ini->read_var( "Error", $message );
    }
}
        
