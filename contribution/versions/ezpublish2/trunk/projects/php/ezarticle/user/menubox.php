<?
// 
// $Id: menubox.php,v 1.2 2000/10/24 15:03:29 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <17-Oct-2000 12:16:07 bf>
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
$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );


$PageCaching = $ini->read_var( "eZArticleMain", "PageCaching");

// do the caching 
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "ezarticle/cache/menubox.cache";
                    
    if ( file_exists( $cachedFile ) )
    {
        include( $cachedFile );
    }
    else
    {
        $GenerateStaticPage = "true";
        createArticleMenu();
    }            
}
else
{
    createArticleMenu();
}

function createArticleMenu()
{
    global $ini;
    global $Language;
    global $menuCachedFile;
    
    include_once( "classes/eztemplate.php" );
    include_once( "common/ezphputils.php" );

    include_once( "ezarticle/classes/ezarticlecategory.php" );
    include_once( "ezarticle/classes/ezarticle.php" );

    $t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                         "ezarticle/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "article_category_tpl", "article_category" );

// Lister alle kategorier
    
    $articleCategory = new eZArticleCategory( 0 );

    $articleCategory_array = $articleCategory->getByParent( $articleCategory );

    if ( count( $articleCategory_array ) == 0 )
    {
        $t->set_var( "category_list", "" );
    }
    else
    {
        foreach( $articleCategory_array as $categoryItem )
        {
            $article_category_id = $categoryItem->id();
            $t->set_var( "articlecategory_id", $article_category_id );
            $t->set_var( "articlecategory_title", $categoryItem->name() );
            
            $t->parse( "article_category", "article_category_tpl", true );
            
        }
    }
    $t->set_var( "articlecategory_id", $LGID );
                       
//      $t->pparse( "output", "article_category_list" );

    if ( $GenerateStaticPage == "true" )
    {
        $fp = fopen ( $menuCachedFile, "w+");

        $output = $t->parse( $target, "menu_box_tpl" );
        // print the output the first time while printing the cache file.

        print( $output );
        fwrite ( $fp, $output );
        fclose( $fp );
    }
    else
    {
        $t->pparse( "output", "menu_box_tpl" );
    }
    
}

?>
