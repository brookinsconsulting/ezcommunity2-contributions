<?
/*!
    $Id: message.php,v 1.15 2000/08/30 14:14:15 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "class.INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "class.INIFile.php" );
include_once( $DOC_ROOT . "classes/ezforummessage.php" );
include_once( $DOC_ROOT . "classes/eztemplate.php" );
include_once( "../classes/ezusergroup.php" );
include_once( "../classes/ezsession.php" );

$ini = new INIFile( "../ezforum.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );

$t = new eZTemplate( $DOC_ROOT . "admin/templates", $DOC_ROOT . "intl", $Language, "forum.php" );
$t->setAllStrings();

$t->set_file( Array( "messages" => "message.tpl",
                     "elements" => "message-elements.tpl",
                     "navigation" => "navigation.tpl",
                     "navigation-bottom" => "navigation-bottom.tpl" ) );

$t->set_var( "docroot", $DOC_ROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$t->parse( "navigation-bar", "navigation" );


if ( $session->get( $AuthenticatedSession ) != 0 )
{
    // fail  - reason: user not logged in.
    die( "your not logged in.. (redirect to login page)" );
}


if ( $modifymessage )
{
    if ( !eZUserGroup::verifyCommand( $session->userID(), "eZForum_DeleteMessage" ) )
    {
        die( "Insufficient user rights to add forum, dying..." );
        exit;
    }

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
    if ( !eZUserGroup::verifyCommand( $session->userID(), "eZForum_DeleteMessage" ) )
    {
        die( "Insufficient user rights to add forum, dying..." );
        exit;
    }

    eZforumMessage::delete( $message_id );
}

$message = new eZForumMessage();
$headers = $message->getAllHeaders( $forum_id );

if ( count ( $headers ) == 0 )
{
    $t->set_var( "fields", "<tr bgcolor=\"#dcdcdc\"><td  colspan=\"6\"><b>No messages / ingen meldinger</b></td></tr>");
}
else
{
    for ($i = 0; $i < count( $headers ); $i++)
    {
        $user = new eZUser();
            
        $t->set_var( "message_id", $headers[$i]["Id"] );
        $t->set_var( "topic", $headers[$i]["Topic"] );
        $t->set_var( "parent", $headers[$i]["Parent"] );
        $t->set_var( "user", $user->resolveUser( $headers[$i]["UserId"] ) );
        $t->set_var( "postingtime", $headers[$i]["PostingTimeFormated"] );
        
        if ( $headers[$i]["EmailNotice"] == "Y" )
            $t->set_var( "emailnotice", "checked" );
        else
        $t->set_var( "emailnotice", "" );
        
        $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );
        $t->parse( "fields", "elements", true );
    }
}
$t->set_var( "link1-url", "");
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "admin/forum.php" );
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "messages" );
?>
