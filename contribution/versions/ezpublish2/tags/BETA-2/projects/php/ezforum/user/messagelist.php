<?
// 
// $Id: messagelist.php,v 1.1 2000/10/18 11:56:07 ce-cvs Exp $
//
// 
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "messagelist.php" );

$t->set_file( "messagelist", "messagelist.tpl"  );

$t->set_block( "messagelist", "message_item_tpl", "message_item" );

$t->setAllStrings();

$forum = new eZForum( $ForumID );
$category = new eZForumCategory( $forum->categoryID()  );

$locale = new eZLocale( $Language );

if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = 30;

$messageList = $forum->messageTree( $Offset, $Limit );

if ( !$messageList )
{
    $ini = new INIFile( "ezforum/user/intl/" . $Language . "/messagelist.php.ini", false );
    $noitem =  $ini->read_var( "strings", "noitem" );

    $t->set_var( "message_item", $noitem );
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
        $t->set_var( "postingtime", $locale->format( $message->postingTime() ) );
        $t->set_var( "message_id", $message->id() );
        
        $user = $message->user();    
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
} 

$t->set_var( "newmessage", $newmessage );


if ( $GenerateStaticPage == "true" )
{
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse( $target, "forum_tpl" );
    // print the output the first time while printing the cache file.
    
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->set_var( "category_id", $category->id( ) );
    $t->set_var( "category_name", $category->name( ) );

    $t->set_var( "forum_id", $forum->id() );
    $t->set_var( "forum_name", $forum->name() );

    $t->pparse( "output", "messagelist" );
}

?>
