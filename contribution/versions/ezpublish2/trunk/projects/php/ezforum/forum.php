<?
/*!
    $Id: forum.php,v 1.2 2000/07/14 13:47:12 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:57:16 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( "template.inc" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );
include( "$DOCROOT/classes/ezforummessage.php" );
include( "$DOCROOT/classes/ezsession.php" );

$msg = new eZforumMessage($forum_id);
$t = new Template(".");

$t->set_file( array("forum" => "$DOCROOT/templates/forum.tpl",
                    "elements" => "$DOCROOT/templates/forum-elements.tpl") );

$t->set_var( "docroot", $DOCROOT);
$t->set_var( "category_id", $category_id);
$t->set_var( "forum_id", $forum_id);
if ($AuthenticatedSession)
{
    $session = new eZSession();
    $session->get( $AuthenticatedSession );
    $UserID = $session->UserID();
}
else
{
    $UserID = 0;
}

if ($post)
{
    $msg->newMessage();
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    $msg->store();
}
    
if ($reply)
{
    $msg->newMessage();    
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    $msg->setParent( $parent );
    $msg->store();
}
    
$headers = $msg->getHeaders( $forum_id );

for ($i = 0; $i < count($headers); $i++)
{
    $Id = $headers[$i]["Id"];
    $Topic  = $headers[$i]["Topic"];
    $User = $headers[$i]["UserId"];
    $PostingTime = $headers[$i]["PostingTime"];
        
    $j = $i + 1;
         
    $t->set_var( "id", $Id);
    $t->set_var( "forum_id", $forum_id);
    $t->set_var( "topic", $Topic);
    $t->set_var( "nr", $j );
    $t->set_var( "user", $User );
    $t->set_var( "postingtime", $PostingTime );
    $t->set_var( "link",$link );
         
    openDB();
         
    $query_id = mysql_query("SELECT COUNT(Id) AS replies FROM MessageTable WHERE Parent='$Id'")
         or die("could not count replies, dying");
         
    $t->set_var( "replies", mysql_result($query_id,0,"replies"));

    if ( ($i % 2) != 0)
        $t->set_var( "color", "#eeeeee");
    else
        $t->set_var( "color", "#bbbbbb");
    
    $t->parse("messages", "elements", true);
}
     
if ( count($headers) == 0)
    $t->set_var( "messages", "<tr><td colspan=\"4\">Ingen meldinger</td></tr>");
     
$t->set_var("newmessage", $newmessage);

$t->pparse("output","forum");
?>
    
    
