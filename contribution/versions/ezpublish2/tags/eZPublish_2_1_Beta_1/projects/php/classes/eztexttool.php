<?php
// 
// $Id: eztexttool.php,v 1.15 2001/02/26 14:12:58 pkej Exp $
//
// Definition of eZTextTool class
//
// Bård Farstad <bf@ez.no>
// Created on: <16-Oct-2000 11:06:56 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZCommon
//! The eZTextTool class provies text utility functions
/*!
  This class consists of static functions for formatting of text.
  Theese functions is made as an extention to PHP ie functions you would
  use all the time, but isn't a part of php.
  
  Example of usage:
  \code
  // create a string with newlines
  $text = "This is
  a
  text
  to break
  up";

  // convert the newlines to xhtml breaks
  $text = eZTextTool::nl2br( $text );

  //print out the result
  print( $text );
  \endcode
  
*/

class eZTextTool
{
    /*!
      \static
      This function converts all newlines \n into breaks.

      The breaks are inserted before every newline.

      The default is to use xhtml breaks, html breaks is used if the
      $xhtml variable is set to false.
    */
    function &nl2br( $string, $xhtml=true )
    {
        if ( $xhtml == true )            
            return ereg_replace( "\n", "<br />\n", $string );
        else
            return ereg_replace( "\n", "<br>\n", $string );
    }    

    /*!
      \static
      This function will add a > at the beginning of each line.
    */
    function &addPre( $string, $char=">" )
    {
        $string =& wordwrap( $string, 60, "\n" );
        return preg_replace( "#^#m", "$char ", $string );
    }

    /*!
      \static
      This function will convert text into capitilzed text.

      Numbers will not be capitalized. E.g.

      "a text string" will be converted to "A  T E X T  S T R I N G"
      Where the first letter in each word will get the css style;
      span with class="$bigClass" given as argument.
      
    */
    function &capitalize( $string, $bigClass="h1bigger" )
    {
        $string = strtoupper( $string );
        
        for ( $i=0; $i<strlen( $string ); $i++)
        {
            $string2 .= $string[$i] . " ";
        }
        
        $string = trim( $string2 );
        
        $string = str_replace ("æ", "Æ", $string );        
        $string = str_replace ("ø", "Ø", $string );
        $string = str_replace ("å", "Å", $string );

        $string = preg_replace( "#(  |^)([a-zA-ZæøåÆØÅ] )#", "\\1<span class=\"$bigClass\">\\2</span>", $string );
        
//        $string = preg_replace( "#(  |^)([^ ])#", "\\1<span class=\"$bigClass\">\\2</span>", $string );
        
        $string = str_replace ( "  ", "&nbsp;&nbsp;", $string );
        
        return  $string;
    }
    
    /*!
      \static
      This function will return the text "true" if the input value is true and
      the text "false" if false.
     */

    function &boolText( $value )
    {
        if( $value == true )
        {
            $string = "true";
        }
        
        if( $value == false )
        {
            $string = "false";
        }
        
        return $string;
    }

    /*!
      Performs a normal htmlspecialchars with a striplashes afterwards,
      this is needed to avoid " and \ being slashed on web pages.
    */
    function &htmlspecialchars( $string )
    {
        return stripslashes( htmlspecialchars( $string ) );
    }
    
    /*!
      This function will split a string into lines of no more than a
      given number of characters, but wont split a word. Useful in
      e-mails. Each new line will be ended with a "\n".
      
      You can also add a padding length, if you wish.
     */
    function &lineSplit( $in, $len = 0, $size = 72 )
    {
        $tmp = "";
        $pad = str_pad( $tmp, $len, " ", STR_PAD_LEFT );
        $temptext = ""; 
        $temparray = explode( " ", $in ); 
        $i = 0; 
        while( $i <= count( $temparray ) )
        { 
            while( ( strlen( $pad . $temptext . " " . $temparray[$i] ) < $size )
                && ($i <= count( $temparray ) ) )
            { 
                $temptext = $temptext." ".$temparray[$i]; 
                $i++;
            } 
            $out = $out."\n" . $pad . $temptext; 
            $temptext = $temparray[$i]; 
            $i++; 
        } 
        return $out;
    }
}

?>

