<?
/*!
    $Id: message.php,v 1.23 2000/09/07 15:44:44 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:54:41 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezforummessage.php" );
include_once( $DOC_ROOT . "/classes/ezforumcategory.php" );
include_once( $DOC_ROOT . "/classes/ezforumforum.php" );

include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/eztemplate.php" );

$msg = new eZForumMessage;
$usr = new eZUser;
$session = new eZSession;
$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "$DOC_ROOT/templates", "$DOC_ROOT/intl", $Language, "message.php" );
$t->setAllStrings();
    
$t->set_file( array("message" => "message.tpl",
                    "elements" => "message-elements.tpl",
                    "navigation" => "navigation.tpl",
                    "navigation-bottom" => "navigation-bottom.tpl",
                    "login" => "login.tpl",
                    "logout" => "logout.tpl"
                    )
              );

$t->set_var( "docroot", $DOC_ROOT);
$t->set_var( "category_id", $category_id);

$message = new eZForumMessage( );
$message->get( $message_id );
$forum_id = $message->forumID();

$forum = new eZForumForum( );
$forum->get( $forum_id );

$category_id = $forum->categoryID();

$category = new eZForumCategory( );
$category->get( $category_id );
$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a> ";

$forumPath .= "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "forum.php&forum_id=" . $forum_id . "&category_id=" . $category_id . "\">" . $forum->name() . "</a> ";

$forumPath .= "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "message.php&forum_id=" . $forum_id . "&category_id=" . $category_id . "&message_id=" . $message_id . "\">" . $message->topic() . "</a>";

$t->set_var( "forum_path", $forumPath );


if ( $session->get( $AuthenticatedSession ) == 0 )
{
    $user = new eZUser();    
    $t->set_var( "user", $user->resolveUser( $session->UserID() ) );
    $t->parse( "logout-message", "logout", true );
}
else
{
    $UserID = 0;
    $t->set_var( "user", "Anonym" );
    $t->parse( "logout-message", "login", true );
}

$t->parse( "navigation-bar", "navigation", true );

$msg->get( $message_id );
    
$t->set_var( "topic", stripslashes( $msg->topic() ) );
$t->set_var( "user", $usr->resolveUser( $msg->userId() ) );
$t->set_var( "postingtime", $msg->postingTime() );
$t->set_var( "body", nl2br( stripslashes( $msg->body() ) ) );
$t->set_var( "reply_id", $message_id );
$t->set_var( "forum_id", $forum_id );

$top_message = $msg->getTopMessage( $message_id );
    
$messages = $msg->printHeaderTree( $forum_id, $top_message, 0, $DOC_ROOT, $category_id );

$t->set_var( "replies", $messages );

$t->set_var( "link1-url", "main.php" );
$t->set_var( "link1-caption", "Gå til topp" );
$t->set_var( "link2-url", "search.php" );
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output","message");
?>
