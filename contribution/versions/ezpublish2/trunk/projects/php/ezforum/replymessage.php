<?
// 
// $Id: replymessage.php,v 1.9 2000/10/12 10:03:53 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <24-Sep-2000 12:20:32 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings


include_once( "common/ezphputils.php" );
include_once( "classes/template.inc" );
include_once( "classes/ezdb.php" );

include_once( "ezforum/classes/ezforummessage.php");
include_once( "ezforum/classes/ezforumcategory.php");

$msg = new eZforumMessage();
$t = new Template(".");

$t->set_file("replymessage","ezforum/templates/replymessage.tpl");

$t->set_var( "category_id", $category_id);

$msg->get( $reply_id );

$category = new eZForumCategory();

//  $info = $category->categoryForumInfo($forum_id);

$infoString = $info["CategoryName"] . "::" . $info["ForumName"];

$user = new eZUser();

$t->set_var("forum_id", $forum_id );
$t->set_var("msg_id", $msg->id() );
$t->set_var("info",  $infoString);
$t->set_var("topic", ("SV: " . stripslashes( $msg->topic() ) ) );

//  $t->set_var("user", $user->resolveUser( $msg->userId() ) );

$t->set_var("body", nl2br( stripslashes( $msg->body() ) ) );
//  $t->set_var("replier", $user->resolveUser( $UserID ) );

$t->pparse("output", "replymessage");
?>
