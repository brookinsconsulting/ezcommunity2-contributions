<?
// 
// $Id: category.php,v 1.33 2000/10/11 14:17:02 bf-cvs Exp $
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

include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforumforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "category.php" );

$t->setAllStrings();

$t->set_file( "category_tpl", "category.tpl" );

$t->set_block( "category_tpl", "forum_tpl", "forum" );

$category = new eZForumCategory( $category_id );

print( $category->name( ) );

// add to template..
$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a>";

$t->set_var( "forum_path", $forumPath );

$forums = $category->forums( );

$i=0;
foreach( $forums as $forum )
{
    $t->set_var( "forum_id", $forum->id() );

    $t->set_var( "name", $forum->name() );    
    $t->set_var( "description", $forum->description() );    

    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight"  );
    else
        $t->set_var( "td_class", "bgdark"  );

    $t->parse( "forum", "forum_tpl", true );

    $i++;
}

$t->set_var( "category_id", $category_id );

$t->pparse( "output", "category_tpl" );

?>
