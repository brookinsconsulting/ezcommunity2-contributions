<?
/*!
    $Id: message.php,v 1.25 2000/10/11 14:17:02 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:54:41 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings

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

// ELO: add to template.

$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a> ";
$forumPath .= "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "forum.php&forum_id=" . $forum_id . "&category_id=" . $category_id . "\">" . $forum->name() . "</a> ";
$forumPath .= "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "message.php&forum_id=" . $forum_id . "&category_id=" . $category_id . "&message_id=" . $message_id . "\">" . $message->topic() . "</a>";

$t->set_var( "forum_path", $forumPath );

$t->set_var( "topic", $message->topic() );

$user = $message->user();

$t->set_var( "user", $user->firstName() . " " . $user->lastName() );

$t->set_var( "postingtime", $message->postingTime() );
$t->set_var( "body", nl2br( $message->body() ) );

$t->set_var( "reply_id", $message_id );
$t->set_var( "forum_id", $forum_id );


//  $top_message = $message->getTopMessage( $message_id );
    
//  $messages = $msg->printHeaderTree( $forum_id, $top_message, 0, $DOC_ROOT, $category_id );

//  $t->set_var( "replies", $messages );

$t->pparse( "output", "message_tpl" );
?>
