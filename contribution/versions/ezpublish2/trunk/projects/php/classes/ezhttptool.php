<?php
// 
// $Id: ezhttptool.php,v 1.7 2001/02/23 14:49:54 bf Exp $
//
// Definition of eZTextTool class
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Jan-2001 12:34:54 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
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
    function &getVar( $name, $onlyCheckPost=false )
    {
        $ret = false;

        $postVars = $GLOBALS["HTTP_POST_VARS"];
        
        if ( $onlyCheckPost == false )
        {
            $getVars = $GLOBALS["HTTP_GET_VARS"];
        }

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

