<?
// 
// $Id: messagesimpleedit.php,v 1.1 2000/11/21 16:20:46 bf-cvs Exp $
//
// Bård Farstad
// Created on: <21-Nov-2000 16:04:30 bf>
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

$ini = new INIFile( "site.ini" );

include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

print( $RedirectURL );

$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZForumMain", "Language" );

if ( $Action == "insert" )
{
    $user = eZUser::currentUser();
    
    $message = new eZForumMessage();

    $message->setForumID( $ForumID );
    $message->setTopic( strip_tags( $Topic ) );
    $message->setBody( strip_tags( $Body, "<b>,<i>,<u>,<font>" ) );

    $message->setUserId( $user->id() );

    if ( $notice )
        $message->enableEmailNotice();
    else
        $message->disableEmailNotice();

    $message->store();

    // delete the cache file(s)

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

    print( $RedirectURL );
    Header( "Location: $RedirectURL" );
    exit();
}

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "messagesimpleedit.php" );
   
$t->set_file( "messagepost", "messagesimpleedit.tpl"  );

$t->set_block( "messagepost", "message_item_tpl", "message_item" );
$t->setAllStrings();

$user = eZUser::currentUser();

if ( !$user )
{
    if ( $Action == "new" )
    {
        Header( "Location: /forum/userlogin/new/$ForumID" );
        exit();
    }
    
    if ( $Action == "reply" )
    {
        Header( "Location: /forum/userlogin/reply/$MessageID" );
        exit();
    }

}

$forum = new eZForum( $ForumID );
$t->set_var( "forum_name", $forum->name() );
$t->set_var( "forum_id", $ForumID );
$category = new eZForumCategory( $forum->categoryID() );
$t->set_var( "category_name", $category->name() );
$t->set_var( "category_id", $category->id() );


$username = ( $user->firstName() . " " . $user->lastName() );
$t->set_var( "user", $username );

$t->set_var( "redirect_url", $RedirectURL );

$t->pparse( "output", "messagepost" );
?>
