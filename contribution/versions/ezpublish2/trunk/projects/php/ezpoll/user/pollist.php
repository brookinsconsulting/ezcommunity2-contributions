<?
// 
// $Id: pollist.php,v 1.1 2000/10/25 10:07:45 ce-cvs Exp $
//
// Definition of eZPoll class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZPollMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZPollMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezpoll.php" );


$t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     "ezpoll/user/intl/", $Language, "pollist.php" );

$t->setAllStrings();

$t->set_file( array(
    "poll_list_page" => "pollist.tpl"
    ) );

$t->set_block( "poll_list_page", "poll_item_tpl", "poll_item" );


$poll = new eZPoll();

$pollList = $poll->getAllActive( );


$ini = new INIFile( "ezpoll/intl/" . $Language . "/pollist.php.ini", false );
$nonActive =  $ini->read_var( "strings", "non_active" );
$active =  $ini->read_var( "strings", "active" );

foreach( $pollList as $pollItem )
{
    $t->set_var( "poll_id", $pollItem->id() );
    $t->set_var( "poll_name", $pollItem->name() );
    $t->set_var( "poll_description", $pollItem->description() );
    if ( $pollItem->IsClosed() )
    {
        $t->set_var( "action", "result" );
        $t->set_var( "poll_is_closed", $nonActive );

    }
    else
    {
        $t->set_var( "action", "votebox" );
        $t->set_var( "poll_is_closed", $active );

    }

    $t->parse( "poll_item", "poll_item_tpl", true );

}

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "poll_list_page" );

?>
