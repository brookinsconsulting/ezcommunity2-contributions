<?php
// 
// $Id: ezrfprenderer.php,v 1.13.2.1 2001/12/03 11:03:45 kaid Exp $
//
// Definition of eZRfpRenderer class
//
// Created on: <18-Oct-2000 16:35:33 bf>
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
//!! eZRfp
//! eZRfpRendrer handles rfp XML rendering.
/*!
  This class handles redering of rfps. 
  
*/

class eZRfpRenderer
{
    function eZRfpRenderer(  &$rfp, $template=false )
    {
        $this->Generator = false;
        $this->Rfp =& $rfp;
        $this->Template = $template;

        $contents =& $this->Rfp->contents();

//        print( nl2br( htmlspecialchars( $contents ) ) );

        // find the generator used
        if ( ereg("<generator>(.*)</generator>", substr( $contents, 0, 200 ), $regs ) )
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
            print( "<b>Error: eZRfpRenderer::eZRfpRenderer()  could not find generator in XML chunk.</b>" );
        }
    }

    /*!
      Returns the intro of the rfp.
    */
    function &renderIntro( )
    {
        $generator =& $this->generator();
        
        return $generator->renderIntro();
    }    

    /*!
      Returns a specific page of a rfp. If no argument is given or
      the rfp has no pages the body is returned.

      It is up to the renderer to handle the page argument.
    */
    function &renderPage( $page=0 )
    {
        $generator =& $this->generator();
//        print( "Using renderer: " . $this->RendererClass . "<br>");
              
        return $generator->renderPage( $page );
    }

    /*!
      Returns the list of images used in the rfp.
    */
    function usedImageList()
    {
        $generator =& $this->generator();

        return $generator->usedImageList();
    }

    /*!
      \private
      Private function to return the generator class.
    */
    function &generator()
    {
        if ( $this->Generator == false )
        {
            include_once( "ezrfp/classes/" . $this->RendererFile );
            $this->Generator = new $this->RendererClass( $this->Rfp, $this->Template );
        }

        return $this->Generator;
    }


    /// varable to store the generator object
    var $Generator;    
    
    var $RendererClass;
    var $RendererFile;
    var $Template;

    var $Rfp;
}

?>
