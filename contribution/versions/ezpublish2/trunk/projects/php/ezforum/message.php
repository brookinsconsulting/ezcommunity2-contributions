<?
/*!
    $Id: message.php,v 1.6 2000/07/26 09:15:29 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:54:41 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "template.inc" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );
include( "$DOCROOT/classes/ezforummessage.php" );

$msg = new eZforumMessage;
$usr = new eZUser;
$t = new Template(".");
    
$t->set_file( array("message" => "$DOCROOT/templates/message.tpl",
                    "elements" => "$DOCROOT/templates/message-elements.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl" ) );

$t->set_var( "docroot", $DOCROOT);
$t->set_var( "category_id", $category_id);

$t->parse( "navigation-bar", "navigation", true );

$msg->get( $message_id );
    
$t->set_var( "topic", stripslashes( $msg->topic() ) );
$t->set_var( "user", $usr->resolveUser( $msg->userId() ) );
$t->set_var( "postingtime", $msg->postingTime() );
$t->set_var( "body", nl2br( stripslashes( $msg->body() ) ) );
$t->set_var( "reply_id", $message_id );
$t->set_var( "forum_id", $forum_id );

$top_message = $msg->getTopMessage( $message_id );
    
$messages = $msg->printHeaderTree( $forum_id, $top_message, 0, $DOCROOT );
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
