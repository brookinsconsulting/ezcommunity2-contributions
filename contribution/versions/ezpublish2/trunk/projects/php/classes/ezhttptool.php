<?php
// 
// $Id: ezhttptool.php,v 1.5 2001/02/08 10:38:27 ce Exp $
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
      fetches a variable from Post or Get operations.

      Returns false if the variable is not set.
     */
    function &getVar( $name )
    {
        $ret = false;

        $postVars = $GLOBALS["HTTP_POST_VARS"];
        $getVars = $GLOBALS["HTTP_GET_VARS"];

        if ( isset( $postVars[$name] ) )
        {
            $ret = $postVars[$name];
        }
        else if ( isset( $getVars[$name] ) )
        {
            $ret = $getVars[$name];
        }

        
        return $ret;
    }
    
    
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
            $string = eZHTTPTool::removeVariable( $string, "PHPSESSID" );
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

    /*!
      \static
      Returns a url with the variable removed from the url.
    */
    function &removeVariable( $url, $variable, $value = "" )
    {
        $pos = strpos( $url, "?" );

        if ( $pos )
        {
            $start = substr( $url, 0, $pos );
            $end = substr( $url, $pos );
            if ( $value )
                $end = preg_replace( "/&?$variable"."=".$value."/", "", $end );
            else
                $end = preg_replace( "/&?$variable"."=[^&]+/", "", $end );
            if ( $end == "?" )
                $end = "";
            $url = $start . $end;
        }

        return $url;
    }
}

?>

