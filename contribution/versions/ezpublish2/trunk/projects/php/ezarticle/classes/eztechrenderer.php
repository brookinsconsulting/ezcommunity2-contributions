<?php
// 
// $Id: eztechrenderer.php,v 1.29 2000/11/01 10:27:31 bf-cvs Exp $
//
// Definition of eZTechRenderer class
//
// B�rd Farstad <bf@ez.no>
// Created on: <18-Oct-2000 17:45:32 bf>
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
  <mail adresse@domain.tld?subject="subject line" link text> - anchor to email address with subject
  <image 42 align size> - image tag, 42 is the id, alignment (left|center|right), size (small|medium|large)

  <ezanchor anchorname>
  
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
        $xml = xmltree( $this->Article->contents() );

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
            foreach ( $xml->root->children as $child )
            {
                if ( $child->name == "intro" )
                {

                    foreach ( $child->children as $paragraph )
                    {
                        // ordinary text
                        if ( $paragraph->name == "text" )
                        {
                            $intro .= eZTextTool::nl2br( $paragraph->content );
                        }

                        $intro = $this->renderCode( $intro, $paragraph );

                        $intro = $this->renderStandards( $intro, $paragraph );

                        $intro = $this->renderLink( $intro, $paragraph );

                        $intro = $this->renderImage( $intro, $paragraph, $articleImages );

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
            $intro = "";
            $body = "";

            $articleImages = $this->Article->images();
            $articleID = $this->Article->id();
            
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
                            $intro .= eZTextTool::nl2br( $paragraph->content );
                        }

                        $intro = $this->renderCode( $intro, $paragraph );

                        $intro = $this->renderStandards( $intro, $paragraph );

                        $intro = $this->renderLink( $intro, $paragraph );

                        $intro = $this->renderImage( $intro, $paragraph, $articleImages );
                    }

                }
                
                if ( $child->name == "body" )
                {
                    $body = $child->children;
                }
            }

            $pageArray = array();
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
                        $pageContent .= eZTextTool::nl2br( $paragraph->content );
                    }

                    $pageContent = $this->renderCode( $pageContent, $paragraph );

                    $pageContent = $this->renderStandards( $pageContent, $paragraph );

                    $pageContent = $this->renderLink( $pageContent, $paragraph );

                    $pageContent = $this->renderImage( $pageContent, $paragraph, $articleImages );
                }

                
                $pageArray[] = $pageContent;
                
            }
            

            if ( $pageNumber != 0 )
            {
                $newArticle = $pageArray[$pageNumber];
            }
            else
            {
//                  $newArticle = eZTextTool::nl2br( $intro ) . "</p><p>". $pageArray[$pageNumber];
                $newArticle = $intro . "</p><p>". $pageArray[$pageNumber];
            }
                
        }
        
        return $newArticle;
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

            if ( !preg_match( "/^([a-z]+:\\/\\/)|\\//", $href ) )
                $href = "http://" . $href;
            $pageContent .= "<a href=\"$href\">" . $text . "</a>";
        }

        // ez anchor
        if ( $paragraph->name == "ezanchor" )
        {
            print( "ancor" );
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

    function &renderImage( $pageContent, $paragraph, $articleImages )
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

                    default :
                    {
                        $variation =& $image->requestImageVariation( $ini->read_var( "eZArticleMain", "MediumImageWidth" ),
                        $ini->read_var( "eZArticleMain", "MediumImageHeight" ) );
                    }
                }
                            
                $imageURL = "/" . $variation->imagePath();
                $imageWidth = $variation->width();
                $imageHeight = $variation->height();
                $imageCaption = $image->caption();
                            
                $imageTags = "<table width=\"$imageWidth\" align=\"$imageAlignment\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
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
            $pageContent .= "<h2>".  $paragraph->children[0]->content . "</h2>";
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
            $pageContent .= "<pre>" . $paragraph->children[0]->content . "</pre>";
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
        $string = ereg_replace ( "(/\*[^\*]+\*/)", "<font color=\"orange\">\\1</font>", $string );

        $reservedWords = array( "/(function)/",
                                "/( as )/",
                                "/(class )/",
                                "/( var )/",
                                "/(^var )/",
                                "/( for)/"
                                );

        $string = preg_replace( $reservedWords, "<font color=\"blue\">\\1</font>", $string );


        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

        $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );

        // indenting
        
        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
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
        
        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
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

        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
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

        
        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
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
        
        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }


    /*!
      \private 
      Returns a lisp highlighted string.
    */
    function &lispHighlight( $string )
    {
        $string = ereg_replace ( "(<)", "&lt;", $string );        
        $string = ereg_replace ( "(>)", "&gt;", $string );        
        
        // some special characters
        $string = ereg_replace ( "([(){}+-]|=|\[|\])", "<font color=\"red\">\\1</font>", $string );

        // reserved words
//          $string = ereg_replace ( "(foreach|function|for|while|switch|as)", "<font color=\"blue\">\\1</font>", $string );

        // comments
        $string = ereg_replace ( "(#[^\n]+)", "<font color=\"orange\">\\1</font>", $string );


        $string = preg_replace( "/( [0-9]+)/", "<font color=\"green\">\\1</font>", $string );

        $string = preg_replace( "/(\$[a-zA-Z0-9]+)/", "<font color=\"#00ffff\">\\1</font>", $string );

        // indenting
        $string = preg_replace( "/^( )+/m", "&nbsp;", $string );
        
        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
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

        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
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
        
        $string = "<p><table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\" border=\"0\"><tr><td bgcolor=\"#f0f0f0\"><pre>" .
             $string . "</pre></td></tr></table></p>";
        
        return $string;
    }
    var $Article;
}

?>

