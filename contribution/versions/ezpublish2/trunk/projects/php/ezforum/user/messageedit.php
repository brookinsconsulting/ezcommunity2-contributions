<?
// 
// $Id: messageedit.php,v 1.1 2000/10/18 11:56:07 ce-cvs Exp $
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

include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );


$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZForumMain", "Language" );

if ( $Action == "insert" )
{
    $user = eZUser::currentUser();
    
    $message = new eZForumMessage();

    $message->setForumID( $ForumID );
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
    // Do some nasty..
}
$forum = new eZForum( $ForumID );
$t->set_var( "forum_name", $forum->name() );
$t->set_var( "forum_id", $ForumID );
$category = new eZForumCategory( $forum->categoryID() );
$t->set_var( "category_name", $category->name() );


$username = ( $user->firstName() . " " . $user->lastName() );
$t->set_var( "user", $username );

if ( $GenerateStaticPage == "true" )
{
    $fp = fopen ( $cachedFile, "w+");

    $output = $t->parse( $target, "message_tpl" );
    // print the output the first time while printing the cache file.
    
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "messagepost" );
}
?>
