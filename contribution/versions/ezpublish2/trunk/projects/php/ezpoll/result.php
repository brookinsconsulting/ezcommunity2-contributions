<?
// 
// $Id: result.php,v 1.7 2000/10/10 13:26:02 ce-cvs Exp $
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


$t = new eZTemplate( $DOC_ROOT . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/result/",
                     $DOC_ROOT . "/intl/", $Language, "result.php" );

$t->setAllStrings();

$t->set_file( array(
    "result" => "result.tpl"
    ) );

$t->set_block( "result", "result_list_tpl", "result_list" );
$t->set_block( "result_list_tpl", "result_item_tpl", "result_item" );

$poll = new eZPoll();
if ( $Show == "all"  )
{
    $pollArray = $poll->getAll();    
}
else
{
    $poll->get( $PollID );
    $pollArray[] = $poll;
}

foreach ( $pollArray as $poll )
{
    $t->set_var( "poll_name", $poll->name() );

    $pollchoice = new eZPollChoice();
    $choiceList = $pollchoice->getAll( $poll->id() );

    $vote =  new eZVote();
    $total = 0;
    $total = $poll->totalVotes();
    
    setType( $total, "double" );

    $i=1;

    if ( $poll->showResult() )
    {
        $t->set_var( "result_item", "" );        
        foreach( $choiceList as $choiceItem )
        {
            $value = 0;
            $t->set_var( "choice_name", $choiceItem->name() );
            $t->set_var( "choice_id", $choiceItem->id() );

            $t->set_var( "choice_vote", $choiceItem->voteCount() );
            $t->set_var( "choice_number", $i );

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
    
            $t->parse( "result_item", "result_item_tpl", true );
            $i++;    
        }

        $t->set_var( "total_votes", $poll->totalVotes() );
        
        $t->parse( "result_list", "result_list_tpl", true );
    }        
}

$t->pparse( "output", "result" );
?>
