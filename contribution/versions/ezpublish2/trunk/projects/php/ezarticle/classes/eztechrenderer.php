<?php
// 
// $Id: eztechrenderer.php,v 1.5 2000/10/20 13:31:24 bf-cvs Exp $
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

  \sa eZTechGenerator  
*/

/*!TODO
  Add better syntax highlighting.

*/
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

                    // image
                    if ( $paragraph->name == "image" )
                    {
                        $imageID = $paragraph->children[0]->content;
                        setType( $imageID, "integer" );
                        
                        $image = $articleImages[$imageID-1];

                        // add image if a valid image was found, else report an error in the log.
                        if ( get_class( $image ) == "ezimage" )
                        {
                            $variation =& $image->requestImageVariation( 250, 250 );

                            $imageURL = "/" . $variation->imagePath();
                            $imageWidth = $variation->width();
                            $imageHeight = $variation->height();
                            $imageCaption = $image->caption();
                                 
                            $imageTags = "<table align=\"right\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                            <tr>
                                            <td>
                                                        <img src=\"$imageURL\" border=\"0\" width=\"$imageWidth\" height=\"$imageHeight\" />
                                                        </td>
                                                </tr>
                                                <tr>
                                                         <td>
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
            

            $newArticle = eZTextTool::nl2br( $intro ) . "</p><p>". $pageArray[$pageNumber];
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
             
        //  	    $source =~ s/( [0-9]+)/\<span class=\"number\"\>$1\<\/span\>/g;
        
//    	    $source =~ s/\</\&lt;/g;
//    	    $source =~ s/\>/\&gt;/g;


//  	    $source =~ s/(\"[^\"]{0,}\")/\<span class=\"string\"\>$1\<\/span\>/g;
//  	    $source =~ s/( [0-9]+)/\<span class=\"number\"\>$1\<\/span\>/g;


//  	    $source =~ s/([(){}+-])/\<span class\=\"specialChar\"\>$1\<\/span\>/g;
//  	    $source =~ s/function/\<span class\=\"reservedWord\"\>function\<\/span\>/g;
//  	    $source =~ s/class /\<span class\=\"reservedWord\"\>class \<\/span\>/g;
//  	    $source =~ s/var /\<span class\=\"reservedWord\"\>var \<\/span\>/g;
//  	    $source =~ s/(\$[a-zA-Z0-9]+)/\<span class\=\"variable\"\>$1\<\/span\>/g;
//  	    $source =~ s/(\/\/.*\n)/\<span class\=\"comment\"\>$1\<\/span\>/g;
//  	    $source =~ s/(\/\*[^*]*\*\/)/\<span class\=\"comment\"\>$1\<\/span\>/g;
        

        
        $string = "<pre>" . $string . "</pre>";
        return $string;
    }
    
    var $Article;
}

?>

