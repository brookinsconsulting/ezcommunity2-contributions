<?
// 
// $Id: vote.php,v 1.2 2000/10/26 10:05:03 ce-cvs Exp $
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
include_once( "ezsession/classes/ezsession.php" );

// Check if poll is closed.
$poll = new eZPoll( $PollID );
if ( $poll->isClosed() )
{
    Header( "Location: /poll/result/$PollID" );
    exit();
}

// Check if the poll is anonymous or not.
$poll = new eZPoll( $PollID );
if ( !$poll->anonymous() )
{
    $user = eZUser::currentUser();
    if ( !$user )
    {
        Header( "Location: /user/user/new/" );
        exit();
    }
}
else
{
    if ( $HTTP_COOKIE_VARS["eZPoll"] == "Voted" )
    {
        $Voted = true;
    }
    else
    {
        setcookie( "eZPoll", "Voted" );
        $Voted = false;
    }
}

if ( $user )
{
    $checkvote = new eZVote();
    if ( $checkvote->isVoted( $user->id(), $PollID  ))
        $Voted = true;
    else
        $Voted = false;
}

if ( !$Voted )
{
    $vote = new eZVote();
    $vote->setPollID( $PollID );
    $vote->setChoiceID( $ChoiceID );
    $vote->setVotingIP( $REMOTE_ADDR );
    if ( $user )
        $vote->setUserID( $user->id() );
    if ( !$ChoiceID == 0 )
    $vote->store();
}

Header( "Location: /poll/result/" . $PollID );
exit();

?>
