<?php
// 
// $Id: ezqdomgenerator.php,v 1.1 2001/04/09 12:37:40 bf Exp $
//
// Definition of eZQDomGenerator class
//
// Bård Farstad <bf@ez.no>
// Created on: <24-Mar-2001 13:10:33 bf>
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
//! eZTechGenerator generates  XML contents for articles.
/*!
  This class will generate a tech XML article. This class is ment
  as an example of how to write your own special generator.

*/

/*!TODO
  
*/

class eZQDomGenerator
{
    /*!
      Creates a new eZQDomGenerator object.      
    */
    function eZQDomGenerator( &$contents )
    {
        $this->Level = 0;
        $this->PageCount = 0;
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
        $newContents .= "<article><generator>qdom</generator>\n";

        //add the contents
        $newContents .= "<intro>" . $this->generatePage( $this->Contents[0] ) . "</intro>";

        // get every page in an array
        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = $page;

            $tmpPage =& $this->generatePage( $tmpPage );

            $body .= "<page>" . $tmpPage  . "</page>";        
        }

        $this->PageCount = count( $pages );
        

        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    /*!
      \private
      
    */
    function &generatePage( $tmpPage )
    {
        // replace & with &amp; to prevent killing the xml parser..
        // is that a bug in the xmltree(); function ? answer to bf@ez.no
        $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );

        $tmpPage = $this->generateUnknowns( $tmpPage );        
        
//        $tmpPage = $this->generateImage( $tmpPage );

//        $tmpPage = $this->generateLink( $tmpPage );

//        $tmpPage = $this->generateModule( $tmpPage );
        
//        $tmpPage = $this->generateHTML( $tmpPage );

        return $tmpPage;
    }

    /*!
      \private
      
    */
    function &generateUnknowns( $tmpPage )
    {
        $tmpPage =& preg_replace( "#< #", "&lt; ", $tmpPage );
        $tmpPage =& preg_replace( "# >#", " &gt;", $tmpPage );
        
        // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
//        $tmpPage = preg_replace( "/<(?!(page|php|\/|image|cpp|shell|sql|hea|lin|iconlink|per|bol|ita|und|str|pre|ver|lis|ezhtml|html|java|ezanchor|mail|module|bullet))/", "&lt;", $tmpPage );

        // look-behind assertion is used here (?<!) 
        // the expression must be fixed width eg just use the 3 last letters of the tag

//        $tmpPage = preg_replace( "#(?<!(age|php|age|cpp|ell|sql|der|erl|old|lic|ine|ike|pre|tim|isp|tml|ava|let))>#", "&gt;", $tmpPage );
        // make better..
//        $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );

        return $tmpPage;
    }


    /*!
      Decodes the xml chunk and returns the original array to the article.

      If htmlSpecialChars == true the output is converted to HTML special chars like:
      &gt; and &lt;...
    */
    function &decodeXML( $htmlSpecialChars=true )
    {
        $contentsArray = array();

//        $xml =& xmltree( $this->Contents );

        $xml =& qdom_tree( $this->Contents );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";

            foreach ( $xml->children as $child )
            {
                if ( $child->name == "article" )
                {
                    foreach ( $child->children as $article )
                    {
                        if ( $article->name == "intro" )
                        {
                            $intro = $this->decodePage( $article );
                        }
                        
                        if ( $article->name == "body" )
                        {
                            $body = $article->children;
                        }
                        
                    }
                }
            }

            $contentsArray[] = $intro;

            $bodyContents = "";
            $i=0;
            // loop on the pages
            foreach ( $body as $page )
            {
                $pageContent = "";

                $pageContent = $this->decodePage( $page );

                if ( $i > 0 )
                    $bodyContents .=  "<page>" . $pageContent;
                else
                    $bodyContents .=  $pageContent;
                    
                $i++;
            }

            if ( $htmlSpecialChars == true )
                $contentsArray[] = htmlspecialchars( $bodyContents );
            else
                $contentsArray[] = $bodyContents;
        }

        return $contentsArray;
    }

    /*!
      \private

    */
    function decodePage( $page )
    {
        $value = "";
        if ( count( $page->children ) > 0 )
        {            
            foreach ( $page->children as $paragraph )
            {
                // can have sub items
                $value .= $this->decodeStandards( $paragraph );
            }
        }

        return $value;
    }
    

    function &decodeStandards(  $paragraph )
    {

        $pageContent = "";
        switch ( $paragraph->name )
        {
            case "bullet" :
            {
                $tmpContent = "";
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "#text" )
                    {
                        $content = $child->content;
                    }
                    else
                    {
                        $content = $this->decodeStandards( $child );
                    }

                    $tmpContent .=  $content;
                }
                
                $pageContent .= "<bullet>" . $tmpContent . "</bullet>";

            } break;

            case "bold" :
            {
                $tmpContent = "";
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "#text" )
                    {                
                        $tmpContent .= $child->content;
                    }
                    else
                    {
                        $tmpContent .= $this->decodeStandards( $child );
                    }
                }
                $pageContent .= "<bold>" . $tmpContent . "</bold>";
            }break;

            case "italic" :
            {
                $tmpContent = "";
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "#text" )
                    {                
                        $tmpContent .= $child->content;
                    }
                    else
                    {
                        $tmpContent .= $this->decodeStandards( $child );
                    }
                }
                $pageContent .= "<italic>" . $tmpContent . "</italic>";
            }break;

            case "underline" :
            {
                $tmpContent = "";
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "#text" )
                    {                
                        $tmpContent .= $child->content;
                    }
                    else
                    {
                        $tmpContent .= $this->decodeStandards( $child );
                    }
                }
                $pageContent .= "<underline>" . $tmpContent . "</underline>";
            }break;

        }
        return $pageContent;
    }

    /*!
      Returns the number of pages found in the article.
    */
    function pageCount( )
    {
        return $this->PageCount;
    }    

    var $Contents;
    var $PageCount;
}

?>
