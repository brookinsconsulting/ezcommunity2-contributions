<?
/*!
    $Id: message.php,v 1.20 2000/10/17 14:19:16 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );


$t = new eZTemplate( $DOC_ROOT . "admin/templates", $DOC_ROOT . "intl", $Language, "forum.php" );
$t->setAllStrings();

$t->set_file( Array( "messages" => "message.tpl",
                     "elements" => "message-elements.tpl",
                     "navigation" => "navigation.tpl",
                     "navigation-bottom" => "navigation-bottom.tpl" ) );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
$DOC_ROOT . "/admin/" . "/intl", $Language, "messageedit.php" );
$t->setAllStrings();

$t->set_file( Array( "message_page" => "message.tpl" ) );

$t->set_block( "message_page", "message_tpl", "message" );
$locale = new eZLocale( $Language );

$msg = new eZforumMessage();
$msg->get( $MessageID );

$t->set_var( "message_topic", $msg->topic() );
$t->set_var( "message_postingtime", $locale->format( $msg->postingTime() ) );
$t->set_var( "message_body", $msg->body() );
$user = $msg->user();
$t->set_var( "message_user", $user->firstName() . " " . $user->lastName() );
$t->set_var( "message_id", $MessageID );



$t->set_var( "category_id", $CategoryID );
$t->set_var( "forum_id", $ForumID );
$t->pparse( "output", "message_page" );
?>
