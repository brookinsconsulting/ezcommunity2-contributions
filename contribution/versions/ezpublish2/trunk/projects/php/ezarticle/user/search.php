<?
// 
// $Id: search.php,v 1.1 2000/10/28 15:55:14 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Oct-2000 15:56:58 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "search.tpl"
    ) );


// article
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

$category = new eZArticleCategory( $CategoryID );

$t->set_var( "current_category_id", $category->id() );
$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );


if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// articles
$article = new eZArticle();
$articleList = $article->search( $SearchText );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "article_list", "" );
foreach ( $articleList as $article )
{
    $t->set_var( "article_name", $article->name() );

    $t->set_var( "article_id", $article->id() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $published = $article->published();

    $t->set_var( "article_published", $locale->format( $published ) );    
    
    $t->parse( "article_item", "article_item_tpl", true );
    $i++;
}

if ( count( $articleList ) > 0 )    
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );


$t->pparse( "output", "article_list_page_tpl" );






?>
