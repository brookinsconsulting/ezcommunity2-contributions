<?
/*!
    $Id: message.php,v 1.9 2000/07/26 14:37:09 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "messages" => "$DOCROOT/admin/templates/message.tpl",
                    "elements" => "$DOCROOT/admin/templates/message-elements.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl" ) );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$t->parse( "navigation-bar", "navigation" );

if ( $modifymessage )
{
    $msg = new eZforumMessage;
    $msg->get( $message_id );
    $msg->setTopic( $topic );
    $msg->setBody( $body );
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    
    $msg->store();
}

if ( $deletemessage )
{
    eZforumMessage::delete( $message_id );
}

$headers = ezForumMessage::getAllHeaders( $forum_id );

for ($i = 0; $i < count( $headers ); $i++)
{
    $t->set_var( "message_id", $headers[$i]["Id"] );
    $t->set_var( "topic", $headers[$i]["Topic"] );
    $t->set_var( "parent", $headers[$i]["Parent"] );
    $t->set_var( "user", ezUser::resolveUser( $headers[$i]["UserId"] ) );
    $t->set_var( "postingtime", $headers[$i]["PostingTimeFormated"] );

    if ( $headers[$i]["EmailNotice"] == "Y" )
        $t->set_var( "emailnotice", "checked" );
    else
        $t->set_var( "emailnotice", "" );

    if ( ($i % 2) != 0)
        $t->set_var( "color", "#eeeeee" );
    else
        $t->set_var( "color", "#bbbbbb" );

    $t->parse( "fields", "elements", true );
}

$t->set_var( "link1-url", "admin/category.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "admin/forum.php" );
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "messages" );
?>
