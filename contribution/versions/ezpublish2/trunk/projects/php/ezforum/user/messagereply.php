<?php
// 
// $Id: messagereply.php,v 1.37 2001/05/14 15:31:15 fh Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <24-Sep-2000 12:20:32 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );

if ( $StartAction == "reply" )
{
    if( !is_object( $msg )  )
    {
        $msg = new eZForumMessage( $MessageID );
    }

    $ForumID = $msg->forumId();

    if( !is_object( $forum ) )
    {
        $forum = new eZForum( $ForumID );
    }
    
    // send mail to forum moderator
    $moderator = $forum->moderator();

    $mail = new eZMail();
    
    $messages = $forum->messageThreadTree( $msg->threadID() );

    $replyAddress = $ini->read_var( "eZForumMain", "ReplyAddress" );
    $mail->setFrom( $replyAddress );
    
    $locale = new eZLocale( $Language );
    
    $mailTemplate = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                                    "ezforum/user/intl", $Language, "mailreply.php" );
    
    $mailTemplate->set_file( "mailreply", "mailreply.tpl" );
    $mailTemplate->setAllStrings();

    $emailNoticeArray = array();
    
    if( is_object( $moderator ) )
    {
        $author = $msg->user();
        $headersInfo = ( getallheaders() );
        
        if( $author->id() == 0 )
        {
            $mailTemplate->set_var( "author", $ini->read_var( "eZForumMain", "AnonymousPoster" ) );
        }
        else
        {
            $mailTemplate->set_var( "author", $author->firstName() . " " . $author->lastName() );
        }
        $mailTemplate->set_var( "posted_at", $locale->format( $msg->postingTime() ) );

        $subject_line = $mailTemplate->Ini->read_var( "strings", "moderator_subject" );
        

        $mailTemplate->set_var( "topic", $msg->topic() );
        $mailTemplate->set_var( "body", $msg->body( false ) );
        $mailTemplate->set_var( "forum_name", $forum->name() );
        $mailTemplate->set_var( "forum_link", "http://"  . $headersInfo["Host"] . "/forum/messagelist/" . $forum->id() );
        $mailTemplate->set_var( "link_1", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
        $mailTemplate->set_var( "link_2", "http://admin." . $headersInfo["Host"] . "/forum/messageedit/edit/" . $msg->id() );
        $mailTemplate->set_var( "intl-info_message_1", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_1" ) );
        $mailTemplate->set_var( "intl-info_message_2", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_2" ) );
        $mailTemplate->set_var( "intl-info_message_3", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_3" ) );
        $mailTemplate->set_var( "intl-info_message_4", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_4" ) );

        $bodyText = ( $mailTemplate->parse( "dummy", "mailreply" ) );

        $mail->setSubject( $subject_line );
        $mail->setBody( $bodyText );

        $mail->setFrom( $moderator->email() );
        $mail->setTo( $moderator->email() );

        $mail->send();
        $emailNoticeArray[] = $moderator->id();
    }
    
    foreach( $messages as $message )
    {
        if( $message->id() != $msg->id() )
        {
            if ( ( $message->treeID() > $msg->treeID() ) && $message->emailNotice() )
            {
                $user =& eZUser::currentUser();
                $headersInfo = ( getallheaders() );
                $mailTemplate->set_var( "author", $user->firstName() . " " . $user->lastName() );
                $mailTemplate->set_var( "posted_at", $locale->format( $msg->postingTime() ) );
                
                $subject_line = $mailTemplate->Ini->read_var( "strings", "subject_prepend" );
                $subject_line = $subject_line . $message->topic();
                $subject_line = $subject_line . $mailTemplate->Ini->read_var( "strings", "subject_append" );
                
                $mailTemplate->set_var( "topic", $msg->topic() );
                $mailTemplate->set_var( "body", $msg->body( false ) );
                $mailTemplate->set_var( "forum_name", $forum->name() );
                $mailTemplate->set_var( "forum_link", "http://" . $headersInfo["Host"] . "/forum/message/" . $message->id() );
                $mailTemplate->set_var( "link_1", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
                $mailTemplate->set_var( "link_2", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );

                $bodyText = $mailTemplate->parse( "dummy", "mailreply" );

                $mail->setSubject( $subject_line );

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

}
?>
