<?
// 
// $Id: choiceedit.php,v 1.3 2000/10/06 09:59:31 ce-cvs Exp $
//
// Definition of eZPollChoice class
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
if ( $Action == "insert" )
{
    $choice = new eZPollChoice();
    $choice->setName( $Name );
    $choice->setPollID( $PollID );
    $choice->setOffset( $Offset );
    $choice->store();

    Header( "Location: /poll/polledit/edit/" . $PollID . "/" );
    exit();
}

// Update
if ( $Action == "update" )
{
    $choice = new eZPollChoice();
    $choice->get( $ChoiceID );
    $choice->setName( $Name );
    $choice->setPollID( $PollID );
    $choice->setOffset( $Offset );
    $choice->store();

    Header( "Location: /poll/polledit/edit/" . $PollID );
    exit();
}

// Delete
if ( $Action == "delete" )
{
    $choice = new eZPollChoice();    
    $choice->get( $ChoiceID );
    $choice->delete();

    Header( "Location: /poll/polledit/edit/" . $PollID );
    exit();
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZPollMain", "TemplateDir" ) . "/choiceedit/",
                     $DOC_ROOT . "/admin/intl/", $Language, "choiceedit.php" );

$t->setAllStrings();

$t->set_file( array( "choice_edit_page" => "choiceedit.tpl" ) );

$Name = "";
$Offset = "";
$Action_value = "insert";

// Edit
if ( $Action == "edit" )
{
    $choice = new eZPollChoice();
    $choice->get( $ChoiceID );

    $Name =  $choice->name();
    $Offset =  $choice->offset();
    $Action_value = "update";

    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/choiceedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );

}

$t->set_var( "choice_id", $ChoiceID );
$t->set_var( "poll_id", $PollID );
$t->set_var( "name_value", $Name );
$t->set_var( "offset_value", $Offset );
$t->set_var( "action_value", $Action_value );

if ( !isset ( $headline ) )
{
    $ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/choiceedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_insert" );
}

$t->set_var( "head_line", $headline );

$t->pparse( "output", "choice_edit_page" );

?>
