<?php
// 
// $Id: messagereply.php,v 1.7 2000/10/27 08:57:12 bf-cvs Exp $
//
// B�rd Farstad <bf@ez.no>
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

$ini = new INIFile( "site.ini" ); // get language settings

include_once( "common/ezphputils.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezmail.php" );
include_once( "classes/eztexttool.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php");
include_once( "ezforum/classes/ezforumcategory.php");

if ( $Action == "insert" )
{
    $original = new eZForumMessage( $ReplyID );
    
    $reply = new eZForumMessage( );

    $reply->setForumID( $original->forumID() );

    $reply->setTopic( $Topic );
    $reply->setBody( $Body );

    $reply->setParent( $original->id() );
    
    $user = eZUser::currentUser();
    
    $reply->setUserId( $user->id() );

    if ( $notice )
        $reply->enableEmailNotice();
    else
        $reply->disableEmailNotice();

    $reply->store();

    $forum_id = $original->forumID();

    // send out email notices
    $forum = new eZForum( $original->forumID() );
    $messages = $forum->messageThreadTree( $reply->threadID() );

    $mail = new eZMail();

    $mail->setFrom( "noreply@ez.no" );
    
    $mail->setSubject( "reply" );

    foreach ( $messages as $message )
    {
        if ( $message->id() != $reply->id() )
        {
            if ( ( $message->treeID() > $reply->treeID() ) && $message->emailNotice() )
            {
                $user =& $message->user();
                
                $mail->setTo( $user->email() );
                $mail->setBody(  $reply->body() . "" . $user->email() );
                
                $mail->send();
            }
        }
    }    


    // clear the cache files.

    $dir = dir( "ezforum/cache/" );
    $files = array();
    while( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        { 
            $files[] = $entry; 
            $numfiles++; 
        } 
    } 
    $dir->close();

    foreach( $files as $file )
    {
        if ( ereg( "forum,([^,]+),.*", $file, $regArray  ) )
        {
            if ( $regArray[1] == $forum_id )
            {
                unlink( "ezforum/cache/" . $file );
            }
        }
    }

    // add deleting of every message in the thread
    unlink( "ezforum/cache/message," . $ReplyID . ".cache" );
    
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
$CategoryID = $forum->categoryID();
$category = new eZForumCategory( $CategoryID );
$t->set_var( "category_name", $category->name() );

$t->set_var( "forum_name", $forum->name() );
$user = eZUser::currentUser();

$t->set_var( "forum_id", $ForumID );

$t->set_var( "msg_id", $msg->id() );

$t->set_var( "topic", ("SV: " . stripslashes( $msg->topic() ) ) );

$t->set_var( "user", $user->firstName() . " " . $user->lastName() );

$text = eZTextTool::addPre( $msg->body() );

$t->set_var("body", $text );
$t->set_var( "category_id", $CategoryID );
$t->set_var( "message_id", $ReplyID );

$t->pparse("output", "replymessage");
?>
