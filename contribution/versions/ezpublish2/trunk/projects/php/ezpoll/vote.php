<?
// 
// $Id: vote.php,v 1.4 2000/10/20 09:16:16 ce-cvs Exp $
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

$user = eZUser::currentUser();
if ( !$user )
{
    Header( "Location: /user/login" );
    exit();
}

$poll = new eZPoll( $PollID );
if ( $poll->isClosed() )
{
    Header( "Location: /poll/result/$PollID" );
    exit();
}

$checkvote = new eZVote();

if ( $checkvote->oneVoteCheck( $user->id(), $PollID  ))
{
    print( "Du har allerede stemt engang" );
}

else
{
    $vote = new eZVote();
    $vote->setPollID( $PollID );
    $vote->setChoiceID( $ChoiceID );
    $vote->setVotingIP( $REMOTE_ADDR );
    $vote->setUserID( $user->id() );
    $vote->store();
}

Header( "Location: /poll/result/" . $PollID );
exit();

?>
