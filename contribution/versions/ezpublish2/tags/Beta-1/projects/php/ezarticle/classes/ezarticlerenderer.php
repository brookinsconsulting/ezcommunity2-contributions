<?php
// 
// $Id: ezarticlerenderer.php,v 1.2 2000/10/19 18:03:40 bf-cvs Exp $
//
// Definition of eZArticleRenderer class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 16:35:33 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZArticleRendrer handles article XML rendering.
/*!
  This class handles redering of articles. 
  
*/

class eZArticleRenderer
{
    function eZArticleRenderer(  &$article )
    {
        $this->Article =& $article;

        $xml =& xmltree( $this->Article->contents() );
        
        if ( $xml->root->children[0]->name == "generator" )
        {
            $generator =& $xml->root->children[0]->children[0]->content;

            switch ( $generator )
            {
                case "tech" :
                {
                    $this->RendererFile = "eztechrenderer.php";
                    $this->RendererClass = "eZTechRenderer";
                }
                break;

                case "simple" :
                {
                    $this->RendererFile = "ezsimplerenderer.php";
                    $this->RendererClass = "eZSimpleRenderer";
                }
                break;

                default:
                {
                    $this->RendererFile = "ezsimplerenderer.php";
                    $this->RendererClass = "eZSimpleRenderer";
                }                    
            }
        }
        else
        {
            print( "<b>Error: eZArticleRenderer::eZArticleRenderer()  could not find generator in XML chunk.</b>" );
        }
    }

    /*!
      Returns the intro of the article.
    */
    function &renderIntro( )
    {
        include_once( "ezarticle/classes/" . $this->RendererFile );

        $generator = new $this->RendererClass( $this->Article );
              
        return $generator->renderIntro();
    }

    /*!
      Returns a specific page of a article. If no argument is given or
      the article has no pages the body is returned.

      It is up to the renderer to handle the page argument.
    */
    function &renderPage( $page=0 )
    {
        include_once( "ezarticle/classes/" . $this->RendererFile );

        $generator = new $this->RendererClass( $this->Article );

        print( "Using rederer: " . $this->RendererClass . "<br>");
              
        return $generator->renderPage( $page );
    }
    
    var $RendererClass;
    var $RendererFile;

    var $Article;
}

?>
