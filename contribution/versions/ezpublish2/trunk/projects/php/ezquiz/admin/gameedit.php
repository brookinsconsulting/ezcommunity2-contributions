<?
// 
// $Id: gameedit.php,v 1.2 2001/05/28 11:14:35 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <22-May-2001 13:44:13 ce>
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

include_once( "ezquiz/classes/ezquizgame.php" );

if ( isSet ( $OK ) )
{
    $Action = "Update";
}
if ( isSet ( $Delete ) )
{
    $Action = "Delete";
}
if ( isSet ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /quiz/game/list/" );
    exit();
}
if ( isSet ( $NewQuestion ) )
{
    $game = new eZQuizGame( $GameID );
    $question = new eZQuizQuestion();
    $question->setGame( $game );
    $question->store();
    $questionID = $question->id();
    eZHTTPTool::header( "Location: /quiz/game/questionedit/$questionID" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZQuizMain", "Language" );

$t = new eZTemplate( "ezquiz/admin/" . $ini->read_var( "eZQuizMain", "AdminTemplateDir" ),
                     "ezquiz/admin/" . "/intl", $Language, "gameedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "game_edit_page" => "gameedit.tpl"
      ) );

$t->set_block( "game_edit_page", "question_list_tpl", "question_list" );
$t->set_block( "question_list_tpl", "question_item_tpl", "question_item" );

$t->set_var( "game_name", "$Name" );
$t->set_var( "game_description", "$Description" );

$t->set_var( "start_month", "$StartMonth" );
$t->set_var( "start_day", "$StartDay" );
$t->set_var( "start_year", "$StartYear" );

$t->set_var( "stop_month", "$StopMonth" );
$t->set_var( "stop_day", "$StopDay" );
$t->set_var( "stop_year", "$StopYear" );

$t->set_var( "game_id", "$GameID" );

if ( ( $Action == "Insert" ) || ( $Action == "Update" ) && ( $user ) )
{
    if ( is_numeric( $GameID ) )
        $game = new eZQuizGame( $GameID);
    else
        $game = new eZQuizGame();
    $game->setName( $Name );
    $game->setDescription( $Description );

    $startDate = new eZDate();
    $startDate->setMonth( $StartMonth );
    $startDate->setDay( $StartDay );
    $startDate->setYear( $StartYear );

    $stopDate = new eZDate();
    $stopDate->setMonth( $StopMonth );
    $stopDate->setDay( $StopDay );
    $stopDate->setYear( $StopYear );

    $game->setStartDate( $startDate );
    $game->setStopDate( $stopDate );

    $game->store();

    if ( count ( $QuestionArrayID ) > 0 )
    {
        for( $i=0; $i < count ( $QuestionArrayID ); $i++ )
        {
            $question = new eZQuizQuestion( $QuestionArrayID[$i] );
            $question->setName( $QuestionArrayName[$i] );
            $question->store();
        }
        unset( $question );
    }
    

    if ( isSet ( $OK ) )
    {
        eZHTTPTool::header( "Location: /quiz/game/list/" );
        exit();
    }
}

if ( $Action == "Delete" )
{
    if ( count ( $GameArrayID ) > 0 )
    {
        foreach( $GameArrayID as $GameID )
        {
            $game = new eZQuizGame( $GameID );
            $game->delete();
        }
    }
    eZHTTPTool::header( "Location: /quiz/game/list/" );
    exit();
}

if ( is_numeric( $GameID ) )
{
    if ( get_class( $game ) != "ezquizgame" )
        $game = new eZQuizGame( $GameID );
    $t->set_var( "game_id", $game->id() );
    $t->set_var( "game_name", $game->name() );
    $t->set_var( "game_description", $game->description() );

    $startDate =& $game->startDate();
    $stopDate =& $game->stopDate();

    $t->set_var( "start_day", $startDate->day() );

    $questionList =& $game->questions();
}

if ( count ( $questionList ) > 0 )
{
    $i=0;
    foreach( $questionList as $question )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "question_id", $question->id() );
        $t->set_var( "question_name", $question->name() );
        $t->set_var( "question_score", $question->score() );

        $i++;
        $t->parse( "question_item", "question_item_tpl", true );
    }
    $t->parse( "question_list", "question_list_tpl", true );
}

$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "game_edit_page" );
?>
