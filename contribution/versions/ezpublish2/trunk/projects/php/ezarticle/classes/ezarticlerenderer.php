<?php
// 
// $Id: ezarticlerenderer.php,v 1.9 2001/04/07 13:54:19 bf Exp $
//
// Definition of eZArticleRenderer class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 16:35:33 bf>
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
//! eZArticleRendrer handles article XML rendering.
/*!
  This class handles redering of articles. 
  
*/

class eZArticleRenderer
{
    function eZArticleRenderer(  &$article )
    {
        $this->Article =& $article;

        $contents =& $this->Article->contents();

//        print( nl2br( htmlspecialchars( $contents ) ) );

        // find the generator used
        if ( ereg("<generator>(.*)</generator>", $contents, $regs ) )
        {
            $generator =& $regs[1];
            
            switch ( $generator )
            {
                case "qdom" :
                {
                    $this->RendererFile = "ezqdomrenderer.php";
                    $this->RendererClass = "eZQDomRenderer";
                }
                break;

                case "tech" :
                {
                    $this->RendererFile = "eztechrenderer.php";
                    $this->RendererClass = "eZTechRenderer";
                }
                break;

                case "ez" :
                {
                    $this->RendererFile = "ezezrenderer.php";
                    $this->RendererClass = "eZEzRenderer";
                }
                break;
                
                case "flower" :
                {
                    $this->RendererFile = "ezflowerrenderer.php";
                    $this->RendererClass = "eZFlowerRenderer";
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

//        print( "Using renderer: " . $this->RendererClass . "<br>");
              
        return $generator->renderPage( $page );
    }
    
    var $RendererClass;
    var $RendererFile;

    var $Article;
}

?>
