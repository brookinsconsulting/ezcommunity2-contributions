<?
/*!
    $Id: message.php,v 1.14 2000/08/28 13:48:03 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:54:41 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezdb.php" );
include_once( $DOC_ROOT . "/classes/ezforummessage.php" );

include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/eztemplate.php" );

$msg = new eZforumMessage;
$usr = new eZUser;
$session = new eZSession;
$ini = new INIFile( "ezforum.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );

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

$t->set_var( "link1-url", "main.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output","message");
?>
