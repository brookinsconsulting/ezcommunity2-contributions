<?php
// 
// $Id: eztechgenerator.php,v 1.25 2000/11/02 18:12:09 bf-cvs Exp $
//
// Definition of eZTechGenerator class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:55:16 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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
        // What does strip_tags do? needed anymore?
//          $newContents .= "<intro>" . strip_tags( $this->Contents[0], "<bold>,<italic>,<strike>,<underline>" ) . "</intro>";
        $newContents .= "<intro>" . $this->generatePage( $this->Contents[0] ) . "</intro>";

        // get every page in an array
        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = $page;

            $tmpPage = $this->generatePage( $tmpPage );

            $body .= "<page>" . $tmpPage  . "</page>";        
        }

        $this->PageCount = count( $pages );

        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    function &generatePage( $tmpPage )
    {
        $tmpPage = $this->generateImage( $tmpPage );

        $tmpPage = $this->generateLink( $tmpPage );

        // replace & with &amp; to prevent killing the xml parser..
        // is that a bug in the xmltree(); function ? answer to bf@ez.no
        $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );

        $tmpPage = $this->generateHTML( $tmpPage );

        $tmpPage = $this->generateUnknowns( $tmpPage );

        return $tmpPage;
    }

    function &generateUnknowns( $tmpPage )
    {
        // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
        $tmpPage = preg_replace( "/<(?!(page|php|\/|image|cpp|shell|sql|hea|lin|per|bol|ita|und|str|pre|ver|lis|ezhtml|java|ezanchor|mail))/", "&lt;", $tmpPage );

        // look-behind assertion is used here (?<!) 
        // the expression must be fixed width eg just use the 3 last letters of the tag

        $tmpPage = preg_replace( "#(?<!(age|php|age|cpp|ell|sql|der|erl|old|lic|ine|ike|pre|tim|isp|tml|ava))>#", "&gt;", $tmpPage );
        // make better..
        $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );

        return $tmpPage;
    }

    function &generateHTML( $tmpPage )
    {
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
            $resultPage = "";
            $isInsideHTML = false;
            $isInsidePHP = false;
            for ( $i=0; $i<strlen( $tmpPage ); $i++ )
            {    
                if ( substr( $tmpPage, $i - strlen( $startHTMLTag ), strlen( $startHTMLTag ) ) == $startHTMLTag )
                {
                    $isInsideHTMLTag = true;
                }

                if ( substr( $tmpPage, $i, strlen( $endHTMLTag ) ) == $endHTMLTag )
                {
                    $isInsideHTMLTag = false;
                }

                if ( substr( $tmpPage, $i - strlen( $startPHPTag ), strlen( $startPHPTag ) ) == $startPHPTag )
                {
                    $isInsidePHPTag = true;
                }

                if ( substr( $tmpPage, $i, strlen( $endPHPTag ) ) == $endPHPTag )
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

            $tmpPage = $resultPage;
        }
        return $tmpPage;
    }

    /*!
      \private
      Converts the link tags to valid XML tags.
    */
    function &generateLink( $tmpPage )
    {
        // convert <link ez.no ez systems> to valid xml
        // $tmpPage = "<link ez.no ez systems> <link ez.no ez systems>";
        $tmpPage = preg_replace( "#(<link\s+?([^ ]+)\s+?([^>]+)>)#", "<link href=\"\\2\" text=\"\\3\" />", $tmpPage );

        // convert <ezanchor anchor> to <ezanchor href="anchor" />
        $tmpPage = preg_replace( "#<ezanchor\s+?(.*?)>#", "<ezanchor href=\"\\1\" />", $tmpPage );
        
        // convert <mail adresse@domain.tld subject line, link text>
        // to valid xml
        $tmpPage = preg_replace( "#<mail\s+?([^ ]*?)\s+?(.*?),\s+?([^>]*?)>#", "<mail to=\"\\1\" subject=\"\\2\" text=\"\\3\" />", $tmpPage );

        return $tmpPage;
    }

    function &generateImage( $tmpPage )
    {
        // parse the <image id align size> tag and convert it
        // to <image id="id" align="align" size="size" />
        $tmpPage = preg_replace( "/(<image\s+?([^ ]+)\s+?([^ ]+)\s+?([^( |>)]+)([^>]*?)>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" />", $tmpPage );
        return $tmpPage;
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
                    if ( count( $child->children ) > 0 )
                    foreach ( $child->children as $paragraph )
                    {                        
                        // ordinary text
                        if ( $paragraph->name == "text" )
                        {
                            $intro .= $paragraph->content;
                        }
                        
                        $intro = $this->decodeStandards( $intro, $paragraph );

                        $intro = $this->decodeCode( $intro, $paragraph );

                        $intro = $this->decodeImage( $intro, $paragraph );

                        $intro = $this->decodeLink( $intro, $paragraph );

                    }
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
                if ( count( $page->children ) > 0 )
                foreach ( $page->children as $paragraph )
                {
                    // ordinary text
                    if ( $paragraph->name == "text" )
                    {
                        $pageContent .= $paragraph->content;
                    }

                    $pageContent = $this->decodeStandards( $pageContent, $paragraph );

                    $pageContent = $this->decodeCode( $pageContent, $paragraph );

                    $pageContent = $this->decodeImage( $pageContent, $paragraph );

                    $pageContent = $this->decodeLink( $pageContent, $paragraph );
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

    function &decodeCode( $pageContent, $paragraph )
    {
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

        
        return $pageContent;
    }

    function &decodeImage( $pageContent, $paragraph )
    {
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
        return $pageContent;
    }

    function &decodeLink( $pageContent, $paragraph )
    {
        // link
        if ( $paragraph->name == "link" )
        {
            foreach ( $paragraph->attributes as $imageItem )
                {
//                      print( $imageItem->name );
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

        // mail
        if ( $paragraph->name == "mail" )
        {
            foreach ( $paragraph->attributes as $mailItem )
                {
                    switch ( $mailItem->name )
                    {
                        case "to" :
                        {
                            $to = $mailItem->children[0]->content;
                        }
                        break;

                        case "subject" :
                        {
                            $subject = $mailItem->children[0]->content;
                        }
                        break;

                        case "text" :
                        {
                            $text = $mailItem->children[0]->content;
                        }
                        break;
                    }
                }
                        
            $pageContent .= "<mail $to $subject, $text>";
        }

        // ez anchor
        if ( $paragraph->name == "ezanchor" )
        {
            foreach ( $paragraph->attributes as $anchorItem )
                {
                    switch ( $anchorItem->name )
                    {
                        case "href" :
                        {
                            $href = $anchorItem->children[0]->content;
                        }
                        break;
                    }
                }
                        
            $pageContent .= "<ezanchor $href>";
        }
        
        return $pageContent;
    }

    function &decodeStandards( $pageContent, $paragraph )
    {
        // header
        if ( $paragraph->name == "header" )
        {
            $pageContent .= "<header>" . $paragraph->children[0]->content . "</header>";
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
