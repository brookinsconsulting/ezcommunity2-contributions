<?php
// 
// $Id: eztechgenerator.php,v 1.1 2000/10/19 10:43:29 bf-cvs Exp $
//
// Definition of eZTechGenerator class
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
//! eZTechGenerator generates  XML contents for articles.
/*!
  This class will generate a tech XML article. This class is ment
  as an example of how to write your own special generator.

*/

class eZTechGenerator
{
    /*!
      Creates a new eZTechGenerator object.
    */
    function eZTechGenerator( &$contents )
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
        $newContents .= "<article><generator>tech</generator>\n";

        //add the contents
        $newContents .= "<intro>" . strip_tags( $this->Contents[0] ) . "</intro>";

        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = strip_tags( $page, "<page>,<php>,</php>" );
            
            // replace & with &amp; to prevent killing the xml parser..
            // is that a bug in the xmltree(); function ? answer to bf@ez.no
            $body .= "<page>" .  ereg_replace ( "&", "&amp;", $tmpPage ) . "</page>";        
        }
        
        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    var $Contents;
}

?>
