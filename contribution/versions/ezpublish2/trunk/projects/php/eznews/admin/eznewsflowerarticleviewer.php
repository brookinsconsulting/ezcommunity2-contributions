<?php
// 
// $Id: eznewsflowerarticleviewer.php,v 1.1 2000/10/14 01:40:51 pkej-cvs Exp $
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
        This function renders the view page.
        
        \out
            \$outPage   The text string with the page info
        \return
            Returns true if successful.
     */
    function viewPage( &$outPage )
    {
        #echo "eZNewsFlowerArticleViewer::viewPage( \$outPage = $outPage )<br />\n";
        $value = true;
        
        $this->IniObject->readAdminTemplate( "eznewsflower/article", "view.php" );
        
        $this->IniObject->set_file( array( "article" => "view.tpl" ) );
        $this->IniObject->set_block( "article", "article_here_template", "article_here" );
        $this->IniObject->set_block( "article", "article_item_template", "article_item" );
        $this->IniObject->set_block( "article", "article_image_template", "article_image" );
        $this->IniObject->set_block( "article", "go_to_parent_template", "go_to_parent" );
        $this->IniObject->set_block( "article", "go_to_self_template", "go_to_self" );
        $this->IniObject->set_block( "article", "upload_picture_template", "upload_picture" );
        $this->IniObject->set_block( "article", "picture_uploaded_template", "picture_uploaded" );
        $this->IniObject->set_block( "article", "picture_template", "picture" );
        $this->Item = new eZNewsFlowerArticle( $this->Item->id() );
        
        $this->doThis();
        $this->IniObject->setAllStrings();
        $this->IniObject->parse( "article_item", "article_item_template" );
        $this->IniObject->parse( "article_here", "article_here_template" );
        $outPage = $this->IniObject->parse( "output", "article" );
            
        return $value;
    }
    
    
    
    /*!
        This function renders the edit pages.
        
        \out
            \$outPage   The text string with the page info
        \return
            Returns true if successful.
     */
    function editPage( &$outPage )
    {
        #echo "eZNewsFlowerArticleViewer::editPage( \$outPage = $outPage )<br />\n";
        $value = false;

        global $form_preview;
        
        #echo "\$form_preview = $form_preview<br />\n";
        
        $this->IniObject->readAdminTemplate( "eznewsflower/article", "view.php" );

        if( !empty( $form_preview ) )
        {
            // OK, we need to store the changes.
            global $Story;
            global $Price;
            global $Name;
            global $ImageID;
            global $ParentID;
            
            $this->IniObject->set_file( array( "article" => "preview.tpl" ) );
            $this->IniObject->set_block( "article", "go_to_parent_template", "go_to_parent" );
            $this->IniObject->set_block( "article", "go_to_self_template", "go_to_self" );
            $this->IniObject->set_block( "article", "upload_picture_template", "upload_picture" );
            $this->IniObject->set_block( "article", "picture_uploaded_template", "picture_uploaded" );
            $this->IniObject->set_block( "article", "picture_template", "picture" );
            $this->IniObject->set_block( "article", "article_image_template", "article_image" );

            $newStory = "<?xml version=\"1.0\"?>\n";
            $newStory = $newStory . "<ezflower>\n";
            $newStory = $newStory . "<name>\n";
            $newStory = $newStory . htmlspecialchars( trim( $Name ) ) . "\n";
            $newStory = $newStory . "</name>\n";
            $newStory = $newStory . "<description>\n";
            $newStory = $newStory . htmlspecialchars( trim( $Story ) ) . "\n";
            $newStory = $newStory . "</description>\n";
            $newStory = $newStory . "<price>\n";
            $newStory = $newStory . htmlspecialchars( trim( $Price ) ) . "\n";
            $newStory = $newStory . "</price>\n";
            $newStory = $newStory . "</ezflower>\n";

            $this->Item->setParent( $ParentID, true );
            $this->Item->setStory( $newStory );

            $file = new eZImageFile();

            if( $file->getUploadedFile( 'Image' ) )
            {
                $Picture = $file->name();

                $image = new eZImage();
                $image->setName( $file );
                $image->setCaption( "Bilde til artikkel: " . $this->Item->name() );

                $image->setImage( $file );

                $image->store();
                $id = $image->ID();
                $PictureID = $id;
            }

            $this->Item->setImage( $PictureID, true );
            $this->Item->store( $outID );
            $this->doThis();
        }
        else
        {
            $this->IniObject->set_file( array( "article" => "edit.tpl" ) );
            $this->IniObject->set_block( "article", "go_to_parent_template", "go_to_parent" );
            $this->IniObject->set_block( "article", "go_to_self_template", "go_to_self" );
            $this->IniObject->set_block( "article", "item_template", "item" );
            $this->IniObject->set_block( "article", "upload_picture_template", "upload_picture" );
            $this->IniObject->set_block( "article", "picture_uploaded_template", "picture_uploaded" );
            $this->IniObject->set_block( "article", "picture_template", "picture" );
            $this->IniObject->set_block( "article", "article_image_template", "article_image" );
            $this->fillInCategories();
            $this->doThis( true );
        }
        

        $this->IniObject->setAllStrings();
        $outPage = $this->IniObject->parse( "output", "article" );
        $value = true;

        $value = true;
            
        return $value;
    }
    
    
    
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

        global $form_abort;
        global $form_submit;
        
        $this->Item = new eZNewsFlowerArticle( $this->Item->id() );

        if( $this->URLObject->getQueries( $queries, "edit\+this" ) && empty( $form_abort ) && empty( $form_submit ) )
        {
            $value = $this->editPage( $outPage );
        }
        else
        {
            $value = $this->viewPage( $outPage );
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
        This function will fill in the information about this category.
        
        \return
            Returns true if successful.
     */
    function doThis( $inEdit = false )
    {
        $value = true;
        
        $oldStory = $this->Item->story();
        $frontImage = $this->Item->getFrontImage();

        if( $inEdit == true )
        {
            ereg( "<price>(.+)</price>" , $oldStory, $regs );
            $price = $regs[1];
            ereg( "<name>(.+)</name>" , $oldStory, $regs );
            $name = $regs[1];
            ereg( "<description>(.+)</description>" , $oldStory, $regs );
            $story = $regs[1];
        }
        else
        {
            ereg( "<price>(.+)</price>" , $oldStory, $regs );
            $price = nl2br( htmlspecialchars( $regs[1] ) );
            ereg( "<name>(.+)</name>" , $oldStory, $regs );
            $name = nl2br( htmlspecialchars( $regs[1] ) );
            ereg( "<description>(.+)</description>" , $oldStory, $regs );
            $story = nl2br( htmlspecialchars( $regs[1] ) );
        }
        
        if( $frontImage )
        {
            $mainImage = new eZImage( $this->Item->getFrontImage(), 0 );

            $image = $mainImage->requestImageVariation( 250, 250 );

            $this->IniObject->set_var( "this_image_id", $mainImage->id() );
            $this->IniObject->set_var( "this_image_name", htmlspecialchars( $mainImage->name() ) );
            $this->IniObject->set_var( "this_image", "/" . $image->imagePath() );
            $this->IniObject->set_var( "this_image_width", $image->width() );
            $this->IniObject->set_var( "this_image_height", $image->height() );
            $this->IniObject->set_var( "this_image_caption", $mainImage->caption() );
            $this->IniObject->parse( "article_image", "article_image_template" );
            $this->IniObject->set_var( "this_picture", $this->IniObject->get_var( "article_image" ) );
            $this->IniObject->set_var( "image", "" );
            $this->IniObject->set_var( "article_image", "" );
            $this->IniObject->set_var( "upload_picture", "" );
            $this->IniObject->parse( "picture_uploaded", "picture_uploaded_template" );
        }
        else
        {
            $this->IniObject->set_var( "image", "" );
            $this->IniObject->set_var( "this_picture", "" );
            $this->IniObject->set_var( "picture_uploaded", "" );
            $this->IniObject->parse( "upload_picture", "upload_picture_template" );
            $this->IniObject->set_var( "article_image", "" );
        }

        $this->IniObject->set_var( "this_price", $price );
        $this->IniObject->set_var( "this_name", $name );
        $this->IniObject->set_var( "this_description", $story );

        $this->IniObject->set_var( "this_id", $this->Item->id() );
        $this->IniObject->set_var( "this_name", $this->Item->name() );
        $this->IniObject->set_var( "this_createdat", $this->Item->createdAtLocal( $this->IniObject->Language ) );

        $itemType = new eZNewsItemType( "flowercategory" );
        
        $thisParent = new eZNewsItem( $this->Item->getIsCanonical() );
        
        $url = $this->IniObject->GlobalIni->read_var( "eZNewsMain", "URL" );
        $this->IniObject->set_var( "this_path", $url );

        if( $thisParent->isCoherent() && $thisParent->itemTypeID() == $itemType->id() )
        {
            $this->IniObject->set_var( "this_canonical_parent_id", $thisParent->id() );
            $this->IniObject->set_var( "this_canonical_parent_name", $thisParent->name() );
            $this->IniObject->parse( "go_to_parent", "go_to_parent_template" );
            $this->IniObject->set_var( "go_to_self", "" );
        }
        else
        {
            $this->IniObject->set_var( "this_canonical_parent_id", "" );
            $this->IniObject->set_var( "this_canonical_parent_name", "" );
            $this->IniObject->set_var( "go_to_parent", "" );
            $this->IniObject->parse( "go_to_self", "go_to_self_template" );
        }
        return $value;
    }
    
    
    
    /*!
        Fill in the categories we can use for this article.
     */
    function fillInCategories()
    {
        #echo "eZNewsFlowerArticleCreator::fillInCategories()<br />\n";

        $name = $this->IniObject->GlobalIni->read_var( "eZNewsCustomer", "Name" );
        $itemType = new eZNewsItemType( "flowercategory" );
        $changeType = new eZNewsChangeType( "publish" );
        
        $categories = new eZNewsItem( $name );
        $categories->getChildren( &$childrenItems, $count );

        $canonicalParent = $this->Item->getIsCanonical();

        $i = 0;
        foreach( $childrenItems as $child )
        {
            $child->get( $outID );
            if( $child->ItemTypeID() == $itemType->ID() && $changeType->ID() == $child->status() )
            {
                $this->IniObject->set_var( "item_id", $child->ID() );
                $this->IniObject->set_var( "item_name", $child->name() );
                $this->IniObject->set_var( "Selected", "" );
                if( $i == 0 && empty( $canonicalParent ) )
                {
                    $this->IniObject->set_var( "Selected", "selected" );
                }

                if( $child->ID() == $canonicalParent || $child->Name() == $canonicalParent )             
                {
                    $this->IniObject->set_var( "Selected", "selected" );
                }
                
                $this->IniObject->parse( "item", "item_template", true );
                
                $i++;
            }
        }
    }
};

?>
