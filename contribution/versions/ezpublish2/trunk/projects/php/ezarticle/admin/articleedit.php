<?
// 
// $Id: articleedit.php,v 1.6 2000/10/20 13:31:24 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:04:39 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
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
    $category = new eZArticleCategory( $CategoryID );
        
    $article = new eZArticle( );
    $article->setName( $Name );

    $generator = new eZArticleGenerator();
    
    $article->setContents( $generator->generateXML( $Contents ) );
    
    $article->setAuthorText( $AuthorText );
    
    $article->setLinkText( $LinkText );

//      print( htmlspecialchars( $article->contents() ) );
    
    $article->store();
    
    $category->addArticle( $article );

    $articleID = $article->id();

    // add images
    if ( isset( $Image ) )
    {
        Header( "Location: /article/articleedit/imagelist/$articleID/" );
        exit();
    }
    
    Header( "Location: /article/archive/$CategoryID/" );
    exit();
}

if ( $Action == "Update" )
{
    $category = new eZArticleCategory( $CategoryID );
    
    $article = new eZArticle( $ArticleID );
    $article->setName( $Name );

    $generator = new eZArticleGenerator();
    
    $article->setContents( $generator->generateXML( $Contents ) );
    
    $article->setAuthorText( $AuthorText );
    
    $article->setLinkText( $LinkText );

    $article->store();

    // remove all category references
    $article->removeFromCategories();
    $category->addArticle( $article );

    // add images
    if ( isset( $Image ) )
    {
        Header( "Location: /article/articleedit/imagelist/$ArticleID/" );
        exit();
    }
    
    
    Header( "Location: /article/archive/$CategoryID/" );
    exit();
}


if ( $Action == "Delete" )
{
    $article = new eZArticle( $ArticleID );
    $article->delete();    

    Header( "Location: /article/archive/" );
    exit();
}

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articleedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_edit_page_tpl" => "articleedit.tpl"
    ) );

$t->set_block( "article_edit_page_tpl", "value_tpl", "value" );

$t->set_var( "article_id", "" );
$t->set_var( "article_name", "" );
$t->set_var( "article_contents_0", "" );
$t->set_var( "article_contents_1", "" );
$t->set_var( "article_contents_2", "" );
$t->set_var( "article_contents_3", "" );
$t->set_var( "author_text", "" );
$t->set_var( "link_text", "" );

$t->set_var( "action_value", "insert" );

if ( $Action == "New" )
{
    $user = eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());    

}

if ( $Action == "Edit" )
{
    print( "Editing aritcle" . $ArticleID );

    $article = new eZArticle( $ArticleID );

    $t->set_var( "article_id", $ArticleID );
    $t->set_var( "article_name", $article->name() );

    $generator = new eZArticleGenerator();
    
    $contentsArray = $generator->decodeXML( $article->contents() );
    
    $i=0;
    foreach ( $contentsArray as $content )
    {
        $t->set_var( "article_contents_$i", $content );
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
            $t->set_var( "selected", "selected" );
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
