<?
/*!
    $Id: message.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

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
                        "elements" => "$DOCROOT/templates/message-elements.tpl") );

$t->set_var( "docroot", $DOCROOT);
$t->set_var( "category_id", $category_id);

$msg->get( $message_id );
    
$t->set_var( "topic", $msg->topic() );
$t->set_var( "user", $usr->resolveUser( $msg->user() ) );
$t->set_var( "postingtime", $msg->postingTime() );
$t->set_var( "body", nl2br( $msg->body() ) );
$t->set_var( "reply_id", $message_id );
$t->set_var( "forum_id", $forum_id );

$replies = $msg->getHeaders( $forum_id, $message_id );

if ( ($replies == 0) || (!$replies) )
{
    $t->set_var("replies", "<tr><td colspan=\"4\"><b>Ingen svar</b></td></tr>");
}
else
{
    for ($i = 0; $i < count($replies); $i++)
    {
        $j = $i + 1;
        $Id = $replies[$i]["Id"];
        $User = $replies[$i]["UserId"];
        $Topic = $replies[$i]["Topic"];
        $PostingTime = $replies[$i]["PostingTime"];
            
        $t->set_var( "reply-id", $Id);
        $t->set_var( "reply-nr", $j);
        $t->set_var( "reply-user", $User);
        $t->set_var( "reply-topic", $Topic);
        $t->set_var( "reply-postingtime", $PostingTime);
        
        $t->parse("replies", "elements", true);
    }
}
    
$t->pparse("output","message");
?> 
