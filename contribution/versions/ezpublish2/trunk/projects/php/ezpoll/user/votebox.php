<?
// 
// $Id: votebox.php,v 1.1 2000/10/25 10:07:45 ce-cvs Exp $
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

include_once( "ezpoll/classes/ezpoll.php" );
include_once( "ezpoll/classes/ezpollchoice.php" );

$t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     "ezpoll/user/intl/", $Language, "votebox.php" );

$t->setAllStrings();

$poll = new eZPoll( $PollID );

if ( $poll->isClosed() )
{
//      Header( "Location: /poll/result/$PollID" );
//      exit();
}

$t->set_file( array(
    "vote_box" => "votebox.tpl"
    ) );

$t->set_block( "vote_box", "vote_item_tpl", "vote_item" );

$choice = new eZPollChoice();

$choiceList = $choice->getAll( $PollID );

foreach( $choiceList as $choiceItem )
{
    $t->set_var( "choice_name", $choiceItem->name() );
    $t->set_var( "choice_id", $choiceItem->id() );

    $t->parse( "vote_item", "vote_item_tpl", true );
    
}

$poll = new eZPoll();
$poll->get( $PollID );
$t->set_var( "head_line", $poll->name() );
$t->set_var( "poll_id", $PollID );

  
$t->pparse( "output", "vote_box" );

?>
