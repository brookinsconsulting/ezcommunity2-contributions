<?php
// 
// $Id: ezhttptool.php,v 1.2 2001/01/23 21:11:16 bf Exp $
//
// Definition of eZTextTool class
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Jan-2001 12:34:54 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZCommon
//! Provied utility functions for http.
/*!
*/

class eZHTTPTool
{
    /*!
      \static
      This function is a wrapper to the PHP function
      header();

      It enabled cookieless sessions with header.
    */
    function header( $string )
    {
        $sid =& $GLOBALS["PHPSESSID"];

        if ( isset( $sid ) )
        {
            $string = eZHTTPTool::addVariable( $string, "PHPSESSID", $sid );
        }
        
        header( $string );
    }

    /*!
      \static
      Returns a url with the variable added to the url.
    */
    function &addVariable( $url, $variable, $value )
    {
        $pos = strpos( $url, "?" );

        if ( $pos )
        {
            $string = $url . "&" . $variable. "=" . $value;
        }
        else
        {
            $string = $url . "?" . $variable. "=" . $value;
        }

        return $string;
    }
}

?>

