<?
/*!
    $Id: editmessage.php,v 1.14 2001/03/01 14:06:25 jb Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <25-Jul-2000 15:13:15 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini =& INIFile::globalINI();

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "classes/template.inc" );
//include_once( "$DOC_ROOT/classes/ezdb.php" );
include_once( "$DOC_ROOT/classes/ezforummessage.php" );
include_once( "../classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "edit" => "$DOC_ROOT/admin/templates/editmessage.tpl",
                    "navigation" => "$DOC_ROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOC_ROOT/templates/navigation-bottom.tpl" ) );

$msg = new eZforumMessage;
$msg->get( $message_id );

$t->set_var( "message_id", $message_id );
$t->set_var( "docroot", $DOC_ROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$t->parse( "navigation-bar", "navigation", true);

// rest
$user = new eZUser();

$t->set_var( "user", $user->resolveUser( $msg->userId() ) );
$t->set_var( "topic", $msg->topic() );
$t->set_var( "body", $msg->body() );

if ( $msg->emailNotice() == "Y" )
{
    $t->set_var( "email-notice", "checked" );
}
else
{
    $t->set_var( "email-notice", "" );
}

$t->set_var( "link1-url", "admin/category.php" );
$t->set_var( "link1-caption", "Gå til topp" );
$t->set_var( "link2-url", "search.php" );
$t->set_var( "link2-caption", "Søk" );

$t->set_var( "back-url", "admin/message.php" );
$t->parse( "navigation-bar-bottom", "navigation-bottom", true );
$t->pparse( "output", "edit" );
?>
