<?
// 
// $Id: polledit.php,v 1.6 2000/10/10 13:26:03 ce-cvs Exp $
//
// Definition of eZPoll class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <21-Sep-2000 10:39:19 ce>
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

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/polledit/",
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

foreach( $pollChoiceList as $pollChoiceItem )
{
    $t->set_var( "choice_id", $pollChoiceItem->id() );
    $t->set_var( "poll_choice_name", $pollChoiceItem->name() );
    $vote = new eZVote();
    $t->set_var( "poll_number", $pollChoiceItem->voteCount() );

    $t->parse( "poll_choice", "poll_choice_tpl", true );
}

$t->set_var( "poll_id", $PollID );
$t->set_var( "name_value", $Name );
$t->set_var( "description_value", $Description );
$t->set_var( "is_enabled", $IsEnabled );
$t->set_var( "is_closed", $IsClosed );
$t->set_var( "show_result", $ShowResult );

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
