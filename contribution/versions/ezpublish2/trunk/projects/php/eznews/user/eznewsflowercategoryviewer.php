<?php
// 
// $Id: eznewsflowercategoryviewer.php,v 1.3 2000/10/13 21:46:07 pkej-cvs Exp $
//
// Definition of eZNewsFlowerCategoryCreator class
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
//! eZNewsFlowerCategoryViewer handles the viewing of flower categories.
/*!
    This class will fetch the correct category and the correct objects.
    
    NOTE: We don't need a constructor in this class.
 */

/*!TODO
    Add direction in renderPage() (of children)
 */

include_once( "eznews/user/eznewscategoryviewer.php" );
include_once( "eznews/user/eznewsflowerarticleviewer.php" );
include_once( "eznews/classes/eznewsflowercategory.php" );
include_once( "eznews/classes/eznewsoutput.php" );  

#echo "eZNewsFlowerCategoryViewer<br />\n";
class eZNewsFlowerCategoryViewer extends eZNewsCategoryViewer
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
        $value = false;
        
        $this->Item = new eZNewsFlowerCategory( $this->Item->id() );
        
        if( $isCached == true )
        {
            /// fetch the cached version
        }
        else
        {
            $this->IniObject->readUserTemplate( "eznewsflower/category", "view.php" );
            
            $this->IniObject->set_file( array( "category" => "view.tpl" ) );
            $this->IniObject->set_block( "category", "this_item_template", "this_item" );
            $this->IniObject->set_block( "category", "articles_template", "articles" );
            $this->IniObject->set_block( "category", "no_articles_template", "no_articles" );
            $this->IniObject->set_block( "category", "article_item_template", "article_item" );
            $this->IniObject->set_block( "category", "go_to_parent_template", "go_to_parent" );
            
            $this->IniObject->set_file( array( "article" => "article.tpl" ) );
            $this->IniObject->set_block( "article", "article_item_template", "article_item" );
            $this->IniObject->set_block( "article", "article_image_template", "article_image" );
            
            if( $this->doChildren( $children ) )
            {
                $this->IniObject->set_var( "article_items", $children );
                $this->IniObject->parse( "articles", "articles_template" );
                $this->IniObject->set_var( "article", "" );
                $this->IniObject->set_var( "article_item", "" );
                $this->IniObject->set_var( "no_articles", "" );
            }
            else
            {
                $this->IniObject->set_var( "articles", "" );
                $this->IniObject->parse( "no_articles", "no_articles_template" );
            }
           

            $this->doThis();
            $this->IniObject->setAllStrings();
            $this->IniObject->parse( "this_item", "this_item_template" );
            $outPage = $this->IniObject->parse( "output", "category" );
            $value = true;
        }
        
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
        This function will call upon the correct viewer for the children of this
        object.
        
        \out
            \$outChild  The text string with the child text.
        
        \return
            Returns true if successful.
     */
    function doChildren( &$outChildren )
    {
        $value = false;
        $outChildren = "";
        
        $this->Item->getChildren( &$childrenItems, $count );
        
        $itemType = new eZNewsItemType( "flowerarticle" );
        $changeType = new eZNewsChangeType( "publish" );
        
        $i = 0;

        foreach( $childrenItems as $child )
        {
            $child->get( $outID );

            if( $child->ItemTypeID() == $itemType->ID() && $changeType->ID() == $child->status() )
            {
                $child = new eZNewsFlowerArticle( $child->id() );

                /* snitched from article class */
                /* Why? Because the fsck template functions don't work across objects. */
                
                $oldStory = $child->story();
                $frontImage = $child->getFrontImage();

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
                    $this->IniObject->set_var( "this_image_name", htmlspecialchars( $mainImage->name() ) );
                    $this->IniObject->set_var( "this_image", "/" . htmlspecialchars( $image->imagePath() ) );
                    $this->IniObject->set_var( "this_image_width", $image->width() );
                    $this->IniObject->set_var( "this_image_height", $image->height() );
                    $this->IniObject->set_var( "this_image_caption", "/" . htmlspecialchars( $mainImage->name() ) );
                    $this->IniObject->parse( "article_image", "article_image_template" );
                    $this->IniObject->set_var( "this_picture", $this->IniObject->get_var( "article_image" ) );
                    $this->IniObject->set_var( "image", "" );
                }
                else
                {
                    $this->IniObject->set_var( "image", "" );
                    $this->IniObject->set_var( "this_picture", "" );
                }

                $this->IniObject->set_var( "this_price", $price . "1000" );
                $this->IniObject->set_var( "this_description", $story . "testing" );
                $this->IniObject->set_var( "this_id", $child->id() );
                $this->IniObject->set_var( "this_name", $child->name() );

                $this->IniObject->setAllStrings();
                $this->IniObject->parse( "article_item", "article_item_template", true );
                $outPage = $this->IniObject->parse( "output", "article" );
                /* snitched from article class end */

                #$viewer = new $class( $child, $this->IniObject, $this->URLObject );
                #$value = $viewer->initializeTemplate();
                #$value = $viewer->renderPage( $outPage );
                
            
                $i++;
            }
            
            $this->IniObject->set_var( "article", $outPage );
            $outChildren = $outChildren . $this->IniObject->parse( "article_item", "article_item_template", true );
        }
        
        if( $i > 0 )
        {
            $value = true;
        }
        else
        {
            $this->IniObject->set_var( "article_item", "" );
            $this->IniObject->set_var( "article_image", "" );
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
            
        $publicDescription = new eZNewsArticle( $this->Item->publicDescriptionID() );
        $this->IniObject->set_var( "this_public_description", $publicDescription->Story() );
        
        $privateDescription = new eZNewsArticle( $this->Item->privateDescriptionID() );
        $this->IniObject->set_var( "this_private_description", $privateDescription->Story() );

        $this->IniObject->set_var( "this_id", $this->Item->id() );
        $this->IniObject->set_var( "this_name", $this->Item->name() );

        $itemType = new eZNewsItemType( "flowercategory" );
        
        $thisParent = new eZNewsItem( $this->Item->getIsCanonical() );
        
        if( $thisParent->isCoherent() && $thisParent->itemTypeID() == $itemType->id() )
        {
            $url = $this->IniObject->GlobalIni->read_var( "eZNewsMain", "URL" );
            $this->IniObject->set_var( "this_canonical_parent_id", $thisParent->id() );
            $this->IniObject->set_var( "this_canonical_parent_name", $thisParent->name() );
            $this->IniObject->set_var( "this_path", $url );
            $this->IniObject->parse( "go_to_parent", "go_to_parent_template" );
        }
        else
        {
            $this->IniObject->set_var( "this_canonical_parent_id", "" );
            $this->IniObject->set_var( "this_canonical_parent_name", "" );
            $this->IniObject->set_var( "go_to_parent", "" );
            $this->IniObject->set_var( "this_path", "" );
        }

        return $value;
    }
};

?>
