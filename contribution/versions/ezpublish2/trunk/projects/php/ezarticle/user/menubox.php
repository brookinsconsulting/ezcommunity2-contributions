<?php
// 
// $Id: menubox.php,v 1.24 2001/09/21 14:28:48 jhe Exp $
//
// 
//
// Created on: <17-Oct-2000 12:16:07 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "classes/ezcachefile.php" );

$ini =& INIFile::globalINI();

include_once( "ezuser/classes/ezobjectpermission.php" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

$PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );


// do the caching 
if ( $PageCaching == "enabled" )
{
    $user =& eZUser::currentUser();
    $groupstr = "";
    if( get_class( $user ) == "ezuser" )
    {
        $groupIDArray = $user->groups( false );
        sort( $groupIDArray );
        $first = true;
        foreach( $groupIDArray as $groupID )
        {
            $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
            $first = false;
        }
    }
    
//    $menuCachedFile = "ezarticle/cache/menubox," . $groupstr . ",". $GlobalSiteDesign . "," . $CategoryID. ".cache";

    $menuCacheFile = new eZCacheFile( "ezarticle/cache",
                                      array( "menubox",
                                             $groupstr,
                                             $GlobalSiteDesign,
                                             $CategoryID
                                             ),
                                      "cache", "," );

    if ( $menuCacheFile->exists() )
    {
        print( $menuCacheFile->contents() );
    }
    else
    {
        createArticleMenu( $menuCacheFile );
    }
}
else
{
    createArticleMenu();
}

function createArticleMenu( $menuCachedFile=false )
{
    global $ini;
    global $Language;
    global $menuCachedFile;
    global $GenerateStaticPage;
	global $GlobalSiteDesign;
	global $CategoryID;

        
    include_once( "classes/eztemplate.php" );

    include_once( "ezarticle/classes/ezarticlecategory.php" );
    include_once( "ezarticle/classes/ezarticle.php" );

    $t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                         "ezarticle/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "article_category_tpl", "article_category" );
    $t->set_block( "menu_box_tpl", "submit_article_tpl", "submit_article" );

    $t->set_var( "submit_article", "" );

    $t->set_var( "sitedesign", $GlobalSiteDesign );

    // Lister alle kategorier

    if ( !isset( $CategoryID  ) )
        $CategoryID = 0;
         
    $articleCategory = new eZArticleCategory( $CategoryID );

    $articleCategory_array = $articleCategory->getByParent( $articleCategory );

    $t->set_var( "top_title", $articleCategory->name() );

    $i = 0;
    foreach( $articleCategory_array as $categoryItem )
    {
        if( eZObjectPermission::hasPermission( $categoryItem->id(), "article_category", 'r' ) )
        {
            $t->set_var( "articlecategory_id", $categoryItem->id()  );
            $t->set_var( "articlecategory_title", $categoryItem->name() );

            $t->parse( "article_category", "article_category_tpl", true );
            $i++;
        }
    }

    if( $i == 0 )
        $t->set_var( "article_category", "" );


    // user-submitted articles
    include_once( "ezuser/classes/ezuser.php" );

    if ( eZUser::currentUser() != false &&
         $ini->read_var( "eZArticleMain", "UserSubmitArticles" ) == "enabled" )
    {
        $t->parse( "submit_article", "submit_article_tpl", true );
    }



    if ( isset( $menuCacheFile ) and get_class( $menuCacheFile ) == "ezcachefile" )
    {
        $output = $t->parse( $target, "menu_box_tpl" );
        $menuCacheFile->store( $output );
        print( $output );
    }
    else
    {
		$t->pparse( "output", "menu_box_tpl" );
    }
    
}

?>
