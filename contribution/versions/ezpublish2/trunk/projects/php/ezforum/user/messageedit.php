<?
// 
// $Id: messageedit.php,v 1.18 2001/02/20 19:14:13 pkej Exp $
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );

include_once( "classes/ezlocale.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "classes/ezmail.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZForumMain", "Language" );

// Let's see who's calling?

$user = eZUser::currentUser();

if( !$user )
{
    // Let''s not be naive, check everyone for login, regardless of action
    
    eZHTTPTool::header( "Location: /forum/userlogin/$Action/$PassOnID" );
}

// $PrevAction keeps track of the incomming action (ie. creatin a new item, or editing an existing).
// $NextAction keeps track of the ending action
// $ActionValue is the next action to perform

if( $Abort )
{
    $Action = "cancel";
}

// Check form for correct data

switch( $Action )
{
    case "preview":
    {
        // First we need to make sure that the user really has data
        // to preview. Since we don''t know which action was the initializing
        // one, we use the $PrevAction variable to decide where to return to
        // on an error
    
        if( empty( $MessageTopic ) || empty( $MessageBody ) )
        {
            $Action = $PrevAction;
            $Error = true;
        }
    }
    break;
    
    case "insert":
    {
        // If the edit button was pressed, change to new, since
        // the user was not pleased with the result.

        if( !empty( $Edit ) )
        {
            $Action = "new";
        }
    }
    break;
    
    case "update":
    {
        // If the edit button was pressed, change to edit, since
        // the user was not pleased with the result.

        if( !empty( $Edit ) )
        {
            $Action = "edit";
        }
    }
    break;
}

switch( $Action )
{
    case "insert": // intentional fall through
    case "update":
    {
        $ActionValue = "completed";
        // Now we need to check that the data we insert hasn''t been inserted earlier,
        // there should exist a temporary object with the same information as we get
        // from the user.
        
        $tmpmsg = new eZForumMessage( $PreviewID );
        
        if( !is_object( $tmpmsg )
            || $tmpmsg->userID() != $user->id() )
        {
            eZHTTPTool::header( "Location: /forum/messageedit/$PrevAction/$MessageID?DataChangedError=1" );
            break;
        }

        if( $Action == "insert" )
        {
            // Just remove the temporary flag for new info!
            
            $tmpmsg->setIsTemporary( false );
            $tmpmsg->store();
            $MessageID = $tmpmsg->id();
        }
        else
        {
            // Delete the temporary object, then insert the new data to the old message.          
            $tmpmsg->delete();

            $msg = new eZForumMessage( $MessageID );

            $msg->setTopic( strip_tags( $MessageTopic ) );
            $msg->setBody( $MessageBody );

            if( $MessageNotice == "checked" )
            {
                $msg->enableEmailNotice();
            }
            else
            {
                $msg->disableEmailNotice();
            }

            $msg->store();
            $MessageID = $msg->id();
        }
        eZHTTPTool::header( "Location: /forum/messageedit/completed/$MessageID?ForumID=$ForumID" );
    }
    break;

    case "new":
    {
        $PrevAction = "new";
        $ActionValue = "preview";
        $NextAction = "insert";
        
        if( empty( $ForumID ) )
        {
            if( empty( $MessageID ) )
            {
                eZHTTPTool::header( "Location: /forum/categorylist/" );
            }
            else
            {
                $ForumID = $MessageID;
            }
        }
        
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "messageedit.php" );

        $t->set_file( "editfile", "messageedit.tpl"  );
        $MessagePostingTime = $ini->read_var( "eZForumMain", "Futuredate" );
        
        $t->setAllStrings();
    }
    break;
    
    case "edit":
    {
        $PrevAction = "edit";
        $ActionValue = "preview";
        $NextAction = "update";
        $msg = new eZForumMessage( $MessageID );
        $msgPoster = $msg->user();
        
        if( $msgPoster->id() != $user->id()
            || !eZPermission::checkPermission( $user, "eZForum", "MessageModify" ) )
        {
            eZHTTPTool::header( "Location: /forum/norights" );
        }
        
        if( empty( $MessageTopic ) )
        {
            $MessageTopic = $msg->topic();
        }
        
        if( empty( $MessageBody ) )
        {
            $MessageBody = $msg->body();
        }
        
        if( empty( $MessageNotice ) )
        {
            $MessageNotice = $msg->emailNotice();
        }
        
        $MessagePostingTime = $locale->format( $msg->postingTime() );
        $ForumID = $msg->forumID();

        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "messageedit.php" );

        $t->set_file( "editfile", "messageedit.tpl"  );
        $t->setAllStrings();
    }
    break;
    
    case "preview":
    {
        $ActionValue = $NextAction;

        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "messageedit.php" );
        
        if( $PrevAction == "new" )
        {
            $MessagePostingTime = $ini->read_var( "eZForumMain", "Futuredate" );
        }
        else
        {
            $tmpmsg = new eZForumMessage( $MessageID );
            $MessagePostingTime = $tmpmsg->postingTime();
        }
        
        $t->set_file( "editfile", "messagepreview.tpl"  );
        $t->setAllStrings();
        
        // Now we will create a temporary object with the posted data.
        // This is to ensure that the user isn''t posting the same data twice and
        // so that script kiddies can''t fool us (I hope).
                
        $msg = new eZForumMessage( $PreviewID );
        
        $msg->setForumID( $ForumID );
        $msg->setTopic( strip_tags( $MessageTopic ) );
        $msg->setBody( $MessageBody );
        $msg->setUserId( $user->id() );
        
        if( $MessageNotice == "on" )
        {
            $msg->enableEmailNotice();
        }
        else
        {
            $msg->disableEmailNotice();
        }
        
        $forum = new eZForum( $ForumID );
        
        if ( $forum->isModerated() )
        {
            $msg->setIsApproved( false );
        }
        else
        {
            $msg->setIsApproved( true );
        }
        
        
        $msg->setIsTemporary( true );
        
        $msg->store();
        
        $PreviewID = $msg->id();
    }
    break;
    
    case "completed":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "messageedit.php" );

        $t->set_file( "editfile", "messageposted.tpl"  );
        $t->setAllStrings();
        
        // Just tell the geezers that their posting has been sent or queued for moderation.
        // Also inform of any e-mails sent.
        
        $msg = new eZForumMessage( $MessageID );
        $MessageTopic = $msg->topic();
        $MessageBody = $msg->body();
        $MessagePostingTime = $locale->format( $msg->postingTime() );
        $ForumID = $msg->forumId();
        
    }
    break;
    
    case "cancel":
    {
        $msg = new eZForumMessage( $PreviewID );
        $msg->delete();
        eZHTTPTool::header( "Location: /forum/messagelist/$ForumID" );            
    }
    break;
}

if( $Action != "preview" || $Action != "completed" );
{
    $t->set_block( "editfile", "errors_tpl", "errors_item" );

    if( $Error == true )
    {
        if( !empty( $MessageBody ) )
        {
            $t->set_block( "errors_tpl", "error_missing_body_item_tpl", "error_missing_body_item" );
            $t->set_var( "error_missing_body_item", "" );
        }

        if( !empty( $MessageTopic ) )
        {
            $t->set_block( "errors_tpl", "error_missing_topic_item_tpl", "error_missing_topic_item" );
            $t->set_var( "error_missing_topic_item", "" );
        }
        $t->parse( "errors_item", "errors_tpl" );
    }
    else
    {
        $t->set_var( "errors_item", "" );
    }
}


$forum = new eZForum( $ForumID );
$ForumName = $forum->name();
$categories = $forum->categories();

$category = new eZForumCategory( $categories[0]->id() );
$MessageUser = $user->firstName() . " " . $user->lastName();

$CategoryName = $category->name();
$CategoryID = $category->id();

if( $MessageNotice == "on" || $MessageNotice == 1 || $MessageNotice == "checked" )
{
    $MessageNotice = "checked";
}
else
{
    $MessageNotice = "";
}

// Now insert everything into the template. Note the use of variables
// if they are empty that is good, since we will fill in the template with
// empy info where needed.

$t->set_var( "preview_id", $PreviewID );
$t->set_var( "message_topic", $MessageTopic );
$t->set_var( "message_postingtime", $MessagePostingTime );
$t->set_var( "message_body", $MessageBody );
$t->set_var( "message_user", $MessageUser );
$t->set_var( "message_id", $MessageID );
$t->set_var( "message_notice", $MessageNotice );
$t->set_var( "reply_to_id", $ReplyToID );
$t->set_var( "category_name", $CategoryName );
$t->set_var( "category_id", $CategoryID );
$t->set_var( "forum_id", $ForumID );
$t->set_var( "forum_name", $ForumName );
$t->set_var( "next_action", $NextAction );      
$t->set_var( "prev_action", $PrevAction );      
$t->set_var( "action_value", $ActionValue );      

$t->pparse( "output", "editfile" );

?>
