<?
// 
// $Id: messagesimplelist.php,v 1.11 2001/03/01 14:06:25 jb Exp $
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
include_once( "classes/eztexttool.php" );


include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "messagesimplelist.php" );

$t->set_file( "messagelist", "messagesimplelist.tpl"  );


$t->set_block( "messagelist", "message_list_tpl", "message_list" );

$t->set_block( "message_list_tpl", "message_item_tpl", "message_item" );

$t->setAllStrings();

$forum = new eZForum( $ForumID );

$locale = new eZLocale( $Language );

$Offset = 0;
$Limit = 30;

$messageList =& $forum->messageTree( $Offset, $Limit );

if ( !$messageList )
{
    $errorIni = new INIFile( "ezforum/user/intl/" . $Language . "/messagesimplelist.php.ini", false );
    $noitem =& $errorIni->read_var( "strings", "noitem" );

    $t->set_var( "message_list", $noitem );
    $t->set_var( "next", "" );
    $t->set_var( "previous", "" );
}
else
{
    $level = 0;
    $i = 0;
    foreach ( $messageList as $message )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        
        $level = $message->depth();
        
        if ( $level > 0 )
            $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
        else
            $t->set_var( "spacer", "" );        
            
        $t->set_var( "topic", $message->topic() );
        $t->set_var( "body", eZTextTool::nl2br( $message->body() ) );
        $time =& $message->postingTime();
        $t->set_var( "postingtime", $locale->format( $time ) );
        $t->set_var( "message_id", $message->id() );
        
        $user =& $message->user();
        $t->set_var( "user", $user->firstName() . " " . $user->lastName() );
        
        $t->set_var( "limit", $Limit );
        
        $prevOffs = $Offset - $Limit;
        $nextOffs = $Offset + $Limit;
        
        if ( $prevOffs >= 0 )
        {
            $t->set_var( "prev_offset", $prevOffs  );
            $t->parse( "previous", "previous_tpl" );
        }
        else
        {
            $t->set_var( "previous", "" );
        }
        
        if ( $nextOffs <= $forum->messageCount() )
        {
            $t->set_var( "next_offset", $nextOffs  );
            $t->parse( "next", "next_tpl" );
        }
        else
        {
            $t->set_var( "next", "" );
        }
        
        
//    $t->set_var( "next_offset", $Offset + $Limit );    
        
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
    $t->parse( "message_list", "message_list_tpl", true );
}


$t->set_var( "redirect_url", $RedirectURL );

$t->set_var( "newmessage", $newmessage );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

$t->pparse( "output", "messagelist" );

?>
