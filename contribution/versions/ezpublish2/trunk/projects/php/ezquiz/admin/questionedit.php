<?
// 
// $Id: questionedit.php,v 1.2 2001/05/28 11:14:35 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <22-May-2001 16:17:22 ce>
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

include_once( "ezquiz/classes/ezquizquestion.php" );


if ( isSet ( $NewAlternative ) )
{
    $question = new eZQuizQuestion( $QuestionID );
    $alternative = new eZQuizAlternative();
    $alternative->setquestion( &$question );
    $alternative->store();
    $Action = "Update";
}

if ( isSet ( $OK ) )
{
    $Action = "Update";
}

if ( isSet ( $Cancel ) )
{
    $question = new eZQuizQuestion( $QuestionID);
    $game =& $question->game();
    $gameID = $game->id();
    eZHTTPTool::header( "Location: /quiz/game/edit/$gameID" );
    exit();
}

if ( isSet ( $Delete ) )
{
    if ( count ( $AlternativeDeleteArray ) > 0 )
    {
        foreach( $AlternativeDeleteArray as $AltID )
        {
            eZQuizAlternative::delete( $AltID );
        }
        unset ( $alternative );
    }
//    $Action = "Update";
}



$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZQuizMain", "Language" );

$t = new eZTemplate( "ezquiz/admin/" . $ini->read_var( "eZQuizMain", "AdminTemplateDir" ),
                     "ezquiz/admin/" . "/intl", $Language, "questionedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "question_edit_page" => "questionedit.tpl"
      ) );

$t->set_block( "question_edit_page", "alternative_list_tpl", "alternative_list" );
$t->set_block( "alternative_list_tpl", "alternative_item_tpl", "alternative_item" );

$t->set_var( "question_name", "$Name" );
$t->set_var( "question_description", "$Description" );

if ( $Action == "Update" )
{
    if ( is_numeric( $QuestionID ) )
        $question = new eZQuizQuestion( $QuestionID);
    else
        $question = new eZQuizQuestion();
    $question->setName( $Name );
    $question->store();

    if ( count ( $AlternativeArrayID ) > 0 )
    {
        for( $i=0; $i < count ( $AlternativeArrayID ); $i++ )
        {
            $alternative = new eZQuizAlternative( $AlternativeArrayID[$i] );
            $alternative->setName( $AlternativeArrayName[$i] );

            if ( $IsCorrect == $AlternativeArrayID[$i] )
                $alternative->setIsCorrect( true );
            else
                $alternative->setIsCorrect( false );
            $alternative->store();
        }
        unset( $alternative );
    }

    if ( isSet ( $OK ) )
    {
        $game =& $question->game();
        $gameID = $game->id();
        eZHTTPTool::header( "Location: /quiz/game/edit/$gameID" );
        exit();
    }
}

if ( $Action == "Delete" )
{
    if ( count ( $AlternativeArrayID ) > 0 )
    {
        foreach( $AlternativeArrayID as $AlternativeID )
        {
            $alternative = new eZQuizAlternative( $AlternativeID );
            $alternative->delete();
        }
    }
    eZHTTPTool::header( "Location: /quiz/game/question/edit/$GameID" );
    exit();
}

if ( is_numeric( $QuestionID ) )
{
    if ( get_class( $question ) != "ezquizquestion" )
        $question = new eZQuizQuestion( $QuestionID );
    $t->set_var( "question_id", $question->id() );
    $t->set_var( "question_name", $question->name() );

    $alternativeList =& $question->alternatives();
}

if ( count ( $alternativeList ) > 0 )
{
    foreach( $alternativeList as $alternative )
    {
        $t->set_var( "alternative_id", $alternative->id() );
        $t->set_var( "alternative_name", $alternative->name() );

        if ( $alternative->isCorrect() == $alternative->id() )
            $t->set_var( "is_selected", "checked" );
        else
            $t->set_var( "is_selected", "" );

        $t->parse( "alternative_item", "alternative_item_tpl", true );
    }
    $t->parse( "alternative_list", "alternative_list_tpl", true );
}
else
$t->set_var( "alternative_list", "" );


$t->pparse( "output", "question_edit_page" );
?>
