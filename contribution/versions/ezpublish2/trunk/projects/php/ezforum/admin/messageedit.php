<?
/*!
    $Id: messageedit.php,v 1.3 2000/10/20 13:31:31 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

if ( $Action == "insert" )
{
}
if ( $Action == "update" )
{
    $msg = new eZForumMessage();
    $msg->get( $MessageID );
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );

    if ( $notice )
        $msg->enableEmailNotice();
    else 
        $msg->disableEmailNotice();

    $ForumID = $msg->forumID();
    $forum = new eZForum( $ForumID );
    $CategoryID = $forum->categoryID();
    $msg->store();
    Header( "Location: /forum/messagelist/$ForumID/" );
    exit();
}

if ( $Action == "delete" )
{
    $msg = new eZForumMessage();
    $msg->get( $MessageID );
    $msg->delete();

    $ForumID = $msg->forumID();
    $forum = new eZForum( $ForumID );
    $CategoryID = $forum->categoryID();

    Header( "Location: /forum/messagelist/$ForumID" );
    exit();
}

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
"ezforum/admin/" . "/intl", $Language, "messageedit.php" );
$t->setAllStrings();

$t->set_file( Array( "message_page" => "messageedit.tpl" ) );

$ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/messageedit.php.ini", false );
$headline =  $ini->read_var( "strings", "head_line_insert" );

$t->set_block( "message_page", "message_edit_tpl", "message_edit" );
$locale = new eZLocale( $Language );

if ( $Action == "edit" )
{
    $msg = new eZForumMessage();
    $msg->get( $MessageID );
    $t->set_var( "message_topic", $msg->topic() );
    $t->set_var( "message_postingtime", $locale->format( $msg->postingTime() ) );
    $t->set_var( "message_body", $msg->body() );
    $user = $msg->user();
    $t->set_var( "message_user", $user->firstName() . " " . $user->lastName() );
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/messageedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );
    $t->set_var( "action_value", "update" );
    $t->set_var( "message_id", $MessageID );
    $t->set_var( "forum_id", $msg->forumID() );
}
$t->set_var( "category_id", $CategoryID );
$t->set_var( "headline", $headline );
$t->pparse( "output", "message_page" );
?>
