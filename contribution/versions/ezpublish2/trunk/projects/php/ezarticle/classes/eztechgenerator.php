<?php
// 
// $Id: eztechgenerator.php,v 1.17 2000/10/29 19:21:19 bf-cvs Exp $
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
            // to <image id="id" align="align" size="size" />
            $tmpPage = preg_replace( "/(<image\s+?([^ ]+)\s+?([^ ]+)\s+?([^( |>)]+)([^>]*?)>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" />", $tmpPage );

            // convert <link ez.no ez systems> to valid xml
            // $tmpPage = "<link ez.no ez systems> <link ez.no ez systems>";
            $tmpPage = preg_replace( "#(<link\s+?([^ ]+)\s+?([^>]+)>)#", "<link href=\"\\2\" text=\"\\3\" />", $tmpPage );
            
            // replace & with &amp; to prevent killing the xml parser..
            // is that a bug in the xmltree(); function ? answer to bf@ez.no
            $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );

 
            // Begin html tag replacer
            // replace all < and >  between <ezhtml> and </ezhtml>
            // and to the same for <php> </php>
            // ok this is a bit slow code, but it works
            $startHTMLTag = "<ezhtml>";
            $endHTMLTag = "</ezhtml>";

            $startPHPTag = "<php>";
            $endPHPTag = "</php>";
            
            $numberBeginHTML = substr_count( $tmpPage, $startHTMLTag );
            $numEndHTML = substr_count( $tmpPage, $endHTMLTag );

            if ( $numberBegin != $numEnd )
            {
                print( "Unmatched ezhtml tags, check that you have end tags for all begin tags" );
            }
            
            $numberBeginPHP = substr_count( $tmpPage, $startPHPTag );
            $numEndPHP = substr_count( $tmpPage, $endPHPTag );
            
            if ( $numberBegin != $numEnd )
            {
                print( "Unmatched PHP tags, check that you have end tags for all begin tags" );
            }

            if ( ( $numberBeginPHP > 0 ) || ( $numberBeginHTML > 0 ) )
            {
                $checkString = $tmpPage;

                $resultPage = "";
                $isInsideHTML = false;
                $isInsidePHP = false;
                for ( $i=0; $i<strlen( $checkString ); $i++ )
                {    
                    if ( substr( $checkString, $i - strlen( $startHTMLTag ), strlen( $startHTMLTag ) ) == $startHTMLTag )
                    {
                        $isInsideHTMLTag = true;
                    }

                    if ( substr( $checkString, $i, strlen( $endHTMLTag ) ) == $endHTMLTag )
                    {
                        $isInsideHTMLTag = false;
                    }

                    if ( substr( $checkString, $i - strlen( $startPHPTag ), strlen( $startPHPTag ) ) == $startPHPTag )
                    {
                        $isInsidePHPTag = true;
                    }

                    if ( substr( $checkString, $i, strlen( $endPHPTag ) ) == $endPHPTag )
                    {
                        $isInsidePHPTag = false;
                    }
                
                    if ( ( $isInsideHTMLTag == true ) ||  ( $isInsidePHPTag == true ) )
                    {
                        switch ( $tmpPage[$i] )
                        {
                            case "<" :
                            {
                                $resultPage .= "&lt;";
                            }
                            break;

                            case ">" :
                            {
                                $resultPage .= "&gt;";
                            }
                            break;
            
                            default:
                            {
                                $resultPage .= $tmpPage[$i];
                            }
                        }
                    }
                    else
                    {
                        $resultPage .= $tmpPage[$i];
                    }
                }

                $tmpPage =& $resultPage;
            }
            
                 // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
            $tmpPage = preg_replace( "/<(?!(page|php|\/|image|cpp|shell|sql|hea|lin|per|bol|ita|und|str|pre|ver|lis|ezhtml|java))/", "&lt;", $tmpPage );

            // look-behind assertion is used here (?<!) 
            // the expression must be fixed with eg just use the 3 last letters of the tag

            $tmpPage = preg_replace( "#(?<!(age|php|age|cpp|ell|sql|der|erl|old|lic|ine|ike|pre|tim|isp|tml|ava))>#", "&gt;", $tmpPage );
            // make better..
            $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );
            

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

                    // html code 
                    if ( $paragraph->name == "ezhtml" )
                    {
                        $pageContent .= "<ezhtml>" . $paragraph->children[0]->content . "</ezhtml>";
                    }

                    // java code 
                    if ( $paragraph->name == "java" )
                    {
                        $pageContent .= "<java>" . $paragraph->children[0]->content . "</java>";
                    }
                    
                    // image 
                    if ( $paragraph->name == "image" )
                    {
                        foreach ( $paragraph->attributes as $imageItem )
                        {
                            switch ( $imageItem->name )
                            {

                                case "id" :
                                {
                                    $imageID = $imageItem->children[0]->content;
                                }
                                break;

                                case "align" :
                                {
                                    $imageAlignment = $imageItem->children[0]->content;
                                }
                                break;

                                case "size" :
                                {
                                    $imageSize = $imageItem->children[0]->content;
                                }
                                break;
                                
                            }
                        }
                        
                        $pageContent .= "<image $imageID $imageAlignment $imageSize>";
                    }

                    // image 
                    if ( $paragraph->name == "link" )
                    {
                        foreach ( $paragraph->attributes as $imageItem )
                        {
                            print( $imageItem->name );
                            switch ( $imageItem->name )
                            {

                                case "href" :
                                {
                                    $href = $imageItem->children[0]->content;
                                }
                                break;

                                case "text" :
                                {
                                    $text = $imageItem->children[0]->content;
                                }
                                break;
                                
                            }
                        }
                        
                        $pageContent .= "<link $href $text>";
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

                    // perl  code
                    if ( $paragraph->name == "perl" )
                    {
                        $pageContent .= "<perl>" . $paragraph->children[0]->content . "</perl>";
                    }

                    // lisp  code
                    if ( $paragraph->name == "lisp" )
                    {
                        $pageContent .= "<lisp>" . $paragraph->children[0]->content . "</lisp>";
                    }
                    
                    // bold text
                    if ( $paragraph->name == "bold" )
                    {
                        $pageContent .= "<bold>" . $paragraph->children[0]->content . "</bold>";
                    }

                    // italic text
                    if ( $paragraph->name == "italic" )
                    {
                        $pageContent .= "<italic>" . $paragraph->children[0]->content . "</italic>";
                    }

                    // underline text
                    if ( $paragraph->name == "underline" )
                    {
                        $pageContent .= "<underline>" . $paragraph->children[0]->content . "</underline>";
                    }

                    // strike text
                    if ( $paragraph->name == "strike" )
                    {
                        $pageContent .= "<strike>" . $paragraph->children[0]->content . "</strike>";
                    }

                    // pre defined text
                    if ( $paragraph->name == "pre" )
                    {
                        $pageContent .= "<pre>" . $paragraph->children[0]->content . "</pre>";
                    }

                    // verbatim text
                    if ( $paragraph->name == "verbatim" )
                    {
                        $pageContent .= "<verbatim>" . $paragraph->children[0]->content . "</verbatim>";
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
