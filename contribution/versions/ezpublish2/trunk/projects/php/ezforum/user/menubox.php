<?
// 
// $Id: menubox.php,v 1.9 2001/04/11 14:18:41 th Exp $
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );

$PageCaching = $ini->read_var( "eZForumMain", "PageCaching" );

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "ezforum/cache/menubox," . $groupstr . ",". $GlobalSiteDesign .".cache";

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
   	global $GlobalSiteDesign;

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
        if ( isset( $GLOBALS["SiteIni"] ) )
        {
            $ini =& $GLOBALS["SiteIni"];
        }
        else
        {
            $ini = new INIFile( "site.ini" );
        }
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
        $t->set_var( "sitedesign", $GlobalSiteDesign );
		
		$t->pparse( "output", "menu_box_tpl" );
    }
    
}

?>
