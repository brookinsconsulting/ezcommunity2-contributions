<?
/*!
    $Id: replymessage.php,v 1.5 2000/08/29 12:08:53 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:49:25 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/


$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );


include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( $DOC_ROOT . "/classes/ezdb.php" );
include_once( $DOC_ROOT . "/classes/ezforummessage.php");
include_once( "classes/ezsession.php" );
include_once( "classes/ezuser.php" );

$msg = new eZforumMessage;
$t = new Template(".");

$t->set_file("replymessage","$DOC_ROOT/templates/replymessage.tpl");

$t->set_var( "docroot", $DOC_ROOT);
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

$category = new eZForumCategory();
$info = $category->categoryForumInfo($forum_id);
$infoString = $info["CategoryName"] . "::" . $info["ForumName"];

$user = new eZUser();

$t->set_var("forum_id", $forum_id );
$t->set_var("msg_id", $msg->id() );
$t->set_var("info",  $infoString);
$t->set_var("topic", ("SV: " . stripslashes( $msg->topic() ) ) );
$t->set_var("user", $user->resolveUser( $msg->userId() ) );
$t->set_var("body", nl2br( stripslashes( $msg->body() ) ) );
$t->set_var("replier", $user->resolveUser( $UserID ) );

$t->pparse("output", "replymessage");
?>
