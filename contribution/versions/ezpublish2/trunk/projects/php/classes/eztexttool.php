<?php
// 
// $Id: eztexttool.php,v 1.1 2000/10/16 09:16:34 bf-cvs Exp $
//
// Definition of eZTextTool class
//
// Bård Farstad <bf@ez.no>
// Created on: <16-Oct-2000 11:06:56 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
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
  
*/

class eZTextTool
{
    /*!
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

}

?>

