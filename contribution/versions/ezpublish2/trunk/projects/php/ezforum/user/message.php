<?
// 
// $Id: message.php,v 1.21 2001/05/07 10:18:34 ce Exp $
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

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/ezlocale.php" );
include_once( "classes/eztexttool.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "message.php" );
$t->setAllStrings();

$t->set_file( "message_tpl", "message.tpl"  );

$t->set_block( "message_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_tpl", "edit_current_message_item_tpl", "edit_current_message_item" );
$t->set_block( "message_item_tpl", "edit_message_item_tpl", "edit_message_item" );

$t->set_var( "edit_current_message_item", "" );

$message = new eZForumMessage( $MessageID );

$forum = new eZForum( $message->forumID() );

$group =& $forum->group();
$viewer = $user;
if ( ( get_class( $group ) == "ezusergroup" ) && ( $group->id() != 0 ) )
{
    if ( get_class ( $viewer ) == "ezuser" )
    {
        $groupList =& $viewer->groups();
        
        foreach ( $groupList as $userGroup )
        {
            if ( $userGroup->id() == $group->id() )
            {
                $readPermission = true;
                break;
            }
        }
    }
}
else
{
    $readPermission = true;
}

$categories = $forum->categories();

if ( count( $categories ) > 0 )
{
    $category = new eZForumCategory( $categories[0]->id() );
    
    $t->set_var( "category_id", $category->id( ) );
    $t->set_var( "category_name", $category->name( ) );
}

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

$t->set_var( "message_id", $message->id() );
$t->set_var( "message_topic", $message->topic() );

$t->set_var( "topic", $message->topic() );

$user = $message->user();

$anonymous=$ini->read_var( "eZForumMain", "AnonymousPoster" );

if( $user->id() == 0 )
{
    $MessageAuthor = $anonymous;
}
else
{
    $MessageAuthor = $user->firstName() . " " . $user->lastName();
}


$t->set_var( "main-user", $MessageAuthor );

$t->set_var( "topic", $message->topic() );

$time = $message->postingTime();
$t->set_var( "main-postingtime", $locale->format( $time  ));

$t->set_var( "body", eZTextTool::nl2br( $message->body( false ) ) );

$t->set_var( "reply_id", $message->id() );

$t->set_var( "forum_id", $forum->id() );

if( get_class( $viewer ) == "ezuser" )
{
    if( $viewer->id() == $message->userId() && eZForumMessage::countReplies( $message->id() ) == 0 )
    {
        $t->parse( "edit_current_message_item", "edit_current_message_item_tpl" );
    }
}


$topMessage = $message->threadTop( $message );

// print out the replies tree

$messages = $forum->messageThreadTree( $message->threadID() );

//  $messages = $forum->messages();

$level = 0;

$i=0;
foreach ( $messages as $message )
{
    $t->set_var( "edit_message_item", "" );
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    
    $level = $message->depth();

    if ( $message->id() == $MessageID )
    {
        $t->set_var( "link_color", "linkselect" );
        $t->set_var( "td_class", "bgselect" );
    }
    else
    {
        $t->set_var( "link_color", "linknormal" );
    }

    if ( $level > 0 )
        $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
    else
        $t->set_var( "spacer", "" );
    
    $t->set_var( "reply_topic", $message->topic() );

    $time = $message->postingTime();
    $t->set_var( "postingtime", $locale->format( $time ) );

    $t->set_var( "message_id", $message->id() );

    $user = $message->user();
    
    if( $user->id() == 0 )
    {
        $MessageAuthor = $anonymous;
    }
    else
    {
        $MessageAuthor = $user->firstName() . " " . $user->lastName();
    }
    
    $t->set_var( "user", $MessageAuthor );

    /*
    if( get_class( $viewer ) == "ezuser" )
    {
        if( ( $viewer->id() == $message->userId() ) && ( eZForumMessage::countReplies( $message->id() ) == 0 ) )
        {
            $t->parse( "edit_message_item", "edit_message_item_tpl" );
        }
    }
    */
    $t->parse( "message_item", "message_item_tpl", true );
    $i++;
}

$t->set_var( "redirect_url", $RedirectURL );

if ( $readPermission == true )
    $t->pparse( "output", "message_tpl" );

?>
