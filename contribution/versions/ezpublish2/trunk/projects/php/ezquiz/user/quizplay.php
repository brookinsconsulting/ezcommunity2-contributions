<?
// 
// $Id: quizplay.php,v 1.2 2001/05/30 08:30:01 pkej Exp $
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <28-May-2001 11:24:41 pkej>
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

// First check if this game is open
// Check if there are other open games

include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezquiz/classes/ezquizquestion.php" );
include_once( "ezquiz/classes/ezquizgame.php" );
include_once( "ezquiz/classes/ezquizanswer.php" );
include_once( "ezquiz/classes/ezquizscore.php" );

$t = new eZTemplate( "ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                     "ezquiz/user/intl/", $Language, "quiz.php" );

if( isset( $SaveButton ) )
{
    // Do we have the same user uploading the data?
    // Me? Paranoid?
    if( $UserID == $user->id() )
    {
        $question = new eZQuizQuestion( $QuestionID );
        $game = $question->game();
        $score = new eZQuizScore();
        $score->getUserGame( $user, $game );
        $score->setNextQuestion( $QuestionNum );
        $score->store();
    }
}



if( isset( $NextButton ) )
{
    // Do we have the same user uploading the data?
    // Me? Paranoid?
    if( $UserID == $user->id() )
    {
        $question = new eZQuizQuestion( $QuestionID );
        $alternative = new eZQuizAlternative( $AlternativeID );

        if( $question->isAlternative( $alternative ) )
        {
            if( $alternative->isCorrect() )
            {
                $scoreValue = $question->score();
                if( empty( $scoreValue ) )
                {
                    $scoreValue = 1;
                }
                
                
            }
            $answer = new eZQuizAnswer();
            
            $answer->setUser( $user );
            $answer->setAlternative( $alternative );
            $game = $question->game();
            $score = new eZQuizScore();
            $score->getUserGame( $user, $game );
            
            if( $answer->hasAnswered() )
            {
                // Answered this question before!
                
                $QuestionNum = $score->nextQuestion();
                echo $QuestionNum;
            }
            else
            {
                $totalScore = $score->totalScore() + $scoreValue;
                $score->setTotalScore( $totalScore );
                $score->setGame( $game );
                $score->setUser( $user );
                $score->setNextQuestion( $QuestionNum );
                $answer->store();
                $score->store();
            }
        }
    }
}

$t->setAllStrings();

$t->set_file( array(
    "question_page_tpl" => "question.tpl"
    ) );

$t->set_block( "question_page_tpl", "question_item_tpl", "question_item" );
$t->set_block( "question_page_tpl", "start_item_tpl", "start_item" );
$t->set_block( "question_item_tpl", "alternative_item_tpl", "alternative_item" );

$t->set_var( "start_item", "" );
$t->set_var( "question_item", "" );

$game = new eZQuizGame( $GameID );
$t->set_var( "game_id", $GameID );
$t->set_var( "user_id", $user->id() );
$t->set_var( "game_name", $game->name() );
$t->set_var( "questions", $game->numberOfQuestions() );
$t->set_var( "players", $game->numberOfPlayers() );

// Find out which question this user should start on (has he saved earlier info).

if( $QuestionNum == 0 )
{
    $t->parse( "start_item", "start_item_tpl" );
}
else
{
    $score = new eZQuizScore();
    $score->getUserGame( $user, $game );

    if( $score->nextQuestion() >= 1 )
    {
        $QuestionNum = $score->nextQuestion();
    }
    
    $questionCount = $game->numberOfQuestions();
    $currentQuestion = $game->question( $QuestionNum );


    if( $questionCount <= 0 )
    {
        echo "no questions defined";$t->set_var( "question_item", "" );
    }
    else if( $QuestionNum <= $questionCount )
    {
        // Sjekk at antall alternativer er større enn null.
        // Sjekk at man ikke prøver å svare på et allerede svart spørsmål
        // Sjekk at man ikke prøver å hoppe over et ubesvart spørsmål.
        $t->set_var( "question_id", $currentQuestion->id() );

        $t->set_var( "placement", $QuestionNum );
        if( $QuestionNum < $questionCount )
        {
            $QuestionNum++;
        }
        $t->set_var( "next_question_num", $QuestionNum );
        $t->set_var( "question_name", $currentQuestion->name() );

        $alternatives = $currentQuestion->alternatives();
        
        $i = 0;
        
        foreach( $alternatives as $alternative )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_class", "bgdark" );
            }
            $t->set_var( "alternative_id", $alternative->id() );
            $t->set_var( "alternative_name", $alternative->name() );
            $t->parse( "alternative_item", "alternative_item_tpl", true );
            $i++;
        }
        $t->parse( "question_item", "question_item_tpl" );
    }
    else
    {
        // finished game.
    }
}

$t->pparse( "output", "question_page_tpl" );

?>
