<?php
// 
// $Id: ezhttptool.php,v 1.1 2001/01/23 11:42:30 bf Exp $
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
            $pos = strpos( $string, "?" );

            if ( $pos )
            {
                $string = $string . "&PHPSESSID=$sid";
            }
            else
            {
                $string = $string . "?PHPSESSID=$sid";    
            }
        }
        header( $string );    
    }
}

?>

