<?
/*!
    $Id: message.php,v 1.12 2000/08/03 13:22:16 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:54:41 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezsession.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/eztemplate.php" );

$msg = new eZforumMessage;
$usr = new eZUser;
$session = new eZSession;
$ini = new INIFile( "ezforum.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );

$t = new eZTemplate( "$DOCROOT/templates", "$DOCROOT/intl", $Language, "message.php" );
$t->setAllStrings();
    
$t->set_file( array("message" => "message.tpl",
                    "elements" => "message-elements.tpl",
                    "navigation" => "navigation.tpl",
                    "navigation-bottom" => "navigation-bottom.tpl",
                    "login" => "login.tpl",
                    "logout" => "logout.tpl"
                    )
              );

$t->set_var( "docroot", $DOCROOT);
$t->set_var( "category_id", $category_id);

if ( $session->get( $AuthenticatedSession ) == 0 )
{
    $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
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
    
$messages = $msg->printHeaderTree( $forum_id, $top_message, 0, $DOCROOT, $category_id );
$t->set_var( "replies", $messages );

$t->set_var( "link1-url", "main.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output","message");
?>
