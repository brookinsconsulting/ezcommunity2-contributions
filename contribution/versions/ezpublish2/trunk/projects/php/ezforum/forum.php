<?
// 
// $Id: forum.php,v 1.54 2000/10/17 14:16:49 ce-cvs Exp $
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


$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "forum.php" );

$t->set_file( "forum_tpl", "forum.tpl"  );

$t->set_block( "forum_tpl", "message_tpl", "message" );
$t->set_block( "forum_tpl", "previous_tpl", "previous" );
$t->set_block( "forum_tpl", "next_tpl", "next" );

$t->setAllStrings();

$forum = new eZForum( $forum_id );
$category = new eZForumCategory( $forum->categoryID()  );

$t->set_var( "category_id", $category->id( ) );
$t->set_var( "category_name", $category->name( ) );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );


// make to $Action .. elo!

$msg = new eZForumMessage( $forum_id );

// new posting
if ( $Action == "post" )
{
    print( "post" );
    $message = new eZForumMessage();

    $message->setForumID( $forum_id );
    $message->setTopic( $Topic );
    $message->setBody( $Body );

    $user = eZUser::currentUser();

    print( $user->id() );
    
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
 
    Header( "Location: /forum/category/forum/$forum_id/" );
}

$locale = new eZLocale( $Language );

if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = 30;

$messages = $forum->messageTree( $Offset, $Limit );

if ( !$messages )
{
    $ini = new INIFile( "ezforum/intl/" . $Language . "/forum.php.ini", false );
    $noitem =  $ini->read_var( "strings", "noitem" );

    $t->set_var( "message", $noitem );
    $t->set_var( "next", "" );
    $t->set_var( "previous", "" );
}
else
{

    $level = 0;
    $i = 0;
    foreach ( $messages as $message )
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
    
            $t->parse( "message", "message_tpl", true );
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
    $t->pparse( "output", "forum_tpl" );
}


?>
