<?
// 
// $Id: forum.php,v 1.40 2000/10/12 11:00:29 ce-cvs Exp $
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

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforumforum.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "eZForumMain", "Language" );


$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "forum.php" );

$t->set_file( "forum_tpl", "forum.tpl"  );

$t->set_block( "forum_tpl", "message_tpl", "message" );

$t->setAllStrings();

$forum = new eZForumForum( $forum_id );
$category = new eZForumCategory( $forum->categoryID()  );

$t->set_var( "category_id", $category->id( ) );
$t->set_var( "category_name", $category->name( ) );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );


// make to $Action .. elo!

$msg = new eZForumMessage( $forum_id );

// new posting
if ( $Action == "post" )
{
    $msg->newMessage();
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    $msg->store();
}

// reply
if ( $reply )
{
    $msg->newMessage();    
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    $msg->setParent( $parent );
    
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    
    $msg->store();
}

$locale = new eZLocale( $Language );

$messages = $forum->messageTree( $forum->id(), 0, 2 );

//  $messages = $forum->messages();

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

    $t->parse( "message", "message_tpl", true );
}
    
$t->set_var( "newmessage", $newmessage );

$t->pparse( "output", "forum_tpl" );

?>
