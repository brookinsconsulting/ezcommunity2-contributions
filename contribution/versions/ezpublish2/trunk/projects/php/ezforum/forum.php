<?
/*!
    $Id: forum.php,v 1.34 2000/10/11 11:43:33 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:57:16 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/



include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings

include_once( "common/ezphputils.php" );

include_once( "classes/template.inc" );
include_once( "classes/INIFile.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforumforum.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "eZForumMain", "Language" );

$msg = new eZForumMessage( $forum_id );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "forum.php" );


$t->set_file( Array( "forum_tpl" => "forum.tpl",
                    "preview" => "forum-preview.tpl",
                    "navigation-bottom" => "navigation-bottom.tpl"
                   )  );

$t->set_block( "forum_tpl", "message_tpl", "message" );

$t->setAllStrings();
$session = new eZSession();

$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$forum = new eZForumForum( );
$forum->get( $forum_id );

$category_id = $forum->categoryID();

$category = new eZForumCategory( );
$category->get( $category_id );
$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a> ";

$forumPath .= "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "forum.php&forum_id=" . $forum_id . "&category_id=" . $category_id . "\">" . $forum->name() . "</a>";

$t->set_var( "forum_path", $forumPath );


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

// preview message
if ( $preview )
{
    $t->set_var( "topic", $Topic );
    $t->set_var( "body", nl2br( $Body ) );
    $t->set_var( "body-clean", $Body );
    $t->set_var( "userid", $UserID );
    $t->pparse( "output", "preview" );
}
else
{
//      $messages = $msg->printHeaderTree( $forum_id, 0, 0, $category_id, $t );

    
    
    $t->set_var( "messages", $messages );
    
    $t->set_var( "newmessage", $newmessage);

    $t->set_var( "link1-url", "newmessage.php" );
    $t->set_var( "link2-url", "search.php" );

    $t->set_var( "back-url", "category.php");
    $t->parse( "navigation-bar-bottom", "navigation-bottom", true);

    $t->pparse( "output", "forum_tpl" );
}

?>
