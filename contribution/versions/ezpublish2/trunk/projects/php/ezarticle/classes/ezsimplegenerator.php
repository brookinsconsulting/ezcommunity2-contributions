<?php
// 
// $Id: ezsimplegenerator.php,v 1.2 2000/10/23 14:33:19 bf-cvs Exp $
//
// Definition of eZSimpleGenerator class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:55:16 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZSimpleGenerator generates  XML contents for articles.
/*!
  This class will generate a simple XML article. This class is ment
  as an example of how to write your own special generator.

*/

class eZSimpleGenerator
{
    /*!
      Creates a new eZSimpleGenerator object.
    */
    function eZSimpleGenerator( &$contents )
    {
        $this->Contents = $contents;
    }

    /*!
      Generates valid XML data to use for storage.
    */
    function &generateXML()
    {
        // add the XML header.
        
        $newContents = "<?xml version=\"1.0\"?>";
        
        //add the generator, this is used for rendering.
        $newContents .= "<article><generator>simple</generator>\n";

        //add the contents
        $newContents .= "<intro>" . strip_tags( $this->Contents[0] ). "</intro>";

        $newContents .= "<body>" . strip_tags( $this->Contents[1] ) . "</body></article>";

        return $newContents;
    }

    /*!
      Returns the page count.
    */
    function pageCount()
    {
        return 1;
    }

    var $Contents;
}

?>
