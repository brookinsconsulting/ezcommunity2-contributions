<?
// 
// $Id: polledit.php,v 1.11 2000/10/26 13:08:34 ce-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <21-Sep-2000 10:39:19 ce>
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
include_once( $DOC_ROOT . "/classes/ezpollchoice.php" );
include_once( $DOC_ROOT . "/classes/ezvote.php" );
        
require( "ezuser/admin/admincheck.php" );

// Insert
if ( $Action == "Insert" )
{
    $poll = new eZPoll();
    if ( $IsEnabled == "on" )
    {
        $poll->setIsEnabled ( true );
    }
    else
    {
        $poll->setIsEnabled ( false );
    }

    if ( $IsClosed == "on" )
    {
        $poll->setIsClosed ( true );
    }
    else
    {
        $poll->setIsClosed ( false );
    }

    if ( $ShowResult == "on" )
    {
        $poll->setShowResult ( true );
    }
    else
    {
        $poll->setShowResult ( false );
    }

    if ( $Anonymous == "on" )
    {
        $poll->setAnonymous ( true );
    }
    else
    {
        $poll->setAnonymous ( false );
    }

    if ( !$Description )
    {
        $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/polledit.php.ini", false );
        $Description =  $ini->read_var( "strings", "description_default" );
    }

    $poll->setName( $Name );
    $poll->setDescription( $Description );
    $poll->store();

    $pollID = $poll->id();
    if ( isset ( $Choice ) )
    {
        Header( "Location: /poll/choiceedit/new/" . $pollID . "/" );
        exit();
    }
    Header( "Location: /poll/pollist/" );
    exit();
}

// Update
if ( $Action == "Update" )
{
    $poll = new eZPoll();
    $poll->get( $PollID );

    if ( $IsEnabled == "on" )
    {
        $poll->setIsEnabled ( true );
    }
    else
    {
        $poll->setIsEnabled ( false );
    }

    if ( $IsClosed == "on" )
    {
        $poll->setIsClosed ( true );
    }
    else
    {
        $poll->setIsClosed ( false );
    }

    if ( $ShowResult == "on" )
    {
        $poll->setShowResult ( true );
    }
    else
    {
        $poll->setShowResult ( false );
    }

    if ( $Anonymous == "on" )
    {
        $poll->setAnonymous ( true );
    }
    else
    {
        $poll->setAnonymous ( false );
    }

    $poll->setName( $Name );
    $poll->setDescription( $Description );
    $poll->store();
    if ( isset ( $Choice ) )
    {
        Header( "Location: /poll/choiceedit/new/" . $PollID . "/" );
        exit();
    }

    Header( "Location: /poll/pollist/" );
    exit();
}

// Delete
if ( $Action == "Delete" )
{
    $poll = new eZPoll();    
    $poll->get( $PollID );
    $poll->delete();

    Header( "Location: /poll/pollist/" );
    exit();
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     $DOC_ROOT . "/admin/intl/", $Language, "polledit.php" );

$t->setAllStrings();

$t->set_file( array( "poll_edit_page" => "polledit.tpl"
                     ) );

$t->set_block( "poll_edit_page", "poll_choice_tpl", "poll_choice" );


$Action_value = "insert";
$Name = "";
$Description = "";
$IsEnabled = "";
$IsClosed = "";
$Anonymous = "";
$nopolls = "";
// Edit
if ( $Action == "Edit" )
{
    $poll = new eZPoll();
    $poll->get( $PollID );

    $Name = $poll->name();
    $Description = $poll->description();

    if ( $poll->isEnabled() == true )
    {
        $IsEnabled = "checked";
    }

    if ( $poll->isClosed() == true )
    {
        $IsClosed = "checked";
    }

    if ( $poll->showResult() == true )
    {
        $ShowResult = "checked";
    }

    if ( $poll->anonymous() == true )
    {
        $Anonymous = "checked";
    }

    $Action_value = "update";
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/polledit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );
}

// Poll choice list
$pollChoice = new eZPollChoice();

$pollChoiceList = $pollChoice->getAll( $PollID );

if ( !$pollChoiceList )
{
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/polledit.php.ini", false );
    $nopolls =  $ini->read_var( "strings", "nopolls" );
    $t->set_var( "poll_choice", "" );
    
}

$i=0;
foreach( $pollChoiceList as $pollChoiceItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->set_var( "choice_id", $pollChoiceItem->id() );
    $t->set_var( "poll_choice_name", $pollChoiceItem->name() );
    $vote = new eZVote();
    $t->set_var( "poll_number", $pollChoiceItem->voteCount() );

    $t->parse( "poll_choice", "poll_choice_tpl", true );
    $i++;
}

$t->set_var( "poll_id", $PollID );
$t->set_var( "name_value", $Name );
$t->set_var( "description_value", $Description );
$t->set_var( "is_enabled", $IsEnabled );
$t->set_var( "is_closed", $IsClosed );
$t->set_var( "show_result", $ShowResult );
$t->set_var( "anonymous", $Anonymous );

$t->set_var( "document_root", $DOC_ROOT );
$t->set_var( "action_value", $Action_value );
$t->set_var( "nopolls", $nopolls );


if ( !isset ( $headline ) )
{
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/polledit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_insert" );
}
$t->set_var( "head_line", $headline );


$t->pparse( "output", "poll_edit_page" );
?>
