<?
// 
// $Id: messagelist.php,v 1.24 2001/05/08 09:55:04 ce Exp $
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

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlist.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );
$UserLimit = $ini->read_var( "eZForumMain", "MessageUserLimit" );
$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "messagelist.php" );

$t->set_file( "messagelist", "messagelist.tpl"  );

$t->set_block( "messagelist", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "edit_message_item_tpl", "edit_message_item" );
$t->set_block( "messagelist", "previous_tpl", "previous" ); 
$t->set_block( "messagelist", "next_tpl", "next" ); 

$t->setAllStrings();

$forum = new eZForum( $ForumID );

$categories =& $forum->categories();

$group =& $forum->group();
$viewer = $user;
if ( get_class( $group ) == "ezusergroup" )
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

if ( count( $categories ) > 0 )
{
    $category = new eZForumCategory( $categories[0]->id() );
    
    $t->set_var( "category_id", $category->id( ) );
    $t->set_var( "category_name", $category->name( ) );
}

$locale = new eZLocale( $Language );

if ( !$Offset )
    $Offset = 0;

if ( $ini->read_var( "eZForumMain", "ShowReplies" ) == "enabled" )
{
    $messageList =& $forum->messageTreeArray( $Offset, $UserLimit );
    $messageCount =& $forum->messageCount( );
}
else
{
    $messageList =& $forum->messageTreeArray( $Offset, $UserLimit, false, false );
    $messageCount =& $forum->messageCount( false, true );
}

if ( !$messageList )
{
    $languageIni = new INIFile( "ezforum/user/intl/" . $Language . "/messagelist.php.ini", false );
    $noitem =  $languageIni->read_var( "strings", "noitem" );

    $t->set_var( "message_item", $noitem );
}
else
{
    $level = 0;
    $i = 0;
    $user = new eZUser( );
    $time = new eZDateTime();
    foreach ( $messageList as $message )
    {        
        $t->set_var( "edit_message_item", "" );

        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        
        $t->set_var( "topic", $message["Topic"] );

        $time->setMySQLTimeStamp( $message["PostingTime"] );
        $t->set_var( "postingtime", $locale->format( $time  ) );

        $t->set_var( "message_id", $message["ID"] );
        
        $userID = $message["UserID"];
        $user->get( $userID );
        
        if ( $ini->read_var( "eZForumMain", "ShowReplies" ) == "enabled" )
        {
            $t->set_var( "count_replies", "" );
            $level = $message["Depth"];
            
            if ( $level > 0 )
                $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
            else
                $t->set_var( "spacer", "" );
        }
        else
        {
            $t->set_var( "spacer", "" );
            $t->set_var( "count_replies", "(" . $message["Count"] . ")" );
        }

        if ( $user->id() == 0 )
        {
            $t->set_var( "user", $ini->read_var( "eZForumMain", "AnonymousPoster" ) );
        }
        else
        {
            $t->set_var( "user", $user->firstName() . " " . $user->lastName() );
        }
        
        /*        
        if ( get_class( $viewer ) == "ezuser" )
        {
            if ( $viewer->id() == $userID && eZForumMessage::countReplies( $message["ID"] ) == 0 && !$forum->IsModerated() )
            {
                $t->parse( "edit_message_item", "edit_message_item_tpl" );
            }
        }
        */
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
}
eZList::drawNavigator( $t, $messageCount, $UserLimit, $Offset, "messagelist" );

$t->set_var( "newmessage", $newmessage );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

if ( $readPermission == true )
    $t->pparse( "output", "messagelist" );

?>
