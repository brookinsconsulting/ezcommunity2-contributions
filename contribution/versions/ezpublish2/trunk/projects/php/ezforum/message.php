<?
/*!
    $Id: message.php,v 1.10 2000/08/02 10:06:17 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:54:41 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezsession.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );

$msg = new eZforumMessage;
$usr = new eZUser;
$session = new eZSession;
$t = new Template( "$DOCROOT/templates" );
    
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

//  $replies = $msg->getHeaders( $forum_id, $message_id );

//  if ( ($replies == 0) || (!$replies) )
//  {
//      $t->set_var("replies", "<tr><td colspan=\"4\"><b>Ingen svar</b></td></tr>");
//  }
//  else
//  {
//      for ($i = 0; $i < count($replies); $i++)
//      {
//          $j = $i + 1;
//          $Id = $replies[$i]["Id"];
//          $User = $replies[$i]["UserId"];
//          $Topic = $replies[$i]["Topic"];
//          $PostingTime = $replies[$i]["PostingTime"];
            
//          $t->set_var( "reply-id", $Id);
//          $t->set_var( "reply-nr", $j);
//          $t->set_var( "reply-user", $User);
//          $t->set_var( "reply-topic", stripslashes( $Topic ) );
//          $t->set_var( "reply-postingtime", $PostingTime);
        
//          $t->parse("replies", "elements", true);
//      }
//  }

$t->set_var( "link1-url", "main.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output","message");
?>
