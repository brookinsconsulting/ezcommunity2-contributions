<?
// 
// $Id: articlelist.php,v 1.39 2001/03/27 09:54:41 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 14:41:37 bf>
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
include_once( "classes/ezlist.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
$UserListLimit = $ini->read_var( "eZArticleMain", "UserListLimit" );


$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "articlelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "articlelist.tpl"
    ) );

$t->set_block( "article_list_page_tpl", "header_item_tpl", "header_item" );

// path
$t->set_block( "article_list_page_tpl", "path_item_tpl", "path_item" );

// article
$t->set_block( "article_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// product
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

$t->set_block( "article_item_tpl", "article_image_tpl", "article_image" );

// prev/next
$t->set_block( "article_list_page_tpl", "previous_tpl", "previous" );
$t->set_block( "article_list_page_tpl", "next_tpl", "next" );

// image dir
$t->set_var( "image_dir", $ImageDir );

$category = new eZArticleCategory( $CategoryID );

$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );

if ( isset( $NoArticleHeader ) and $NoArticleHeader )
{
    $t->set_var( "header_item", "" );
}
else
{
    $t->parse( "header_item", "header_item_tpl" );
}

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    if ( $CapitalizeHeadlines == "enabled" )
    {
        include_once( "classes/eztexttool.php" );
        $t->set_var( "category_name", eZTextTool::capitalize(  $path[1] ) );
    }
    else
    {
        $t->set_var( "category_name", $path[1] );
    }
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList = $category->getByParent( $category );

$user =& eZUser::currentUser();

// categories
$i=0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    if ( eZObjectPermission::hasPermission( $categoryItem->id(), "article_category", 'r' == true ) ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $categoryItem->id() ) )
    {    
        $t->set_var( "category_id", $categoryItem->id() );
        
        $t->set_var( "category_name", $categoryItem->name() );
        
        $parent = $categoryItem->parent();
        
        
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "category_description", $categoryItem->description() );

        $t->parse( "category_item", "category_item_tpl", true );
        $i++;
    }
}

if ( count( $categoryList ) > 0 )
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// set the offset/limit
if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$Limit = $UserListLimit;


if ( $CategoryID == 0 )
{
    // do not set offset for the main page news
    // always sort by publishing date is the merged category
    $article = new eZArticle();
    $articleList = $article->articles( "time", false, $Offset, $Limit );
    $articleCount = $article->articleCount( false );
}
else
{
    $articleList = $category->articles( $category->sortMode(), false, true, $Offset, $Limit );
    $articleCount = $category->articleCount( false, true  );
}

$t->set_var( "category_current_id", $CategoryID );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "article_list", "" );


foreach ( $articleList as $article )
{
    // check if user has permission, if not break to next article.
    $aid = $article->id();
    if( eZObjectPermission::hasPermission( $aid, "article_article", 'r' )  ||
         eZArticle::isAuthor( eZUser::currentUser(), $article->id() ) )
    {
    
        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );

        $t->set_var( "author_text", $article->authorText() );
    
        // preview image
        $thumbnailImage = $article->thumbnailImage();
        if ( $thumbnailImage )
        {
            $variation =& $thumbnailImage->requestImageVariation( $ini->read_var( "eZArticleMain", "ThumbnailImageWidth" ),
            $ini->read_var( "eZArticleMain", "ThumbnailImageHeight" ));
    
            $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
            $t->set_var( "thumbnail_image_width", $variation->width() );
            $t->set_var( "thumbnail_image_height", $variation->height() );
            $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

            $t->parse( "article_image", "article_image_tpl" );
        }
        else
        {
            $t->set_var( "article_image", "" );    
        }
    

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
    

        $renderer = new eZArticleRenderer( $article );

        $t->set_var( "article_intro", $renderer->renderIntro(  ) );

        if ( $article->linkText() != "" )
        {
            $t->set_var( "article_link_text", $article->linkText() );
        }
        else
        {
            $t->set_var( "article_link_text", $DefaultLinkText );
        }

        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $articleCount, $Limit, $Offset, "article_list_page_tpl" );

if ( count( $articleList ) > 0 )    
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );


if ( $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse( $target, "article_list_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "article_list_page_tpl" );
}


?>

