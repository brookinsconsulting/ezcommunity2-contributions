<?
// 
// $Id: newmessage.php,v 1.17 2000/10/17 14:16:49 ce-cvs Exp $
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

$forum = new eZForum();
$forum->get( $ForumID );
$t->set_var( "forum_name", $forum->name() );

$user = eZUser::currentUser();

$category->get( $forum->categoryID() );
$t->set_var( "category_name", $category->name() );

$t->set_var( "user", $user->firstName(). " ". $user->lastName() );

$t->set_var("info", $infoString );
$t->set_var("forum_id", $ForumID );
$t->set_var( "category_id", $category->id() );


$t->pparse( "output", "new_message_tpl" );
?>
