<?
// 
// $Id: search.php,v 1.1 2000/10/18 11:56:07 ce-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no
// Created on: <12-Oct-2000 20:33:02 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "ezforum/classes/ezforum.php" );

include_once( "classes/ezlocale.php" );

include_once( "ezuser/classes/ezuser.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );


$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/user/intl", $Language, "search.php" );


$t->setAllStrings();

$t->set_file( "search_tpl", "search.tpl" );

$t->set_block( "search_tpl", "message_tpl", "message" );

$t->set_block( "search_tpl", "empty_result_tpl", "empty_result" );
$t->set_block( "search_tpl", "search_result_tpl", "search_result" );

$t->set_block( "search_tpl", "previous_tpl", "previous" );
$t->set_block( "search_tpl", "next_tpl", "next" );


if ( isSet( $URLQueryString ) )
{
    $QueryString = urldecode( $URLQueryString );
}

$t->set_var( "query_string", $QueryString );

$t->set_var( "previous", "" );
$t->set_var( "next", "" );

$t->set_var( "search_result", "" );



if ( $QueryString != "" )
{
    $t->set_var( "query_string", $QueryString );

    if ( !isset( $Offset ) )
        $Offset = 0;

    if ( !isset( $Limit ) )
        $Limit = 30;

    $forum = new eZForum();
    
    // do a search in all forums
    $messages = $forum->search( $QueryString, $Offset, $Limit );
    $total_count = $forum->getQueryCount( $QueryString );

    $locale = new eZLocale( $Language );

    $level = 0;
    $i = 0;


    foreach ( $messages as $message )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
    
        $t->set_var( "message_topic", $message->topic() );
        
        $t->set_var( "postingtime", $locale->format( $message->postingTime() ) );

        $t->set_var( "message_id", $message->id() );
        
        $user = $message->user();
        
        $t->set_var( "user", $user->firstName() . " " . $user->lastName() );

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
        
        if ( $nextOffs <= $total_count )
        {
            $t->set_var( "next_offset", $nextOffs  );
            $t->parse( "next", "next_tpl" );
        }
        else
        {
            $t->set_var( "next", "" );
        }
        
        $t->set_var( "limit", $Limit );
        
        $t->parse( "message", "message_tpl", true );
        $i++;
    }
    
    if ( count( $messages ) == 0 )
    {
        $t->parse( "empty_result", "empty_result_tpl" );
    }
    else
    {
        $t->parse( "search_result", "search_result_tpl", true );
        $t->set_var( "empty_result", "" );
    }
}
else
{
    $t->parse( "empty_result", "empty_result_tpl" );
} 

$t->set_var( "url_query_string", urlencode( $QueryString ) );

$t->pparse("output","search_tpl");
?>
