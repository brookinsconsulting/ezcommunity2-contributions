<?php
// 
// $Id: eztechrenderer.php,v 1.11 2000/10/25 08:52:08 bf-cvs Exp $
//
// Definition of eZTechRenderer class
//
// B�rd Farstad <bf@ez.no>
// Created on: <18-Oct-2000 17:45:32 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZTechRenderer renders XML contents into html articles.
/*!
  This class wil decode the tech articles generated by eZTechGenerator.
  Supported tags:
  \code
  <page> - pagebreak
  <header>
  Header text
  </header>
  <link ez.no text to the link> - anchor
  <image 42 align size> - image tag, 42 is the id, alignment (left|center|right), size (small|medium|large)
  <cpp>
  cpp code
  </cpp>
  
  <php>
  php code
  </php>
  
  <shell>
  shell code
  </shell>
  
  <sql>
  sql code
  </sql>
  
  \endcode
  \sa eZTechGenerator  
*/

/*!TODO
  Add better syntax highlighting.

*/

//  $tmpPage = "<image 1 center big> <image 43 large large>";
//  $tmpPage = preg_replace( "/(<image\s+?([^ ]+)\s+?([^ ]+)\s+?([^( |>)]+)([^>]*?)>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" />", $tmpPage );

//  $tmpPage = "<link ez.no ez systems> <link ez.no ez systems>";
//  $tmpPage = preg_replace( "#(<link\s+?([^ ]+)\s+?([^>]+)>)#", "<link href=\"\\2\" text=\"\\3\" />", $tmpPage );

//  $tmpPage = preg_replace( "#(?<!(age|php|age|cpp|ell|sql|der))>#", "&gt;", $tmpPage );
//  $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );

//  print( htmlspecialchars( $tmpPage ) );

include_once( "classes/eztexttool.php" );
include_once( "classes/ezlog.php" );

class eZTechRenderer
{
    /*!
      Creates a new eZTechGenerator object.
    */
    function eZTechRenderer( &$article )
    {
        $this->Article = $article;
    }

    /*!
      Returns the XHTML contents of the introduction of the article.
    */
    function &renderIntro()
    {
        $xml = xmltree( $this->Article->contents() );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";
            
            $i=0;
            foreach ( $xml->root->children as $child )
            {
                if ( $child->name == "intro" )
                {
                    $intro = $child->children[0]->content;
                }
            }

            $newArticle = eZTextTool::nl2br( $intro );
        }
        
        return $newArticle;
    }

    /*!
      Returns the XHTML article of the article.
    */
    function &renderPage( $pageNumber=0 )
    {
        $xml = xmltree( $this->Article->contents() );

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

            $articleImages = $this->Article->images();
            $articleID = $this->Article->id();
            $pageArray = array();
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
                        $pageContent .= eZTextTool::nl2br( $paragraph->content );
                    }

                    // php code 
                    if ( $paragraph->name == "php" )
                    {
                        $pageContent .= $this->phpHighlight( $paragraph->children[0]->content );
                    }

                    
                    // header
                    if ( $paragraph->name == "header" )
                    {
                        $pageContent .= "<h3>".  $paragraph->children[0]->content . "</h3>";
                    }


                    // sql code 
                    if ( $paragraph->name == "sql" )
                    {
                        $pageContent .= $this->sqlHighlight( $paragraph->children[0]->content );
                    }

                    // c++ code 
                    if ( $paragraph->name == "cpp" )
                    {
                        $pageContent .= $this->cppHighlight( $paragraph->children[0]->content );
                    }

                    // shell code 
                    if ( $paragraph->name == "shell" )
                    {
                        $pageContent .= $this->shellHighlight( $paragraph->children[0]->content );
                    }

                    // link
                    if ( $paragraph->name == "link" )
                    {
                        foreach ( $paragraph->attributes as $imageItem )
                        {
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
                        
                        $pageContent .= "<a href=\"http://$href\">" . $text . "</a>";
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

                            
//                          $imageID = $paragraph->children[0]->content;
                        setType( $imageID, "integer" );
                        
                        $image = $articleImages[$imageID-1];
                        
                        // add image if a valid image was found, else report an error in the log.
                        if ( get_class( $image ) == "ezimage" )
                        {
                            $ini = new INIFile( "site.ini" );
                            
                            switch ( $imageSize )
                            {
                                case "small" :
                                {
                                    $variation =& $image->requestImageVariation( $ini->read_var( "eZArticleMain", "SmallImageWidth" ),
                                    $ini->read_var( "eZArticleMain", "SmallImageHeight" ) );
                                }
                                break;
                                case "medium" :
                                {
                                    $variation =& $image->requestImageVariation( $ini->read_var( "eZArticleMain", "MediumImageWidth" ),
                                    $ini->read_var( "eZArticleMain", "MediumImageHeight" ) );
                                }
                                break;
                                case "large" :
                                {
                                    $variation =& $image->requestImageVariation( $ini->read_var( "eZArticleMain", "LargeImageWidth" ),
                                    $ini->read_var( "eZArticleMain", "LargeImageHeight" ) );
                                }
                                break;
                            }
                            
                            $imageURL = "/" . $variation->imagePath();
                            $imageWidth = $variation->width();
                            $imageHeight = $variation->height();
                            $imageCaption = $image->caption();
                            
                            $imageTags = "<table width=\"$imageWidth\" align=\"$imageAlignment\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                            <tr>
                                            <td>
                                                        <img src=\"$imageURL\" border=\"0\" width=\"$imageWidth\" height=\"$imageHeight\" />
                                                        </td>
                                                </tr>
                                                <tr>
                                                         <td class=\"pictext\">
                                                         $imageCaption
                                                         </td>
                                                </tr>
                                             </table>";
                                $pageContent .=  $imageTags;
                        }
                        else
                        {
                            eZLog::writeError( "Image nr: $imageID not found in article: $articleID from IP: $REMOTE_ADDR" );        
                        }
                    }
                }

                
                $pageArray[] = $pageContent;
                
            }
            

            if ( $pageNumber != 0 )
            {
                $newArticle = $pageArray[$pageNumber];
            }
            else
            {
                $newArticle = eZTextTool::nl2br( $intro ) . "</p><p>". $pageArray[$pageNumber];
            }
                
        }
        
        return $newArticle;
    }


    /*!
      Returns the XHTML contents of the introduction of the article.
    */
    function &renderIntro()
    {
        $xml = xmltree( $this->Article->contents() );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";
            
            $i=0;
            foreach ( $xml->root->children as $child )
            {
                if ( $child->name == "intro" )
                {
                    $intro = $child->children[0]->content;
                }
            }

            $newArticle = eZTextTool::nl2br( $intro );
        }
        
        return $newArticle;
    }

    
    /*!
      Returns a php highlighted string.
    */
    function &phpHighlight( $string )
    {
        $string = ereg_replace ( "(<)", "&lt;", $string );        
        $string = ereg_replace ( "(>)", "&gt;", $string );        
        
        // some special characters
        $string = ereg_replace ( "([(){}+-]|=|\[|\])", "<font color=\"red\">\\1</font>", $string );

        // reserved words
//          $string = ereg_replace ( "(foreach|function|for|while|switch|as)", "<font color=\"blue\">\\1</font>", $string );

        // comments
        $string = ereg_replace ( "(//[^\n]+)", "<font color=\"orange\">\\1</font>", $string );
        $string = ereg_replace ( "(/\*[^\*]+\*/)", "<font color=\"orange\">\\1</font>", $string );

        $reservedWords = array( "/(function)/",
                                "/( as )/",
                                "/(class )/",
                                "/(var )/",
                                "/( for)/"
                                );

        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );


        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

        $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );

        // indenting
        $string = preg_replace( "/^( )+/m", "&nbsp;", $string );
        
        $string = "<p>" . $string . "</p>";
        
        return eZTextTool::nl2br( $string );
    }

    /*!
      Returns a sql highlighted string.
    */
    function &sqlHighlight( $string )
    {
        
        $string = preg_replace( "/(\([0-9]+\))/", "<font color=\"green\">\\1</font>", $string );
        $string = preg_replace( "/('[0-9]+')/", "<font color=\"red\">\\1</font>", $string );
        
        $reservedWords = array( "/(DROP )/i",
                                "/(CREATE )/i",
                                "/(TABLE )/i",
                                "/(DELETE )/i",
                                "/(DROP )/i",
                                "/(IF )/i",
                                "/(EXISTS )/i",
                                "/(DEFAULT )/i",
                                "/(AUTO_INCREMENT)/i",
                                "/(PRIMARY )/i",
                                "/(KEY )/i",
                                "/(NULL )/i",
                                "/(NULL,)/i",
                                "/(INT)/i",
                                "/(INT,)/i",
                                "/(CHAR )/i",
                                "/( TEXT,)/i",
                                "/( TEXT )/i",
                                "/(TIMESTAMP)/i",
                                "/(VARCHAR)/i",
                                "/( NOT )/i",
                                "/( AND )/i"
                                );

        
        
        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );

        // some special characters
        $string = ereg_replace ( "([;,])", "<font color=\"red\">\\1</font>", $string );
        
        $string = "<pre>" . $string . "</pre>";
        return $string;
    }

    /*!
      Returns a c++ highlighted string.
    */
    function &cppHighlight( $string )
    {        
        $string = ereg_replace ( "(<)", "&lt;", $string );
        $string = ereg_replace ( "(>)", "&gt;", $string );
        
        // some special characters
        $string = ereg_replace ( "([(){}+-]|=|\[|\])", "<font color=\"red\">\\1</font>", $string );

        // comments
        $string = ereg_replace ( "(//[^\n]+)", "<font color=\"orange\">\\1</font>", $string );
        $string = ereg_replace ( "(/\*[^\*]+\*/)", "<font color=\"orange\">\\1</font>", $string );

        // reserved words
        $reservedWords = array( "/(function)/",
                                "/( as )/",
                                "/(void )/",
                                "/(class )/",
                                "/(var )/",
                                "/( for)/"
                                );
        
        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );


        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

        
        $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );

        // newlines
        $string = ereg_replace ( "\n", "<br />\n newline", $string );

        $string = "<pre>" . $string . "</pre>";
        return $string;
    }

    /*!
      Returns a shell script highlighted string.
    */
    function &shellHighlight( $string )
    {
        $reservedWords = array( "/(IF )/i",
                                "/(FI )/i",
                                "/(FI\n)/i",
                                "/( THEN)/i"      
                                );
        
        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );


        // comment
        $string = ereg_replace ( "(\#[^\n]+)", "<font color=\"orange\">\\1</font>", $string );
        
        // some special characters
        $string = ereg_replace ( "([;,]|\]|\[)", "<font color=\"red\">\\1</font>", $string );

        
        $string = "<pre>" . $string . "</pre>";
        return $string;
    }
    
    var $Article;
}

?>

