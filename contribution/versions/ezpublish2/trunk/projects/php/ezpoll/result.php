<?
// 
// $Id: result.php,v 1.2 2000/10/02 11:58:14 bf-cvs Exp $
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
    "result" => "result.tpl"
    ) );

$t->set_block( "result", "result_item_tpl", "result_item" );
$t->set_block( "result", "choice_item_tpl", "choice_item" );

$poll = new eZPoll();
$poll->get( $PollID );
$t->set_var( "head_line", $poll->name() );

$pollchoice = new eZPollChoice();

$choiceList = $pollchoice->getAll( $PollID );

$vote =  new eZVote();
$total = 0;
setType( $total, "double" );

$i=1;
foreach( $choiceList as $choiceItem )
{
    $t->set_var( "choice_name", $choiceItem->name() );
    $t->set_var( "choice_number", $i );
    
    $t->parse( "choice_item", "choice_item_tpl", true );
    $i++;
}

$i=1;
foreach( $choiceList as $choiceItem )
{
    $t->set_var( "choice_name", $choiceItem->name() );
    $t->set_var( "choice_id", $choiceItem->id() );

    $t->set_var( "choice_vote", $choiceItem->voteCount() );
    $t->set_var( "choice_number", $i );

    $total = $poll->totalVotes();
    if ( $total != 0 )
    {
        $percent = ( ( $choiceItem->voteCount() / $total ) * 100 );
        setType( $percent, "integer" );
        $t->set_var( "choice_percent", $percent );
        $t->set_var( "choice_inverted_percent", 100 - $percent );
        
    }
    else
    {
        $t->set_var( "choice_percent", 0 );
        $t->set_var( "choice_inverted_percent", 100 );
    }
    
    $value = $choiceItem->voteCount();
    
    setType( $value, "double" );
    setType( $total, "double" );

    $total = $total + $value;
    
    $t->parse( "result_item", "result_item_tpl", true );
    $i++;    
}

$t->set_var( "total_votes", $poll->totalVotes() );

$t->pparse( "output", "result" );
?>
