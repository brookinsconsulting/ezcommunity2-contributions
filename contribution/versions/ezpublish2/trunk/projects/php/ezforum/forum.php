<?
// 
// $Id: forum.php,v 1.35 2000/10/11 13:37:29 bf-cvs Exp $
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

$msg = new eZForumMessage( $forum_id );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "forum.php" );

$t->set_file( Array( "forum_tpl" => "forum.tpl"
                   )  );

$t->set_block( "forum_tpl", "message_tpl", "message" );

$t->setAllStrings();
$session = new eZSession();

$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$forum = new eZForumForum( $forum_id );

$category = new eZForumCategory( $forum->categoryID()  );

// ELO: remove docroot and add to template.
$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a> ";

$forumPath .= "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "forum.php&forum_id=" . $forum_id . "&category_id=" . $category_id . "\">" . $forum->name() . "</a>";

$t->set_var( "forum_path", $forumPath );

// make to $Action 

// new posting
if ( $post )
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

$messages = $forum->messages();    

foreach ( $messages as $message )
{
    $t->set_var( "topic", $message->topic() );

    $t->set_var( "postingtime", $locale->format( $message->postingTime() ) );

    $user = $message->user();
    
    $t->set_var( "user", $user->firstName() . " " . $user->lastName() );

    $t->parse( "message", "message_tpl", true );
}    
    
$t->set_var( "newmessage", $newmessage);

$t->pparse( "output", "forum_tpl" );

?>
