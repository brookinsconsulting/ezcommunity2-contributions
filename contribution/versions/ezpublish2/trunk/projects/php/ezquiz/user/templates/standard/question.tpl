<h1>{game_name}</h1>

<hr noshade="noshade" size="4" />

<p>{players} people have played this game before you.</p>
<p>Current high score is {high_score} by {scorer}</p>

<!-- BEGIN start_item_tpl -->
<h2>{intl-instructions}</h2>
<p>{intl-several_alternatives}. {intl-select_your_choice}.</p>
<p>{intl-no_turning_back}. {intl-no_return}.</p>
<p>{intl-your_score}.</p>
<p>{intl-answer_before_end_date}.</p>
<p>{intl-save_and_return}. {intl-also_saved_games}.</p>
<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/quiz/game/play/{game_id}/1">{intl-start_game}</a><br />
<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/quiz/game/scores/{game_id}">{intl-view_high_score}</a><br />
<!-- END start_item_tpl -->

<!-- BEGIN question_item_tpl -->
<h2>Question {placement} of {questions}: <br />{question_name}</h2>

<form method="post" action="/quiz/game/play/{game_id}/{next_question_num}/">
<table width="100%" cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN alternative_item_tpl -->
<tr>
	<td width="1%"><input type="radio" value="{alternative_id}" name="AlternativeID"></td>
	<td class="menutext" width="99%">{alternative_name}</td>
</tr>
<!-- END alternative_item_tpl -->

<tr>
        <td>
        <input type="hidden" name="QuizID" value="{game_id}" />
        <input type="hidden" name="QuestionID" value="{question_id}" />
        <input type="hidden" name="UserID" value="{user_id}" />
        <input class="stdbutton" name="NextButton" type="submit" value="{intl-next}" />
        </td>
        <td>
        <input class="stdbutton" name="SaveButton" type="submit" value="{intl-save}" />
        </td>
</tr>
</table>
</form>
<!-- END question_item_tpl -->
