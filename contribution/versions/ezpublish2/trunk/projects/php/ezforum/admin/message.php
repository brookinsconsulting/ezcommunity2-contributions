<?
/*!
    $Id: message.php,v 1.1 2000/07/24 10:44:25 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "messages" => "$DOCROOT/admin/templates/message.tpl",
                    "" => "$DOCROOT/admin/templates/message-elements.tpl") );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );


$usr = new eZUser;
$message = new eZforumMessage;
$headers = $message->getHeaders( $forum_id );

for ($i = 0; $i < count( $headers ); $i++)
{
    $t->set_var( "message_id", $headers[$i]["Id"] );
    $t->set_var( "topic", $headers[$i]["Topic"] );
//    $t->set_var( "body", $headers[$i]["Body"] );
    $t->set_var( "parent", $headers[$i]["Parent"] );
    $t->set_var( "user_id", $headers[$i]["UserId"] );
    $t->set_var( "user", $usr->resolveUser( $headers[$i]["UserId"] ) );
    $t->set_var( "postingtime", $headers[$i]["PostingTime"] );
    if ( $headers[$i]["EmailNotice"] == "Y" )
        $t->set_var( "emailnotice", "checked" );
    else
        $t->set_var( "emailnotice", "" );

    if ( ($i % 2) != 0)
        $t->set_var( "color", "#eeeeee" );
    else
        $t->set_var( "color", "#bbbbbb" );

    $t->parse( "messages", "elements", true );
}
$t->pparse( "output", "messages" );
?>
