<?
// 
// $Id: mainvotebox.php,v 1.2 2000/10/21 13:12:02 bf-cvs Exp $
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


include_once( "ezpoll/classes/ezpoll.php" );

$poll = new eZPoll();
$poll = $poll->mainPoll();
$PollID = $poll->id();

  include( "ezpoll/votebox.php" );

?>
