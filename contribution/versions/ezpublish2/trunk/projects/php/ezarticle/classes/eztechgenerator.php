<?php
// 
// $Id: eztechgenerator.php,v 1.11 2000/10/24 19:03:13 bf-cvs Exp $
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

/*!TODO
  
*/

class eZTechGenerator
{
    /*!
      Creates a new eZTechGenerator object.
    */
    function eZTechGenerator( &$contents )
    {
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
        $newContents .= "<article><generator>tech</generator>\n";

        //add the contents
        $newContents .= "<intro>" . strip_tags( $this->Contents[0] ) . "</intro>";

        // get every page in an array
        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = $page;

            // parse the <image id align size> tag and convert it
            // to <image id="id" align="align" size="size"></image>

            $tmpPage = preg_replace( "/(<image\s+?([^ ]+)\s+?([^ ]+)\s+?([^( |>)]+)([^>]*?)>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" ></image>", $tmpPage );

            print( htmlspecialchars( $tmpPage ) . "<br>");
            
            // replace & with &amp; to prevent killing the xml parser..
            // is that a bug in the xmltree(); function ? answer to bf@ez.no
            $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );
            
            // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
            $tmpPage = preg_replace( "/<(?!(page|php|\/|image|cpp|shell|sql|hea))/", "&lt;", $tmpPage );

            // look-behind assertion is used here (?<!)
            // the expression must be fixed with eg just use the 3 last letters of the tag

            // make better..
//              $tmpPage = preg_replace( "/(?<!(age|php|age|cpp|ell|sql|der))>/", "&gt;", $tmpPage );

            // strip for tags, not much sense to have this here... will problably remove this later
//              $tmpPage = strip_tags( $tmpPage, "<page>,<php>,</php>,<image>,</image>,<cpp>,</cpp>,<shell>,</shell>,<sql>,</sql>,<header>,</header>" );

            $body .= "<page>" . $tmpPage  . "</page>";        
        }
        
        
        $this->PageCount = count( $pages );

        
        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    /*!
      Decodes the xml chunk and returns the original array to the article. 
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
                    $body = $child->children;
                }
            }

            $contentsArray[] = $intro;

            $bodyContents = "";
            $i=0;
            // loop on the pages
            foreach ( $body as $page )
            {
                $pageContent = "";
                // loop on the contents of the pages
                foreach ( $page->children as $paragraph )
                {
                    // ordinary text
                    if ( $paragraph->name == "text" )
                    {
                        $pageContent .= $paragraph->content;
                    }

                    // header
                    if ( $paragraph->name == "header" )
                    {
                        $pageContent .= "<header>" . $paragraph->children[0]->content . "</header>";
                    }


                    // php code 
                    if ( $paragraph->name == "php" )
                    {
                        $pageContent .= "<php>" . $paragraph->children[0]->content . "</php>";
                    }

                    // image 
                    if ( $paragraph->name == "image" )
                    {
                        $pageContent .= "<image>" . $paragraph->children[0]->content . "</image>";
                    }

                    // sql code
                    if ( $paragraph->name == "sql" )
                    {
                        $pageContent .= "<sql>" . $paragraph->children[0]->content . "</sql>";
                    }

                    // shell code
                    if ( $paragraph->name == "shell" )
                    {
                        $pageContent .= "<shell>" . $paragraph->children[0]->content . "</shell>";
                    }

                    // c++  code
                    if ( $paragraph->name == "cpp" )
                    {
                        $pageContent .= "<cpp>" . $paragraph->children[0]->content . "</cpp>";
                    }
                }

                if ( $i > 0 )
                    $bodyContents .=  "<page>" . $pageContent;
                else
                    $bodyContents .=  $pageContent;
                    
                $i++;
            }

            $contentsArray[] = $bodyContents;
        }

        return $contentsArray;
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
