<?
// 
// $Id: vote.php,v 1.12 2001/01/23 13:16:57 jb Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZPollMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZPollMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezpoll.php" );
include_once( $DOC_ROOT . "/classes/ezvote.php" );
include_once( $DOC_ROOT . "/classes/ezpollchoice.php" );
include_once( "ezsession/classes/ezsession.php" );

$session =& eZSession::globalSession();

if( !$session->fetch() )
    $session->store();

// Check if poll is closed.
$poll = new eZPoll( $PollID );
if ( $poll->isClosed() )
{
    eZHTTPTool::header( "Location: /poll/result/$PollID" );
    exit();
}

// Check if the poll is anonymous or not.
$poll = new eZPoll( $PollID );
if ( !$poll->anonymous() )
{
    $user = eZUser::currentUser();
    if ( !$user )
    {
        eZHTTPTool::header( "Location: /user/user/new/" );
        exit();
    }
}
else
{
    $vote = new eZVote();
    if ( $vote->ipHasVoted( $REMOTE_ADDR, $PollID ) == true )
    {
        $Voted = false;
    }
    else
    {
        $Voted = true;
    }
    
//      if ( $session->variable( "VoteOnID".$PollID ) )
//      {
//          $Voted = true;
//      }
//      else
//      {
//          $session->setVariable( "VoteOnID".$PollID, "true" );
//          $Voted = false;
//      }
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

eZHTTPTool::header( "Location: /poll/result/" . $PollID );
exit();

?>
