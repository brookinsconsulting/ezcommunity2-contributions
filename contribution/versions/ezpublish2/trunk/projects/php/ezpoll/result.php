<?
// 
// $Id: result.php,v 1.1 2000/09/29 11:55:27 ce-cvs Exp $
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
include_once( $DOC_ROOT . "/classes/ezvote.php" );
include_once( $DOC_ROOT . "/classes/ezpollchoice.php" );


$t = new eZTemplate( $DOC_ROOT . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/polllist/",
                     $DOC_ROOT . "/intl/", $Language, "polllist.php" );

$t->setAllStrings();

$t->set_file( array(
    "result" => "result.tpl",
    "result_item" => "resultitem.tpl"
    ) );

$poll = new eZPoll();
$poll->get( $PollID );
$t->set_var( "head_line", $poll->name() );

$pollchoice = new eZPollChoice();

$choiceList = $pollchoice->getAll( $PollID );

$vote =  new eZVote();
$total = 0;
setType( $total, "double" );

foreach( $choiceList as $choiceItem )
{
    $t->set_var( "choice_name", $choiceItem->name() );
    $t->set_var( "choice_id", $choiceItem->id() );
    
    $t->set_var( "choice_vote", $vote->getCountByChoiceID( $choiceItem->id() ) );

    $value = $vote->getCountByChoiceID( $choiceItem->id() );
    
    setType( $value, "double" );
    setType( $total, "double" );

    $total = $total + $value;
    
    $t->parse( "result_list", "result_item", true );
}

$t->set_var( "total", $total );

$t->pparse( "output", "result" );
?>
