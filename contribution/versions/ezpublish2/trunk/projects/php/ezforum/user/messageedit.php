<?
// 
// $Id: messageedit.php,v 1.15 2001/01/22 14:43:00 jb Exp $
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

include_once( "classes/ezlocale.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "classes/ezmail.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZForumMain", "Language" );

if ( $Action == "insert" )
{
    $forum = new eZForum( $ForumID );

    $user = eZUser::currentUser();
    
    $message = new eZForumMessage();

    $message->setForumID( $ForumID );
    $message->setTopic( strip_tags( $Topic ) );
    $message->setBody( $Body );

    $user = eZUser::currentUser();

    $message->setUserId( $user->id() );

    if ( $notice )
        $message->enableEmailNotice();
    else
        $message->disableEmailNotice();

    if ( $forum->isModerated() )
    {
        $message->setIsApproved( false );
    }
    else
    {
        $message->setIsApproved( true );
    }
    
    $message->store();


    $moderator = $forum->moderator();

    if ( $moderator )
    {
        $mail = new eZMail();

        $mail->setSubject( $message->topic() );
        $mail->setBody( $message->body( false ) );

        $mail->setFrom( $moderator->email() );
        $mail->setTo( $moderator->email() );

        $mail->send();
    }    

    Header( "Location: /forum/messagelist/$ForumID/" );
}

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "messageedit.php" );
   
$t->set_file( "messagepost", "messageedit.tpl"  );
$t->set_block( "messagepost", "message_item_tpl", "message_item" );
$t->setAllStrings();

$user = eZUser::currentUser();

if ( !$user )
{
    if ( $Action == "new" )
    {
        Header( "Location: /forum/userlogin/new/$ForumID" );
    }
    
    if ( $Action == "reply" )
    {
        Header( "Location: /forum/userlogin/reply/$MessageID" );
    }

}

$forum = new eZForum( $ForumID );
$t->set_var( "forum_name", $forum->name() );
$t->set_var( "forum_id", $ForumID );

$categories = $forum->categories();

$category = new eZForumCategory( $categories[0]->id() );

$t->set_var( "category_name", $category->name() );
$t->set_var( "category_id", $category->id() );


$username = ( $user->firstName() . " " . $user->lastName() );
$t->set_var( "user", $username );

$t->pparse( "output", "messagepost" );
?>
