<?
// 
// $Id: newmessage.php,v 1.14 2000/10/13 14:19:59 ce-cvs Exp $
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

include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforummessage.php" );

$msg = new eZForumMessage();

$session = new eZSession();
$ini = new INIFile( "site.ini" ); // get language settings

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "newmessage.php" );

$t->set_file(  "new_message_tpl", "newmessage.tpl"  );

$t->setAllStrings();

$t->set_var( "category_id", $category_id );

$category = new eZForumCategory();

//  $info = $category->categoryForumInfo( $forum_id );

$infoString = $info["CategoryName"] . "::" . $info["ForumName"];

$user = new eZUser();
$category->get( $category_id );
$t->set_var( "category_name", $category->name() );

$forum = new eZForumForum();
$forum->get( $forum_id );
$t->set_var( "forum_name", $forum->name() );

$t->set_var("info", $infoString );
$t->set_var("forum_id", $forum_id);
$t->set_var( "category_id", $category_id );


$t->pparse( "output", "new_message_tpl" );
?>
