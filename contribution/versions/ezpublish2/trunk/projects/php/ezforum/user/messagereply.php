<?php
// 
// $Id: messagereply.php,v 1.19 2001/01/20 19:30:42 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <24-Sep-2000 12:20:32 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZForumMain", "Language" );
$ReplyPrefix = $ini->read_var( "eZForumMain", "ReplyPrefix" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezmail.php" );
include_once( "classes/eztexttool.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php");
include_once( "ezforum/classes/ezforumcategory.php");

if ( $Action == "insert" )
{
    $original = new eZForumMessage( $ReplyID );
    
    $reply = new eZForumMessage( );

    $reply->setForumID( $original->forumID() );

    $reply->setTopic( strip_tags( $Topic ) );
         
    $reply->setBody( $Body );

    $reply->setParent( $original->id() );
    
    $user = eZUser::currentUser();
    
    $reply->setUserId( $user->id() );

    if ( $notice )
        $reply->enableEmailNotice();
    else
        $reply->disableEmailNotice();

    $forum_id = $original->forumID();
    $forum = new eZForum( $forum_id );

    if ( $forum->isModerated() )
    {
        $reply->setIsApproved( false );
    }
    else
    {
        $reply->setIsApproved( true );
    }
    
    $reply->store();    


    // send mail to forum moderator
    $moderator = $forum->moderator();

    if ( $moderator )
    {
        $mail = new eZMail();

        $mail->setSubject( $reply->topic() );
        $mail->setBody( $reply->body( false ) );

        $mail->setFrom( $moderator->email() );
        $mail->setTo( $moderator->email() );

        $mail->send();
    }

    // send out email notices
    $forum = new eZForum( $original->forumID() );
    $messages = $forum->messageThreadTree( $reply->threadID() );

    $mail = new eZMail();

    $mail->setFrom( "noreply@ez.no" );
    
    $locale = new eZLocale( $Language );
    
    $mailTemplate = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                                    "ezforum/user/intl", $Language, "mailreply.php" );
    
    $mailTemplate->set_file( "mailreply", "mailreply.tpl" );
    $mailTemplate->setAllStrings();

    $emailNoticeArray = array();
    foreach ( $messages as $message )
    {
        if ( $message->id() != $reply->id() )
        {
            if ( ( $message->treeID() > $reply->treeID() ) && $message->emailNotice() )
            {
                
                $headersInfo = ( getallheaders() );
                $mailTemplate->set_var( "arthur", $user->firstName() . " " . $user->lastName() );
                $mailTemplate->set_var( "postingtime", $locale->format( $message->postingTime() ) );
                
                $mailTemplate->set_var( "topic", $reply->topic() );
                $mailTemplate->set_var( "body", $reply->body( false ) );
                $mailTemplate->set_var( "link", "http://" . $headersInfo["Host"] . "/forum/message/" . $reply->id() );

                $bodyText = ( $mailTemplate->parse( "dummy", "mailreply" ) );

                $mail->setSubject( $reply->topic() );

                $user =& $message->user();

                $mail->setTo( $user->email() );
                $mail->setBody( $bodyText );

                // only send replies to a user once
                if ( !in_array( $user->id(), $emailNoticeArray ) )
                {
                    $mail->send();                
                    $emailNoticeArray[] = $user->id();                    
                }                
            }
        }
    }

    Header( "Location: /forum/messagelist/$forum_id/" );
}

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "messagereply.php" );
$t->setAllStrings();

$t->set_file( "replymessage", "messagereply.tpl");

$category = new eZForumCategory();

$msg = new eZForumMessage( $ReplyID );
$ForumID = $msg->forumID();
$forum = new eZForum( $ForumID );

$categories = $forum->categories();
$category = new eZForumCategory( $categories[0]->id() );

$t->set_var( "category_name", $category->name() );

$t->set_var( "forum_name", $forum->name() );
$user = eZUser::currentUser();

if ( !$user )
{
    Header( "Location: /forum/userlogin/reply/$ReplyID" );
}

$t->set_var( "forum_id", $ForumID );

$t->set_var( "msg_id", $msg->id() );

$topic =  stripslashes( $msg->topic() );

if ( !ereg( "^$ReplyPrefix", $topic ) )
{
    $topic = $ReplyPrefix . $topic;
}

$t->set_var( "topic", $topic );

$t->set_var( "user", $user->firstName() . " " . $user->lastName() );

$text = eZTextTool::addPre( $msg->body() );

$t->set_var("body", $text );
$t->set_var( "category_id", $CategoryID );
$t->set_var( "message_id", $ReplyID );

$t->pparse("output", "replymessage");
?>
