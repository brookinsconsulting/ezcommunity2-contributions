<?php
// 
// $Id: eznewsflowerarticleviewer.php,v 1.1 2000/10/13 20:55:50 pkej-cvs Exp $
//
// Definition of eZNewsFlowerArticleViewer class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <12-Okt-2000 10:59:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsFlowerArticleViewer handles the viewing of flower articles.
/*!
    This class will fetch the correct article and render it.
    
    NOTE: We don't need a constructor in this class.
 */

include_once( "eznews/classes/eznewsflowerarticle.php" );
include_once( "eznews/classes/eznewsoutput.php" );  
include_once( "eznews/user/eznewsarticleviewer.php" );

#echo "eZNewsFlowerArticleViewer<br />\n";
class eZNewsFlowerArticleViewer extends eZNewsArticleViewer
{
    /*!
        This function renders the page.
        
        \out
            \$outPage   The text string with the page info
        \return
            Returns true if successful.
     */
    function renderPage( &$outPage )
    {
        #echo "eZNewsFlowerArticleViewer::renderPage( \$outPage = $outPage )<br />\n";
        $value = false;

        $this->IniObject->readUserTemplate( "eznewsflower/article", "view.php" );
        $this->IniObject->set_file( array( "article" => "view.tpl" ) );
        $this->IniObject->set_block( "article", "article_here_template", "article_here" );
        $this->IniObject->set_block( "article", "article_item_template", "article_item" );
        $this->IniObject->set_block( "article", "article_image_template", "article_image" );
        $this->Item = new eZNewsFlowerArticle( $this->Item->id() );
        
        if( $isCached == true )
        {
            /// fetch the cached version
        }
        else
        {
            $this->doThis();
            $this->IniObject->setAllStrings();
            $this->IniObject->parse( "article_item", "article_item_template" );
            $this->IniObject->parse( "article_here", "article_here_template" );
            $outPage = $this->IniObject->parse( "output", "article" );
        }
        
        $value = true;
        
        return $value;
    }



    /*!
        This function renders the header info.
        
        \out
            \$outPage   The text string with the page header
        \return
            Returns true if successful.
     */
    function renderHead( &$outHead )
    {
        $value = false;
        
        if( $isCached == true )
        {
            /// fetch the cached version
        }
        else
        {
        }
        
        
        return $value;
    }



    /*!
        This function will fill in the information about this category.
        
        \return
            Returns true if successful.
     */
    function doThis()
    {
        $value = true;
        
        $oldStory = $this->Item->story();
        $frontImage = $this->Item->getFrontImage();

        ereg( "<price>(.+)</price>" , $oldStory, $regs );
        $price = nl2br( htmlspecialchars( $regs[1] ) );
        ereg( "<name>(.+)</name>" , $oldStory, $regs );
        $name = nl2br( htmlspecialchars( $regs[1] ) );
        ereg( "<description>(.+)</description>" , $oldStory, $regs );
        $story = nl2br( htmlspecialchars( $regs[1] ) );
        
        if( $frontImage )
        {
            $mainImage = new eZImage( $PictureID, 0 );

            $image = $mainImage->requestImageVariation( 250, 250 );

            $this->IniObject->set_var( "this_image_id", $mainImage->id() );
            $this->IniObject->set_var( "this_image_value", htmlspecialchars( $mainImage->name() ) );
            $this->IniObject->set_var( "this_image", "/" . htmlspecialchars( $image->imagePath() ) );
            $this->IniObject->set_var( "this_image_width", "/" . htmlspecialchars( $image->width() ) );
            $this->IniObject->set_var( "this_image_height", "/" . htmlspecialchars( $image->height() ) );
            $this->IniObject->set_var( "this_image_caption", "/" . htmlspecialchars( $mainImage->caption() ) );
            $this->IniObject->parse( "article_image", "article_image_template" );
            $this->IniObject->set_var( "this_picture", $this->IniObject->get_var( "article_image" ) );
            $this->IniObject->set_var( "image", "" );
            $this->IniObject->set_var( "article_image", "" );
        }
        else
        {
            $this->IniObject->set_var( "image", "" );
            $this->IniObject->set_var( "this_picture", "" );
        }

        $this->IniObject->set_var( "this_price", $price . "1000" );
        $this->IniObject->set_var( "this_name", $name . "besting" );
        $this->IniObject->set_var( "this_description", $story . "testing" );

        $value = eZNewsArticleViewer::doThis();
        return $value;
    }
};

?>
