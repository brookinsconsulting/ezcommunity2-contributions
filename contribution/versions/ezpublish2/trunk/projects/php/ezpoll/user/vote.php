<?
// 
// $Id: vote.php,v 1.4 2000/10/30 08:32:17 ce-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
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
    if ( !$HTTP_COOKIE_VARS["eZPoll"] )
        $Voted = true;
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
