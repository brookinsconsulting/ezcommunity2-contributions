<?
// 
// $Id: articleedit.php,v 1.36 2001/01/28 12:22:40 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:04:39 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezmail.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlegenerator.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

if ( isset ( $DeleteArticles ) )
{
    $Action = "DeleteArticles";
}

$ini =& $GLOBALS["GlobalSiteIni"];

$PublishNoticeReceiver = $ini->read_var( "eZArticleMain", "PublishNoticeReceiver" );
$PublishNoticeSender = $ini->read_var( "eZArticleMain", "PublishNoticeSender" );

// insert a new article in the database

if ( $Action == "Insert" )
{
    $user = eZUser::currentUser();
        
    $article = new eZArticle( );
    $article->setName( $Name );
    
    $article->setAuthor( $user );

    $generator = new eZArticleGenerator();

    $contents = $generator->generateXML( $Contents );
    $article->setContents( $contents );

    $article->setPageCount( $generator->pageCount() );
    
    $article->setAuthorText( $AuthorText );
    
    $article->setLinkText( $LinkText );
    
    // add check for publishing rights here
    if ( $IsPublished == "on" )
    {
        // send a notice mail
        $noticeMail = new eZMail();

        $noticeMail->setFrom( $PublishNoticeSender );
        $noticeMail->setTo( $PublishNoticeReceiver );
            
        $renderer = new eZArticleRenderer( $article );
        $intro = strip_tags( $renderer->renderIntro( ) );
            
        $noticeMail->setSubject( $article->name() );
        $noticeMail->setBody( $intro );

        $noticeMail->send();                        
        
        $article->setIsPublished( true );
    }
    else
    {
        $article->setIsPublished( false );
    }
    
    // check if the contents is parseable
    if ( xmltree( $contents ) )
    {
        // generate keywords
        $contents = strip_tags( $contents );
        $contents = ereg_replace( "#\n#", "", $contents );
        $contents_array =& split( " ", $contents );
        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            
            $keywords .= $word . " ";
        }

        $article->setKeywords( $keywords );
        
        $article->store();
    

        // add to categories
        $category = new eZArticleCategory( $CategoryID );
        $category->addArticle( $article );

        $article->setCategoryDefinition( $category );

        if ( count( $CategoryArray ) > 0 )
        {
            foreach ( $CategoryArray as $categoryItem )
            {
                if ( $categoryItem != $CategoryID )
                {
                    $category = new eZArticleCategory( $categoryItem );
                    $category->addArticle( $article );
                }
            }
        }


        $articleID = $article->id();

        $categoryArray = $article->categories();
        $categoryIDArray = array();
        foreach ( $categoryArray as $cat )
        {
            $categoryIDArray[] = $cat->id();
        }


        // clear the cache files.
        $dir = dir( "ezarticle/cache/" );
        $files = array();
        while( $entry = $dir->read() )
        { 
            if ( $entry != "." && $entry != ".." )
            { 
                if ( ereg( "articleprint,([^,]+),.*", $entry, $regArray  ) )
                {
                    if ( $regArray[1] == $articleID )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }

                if ( ereg( "articleview,([^,]+),.*", $entry, $regArray  ) )
                {
                    if ( $regArray[1] == $articleID )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }

                if ( ereg( "articlestatic,([^,]+),.*", $entry, $regArray  ) )
                {
                    if ( $regArray[1] == $articleID )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }

                if ( ereg( "articlelist,(.+)\..*", $entry, $regArray  ) )
                {
                    if ( in_array( $regArray[1], $categoryIDArray ) || ( $regArray[1] == 0 ) )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }
            } 
        } 
        $dir->close();
        
        // add images
        if ( isset( $Image ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/imagelist/$articleID/" );
            exit();
        }

        // add files
        if ( isset( $File ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/filelist/$articleID/" );
            exit();
        }
        
        // preview
        if ( isset( $Preview ) )
        {
            eZHTTPTool::header( "Location: /article/articlepreview/$articleID/" );
            exit();
        }


        // get the category to redirect to
        $category = $article->categoryDefinition( );
        $categoryID = $category->id();

        eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
        exit();
    }
    else
    {
        $Action = "New";
        $ErrorParsing = true;
    }
}


if ( $Action == "Cancel" )
{
    $article = new eZArticle( $ArticleID );

    $category = $article->categoryDefinition( );
    $categoryID = $category->id();

    eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
    exit();
}

// update an existing article in the database
if ( $Action == "Update" )
{
    $article = new eZArticle( $ArticleID );
    $article->setName( $Name );

    $oldCategory = $article->categoryDefinition();
    $oldCategoryID = $oldCategory->id();

    $generator = new eZArticleGenerator();

    $contents = $generator->generateXML( $Contents );
    
    $article->setContents( $contents  );

    $article->setPageCount( $generator->pageCount() );
    
    $article->setAuthorText( $AuthorText );
    
    $article->setLinkText( $LinkText );

    
    // add check for publishing rights here
    if ( $IsPublished == "on" )
    {
        // check if the article is published now
        if ( $article->isPublished() == false )
        {
            // send a notice mail
            $noticeMail = new eZMail();

            $noticeMail->setFrom( $PublishNoticeSender );
            $noticeMail->setTo( $PublishNoticeReceiver );
            
            $renderer = new eZArticleRenderer( $article );
            $intro = strip_tags( $renderer->renderIntro( ) );
            
            $noticeMail->setSubject( $article->name() );
            $noticeMail->setBody( $intro );

            $noticeMail->send();                        
        }
        
        $article->setIsPublished( true );
    }
    else
    {
        $article->setIsPublished( false );
    }
        
    // check if the contents is parseable
    if ( xmltree( $contents ) )
    {

        // generate keywords
        $contents = strip_tags( $contents );
        $contents = ereg_replace( "#\n#", "", $contents );
        $contents_array =& split( " ", $contents );
        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            $keywords .= $word . " ";
        }

        $article->setKeywords( $keywords );

        
        $article->store();

        $categoryArray = $article->categories();

        $categoryIDArray = array();
        
        foreach ( $categoryArray as $cat )
        {
            $categoryIDArray[] = $cat->id();
        }

        // clear the cache files.
        $dir = dir( "ezarticle/cache/" );
        $files = array();
        while( $entry = $dir->read() )
        { 
            if ( $entry != "." && $entry != ".." )
            {
                if ( ereg( "articleprint,([^,]+),.*", $entry, $regArray ) )
                {
                    if ( $regArray[1] == $ArticleID )
                    {
                        print( "deleting" );
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }
                
                if ( ereg( "articleview,([^,]+),.*", $entry, $regArray ) )
                {
                    if ( $regArray[1] == $ArticleID )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }

                if ( ereg( "articlestatic,([^,]+),.*", $entry, $regArray  ) )
                {
                    if ( $regArray[1] == $ArticleID )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }

                if ( ereg( "articlelist,(.+)\..*", $entry, $regArray  ) )
                {
                    if ( in_array( $regArray[1], $categoryIDArray ) || ( $regArray[1] == 0 ) )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }
            } 
        } 
        $dir->close();
        
    // remove all category references
        $article->removeFromCategories();

        // add to categories
        $category = new eZArticleCategory( $CategoryID );
        $category->addArticle( $article );

        $article->setCategoryDefinition( $category );

        if ( count( $CategoryArray ) > 0 )
        foreach ( $CategoryArray as $categoryItem )
        {
            if ( $categoryItem != $CategoryID )
            {
                $category = new eZArticleCategory( $categoryItem );
                $category->addArticle( $article );
            }
        }

        // add images
        if ( isset( $Image ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/imagelist/$ArticleID/" );
            exit();
        }

        // add files
        if ( isset( $File ) )
        {
            eZHTTPTool::header( "Location: /article/articleedit/filelist/$ArticleID/" );
            exit();
        }
        

        // preview
        if ( isset( $Preview ) )
        {
            eZHTTPTool::header( "Location: /article/articlepreview/$ArticleID/" );
            exit();
        }

        // get the category to redirect to
        $category = $article->categoryDefinition( );
        $categoryID = $category->id();

        eZHTTPTool::header( "Location: /article/archive/$oldCategoryID/" );
        exit();
    }
    else
    {
        $Action = "Edit";
        $ErrorParsing = true;        
    }
}

if ( $Action == "DeleteArticles" )
{
    if ( count ( $ArticleArrayID ) != 0 )
    {
        foreach( $ArticleArrayID as $ArticleID )
        {
            $article = new eZArticle( $ArticleID );

            // get the category to redirect to
            $articleID = $article->id();

            $categoryArray = $article->categories();
            $categoryIDArray = array();
            foreach ( $categoryArray as $cat )
            {
                $categoryIDArray[] = $cat->id();
            }
    
    
            // clear the cache files.
            $dir = dir( "ezarticle/cache/" );
            $files = array();
            while( $entry = $dir->read() )
            { 
                if ( $entry != "." && $entry != ".." )
                {
                    if ( ereg( "articleprint,([^,]+),.*", $entry, $regArray  ) )
                    {
                        if ( $regArray[1] == $articleID )
                        {
                            unlink( "ezarticle/cache/" . $entry );
                        }
                    }
            
                    if ( ereg( "articleview,([^,]+),.*", $entry, $regArray  ) )
                    {
                        if ( $regArray[1] == $articleID )
                        {
                            unlink( "ezarticle/cache/" . $entry );
                        }
                    }

                    if ( ereg( "articlestatic,([^,]+),.*", $entry, $regArray  ) )
                    {
                        if ( $regArray[1] == $articleID )
                        {
                            unlink( "ezarticle/cache/" . $entry );
                        }
                    }

                    if ( ereg( "articlelist,(.+)\..*", $entry, $regArray  ) )
                    {
                        if ( in_array( $regArray[1], $categoryIDArray ) || ( $regArray[1] == 0 ) )
                        {
                            unlink( "ezarticle/cache/" . $entry );
                        }
                    }
                } 
            } 
            $dir->close();

            $categories = $article->categories();    
            $categoryID = $categories[0]->id();
    
            $article->delete();
        }
        eZHTTPTool::header( "Location: /article/archive/$categoryID/" );
        exit();

    }    
}

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articleedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_edit_page_tpl" => "articleedit.tpl"
    ) );

$t->set_block( "article_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "article_edit_page_tpl", "multiple_value_tpl", "multiple_value" );

$t->set_block( "article_edit_page_tpl", "error_message_tpl", "error_message" );

if ( $ErrorParsing == true )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

$t->set_var( "article_is_published", "" );

$t->set_var( "article_id", "" );
$t->set_var( "article_name", stripslashes( $Name ) );
$t->set_var( "article_contents_0", stripslashes( $Contents[0] ) );
$t->set_var( "article_contents_1", stripslashes($Contents[1] ) );
$t->set_var( "article_contents_2", stripslashes($Contents[2] ) );
$t->set_var( "article_contents_3", stripslashes($Contents[3] ) );
$t->set_var( "author_text", stripslashes($AuthorText ) );
$t->set_var( "link_text", stripslashes($LinkText  ));

$t->set_var( "action_value", "insert" );

if ( $Action == "New" )
{
    $user = eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());    

}


$article = new eZArticle( $ArticleID );

if ( $Action == "Edit" )
{
    $t->set_var( "article_id", $ArticleID );

    if (  $article->isPublished() )
    {
        $t->set_var( "article_is_published", "checked" );
    }
    else
    {
        $t->set_var( "article_is_published", "" );
    }
    
    if ( !isset( $Name ) )        
         $t->set_var( "article_name", $article->name() );

    $generator = new eZArticleGenerator();
    
    $contentsArray = $generator->decodeXML( $article->contents() );
    
    $i=0;
    foreach ( $contentsArray as $content )
    {
        if ( !isset( $Contents[$i] ) )
        {
            $t->set_var( "article_contents_$i", $content );
        }
        $i++;
    }
    
    $t->set_var( "author_text", $article->authorText() );
    $t->set_var( "link_text", $article->linkText() );
    
    $t->set_var( "action_value", "update" );
}

// category select
$category = new eZArticleCategory();
$categoryArray = $category->getAll( );


$tree = new eZArticleCategory();
$treeArray = $tree->getTree();

foreach ( $treeArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        $defCat = $article->categoryDefinition( );
        
        if ( get_class( $defCat ) == "ezarticlecategory" )
        {
            if ( $article->existsInCategory( $catItem[0] ) &&
                ( $defCat->id() != $catItem[0]->id() ) )
            {
                $t->set_var( "multiple_selected", "selected" );
            }
            else
            {
                $t->set_var( "multiple_selected", "" );
            }
        }
        else
        {
            $t->set_var( "selected", "" );
        }
            
        if ( get_class( $defCat ) == "ezarticlecategory" )
        {
            if ( $defCat->id() == $catItem[0]->id() )
            {
                $t->set_var( "selected", "selected" );
            }
            else
            {
                $t->set_var( "selected", "" );
            }
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
        $t->set_var( "multiple_selected", "" );
    }    
        
    
    $t->set_var( "option_value", $catItem[0]->id() );
    $t->set_var( "option_name", $catItem[0]->name() );

    if ( $catItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    
    $t->parse( "value", "value_tpl", true );    
    $t->parse( "multiple_value", "multiple_value_tpl", true );
}

$t->pparse( "output", "article_edit_page_tpl" );

?>
