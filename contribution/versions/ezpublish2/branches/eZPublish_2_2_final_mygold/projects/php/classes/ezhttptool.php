<?php
// 
// $Id: ezhttptool.php,v 1.14.4.1 2001/11/14 11:35:33 ce Exp $
//
// Definition of eZTextTool class
//
// Created on: <23-Jan-2001 12:34:54 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
  \code
  // will return the HTTP post variable $CategoryID if set, false if not.
  eZHTTPTool::getVar( "CategoryID", true );
  \endcode
*/

class eZHTTPTool
{
    /*!
      Initialization of object;
      */
    function eZHTTPTool()
    {
        global $REQUEST_URI;
 
        $this->url_array =& explode( "/", $REQUEST_URI );
        $this->url_array_length = count( $this->url_array );
    }

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
        global $GlobalSiteIni;        

        $sid =& $GLOBALS["PHPSESSID"];

        $cookie_vars = $GLOBALS["HTTP_COOKIE_VARS"];
        
        // fix location if session is not by cookie
        if ( !isset( $cookie_vars["PHPSESSID"] ) && isset( $sid ) )
        {
            $string = eZHTTPTool::removeVariable( $string, "PHPSESSID" );
            $string = eZHTTPTool::addVariable( $string, "PHPSESSID", $sid );
        }
        
        // Redirect differently, when we are not using virtual hosts/mod_rewrite
        if ( ereg( "^Location:[ ]*(/.*)", $string, $regs ) )
        {
            $string = "Location: " . $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $regs[1];
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
    
    /*!
      This function will assign dates and times to the variables
      named $prefix . unit $postfix in the global scope, where
      unit is [Year|Month|Day|Hour|Minute|Second]. It will try
      to fill in as many as possible.
      
     */
    function assignDate( $start, $prefix = "", $postfix = "" )
    {
        $url_array_length = $this->url_array_length;
        $url_array =& $this->url_array;

        $variableName = $prefix . "Year" . $postfix;
        if( is_numeric( $url_array[ $start ] ) )
        {
            global $$variableName;
            $$variableName = $url_array[ $start ];
            $start++;
            $year = true;
        }

        $variableName = $prefix . "Month" . $postfix;
        if( is_numeric( $url_array[ $start ] ) && $year )
        {
            global $$variableName;
            $$variableName = $url_array[ $start ];
            $start++;
            $month = true;
        }

        $variableName = $prefix . "Day" . $postfix;
        if( is_numeric( $url_array[ $start ] ) && $month )
        {
            global $$variableName;
            $$variableName = $url_array[ $start ];
            $start++;
            $day = true;
        }

        $variableName = $prefix . "Hour" . $postfix;
        if( is_numeric( $url_array[ $start ] ) && $day && $url_array[ $start ] >= 0 && $url_array[ $start ] <= 23  )
        {
            global $$variableName;
            $$variableName = $url_array[ $start ];
            $start++;
            $hour = true;
        }

        $variableName = $prefix . "Minute" . $postfix;
        if( is_numeric( $url_array[ $start ] ) && $hour && $url_array[ $start ] >= 0 && $url_array[ $start ] <= 59  )
        {
            global $$variableName;
            $$variableName = $url_array[ $start ];
            $start++;
            $minute = true;
        }

        $variableName = $prefix . "Second" . $postfix;
        if( is_numeric( $url_array[ $start ] ) && $minute && $url_array[ $start ] >= 0 && $url_array[ $start ] <= 59 )
        {
            global $$variableName;
            $$variableName = $url_array[ $start ];
            $start++;
        }

        $end = $start;

        return $end;
    }


    /*!
      This function will assign values to variables and name them
      named $prefix . unit $postfix in the global scope. It will
      only assign values to variables with names. It will also
      try to assign dates/times to any variables where the names
      in the url is called [start|begin|from|until|stop|end]

      This function will parse the following url values, regardless
      of where they are in the url:
      
      [start|begin|from|stop|end|until]/$year[/$month[/$day[/$hour[/$minute[/$second]]]]]
      
      Any url part on the form:
      
      string/value
      
      will be parsed into a global variable called $prefix[string]$postfix
     */
    function assignValues( $position, $prefix = "", $postfix = "" )
    {
        $url_array_length = $this->url_array_length;
        $url_array =& $this->url_array;

        $i = $position;
        $j = $i + 1;

        for( $i; $j < $url_array_length; $i++, $j++ )
        {
            $arg = $url_array[$i];
            $var = $url_array[$j];

            if( is_string( $arg ) )
            {
                if( is_numeric( $var ) )
                {
                    switch( $arg )
                    {
                        case "start":
                        case "begin":
                        case "from":
                        {
                            $i = $this->assignDate( $j, $prefix . ucfirst( $arg ) );
                        }
                        break;

                        case "end":
                        case "stop":
                        case "until":
                        {
                            $i = $this->assignDate( $j, $prefix . ucfirst( $arg ) );
                        }
                        break;

                        default:
                        {
                            $variableName = $prefix . ucfirst( $arg ) . $postfix;
                            global $$variableName;
                            $$variableName = $var;
                        }
                        break;
                    }
                }
                else
                {
                    $variableName = $prefix . ucfirst( $arg ) . $postfix;
                    global $$variableName;
                    $$variableName = $var;
                }
            }
        }
    }

    /*!
      \static
      
      Will set a cookie variable.
     */
    function setCookie( $variable, $value, $timeout=365 )
    {        
        $exp= time() + ( $timeout * 86400 ); 
        $exp=strftime("%a, %d-%b-%Y %H:%M:%S", $exp);
        $exp="$exp GMT";
        $host = $GLOBALS["HTTP_HOST"];
        header("Set-Cookie: $variable=$value;expires=$exp;path=/;domain=.$host"); 
    }
    
    /*!
      \static
      Initalizes the global object, and static variables.
     */
    function globaleZHTTPTool()
    {
        global $eZHTTPToolObject;
        
        if ( get_class( $eZHTTPToolObject ) != "ezhttptool" )
        {
            $eZHTTPToolObject = new eZHTTPTool();
        }
        return $eZHTTPToolObject;
    }
    
    /*!
      The global url exploded into an array.
    */
    var $url_array = array();

    /*!
      The number of elements in the global url array.
    */
    var $url_array_length = 0;
}

?>

