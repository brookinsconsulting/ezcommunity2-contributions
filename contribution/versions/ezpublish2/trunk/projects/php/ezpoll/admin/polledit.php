<?
// 
// $Id: polledit.php,v 1.2 2000/10/02 11:58:14 bf-cvs Exp $
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
        



// Insert
if ( $Action == "Insert" )
{
    $poll = new eZPoll();
    if ( $IsEnabled == "on" )
    {
        $poll->setIsEnabled ( "true" );
    }
    else
    {
        $poll->setIsEnabled ( "false" );
    }

    if ( $IsClosed == "on" )
    {
        $poll->setIsClosed ( "true" );
    }
//      else
//      {
//          $poll->setIsClosed ( "false" );
//      }




//      if ( $ShowResult == "on" )
//      {
//          $poll->setIsClosed ( "true" );
//      }
//      else
//      {
//          $poll->setIsClosed ( "false" );
//      }
//      if ( $Anonymous == "on" )
//      {
//          $poll->setAnonymous ( "true" );
//      }
//      else
//      {
//          $poll->setAnonymous ( "false" );
//      }
//      if ( $Number == "on" )
//      {
//          $poll->setNumber ( "true" );
//      }
//      else
//      {
//          $poll->setAnonymous ( "false" );
//      }
//      if ( $Percent == "on" )
//      {
//          $poll->setPercent ( "true" );
//      }
//      else
//      {
//          $poll->setAnonymous ( "false" );
//      }

    $poll->setName( $Name );
    $poll->setDescription( $Description );
//    $poll->setIsClosed($Closed );
    
//      $poll->setShowResult = $ShowResult; // Not included in eZPoll
//      $poll->setAnonymous = $Anonymus; // Not included in eZPoll
//      $poll->setNumber = $Number; // Not included in eZPoll
//      $poll->setPercent = $Percent; // Not included in eZPoll

    $poll->store();

    $pollID = $poll->id();
    if ( isset ( $Choice ) )
    {
        Header( "Location: /poll/choiceedit/new/" . $pollID . "/" );
        exit();
    }
    Header( "Location: /poll/polllist/" );
    exit();
}

// Update
if ( $Action == "Update" )
{
    $poll = new eZPoll();
    $poll->get( $PollID );

    if ( $IsEnabled == "on" )
    {
        $poll->setIsEnabled ( "true" );
    }
    else
    {
        $poll->setIsEnabled ( "false" );
    }
    
    if ( $IsClosed == "on" )
    {
        $poll->setIsClosed ( "true" );
    }
    else
    {
        $poll->setIsClosed ( "false" );
    }

    $poll->setName( $Name );
    $poll->setDescription( $Description );
    $poll->store();
    if ( isset ( $Choice ) )
    {
        Header( "Location: /poll/choiceedit/new/" . $PollID . "/" );
        exit();
    }

    Header( "Location: /poll/polllist/" );
    exit();
}

// Delete
if ( $Action == "Delete" )
{
    $poll = new eZPoll();    
    $poll->get( $PollID );
    $poll->delete();

    Header( "Location: /poll/polllist/" );
    exit();
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/polledit/",
                     $DOC_ROOT . "/admin/intl/", $Language, "polledit.php" );

$t->setAllStrings();

$t->set_file( array( "poll_edit_page" => "polledit.tpl",
                     "poll_choice_item" => "pollchoiceitem.tpl" ) );


$Action_value = "insert";
$Name = "";
$Description = "";
$IsEnabled = "";
$IsClosed = "";

// Edit
if ( $Action == "Edit" )
{
    $poll = new eZPoll();
    $poll->get( $PollID );

    $Name = $poll->name();
    $Description = $poll->description();

    if ( $poll->isEnabled() == "true" )
    {
        $IsEnabled = "checked";
    }

    if ( $poll->isClosed() == "true" )
    {
        $IsClosed = "checked";
    }

    $Action_value = "update";
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/polledit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );

}

// Poll choice list
$pollChoice = new eZPollChoice();

$pollChoiceList = $pollChoice->getAll( $PollID );

foreach( $pollChoiceList as $pollChoiceItem )
{
    $t->set_var( "choice_id", $pollChoiceItem->id() );
    $t->set_var( "poll_choice_name", $pollChoiceItem->name() );
    $vote = new eZVote();
    $t->set_var( "poll_number", $pollChoiceItem->voteCount() );

    $t->parse( "poll_choice_list", "poll_choice_item", true );
}



$t->set_var( "poll_id", $PollID );
$t->set_var( "name_value", $Name );
$t->set_var( "description_value", $Description );
$t->set_var( "is_enabled", $IsEnabled );
$t->set_var( "is_closed", $IsClosed );
$t->set_var( "document_root", $DOC_ROOT );
$t->set_var( "action_value", $Action_value );
if ( !isset ( $headline ) )
{
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/polledit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_insert" );
}
$t->set_var( "head_line", $headline );


$t->pparse( "output", "poll_edit_page" );
?>
