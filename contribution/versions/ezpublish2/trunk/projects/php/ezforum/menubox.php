<?
// 
// $Id: menubox.php,v 1.5 2000/10/17 11:52:58 bf-cvs Exp $
//
// 
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );


$PageCaching = $ini->read_var( "eZForumMain", "PageCaching");

// do the caching 
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "ezforum/cache/menubox.cache";
                    
    if ( file_exists( $cachedFile ) )
    {
        include( $cachedFile );
    }
    else
    {
        $GenerateStaticPage = "true";
        createForumMenu();
    }            
}
else
{
    createForumMenu();
}

function createForumMenu()
{
    include_once( "classes/eztemplate.php" );
    include_once( "classes/ezdb.php" );
    include_once( "ezforum/classes/ezforumcategory.php" );

    $t = new Template( "." );

    $t->set_file( Array( "categorylist_tpl" => "ezforum/templates/standard/categorymenu.tpl" ) );

    $t->set_block( "categorylist_tpl", "category_tpl", "category" );

    $category = new eZForumCategory();
    $categories = $category->getAllCategories();

    foreach( $categories as $category )
    {
        $t->set_var("id", $category->id() );
        $t->set_var("name", $category->name() );
        
        $t->parse( "category", "category_tpl", true);
    }


    if ( $GenerateStaticPage == "true" )
    {
        $fp = fopen ( $menuCachedFile, "w+");

        $output = $t->parse( $target, "categorylist_tpl" );
        // print the output the first time while printing the cache file.
    
        print( $output );
        fwrite ( $fp, $output );
        fclose( $fp );
    }
    else
    {
        $t->pparse( "output", "categorylist_tpl" );
    }
}

?>
