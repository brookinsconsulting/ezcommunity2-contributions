<?php
// 
// $Id: ezflowergenerator.php,v 1.3 2001/01/22 14:42:59 jb Exp $
//
// Definition of eZFlowerGenerator class
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Oct-2000 15:13:39 bf>
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
