<?
// 
// $Id: votebox.php,v 1.3 2000/10/27 10:20:53 ce-cvs Exp $
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
$iniError = new INIFile( "ezpoll/user/intl/" . $Language . "/votebox.php.ini", false );

include_once( "ezpoll/classes/ezpoll.php" );
include_once( "ezpoll/classes/ezpollchoice.php" );

$t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     "ezpoll/user/intl/", $Language, "votebox.php" );

$t->setAllStrings();

$poll = new eZPoll( $PollID );

if ( $poll->isClosed() )
{
    Header( "Location: /poll/result/$PollID" );
    exit();
}

$t->set_file( array(
    "vote_box" => "votebox.tpl"
    ) );

$t->set_block( "vote_box", "vote_item_tpl", "vote_item" );

$choice = new eZPollChoice();

$choiceList = $choice->getAll( $PollID );

if ( !$choiceList )
{
    $noitem = $iniError->read_var( "strings", "noitem" );
    $t->set_var( "vote_item", $noitem );
}

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
