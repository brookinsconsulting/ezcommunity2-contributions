<?php
// 
// $Id: ezqdomrenderer.php,v 1.20 2001/07/19 12:19:21 jakobn Exp $
//
// Definition of eZQDomRenderer class
//
// Created on: <24-Mar-2001 12:54:26 bf>
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
  <image 42 align size link> - image tag, 42 is the id, alignment (left|center|right|float), size (small|medium|large|original), the link is optional

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

class eZQDomrenderer
{
    /*!
      Creates a new eZTechGenerator object.
    */
    function eZQDomrenderer( &$article )
    {
        $ini =& INIFile::globalINI();

        $this->Template = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", "en_GB", "articleview.php" );

        $this->Template->set_file( "articletags_tpl", "articletags.tpl"  );

        $this->Template->set_block( "articletags_tpl", "header_1_tpl", "header_1"  );
        $this->Template->set_block( "articletags_tpl", "header_2_tpl", "header_2"  );
        $this->Template->set_block( "articletags_tpl", "header_3_tpl", "header_3"  );
        $this->Template->set_block( "articletags_tpl", "header_4_tpl", "header_4"  );
        $this->Template->set_block( "articletags_tpl", "header_5_tpl", "header_5"  );
        $this->Template->set_block( "articletags_tpl", "header_6_tpl", "header_6"  );

        $this->Template->set_block( "articletags_tpl", "image_tpl", "image"  );
        $this->Template->set_block( "image_tpl", "image_link_tpl", "image_link"  );
        $this->Template->set_block( "image_tpl", "ext_link_tpl", "ext_link"  );

        $this->Template->set_block( "image_tpl", "image_text_tpl", "image_text"  );


        $this->Template->set_block( "articletags_tpl", "image_float_tpl", "image_float" );
        $this->Template->set_block( "image_float_tpl", "image_link_float_tpl", "image_link_float" );
        $this->Template->set_block( "image_float_tpl", "ext_link_float_tpl", "ext_link_float"  );        

        $this->Template->set_block( "articletags_tpl", "link_tpl", "link"  );        
        
        $this->Template->set_block( "articletags_tpl", "bold_tpl", "bold"  );
        $this->Template->set_block( "articletags_tpl", "italic_tpl", "italic"  );
        $this->Template->set_block( "articletags_tpl", "underline_tpl", "underline"  );
        $this->Template->set_block( "articletags_tpl", "strong_tpl", "strong"  );
        $this->Template->set_block( "articletags_tpl", "factbox_tpl", "factbox"  );
        $this->Template->set_block( "articletags_tpl", "quote_tpl", "quote"  );
        $this->Template->set_block( "articletags_tpl", "pre_tpl", "pre"  );

        // lists
        $this->Template->set_block( "articletags_tpl", "bullet_tpl", "bullet"  );
        $this->Template->set_block( "bullet_tpl", "bullet_item_tpl", "bullet_item"  );

        $this->Template->set_block( "articletags_tpl", "list_tpl", "list"  );
        $this->Template->set_block( "list_tpl", "list_item_tpl", "list_item"  );
        
        $this->Article = $article;
    }

    /*!
      Returns the XHTML contents of the introduction of the article.
    */
    function &renderIntro()
    {
        $xml =& xmltree( $this->Article->contents() );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $intro = "";

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
                                    $intro .= $this->renderPlain( $paragraph );
                                    $intro .= $this->renderStandards( $paragraph );
                                    $intro .= $this->renderImage( $paragraph );
                                    $intro .= $this->renderHeader( $paragraph );
                                    
                                    
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
    */
    function &renderPage( $pageNumber=0 )
    {
//        $xml =& qdom_tree( $this->Article->contents() );

        $xml =& xmltree( $this->Article->contents() );

//        $err = qdom_error();
//        if ( $err )
//            print( $err );
        
        if ( !$xml )
        {
            print( "<br /><b>Error: eZTechRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $intro = "";
            $body = "";

            $this->PrevTag = "";
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
                                    $intro .= $this->renderHeader( $paragraph );
                                    $intro .= $this->renderPlain( $paragraph );
                                    $intro .= $this->renderStandards( $paragraph );
                                    $intro .= $this->renderImage( $paragraph );
                                    $intro .= $this->renderLink( $paragraph );
                                    
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
                    $pageContent .= $this->renderHeader( $paragraph );
                    $pageContent .= $this->renderStandards( $paragraph );
                    $pageContent .= $this->renderPlain( $paragraph );
                    $pageContent .= $this->renderImage( $paragraph );
                    $pageContent .= $this->renderLink( $paragraph );
                    
//                      $pageContent = $this->renderCode( $pageContent, $paragraph );
//                      $pageContent = $this->renderLink( $pageContent, $paragraph );
//                      $pageContent = $this->renderModule( $pageContent, $paragraph );
//                      $pageContent = $this->renderImage( $pageContent, $paragraph, $articleImages );

                    $this->PrevTag = $paragraph->name;
                }

                
                $pageArray[] = $pageContent;
                
            }

            $returnArray = array();
            $bodyContents = "";
            
            if ( $pageNumber == -1 )
            {
                $newArticle = $intro . "\n</p><p>\n";
                if ( count( $pageArray ) > 0 )
                    foreach ( $pageArray as $page )
                    {
                        $bodyContents .= $page;
                    }
            }
            else if ( $pageNumber != 0 )
            {
                $intro = "";
                $bodyContents = $pageArray[$pageNumber];
            }
            else
            {
                $bodyContents = $pageArray[$pageNumber];
            }
                
        }

        $returnArray[] =& $intro;
        $returnArray[] =& $bodyContents;
        
        return $returnArray;
    }

    /*!
      Renders header tags.
    */
    function &renderHeader( $paragraph )
    {
        $pageContent = "";
        if ( $paragraph->name == "header" )
        {
            $level = 1;
            if  ( count( $paragraph->attributes ) > 0 )
            foreach ( $paragraph->attributes as $attr )
            {
                switch ( $attr->name )
                {
                    case "level" :
                    {
                       $level = $attr->children[0]->content;
                    }
                    break;
                }
            }

            foreach ( $paragraph->children as $child )
            {
                if ( $child->name == "text" )
                {
                    $content = $child->content;
                }
            }
            
            $level = min( 6, $level );
            $level = max( 1, $level );

            $this->Template->set_var( "contents", $content );
            $pageContent =& $this->Template->parse( "header_" . $level, "header_" . $level. "_tpl" );
        }

        return $pageContent;
    }

    /*!
      Renders image tags.
    */
    function &renderImage( $paragraph )
    {
        $pageContent = "";
        if ( $paragraph->name == "image" )
        {
            $articleImages = $this->Article->images();
            $articleID = $this->Article->id();

            $level = 1;
            if  ( count( $paragraph->attributes ) > 0 )
            foreach ( $paragraph->attributes as $attr )
            {
                switch ( $attr->name )
                {
                    case "id" :
                    {
                       $imageID = $attr->children[0]->content;
                    }
                    break;

                    case "align" :
                    {
                       $imageAlignment = $attr->children[0]->content;
                    }
                    break;

                    case "size" :
                    {
                       $imageSize = $attr->children[0]->content;
                    }
                    break;

                    case "href" :
                    {
                       $imageHref = $attr->children[0]->content;
                    }
                    break;
                }
            }

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

                
                $this->Template->set_var( "image_width", $imageWidth );
                $this->Template->set_var( "image_height", $imageHeight );
                $this->Template->set_var( "image_alignment", $imageAlignment );
                $this->Template->set_var( "image_id", $imageID );
                $this->Template->set_var( "image_url", $imageURL );
                $this->Template->set_var( "article_id", $articleID );
                $this->Template->set_var( "view_mode", $viewMode );
                $this->Template->set_var( "caption", $imageCaption );

                if ( $imageAlignment != "float"  )
                {                
                    if ( $imageHref != "" )
                    {
                        // convert link
                        if ( !preg_match( "%^(([a-z]+://)|/|#)%", $imageHref ) )
                            $imageHref = "http://" . $imageHref;
                        $this->Template->set_var( "image_href", $imageHref );
                        $this->Template->set_var( "image_link", "" );
                        $this->Template->parse( "ext_link", "ext_link_tpl" );
                    }
                    else
                    {
                        $this->Template->set_var( "ext_link", "" );
                        $this->Template->parse( "image_link", "image_link_tpl" );
                    }

                    if ( trim( $imageCaption ) == "" )
                    {
                        $this->Template->set_var( "image_text", "" );
                    }
                    else
                    {
                        $this->Template->parse( "image_text", "image_text_tpl" );
                    }
                    
                    $pageContent = $this->Template->parse( "image", "image_tpl" );
                }
                else
                {                    
                    if ( $imageHref != "" )
                    {
                        // convert link
                        if ( !preg_match( "%^(([a-z]+://)|/|#)%", $imageHref ) )
                            $imageHref = "http://" . $imageHref;
                        $this->Template->set_var( "image_href", $imageHref );
                        $this->Template->set_var( "image_link_float", "" );
                        $this->Template->parse( "ext_link_float", "ext_link_float_tpl" );
                    }
                    else
                    {
                        $this->Template->set_var( "ext_link_float", "" );
                        $this->Template->parse( "image_link_float", "image_link_float_tpl" );
                    }

                    $pageContent = $this->Template->parse( "image_float", "image_float_tpl" );
                }
            }
        }

        return $pageContent;
    }
    
    function &renderPlain( $paragraph )
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
    

    function &renderStandards( $paragraph )
    {
        $pageContent = "";
        switch ( $paragraph->name )
        {
            case "bullet" :
            case "list" :
            {
                $tmpContent = "";
                $content = "";
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "text" )
                    {
                        $content .= $child->content;
                    }
                    else
                    {
                        $content .= $this->renderStandards( $child );
                        $content .= $this->renderLink( $child );                        
                        $content .= $this->renderImage( $child );
                        $content .= $this->renderHeader( $child );
                    }

                }

                $content = trim( $content );
                $lines = explode( "\n", $content );
                
                if ( $paragraph->name == "bullet" )
                {
                    $this->Template->set_var( "bullet", "" );
                    $this->Template->set_var( "bullet_item", "" );

                    foreach ( $lines as $line )
                    {
                        $this->Template->set_var( "contents", $line );
                        $tmpContent = $this->Template->parse( "bullet_item", "bullet_item_tpl", true );
                    }
                    $pageContent = $this->Template->parse( "bullet", "bullet_tpl" );
                }
                else
                {
                    $this->Template->set_var( "list", "" );
                    $this->Template->set_var( "list_item", "" );

                    foreach ( $lines as $line )
                    {
                        $this->Template->set_var( "contents", $line );
                        $tmpContent = $this->Template->parse( "list_item", "list_item_tpl", true );
                    }
                    $pageContent = $this->Template->parse( "list", "list_tpl" );
                }

            } break;

            case "bold" :
            case "italic" :                
            case "underline" :
            case "strong" :
            case "factbox" :
            case "quote" :
            case "pre" :
            {
                $tmpContent = "";
                if ( count( $paragraph->children ) )
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "text" )
                    {                
                        $tmpContent .= eZTextTool::nl2br( $child->content );
                    }
                    else
                    {
                        $tmpContent .= $this->renderStandards( $child );
                        $tmpContent .= $this->renderLink( $child );
                        $tmpContent .= $this->renderImage( $child );
                        $tmpContent .= $this->renderHeader( $child );
                    }
                }

                $this->Template->set_var( "contents", $tmpContent );
                switch ( $paragraph->name )
                {
                    case "bold" :
                        $pageContent = trim( $this->Template->parse( "bold", "bold_tpl" ) );
                        break;
                    case "italic" :
                        $pageContent = trim( $this->Template->parse( "italic", "italic_tpl" ) );
                        break;
                    case "underline" :
                        $pageContent = trim( $this->Template->parse( "underline", "underline_tpl" ) );
                    break;
                    case "strong" :
                        $pageContent = trim( $this->Template->parse( "strong", "strong_tpl" ) );
                    break;
                    case "factbox" :
                        $pageContent = trim( $this->Template->parse( "factbox", "factbox_tpl" ) );
                    break;
                    case "quote" :
                        $this->Template->set_var( "contents", trim( $tmpContent ) );
                        $pageContent = trim( $this->Template->parse( "quote", "quote_tpl" ) );
                    break;
                    case "pre" :
                        $this->Template->set_var( "contents", trim( $tmpContent ) );
                        $pageContent = trim( $this->Template->parse( "pre", "pre_tpl" ) );
                    break;
                }
                
            }break;

        }
        
        return $pageContent;
    }    

    /*!
      Renders link tags.
    */
    function &renderLink( $paragraph )
    {
        $pageContent = "";
        if ( $paragraph->name == "link" )
        {
            if ( count( $paragraph->attributes ) > 0 )
            foreach ( $paragraph->attributes as $attr )
            {                
                switch ( $attr->name )
                {
                    case "href" :
                    {
                       $href = $attr->children[0]->content;
                    }
                    break;

                    case "text" :
                    {
                       $text = $attr->children[0]->content;
                    }
                    break;
                }
            }

            if ( !preg_match( "%^(([a-z]+://)|/|#)%", $href ) )
                $href = "http://" . $href;
            
            $this->Template->set_var( "href", $href );
            $this->Template->set_var( "link_text", $text );
            $pageContent =& trim( $this->Template->parse( "link", "link_tpl" ) );
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

            $this->Template->set_var( "href", "mailto:$to?subject=$subject" );
            $this->Template->set_var( "link_text", $text );
            $pageContent =& $this->Template->parse( "link", "link_tpl" );
        }
        
        return $pageContent;
    }

    
    var $Article;
    var $PrevTag;
    var $Template;
}

?>
