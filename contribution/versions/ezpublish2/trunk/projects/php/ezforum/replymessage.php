<?
/*!
    $Id: replymessage.php,v 1.2 2000/07/19 12:36:55 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:49:25 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( "template.inc" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezforummessage.php");
include( "$DOCROOT/classes/ezsession.php" );
include( "$DOCROOT/classes/ezuser.php" );

$msg = new eZforumMessage;
$usr = new eZUser;
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

function categoryForumInfo($Id)
{
    openDB();
    
    $query_id = mysql_query("SELECT ForumTable.Name AS ForumName,CategoryTable.Name AS CategoryName FROM ForumTable, CategoryTable
                                 WHERE CategoryTable.Id = ForumTable.CategoryId AND ForumTable.Id = '$Id'")
         or die("categoryForumInfo()");
    
    $r = mysql_fetch_row($query_id);
    
    return ($r[0] . "::" . $r[1]);
}    
    
$t->set_var("forum_id", $forum_id );
$t->set_var("msg_id", $msg->id() );
$t->set_var("info", categoryForumInfo($forum_id) );
$t->set_var("topic", ("SV: " . stripslashes( $msg->topic() ) ) );
$t->set_var("user", $usr->resolveUser( $msg->user() ) );
$t->set_var("body", nl2br( stripslashes( $msg->body() ) ) );
$t->set_var("replier", $usr->resolveUser( $UserID ) );

$t->pparse("output", "replymessage");
?>
