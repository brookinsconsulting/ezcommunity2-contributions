<?
// 
// $Id: message.php,v 1.27 2000/10/11 16:47:49 bf-cvs Exp $
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

$ini = new INIFile( "site.ini" ); // get language settings

include_once( "classes/ezlocale.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforumforum.php" );


$ini = new INIFile( "site.ini" ); // get language settings

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "message.php" );

    
$t->set_file( "message_tpl", "message.tpl"  );

$t->set_block( "message_tpl", "reply_tpl", "reply" );

$t->setAllStrings();

$t->set_var( "category_id", $category_id);

$message = new eZForumMessage( $message_id );
$forum = new eZForumForum( $message->forumID() );

$category_id = $forum->categoryID();

$category = new eZForumCategory( );
$category->get( $category_id );

$t->set_var( "category_id", $category->id( ) );
$t->set_var( "category_name", $category->name( ) );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

$t->set_var( "message_id", $message->id() );
$t->set_var( "message_topic", $message->topic() );

$t->set_var( "topic", $message->topic() );

$user = $message->user();

$t->set_var( "user", $user->firstName() . " " . $user->lastName() );

$t->set_var( "postingtime", $message->postingTime() );
$t->set_var( "body", nl2br( $message->body() ) );

$t->set_var( "reply_id", $message_id );
$t->set_var( "forum_id", $forum->id() );


// print out the replies tree
$messages = $forum->messageTree( $forum->id(), 0, 2 );

//  $messages = $forum->messages();

$locale = new eZLocale( $Language );

$level = 0;
foreach ( $messages as $message )
{
    $level = $message->level();
    
    if ( $level > 0 )
        $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
    else
        $t->set_var( "spacer", "" );
    
    $t->set_var( "topic", $message->topic() );

    $t->set_var( "postingtime", $locale->format( $message->postingTime() ) );

    $t->set_var( "message_id", $message->id() );

    $user = $message->user();
    
    $t->set_var( "user", $user->firstName() . " " . $user->lastName() );

    $t->parse( "reply", "reply_tpl", true );
}

$t->pparse( "output", "message_tpl" );
?>
