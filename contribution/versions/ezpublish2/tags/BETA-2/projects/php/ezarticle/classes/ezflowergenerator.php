<?php
// 
// $Id: ezflowergenerator.php,v 1.1 2000/10/23 14:33:19 bf-cvs Exp $
//
// Definition of eZFlowerGenerator class
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Oct-2000 15:13:39 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZFlowerGenerator generates XML contents for articles.
/*!
  This class will generate a flower XML article. This class is ment
  as an implementation of the generation of www.heistad-hagesenter.no.
*/

class eZFlowerGenerator
{
    /*!
      Creates a new eZFlowerGenerator object.
    */
    function eZFlowerGenerator( &$contents )
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
        $newContents .= "<article><generator>flower</generator>\n";

        //add the contents
        $newContents .= "<intro>" . strip_tags( $this->Contents[0] ). "</intro>";

        $newContents .= "<body>" . strip_tags( $this->Contents[1] ) . "</body></article>";

        return $newContents;
    }

    /*!
      Generates valid XML data to use for storage.
    */
    function &decodeXML()
    {
        $contentsArray = array();
        
        $xml = xmltree( $this->Contents );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";
            
            foreach ( $xml->root->children as $child )
            {
                if ( $child->name == "intro" )
                {
                    $intro = $child->children[0]->content;
                }
                

                if ( $child->name == "body" )
                {
                    $body = $child->children[0]->content;
                }
            }

            $contentsArray[] = $intro;
            $contentsArray[] = $body;

        }

        return $contentsArray;
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
