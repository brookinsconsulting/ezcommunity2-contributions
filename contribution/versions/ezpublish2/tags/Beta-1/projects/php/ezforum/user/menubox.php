<?
// 
// $Id: menubox.php,v 1.4 2000/10/23 08:05:18 bf-cvs Exp $
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

//  $ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$PageCaching = $ini->read_var( "eZForumMain", "PageCaching" );

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "ezforum/cache/menubox.cache";

    if ( file_exists( $menuCachedFile ) )
    {
        include( $menuCachedFile );
    }
    else
    {
        $GenerateStaticPage = true;
        createPage();
    }            
}
else
{
    $GenerateStaticPage = false;    
    createPage();
}

function createPage()
{
    global $GenerateStaticPage;
    global $menuCachedFile;
    global $ini;
    global $Language;
    
    include_once( "classes/eztemplate.php" );
    include_once( "classes/ezdb.php" );
    include_once( "ezforum/classes/ezforumcategory.php" );

    $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                         "ezforum/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );
    
//      $t = new Template( "." );

//      $t->set_file( Array( "categorylist_tpl" => "ezforum/user/templates/standard/categorymenu.tpl" ) );

    $t->set_block( "menu_box_tpl", "category_tpl", "category" );

    $category = new eZForumCategory();
    $categories = $category->getAllCategories();

    if ( !$categories )
    {
        $ini = new INIFile( "site.ini" );
        $Language = $ini->read_var( "eZForumMain", "Language" );
        $nofound = new INIFile( "ezforum/user/intl/" . $Language . "/categorylist.php.ini", false );
        $noitem =  $nofound->read_var( "strings", "noitem" );
        
        $t->set_var( "category", $noitem );
    }
    else
    {
        foreach( $categories as $category )
        {
            $t->set_var("id", $category->id() );
            $t->set_var("name", $category->name() );
        
            $t->parse( "category", "category_tpl", true);
        }
    }


    if ( $GenerateStaticPage == true )
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
