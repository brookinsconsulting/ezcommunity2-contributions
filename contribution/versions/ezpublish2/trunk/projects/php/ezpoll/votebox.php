<?
// 
// $Id: votebox.php,v 1.3 2000/10/03 10:52:41 ce-cvs Exp $
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
include_once( $DOC_ROOT . "/classes/ezpollchoice.php" );


$t = new eZTemplate( $DOC_ROOT . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/polllist/",
                     $DOC_ROOT . "/intl/", $Language, "polllist.php" );

$t->setAllStrings();

$t->set_file( array(
    "vote_form" => "voteform.tpl",
    "vote_item" => "voteitem.tpl"
    ) );

$choice = new eZPollChoice();

$choiceList = $choice->getAll( $PollID );

foreach( $choiceList as $choiceItem )
{
    $t->set_var( "choice_name", $choiceItem->name() );
    $t->set_var( "choice_id", $choiceItem->id() );

    $t->parse( "vote_list", "vote_item", true );
    
}

$poll = new eZPoll();
$poll->get( $PollID );
$t->set_var( "head_line", $poll->name() );
$t->set_var( "poll_id", $PollID );


  
$t->pparse( "output", "vote_form" );

?>
