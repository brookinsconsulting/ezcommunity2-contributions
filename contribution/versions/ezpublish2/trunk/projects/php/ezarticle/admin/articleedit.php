<?
// 
// $Id: articleedit.php,v 1.18 2000/11/01 06:58:23 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:04:39 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlegenerator.php" );

if ( $Action == "Insert" )
{
    $user = eZUser::currentUser();
    $category = new eZArticleCategory( $CategoryID );
        
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
    
        $category->addArticle( $article );

        $articleID = $article->id();


        // clear the cache files.
        $dir = dir( "ezarticle/cache/" );
        $files = array();
        while( $entry = $dir->read() )
        { 
            if ( $entry != "." && $entry != ".." )
            { 
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
                    if ( $regArray[1] == $CategoryID )
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
            Header( "Location: /article/articleedit/imagelist/$articleID/" );
            exit();
        }

        // preview
        if ( isset( $Preview ) )
        {
            Header( "Location: /article/articlepreview/$articleID/" );
            exit();
        }


        // get the category to redirect to
        $categories = $article->categories();    
        $categoryID = $categories[0]->id();

    
        Header( "Location: /article/archive/$categoryID/" );
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

    $categories = $article->categories();

    $categoryID = $categories[0]->id();

    Header( "Location: /article/archive/$categoryID/" );
    exit();
}


if ( $Action == "Update" )
{
    $category = new eZArticleCategory( $CategoryID );
    
    $article = new eZArticle( $ArticleID );
    $article->setName( $Name );

    $generator = new eZArticleGenerator();

    $contents = $generator->generateXML( $Contents );
    
    $article->setContents( $contents  );

    $article->setPageCount( $generator->pageCount() );
    
    $article->setAuthorText( $AuthorText );
    
    $article->setLinkText( $LinkText );

    
    // add check for publishing rights here
    if ( $IsPublished == "on" )
    {
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

        // clear the cache files.
        $dir = dir( "ezarticle/cache/" );
        $files = array();
        while( $entry = $dir->read() )
        { 
            if ( $entry != "." && $entry != ".." )
            { 
                if ( ereg( "articleview,([^,]+),.*", $entry, $regArray  ) )
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
                    if ( $regArray[1] == $CategoryID )
                    {
                        unlink( "ezarticle/cache/" . $entry );
                    }
                }
            } 
        } 
        $dir->close();
        
    // remove all category references
        $article->removeFromCategories();
        $category->addArticle( $article );

    // add images
        if ( isset( $Image ) )
        {
            Header( "Location: /article/articleedit/imagelist/$ArticleID/" );
            exit();
        }

        // preview
        if ( isset( $Preview ) )
        {
            Header( "Location: /article/articlepreview/$ArticleID/" );
            exit();
        }

        // get the category to redirect to
        $categories = $article->categories();    
        $categoryID = $categories[0]->id();
    
        Header( "Location: /article/archive/$categoryID/" );
        exit();
    }
    else
    {
        $Action = "Edit";
        $ErrorParsing = true;        
    }
}


if ( $Action == "Delete" )
{
    $article = new eZArticle( $ArticleID );


    // get the category to redirect to
    $categories = $article->categories();    
    $categoryID = $categories[0]->id();

    $articleID = $article->id();
    
    // clear the cache files.
    $dir = dir( "ezarticle/cache/" );
    $files = array();
    while( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        { 
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
                if ( $regArray[1] == $categoryID )
                {
                    unlink( "ezarticle/cache/" . $entry );
                }
            }
        } 
    } 
    $dir->close();

    
    $article->delete();    
    
    Header( "Location: /article/archive/$categoryID/" );
    exit();
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articleedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_edit_page_tpl" => "articleedit.tpl"
    ) );



$t->set_block( "article_edit_page_tpl", "value_tpl", "value" );

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

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        if ( $article->existsInCategory( $catItem ) )
        {
            $t->set_var( "selected", "selected" );
        }
        else
            $t->set_var( "selected", "" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }    
        
    
    $t->set_var( "option_value", $catItem->id() );
    $t->set_var( "option_name", $catItem->name() );

    $t->parse( "value", "value_tpl", true );    
}


$t->pparse( "output", "article_edit_page_tpl" );

?>
