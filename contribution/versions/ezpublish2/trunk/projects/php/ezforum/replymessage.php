<?
/*!
    $Id: replymessage.php,v 1.19 2000/10/17 13:44:44 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:49:25 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforummessage.php");
include_once( "$DOCROOT/classes/ezsession.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$msg = new eZforumMessage;
$t = new Template(".");

$t->set_file("replymessage","$DOCROOT/templates/replymessage.tpl");

$t->set_var( "docroot", $DOCROOT);
$t->set_var( "category_id", $category_id);

$msg->get( $reply_id );
    
if ($AuthenticatedSession)
{
    $session = new eZSession();
    $session->get( $AuthenticatedSession );
    $UserID = $session->UserID();
}
else
{
    $UserId = 0;
}

$info = eZforumCategory::categoryForumInfo($forum_id);
$infoString = $info["CategoryName"] . "::" . $info["ForumName"];

$t->set_var("forum_id", $forum_id );
$t->set_var("msg_id", $msg->id() );
$t->set_var("info",  $infoString);
$t->set_var("topic", ("SV: " . stripslashes( $msg->topic() ) ) );
$t->set_var("user", eZUser::resolveUser( $msg->userId() ) );
$t->set_var("body", nl2br( stripslashes( $msg->body() ) ) );
$t->set_var("replier", eZUser::resolveUser( $UserID ) );

$t->pparse("output", "replymessage");
?>
