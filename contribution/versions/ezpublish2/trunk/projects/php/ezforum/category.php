<?
// 
// $Id: category.php,v 1.37 2000/10/17 14:16:49 ce-cvs Exp $
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

include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "category.php" );

$t->setAllStrings();

$t->set_file( "category_tpl", "category.tpl" );

$t->set_block( "category_tpl", "forum_tpl", "forum" );

$category = new eZForumCategory( $category_id );

$t->set_var( "category_id", $category->id( ) );
$t->set_var( "category_name", $category->name( ) );

$forums = $category->forums( );

if ( !$forums )
{
    $ini = new INIFile( "ezforum/intl/" . $Language . "/forum.php.ini", false );
    $noitem =  $ini->read_var( "strings", "noitem" );

    $t->set_var( "forum", $noitem );
}

$i=0;
foreach( $forums as $forum )
{
    $t->set_var( "forum_id", $forum->id() );

    $t->set_var( "name", $forum->name() );    
    $t->set_var( "description", $forum->description() );

    $t->set_var( "threads", $forum->threadCount() );    
    $t->set_var( "messages", $forum->messageCount() );    
    

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
