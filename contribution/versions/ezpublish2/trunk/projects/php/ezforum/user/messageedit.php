<?
// 
// $Id: messageedit.php,v 1.19 2001/02/23 16:05:02 pkej Exp $
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <21-Feb-2001 18:00:00 pkej>
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

if( isset( $EditButton ) )
{
    $Action = "edit";
}

if( !empty( $CancelButton ) )
{
    $Action = "cancel";
}

if( !empty( $PreviewButton ) )
{
    $Action = "preview";
}

// Select which main page we are going to view.

switch( $Action )
{
    case "reply":
    case "new":
    case "edit":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "message.php" );

        $t->set_file( "page", "messageedit.tpl"  );
        $t->set_block( "page", "errors_tpl", "errors_item" );
        $t->set_var( "errors_item", "" );
    }
    break;
    
    case "preview":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "message.php" );

        $t->set_file( "page", "messagepreview.tpl"  );
    }
    break;
    
    case "completed":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "messageedit.php" );
        
        $t->set_file( "page", "messageposted.tpl"  );
        $t->setAllStrings();
    }
    break;
}


// Any errors?

$Errors = false;


// Do some action!

switch( $Action )
{
    case "completed":
    {
        $msg = new eZForumMessage( $MessageID );

        $CheckMessageID = $msg->id();
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );
        if( $MessageEdit == false )
        {
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }
        
        // Just tell the geezers that their posting has been sent or queued for moderation.
        // Also inform of any e-mails sent.
    }
    break;
    
    case "cancel":
    {
       // If PreviewID is set then we need to delete the object.
        // Since all objects are smart enough to not generate any
        // error messages if we new an empty object and then delete it
        // no ifs are neccessary.
        $msg = new eZForumMessage( $PreviewID );
        $msg->delete();
        
        include_once( "classes/ezhttptool.php" );
        if( empty( $RedirectURL ) )
        {
            if( empty( $ForumID ) )
            {
                eZHTTPTool::header( "Location: /forum/categorylist" );
            }
            else
            {
                eZHTTPTool::header( "Location: /forum/messagelist/$ForumID" );
            }
        }
        else
        {
            eZHTTPTool::header( "Location: $RedirectURL" );
        }
    }
    break;
    
    case "insert":
    {
        $ActionValue = "completed";
        $msg = new eZForumMessage( $OriginalID );

        $CheckMessageID = $OriginalID;
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );
        if( $ForumPost == false )
        {
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }

        $msg->setIsTemporary( false );
        $msg->store();
        eZHTTPTool::header( "Location: /forum/messageedit/$ActionValue/$OriginalID?ReplyToID=$ReplyToID&ActionStart=$ActionStart&RedirectURL=$RedirectURL" );
    }
    break;
    
    case "update":
    {
        $ActionValue = "completed";
        $msg = new eZForumMessage( $OriginalID );
        $tmpmsg = new eZForumMessage( $PreviewID );

        $CheckMessageID = $OriginalID;
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        if( $MessageEdit == false )
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }
        
        $msg->setTopic( $tmpmsg->topic() );
        $msg->setBody( $tmpmsg->body() );
        $msg->setEmailNotice( $tmpmsg->emailNotice() );
        
        $msg->store();
        
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /forum/messageedit/$ActionValue/$OriginalID?ActionStart=$ActionStart&RedirectURL=$RedirectURL" );
    }
    
    case "new":
    {
        $StartAction = "new";
        $EndAction = "insert";
        $ActionValue = "preview";

        $NewMessagePostedAt = htmlspecialchars( $ini->read_var( "eZForumMain", "FutureDate" ) );

        $ShowMessage = false;
        include_once( "ezforum/user/messagebody.php" );

        $msg = new eZForumMessage();
        $msg->setForumID( $ForumID );

        $CheckMessageID = 0;
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        if( $ForumPost == false )
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }

        $doParse = true;
        $ShowPath = true;
        $isPreview = true;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessageForm = true;
        $ShowEmptyMessageForm = true;
        $ShowVisibleMessageForm = true;
        $ShowHiddenMessageForm = true;
        $ShowReplyInfo = true;
        $ShowBodyInfo = true;
        include_once( "ezforum/user/messageform.php" );
    }
    break;
    
    case "edit":
    {
        if( !isset( $StartAction ) )
        {
            $StartAction = "edit";
            $EndAction = "update";
        }
        
        unset( $NewMessageAuthor );
        unset( $NewMessagePostedAt );
        
        $ActionValue = "preview";
        $msg = new eZForumMessage( $MessageID );
        $ForumID = $msg->forumID();
        
        $CheckMessageID = $MessageID;
        $CheckForumID = $ForumID;
        include( "ezforum/user/messagepermissions.php" );

        if( $MessageEdit == false )
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }
        
        $ShowMessage = true;
        include_once( "ezforum/user/messagebody.php" );

        $doParse = true;
        $ShowPath = true;
        $isPreview = false;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessageForm = true;
        $ShowEmptyMessageForm = false;
        $ShowVisibleMessageForm = true;
        $ShowHiddenMessageForm = true;
        $ShowReplyInfo = true;
        $ShowBodyInfo = true;
        include_once( "ezforum/user/messageform.php" );
        
        $doPrint = true;
    }
    break;
    
    case "reply":
    {
        $StartAction = $Action;
        $ActionValue = "preview";
        $EndAction = "insert";
        
        $MessageID = $ReplyToID;
        $NewMessagePostedAt = htmlspecialchars( $ini->read_var( "eZForumMain", "FutureDate" ) );
        $ReplyTags = $ini->read_var( "eZForumMain", "ReplyTags" );
        $ReplyStartTag = $ini->read_var( "eZForumMain", "ReplyStartTag" );
        $ReplyEndTag = $ini->read_var( "eZForumMain", "ReplyEndTag" );
        
        $msg = new eZForumMessage( $MessageID );
        $forum = new eZForum( $msg->forumID() );
        $ForumID = $forum->id();
        
        $CheckMessageID = $MessageID;
        $CheckForumID = $ForumID;
        include( "ezforum/user/messagepermissions.php" );

        if( $MessageReply == false )
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }

        if( $ReplyTags == "enabled" )
        {
            $NewMessageBody = $ReplyStartTag . "\n" . $msg->body( false ) . "\n" . $ReplyEndTag;
        }
        else
        {
            include( "classes/eztexttool.php" );
            $NewMessageBody = eZTextTool::addPre( $msg->body() );
        }

        $NewMessageTopic = $msg->topic();

        $ReplyPrefix = $ini->read_var( "eZForumMain", "ReplyPrefix" );
        
        if ( !ereg( "^$ReplyPrefix", $NewMessageTopic ) )
        {
            $NewMessageTopic = $ReplyPrefix . $NewMessageTopic;
        }

        $doParse = true;
        $ShowMessage = true;
        include_once( "ezforum/user/messagebody.php" );
        
        $ShowPath = true;
        $isPreview = false;
        include_once( "ezforum/user/messagepath.php" );
        
        $ShowMessageForm = true;
        $ShowEmptyMessageForm = false;
        $ShowVisibleMessageForm = true;
        $ShowHiddenMessageForm = true;
        $ShowReplyInfo = true;
        $ShowBodyInfo = true;
        include_once( "ezforum/user/messageform.php" );

        $doPrint = true;
    }
    break;
    
    case "preview":
    {
        $log = new eZLog();
        
        $ActionValue = $EndAction;
        if( $Error == false )
        {
            $log->notice( "no error <br>" );
            if( empty( $PreviewID ) )
            {
                $log->notice( "no preview id $PreviewID <br>");
                switch( $StartAction )
                {
                    case "edit":
                    {
                        $log->notice( "editing $MessageID <br>");
                        $msg = new eZForumMessage();
                        $tmpmsg = new eZForumMessage( $MessageID );
                        $msg = $tmpmsg->clone();
                    }
                    break;

                    case "reply":
                    {
                        $log->notice(  "replying to $ReplyToID <br>");
                        $msg = new eZForumMessage();
                        $tmpmsg = new eZForumMessage( $ReplyToID );
                        $ForumID = $tmpmsg->forumID();
                        $msg->setForumID( $ForumID );
                        $msg->setParent( $ReplyToID );
                        
                    }
                    break;
                    
                    case "new":
                    {
                        $msg = new eZForumMessage();
                        $msg->setForumID( $ForumID );
                    }
                    break;
                    
                }
                
                $author = eZUser::currentUser();

                if( is_object( $author ) )
                {
                    $msg->setUserID( $author->id() );
                }
                else
                {
                    $msg->setUserID( 0 );
                }
            }
            else
            {
                $log->notice(  "preview id? $PreviewID" );
                $msg = new eZForumMessage( $PreviewID );
            }
            
            if( $NewMessageNotice == "on" )
            {
                $msg->enableEmailNotice();
            }
            else
            {
                $msg->disableEmailNotice();
            }
            
            $msg->setTopic( stripslashes( strip_tags( $NewMessageTopic ) ) );
            $msg->setBody( stripslashes( strip_tags( $NewMessageBody, $AllowedTags ) ) );
            $msg->setIsTemporary( true );

            $msg->store();
            $PreviewID = $msg->id();
            
            if( $EndAction == "insert" )
            {
                $OriginalID = $PreviewID;
            }
            else
            {
                $OriginalID = $MessageID;
            }
            
            $MessageID = $PreviewID;

            $CheckMessageID = $msg->id();
            $CheckForumID = $msg->forumID();
            include( "ezforum/user/messagepermissions.php" );

            if( $MessageEdit == false )
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
            }

           
            $ShowPath = true;
            $isPreview = false;
            include_once( "ezforum/user/messagepath.php" );
            
            $ShowMessage = true;
            include_once( "ezforum/user/messagebody.php" );
            
            $ShowMessageForm = true;
            $ShowEmptyMessageForm = false;
            $ShowVisibleMessageForm = false;
            $ShowHiddenMessageForm = true;
            $ShowReplyInfo = true;
            $ShowBodyInfo = true;
            include_once( "ezforum/user/messageform.php" );
            
        }
        
        $log->notice(  "message id: " . $MessageID . "<br>");
        
        $doPrint = true;
    }
    break;
    
    default:
    {
        include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
    }
    break;
}

// print( "Dette kan ikke fungere! <br>" );
// print( "ActionValue = $ActionValue <br>" );
// print( "NewMessageBody = $NewMessageBody <br>" );
// print( "MessageBody = $MessageBody <br>" );
// print( "PreviewID = $PreviewID <br>" );
// print( "ReplyToID = $ReplyToID <br>" );
// print( "OriginalID = $OriginalID <br>" );
// print( "MessageID = $MessageID <br>" );
// print( "RedirectURL = $RedirectURL <br>" );

$t->set_var( "start_action", $StartAction );      
$t->set_var( "end_action", $EndAction );      
$t->set_var( "action_value", $ActionValue );
$t->set_var( "message_id", $MessageID );

$t->setAllStrings();

if( $doPrint == true )
{
    $t->pparse( "output", "page" );
}
else
{
    $t->pparse( "forum", "page" );
}

?>
