<?php
// 
// $Id: eztechrenderer.php,v 1.70 2001/07/19 12:19:21 jakobn Exp $
//
// Definition of eZTechRenderer class
//
// Created on: <18-Oct-2000 17:45:32 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
  <iconlink ez.no text to the link> - anchor
  <mail adresse@domain.tld subject line, link text> - anchor to email address with subject
  <image 42 align size> - image tag, 42 is the id, alignment (left|center|right|float), size (small|medium|large|original)

  <ezanchor anchorname>

  <module modulename> - this will include a php file named "modulename.php" if it is found in the
  ezrticle/modules dir.

  <bullet>
  
  </bullet>

  <html>
  html code, this will print out the HTML code..
  </html>
  
  <cpp>
  cpp code
  </cpp>
  
  <php>
  php code
  </php>

  <java>
  java code
  </java>

  <ezhtml>
  html code
  </ezhtml>
  
  <shell>
  shell code
  </shell>
  
  <sql>
  sql code
  </sql>

  <perl>
  perl code
  </perl>

  <lisp>
  lisp code
  </lisp>
  
  <bold>
  bold text

  </bold>

  <italic>
  italic text
  </italic>

  <underline>
  underlined text
  </underline>

  <strike>
  strike text
  </strike>

  <pre>
  predefined text
  </pre>

  <verbatim>
  predefined text
  </verbatim>
  
  \endcode
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
//          print( "<pre>" );
        
        $xml = xmltree( $this->Article->contents() );

//          print_r( $xml );
        
//          $xml =& qdom_tree( $this->Article->contents() );

//          print_r( $xml );

//          print( "</pre>" );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $intro = "";
            $body = "";

            $articleImages = $this->Article->images();
            $articleID = $this->Article->id();

            $i=0;
            $this->$PrevTag = "";
            foreach ( $xml->children as $child )
            {
                if ( $child->name == "article" )
                {
                    foreach ( $child->children as $article )
                    {
                        if ( $article->name == "intro" )
                        {                           
                            if ( count( $article->children ) > 0 )
                            {
                                foreach ( $article->children as $paragraph )
                                {
                                    $intro = $this->renderPlain( $intro, $paragraph );
                                    
                                    $intro = $this->renderCode( $intro, $paragraph );
                                    
                                    $intro = $this->renderStandards( $intro, $paragraph );
                                    
                                    $intro = $this->renderLink( $intro, $paragraph );
                                    
                                    $intro = $this->renderModule( $intro, $paragraph );
                                    
                                    $intro = $this->renderImage( $intro, $paragraph, $articleImages );
                                    
                                    $this->PrevTag = $paragraph->name;
                                }
                            }
                        }
                    }
                }
            }
                
//            $newArticle = eZTextTool::nl2br( $intro );
                $newArticle = $intro;
        }
        
        return $newArticle;
    }

    /*!
      Returns the XHTML article of the article.

      Returns an array( $intro, $contents );
    */
    function &renderPage( $pageNumber=0 )
    {
        $xml =& xmltree( $this->Article->contents() );

//        $xml =& qdom_tree( $this->Article->contents() );

        $returnArray = array( );
        
        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $intro = "";
            $body = "";

            $this->$PrevTag = "";
            $articleImages =& $this->Article->images();
            $articleID = $this->Article->id();
            
            foreach ( $xml->children as $child )
            {
                if ( $child->name == "article" )
                {
                    foreach ( $child->children as $article )
                    {
                        if ( $article->name == "intro" )
                        {
                            if ( count( $article->children ) > 0 )
                                foreach ( $article->children as $paragraph )
                                {
                                    $intro = $this->renderPlain( $intro, $paragraph );
                                    
                                    $intro = $this->renderCode( $intro, $paragraph );
                                    
                                    $intro = $this->renderStandards( $intro, $paragraph );
                                    
                                    $intro = $this->renderLink( $intro, $paragraph );
                                    
                                    $intro = $this->renderModule( $intro, $paragraph );
                                    
                                    $intro = $this->renderImage( $intro, $paragraph, $articleImages );
                                    
                                    $this->PrevTag = $paragraph->name;
                                }
                        }
                        
                        if ( $article->name == "body" )
                        {
                            $body = $article->children;
                        }                        
                    }
                }                
            }

            $pageArray = array();
            // loop on the pages
            foreach ( $body as $page )
            {
                $pageContent = "";
                $this->$PrevTag = "";
                // loop on the contents of the pages
                if ( count( $page->children ) > 0 )
                foreach ( $page->children as $paragraph )
                {
                    $pageContent = $this->renderPlain( $pageContent, $paragraph );

                    $pageContent = $this->renderCode( $pageContent, $paragraph );

                    $pageContent = $this->renderStandards( $pageContent, $paragraph );

                    $pageContent = $this->renderLink( $pageContent, $paragraph );

                    $pageContent = $this->renderModule( $pageContent, $paragraph );

                    $pageContent = $this->renderImage( $pageContent, $paragraph, $articleImages );

                    $this->PrevTag = $paragraph->name;
                }

                
                $pageArray[] = $pageContent;
                
            }


            $bodyContents = "";
            if ( $pageNumber == -1 )
            {
//                $newArticle = "<span class=\"intro\">" . $intro . "</span>\n</p><p>\n";
                
                if ( count( $pageArray ) > 0 )
                    foreach ( $pageArray as $page )
                    {
                        $bodyContents .= $page;
                    }
            }
            else if ( $pageNumber != 0 )
            {
                $bodyContents = $pageArray[$pageNumber];
            }
            else
            {
//                  $newArticle = eZTextTool::nl2br( $intro ) . "</p><p>". $pageArray[$pageNumber];
//                $newArticle = "<span class=\"intro\">" . $intro . "</span>\n</p><p>\n". $pageArray[$pageNumber];
                $bodyContents = $pageArray[$pageNumber];
            }
            
            $returnArray[] =& $intro;
            $returnArray[] =& $bodyContents;            
        }
        
        return $returnArray;
    }

    function &renderPlain( $pageContent, $paragraph )
    {
        // ordinary text
        if ( $paragraph->name == "#text" || $paragraph->name == "text" )
        {
            $paragraph_text = $paragraph->content;
            if ( $paragraph_text[0] == "\n" )
            {
                if ( $this->PrevTag != "link" )
                    $paragraph_text[0] = " ";
            }
            $pageContent .= eZTextTool::nl2br( $paragraph_text );
        }
        return $pageContent;
    }

   
    function &renderLink( $pageContent, $paragraph )
    {
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

            if ( !preg_match( "%^(([a-z]+://)|/|#)%", $href ) )
                $href = "http://" . $href;
            $pageContent .= "<a href=\"$href\">" . $text . "</a>";
        }

        // link
        if ( $paragraph->name == "iconlink" )
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

            if ( !preg_match( "%^(([a-z]+://)|/|#)%", $href ) )
                $href = "http://" . $href;

            $pageContent .= "<img align=\"baseline\" src=\"/images/bulletlink.gif\" width=\"50\" height=\"10\" border=\"0\" hspace=\"0\">&nbsp;<a class=\"path\" href=\"$href\">" . $text . "</a>";
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
                        
            $pageContent .= "<a name=\"$href\"></a>";
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
                        
            $pageContent .= "<a href=\"mailto:$to?subject=$subject\">$text</a>";
        }
        
        
        return $pageContent;
    }

    /*!
      \private
    */
    function &renderModule( $pageContent, $paragraph )
    {
        // ordinary text
        if ( $paragraph->name == "module" )
        {
            foreach ( $paragraph->attributes as $moduleItem )
            {
                switch ( $moduleItem->name )
                {
                    case "name" :
                    {
                        $name = $moduleItem->children[0]->content;
                    }
                    break;
                }
            }

            $localModuleFile = "modules/" . $name . ".php";
            
            if ( file_exists( $localModuleFile ) )
            {
                $moduleFile = $localModuleFile;
            }
            else
            {
                $moduleFile = "ezarticle/modules/" . $name . ".php";                
            }

            if ( file_exists( $moduleFile ) )
            {
                // save the buffer contents
                $buffer =& ob_get_contents();
                ob_end_clean();

                // fetch the module printout
                ob_start();
                include( $moduleFile );
                $moduleContents .= ob_get_contents();
                ob_end_clean();

                // fill the buffer with the old values
                ob_start();
                print( $buffer );
                
                $pageContent .= "$moduleContents";
            }
            else
            {
                $pageContent .= "<b>Error</b>: module $name, not found. Looking in: $moduleFile";
            }
        }
        return $pageContent;
    }
    

    function &renderImage( $pageContent, $paragraph, $articleImages )
    {
        $articleID = $this->Article->id();
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
                $ini =& INIFile::globalINI();
                            
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

                    case "original" :
                    {
                        $variation =& $image;
                    }
                    break;
                    
                    default :
                    {
                        $variation =& $image->requestImageVariation( $ini->read_var( "eZArticleMain", "MediumImageWidth" ),
                        $ini->read_var( "eZArticleMain", "MediumImageHeight" ) );
                    }
                }
                            
                if ( get_class( $variation ) == "ezimage" )
                {
                    $imageURL = $variation->filePath();
                }
                else
                {
                    $imageURL = "/" . $variation->imagePath();
                }

                $imageWidth = $variation->width();
                $imageHeight = $variation->height();
                $imageCaption = $image->caption();
                $imageID = $image->id();

                $viewMode = $GLOBALS["ViewMode"];

                if ( $viewMode == "" )
                {
                    $viewMode = "view";
                }
                if ( $imageAlignment != "float"  )
                {                            
                    $imageTags = "<br clear=\"all\"><table width=\"$imageWidth\" align=\"$imageAlignment\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                                            <tr>
                                            <td>
                                                     	<a href=\"/imagecatalogue/imageview/$imageID/?RefererURL=/article/$viewMode/$articleID/\">
                                                        <img src=\"$imageURL\" border=\"0\" width=\"$imageWidth\" height=\"$imageHeight\" alt=\"\" />
                                                        </a>   
                                                        </td>
                                                </tr>
                                                <tr>
                                                         <td class=\"pictext\">
                                                         $imageCaption
                                                         </td>
                                                </tr>
                                             </table>";
                }
                else
                {                    
                    $imageTags = "<a href=\"/imagecatalogue/imageview/$imageID/?RefererURL=/article/$viewMode/$articleID/\"><img src=\"$imageURL\" border=\"0\" width=\"$imageWidth\" height=\"$imageHeight\" alt=\"\" /></a>";
                }
                $pageContent .=  $imageTags;
            }
            else
            {
                eZLog::writeError( "Image nr: $imageID not found in article: $articleID from IP: $REMOTE_ADDR" );        
            }
        }
        return $pageContent;
    }

    function &renderCode( $pageContent, $paragraph )
    {
        // php code 
        if ( $paragraph->name == "php" )
        {
            $pageContent .= $this->phpHighlight( trim( $paragraph->children[0]->content ) );
        }

        // java code 
        if ( $paragraph->name == "java" )
        {
            $pageContent .= $this->javaHighlight( trim( $paragraph->children[0]->content ) );
        }

        // html code 
        if ( $paragraph->name == "ezhtml" )
        {
            $pageContent .= $this->htmlHighlight( trim( $paragraph->children[0]->content ) );
        }

        // html code 
        if ( $paragraph->name == "html" )
        {
            $pageContent .= trim( $paragraph->children[0]->content );
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

        // perl code 
        if ( $paragraph->name == "perl" )
        {
            $pageContent .= $this->perlHighlight( $paragraph->children[0]->content );
        }

        // lisp code 
        if ( $paragraph->name == "lisp" )
        {
            $pageContent .= $this->lispHighlight( $paragraph->children[0]->content );
        }
        return $pageContent;
    }

    function &renderStandards( $pageContent, $paragraph )
    {
        // header
        if ( $paragraph->name == "header" )
        {
            $pageContent .= "\n<h2>".  $paragraph->children[0]->content . "</h2>\n";
        }
                    
        // bold text
        if ( $paragraph->name == "bold" )
        {
            $pageContent .= "<b>" . $paragraph->children[0]->content . "</b>";
        }

        // italic text
        if ( $paragraph->name == "italic" )
        {
            $pageContent .= "<i>" . $paragraph->children[0]->content . "</i>";
        }

        // underline text
        if ( $paragraph->name == "underline" )
        {
            $pageContent .= "<u>" . $paragraph->children[0]->content . "</u>";
        }

        // strike text
        if ( $paragraph->name == "strike" )
        {
            $pageContent .= "<s>" . $paragraph->children[0]->content . "</s>";
        }

        // pre text
        if ( ( $paragraph->name == "pre" ) || ( $paragraph->name == "verbatim" ) )
        {
            $pageContent .= "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $paragraph->children[0]->content . "</pre></td></tr></table></p>";
        }

        // bullet list
        if ( $paragraph->name == "bullet" )
        {
            $tmpContent =& trim( $paragraph->children[0]->content );
            
            $tmpContent =& preg_replace( "#^(.*)$#m", "<li>\\1</li>", $tmpContent );
            
            $pageContent .= "<ul>" . $tmpContent . "</ul>";
        }
        
        return $pageContent;
    }

    
    /*!
      \private
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
        
        $string = preg_replace ( "#(/\*.+?\*/)#ms", "<font color=\"orange\">\\1</font>", $string );



        $reservedWords = array( "/(function)/",
                                "/( as )/",
                                "/(class )/",
                                "/(var )/",
                                "/(^var )/",
                                "/(for )/"
                                );

        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );

        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

        $string = preg_replace( "#(\\$[a-zA-Z0-9]+)#", "<font color=\"#00aaaa\">\\1</font>", $string );

//          $string = preg_replace ( "#(\\$.+?)[\s|\;]#", "<font color=\"#00aaaa\">\\1</font>", $string );
        
        // indenting
        
        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }

    /*!
      \private
      Returns a sql highlighted string.
    */
    function &sqlHighlight( $string )
    {

        // some special characters
        $string = ereg_replace ( "([(){},+-;]|=|\[|\])", "<font color=\"green\">\\1</font>", $string );

        $string = preg_replace ( "#('.*?')#", "<font color=\"red\">\\1</font>", $string );
        
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
                                "/(INSERT )/i",
                                "/(INTO )/i",
                                "/(VALUES )/i",
                                "/(UNIQUE )/i",
                                "/(INT )/i",
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
        
        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }

    /*!
      \private
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
                                "/(int )/",
                                "/( for)/"
                                );
        
        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );


        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

        
        $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );

        // newlines
//        $string = ereg_replace ( "\n", "<br />\n newline", $string );

        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }

    /*!
      \private
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

        
        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }

    /*!
      \private 
      Returns a perl highlighted string.
    */
    function &perlHighlight( $string )
    {
        $string = ereg_replace ( "(<)", "&lt;", $string );        
        $string = ereg_replace ( "(>)", "&gt;", $string );        
        
        // some special characters
        $string = ereg_replace ( "([(){}+-]|=|\[|\])", "<font color=\"red\">\\1</font>", $string );

        // reserved words
//          $string = ereg_replace ( "(foreach|function|for|while|switch|as)", "<font color=\"blue\">\\1</font>", $string );

        // comments
        $string = ereg_replace ( "(#[^\n]+)", "<font color=\"orange\">\\1</font>", $string );

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
        
        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }


    /*!
      \private 
      Returns a lisp highlighted string.
    */
    function &lispHighlight( $string )
    {
        $len = strlen( $string );
        $index = 0;
        $tmpstring = "";

        while ( $index < $len )
        {
            $char = $string[$index];
            if ( $char == ';' )
            {
                $end = strpos( $string, "\n", $index );
                if ( $end === false )
                {
                    $tmpstring .= "<font color=\"orange\">" . $this->sptobsp( substr( $string, $index ) ) . "</font>\n";
                    $index = $len;
                }
                else
                {
                    $tmpstring .= "<font color=\"orange\">" . $this->sptobsp( substr( $string, $index, $end - $index ) ) . "</font>\n";
                    $index = $end + 1;
                }
            }
            else if ( $char == ' ' )
            {
                $tmpstring .= "&nbsp;";
                ++$index;
            }
            else if ( $char == '(' )
            {
                $end = strpos( $string, " ", $index + 1 );
                $end_paren = strpos( $string, ")", $index + 1 );
                if ( $end_paren  < $end )
                    $end = $end_paren;
                if ( $end === false )
                {
                    $tmpstring .= "(";
                    ++$index;
                }
                else
                {
                    $tmpstring .= "<font color=\"red\">(</font>";
                    $command = substr( $string, $index + 1, $end - $index - 1 );
                    if ( eregi( "(let|if|while)", $command ) )
                    {
                        $tmpstring .= "<font color=\"blue\">" . $command . "</font>";
                    }
                    else if ( eregi( "(defun|defvar)", $command ) )
                    {
                        $name_end = strpos( $string, " ", $end + 1 );
                        if ( $name_end === false )
                        {
                            $tmpstring .= "<font color=\"blue\">" . $command . "</font>";
                            $end = $len;
                        }
                        else
                        {
                            $name = substr( $string, $end + 1, $name_end - $end - 1 );
                            $tmpstring .= "<font color=\"blue\">" . $command . "</font>&nbsp;";
                            $tmpstring .= "<font color=\"magenta\">" . $name . "</font>";
                            $end = $name_end;
                        }
                    }
                    else
                    {
                        $tmpstring .= $command;
                    }
                    $index = $end;
                }
            }
            else if ( $char == ')' )
            {
                $tmpstring .= "<font color=\"red\">)</font>";
                ++$index;
            }
            else if ( $char == '"' )
            {
                $end = strpos( $string, "\"", $index + 1 );
                if ( $end === false )
                {
                    $tmpstring .= "<font color=\"green\">\"" . $this->sptobsp( substr( $string, $index + 1 ) ) . "</font>";
                    $index = $len;
                }
                else
                {
                    $tmpstring .= "<font color=\"green\">\"" . $this->sptobsp( substr( $string, $index + 1, $end - $index - 1 ) ) . "\"</font>";
                    $index = $end + 1;
                }
            }
            else
            {
                $tmpstring .= $char;
                ++$index;
            }
        }
        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $tmpstring . "</pre></td></tr></table></p>";

        return $string;

//          $string = ereg_replace ( "(<)", "&lt;", $string );
//          $string = ereg_replace ( "(>)", "&gt;", $string );

//          // comments
//          $string = preg_replace ( "#(;.*$)#m", "<font color=\"orange\">\\1</font>", $string );

//          // indenting
//          $string = preg_replace( "/( )/m", "&nbsp;", $string );

//          $string = ereg_replace( "(\"[^\"]*\")", "<font color=\"green\">\\1</font>", $string );

//          // some special characters
//          $string = ereg_replace ( "([(){}+-]|\[|\])", "<font color=\"red\">\\1</font>", $string );

//          $string = ereg_replace( "(defun|let|if|while)", "<font color=\"blue\">\\1</font>", $string );

//          // reserved words
//  //          $string = ereg_replace ( "(foreach|function|for|while|switch|as)", "<font color=\"blue\">\\1</font>", $string );


//          $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

//          $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );
        
//          $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
//               $string . "</pre></td></tr></table></p>";
        
//          return $string;
    }

    function &sptobsp( $string )
    {
        preg_replace( "# #m", "&nbsp;", $string );
        return $string;
    }

    /*!
      \private
      Returns a java highlighted string.
    */
    function &javaHighlight( $string )
    {        
        $string = ereg_replace ( "(<)", "&lt;", $string );
        $string = ereg_replace ( "(>)", "&gt;", $string );
        
        // some special characters
        $string = ereg_replace ( "([(){},+-;]|=|\[|\])", "<font color=\"red\">\\1</font>", $string );

        // comments
        $string = ereg_replace ( "(//[^\n]+)", "<font color=\"orange\">\\1</font>", $string );
        $string = ereg_replace ( "(/\*[^\*]+\*/)", "<font color=\"orange\">\\1</font>", $string );

        // reserved words
        $reservedWords = array( "/(function)/",
                                "/( as )/",
                                "/(void )/",
                                "/(class )/",
                                "/(float )/",
                                "/(doble )/",
                                "/(int )/",
                                "/(var )/",
                                "/(private )/",
                                "/(public )/",
                                "/(int )/",
                                "/( for)/"
                                );
        
        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );

        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );
        
        $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );

        // newlines
//        $string = ereg_replace ( "\n", "<br />\n newline", $string );

        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }

    /*!
      \private
      Returns a html highlighted string.
    */
    function &htmlHighlight( $string )
    {
        $string =& htmlspecialchars( $string );

        $string = preg_replace( "#(&lt;.*?&gt;)#", "<font color=\"blue\">\\1</font>", $string );
        
        $string = "<br clear=\"all\"><p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }
    
    var $Article;
    var $PrevTag;
}

?>

