<?
// 
// $Id: choiceedit.php,v 1.11 2001/01/23 13:16:57 jb Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <21-Sep-2000 10:39:19 ce>
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

include_once( "ezpoll/classes/ezpoll.php" );
include_once( "ezpoll/classes/ezpollchoice.php" );
include_once( "ezpoll/classes/ezvote.php" );

require( "ezuser/admin/admincheck.php" );

// Insert
if ( $Action == "insert" )
{
    $choice = new eZPollChoice();
    $choice->setName( $Name );
    $choice->setPollID( $PollID );
    $choice->setOffset( $Offset );
    $choice->store();

    eZHTTPTool::header( "Location: /poll/polledit/edit/" . $PollID . "/" );
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

    eZHTTPTool::header( "Location: /poll/polledit/edit/" . $PollID );
    exit();
}

// Delete
if ( $Action == "delete" )
{
    $choice = new eZPollChoice();    
    $choice->get( $ChoiceID );
    $choice->delete();

    eZHTTPTool::header( "Location: /poll/polledit/edit/" . $PollID );
    exit();
}

$t = new eZTemplate( "ezpoll/admin/" . $ini->read_var( "eZPollMain", "AdminTemplateDir" ),
                     "ezpoll/admin/intl/", $Language, "choiceedit.php" );

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

    $languageIni = new INIFile( "ezpoll/admin/" . "intl/" . $Language . "/choiceedit.php.ini", false );
    $headline = $languageIni->read_var( "strings", "head_line_edit" );

}

$t->set_var( "choice_id", $ChoiceID );
$t->set_var( "poll_id", $PollID );
$t->set_var( "name_value", $Name );
$t->set_var( "offset_value", $Offset );
$t->set_var( "action_value", $Action_value );

if ( !isset ( $headline ) )
{
    $languageIni = new INIFile( "ezpoll/admin/" . "intl/" . $Language . "/choiceedit.php.ini", false );
    $headline =  $languageIni->read_var( "strings", "head_line_insert" );
}

$t->set_var( "head_line", $headline );

$t->pparse( "output", "choice_edit_page" );

?>
