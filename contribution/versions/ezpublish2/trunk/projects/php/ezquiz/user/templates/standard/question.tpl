<h1>You are playing: {game_name}</h1>
<p>{players} people have played this game before you</p>
<p>Current high score is {high_score} by {scorer}</p>

<!-- BEGIN start_item_tpl -->
<h2>{intl-instructions}</h2>
<p>{intl-several_alternatives}. {intl-select_your_choice}.</p>
<p>{intl-no_turning_back}. {intl-no_return}.</p>
<p>{intl-your_score}.</p>
<p>{intl-answer_before_end_date}.</p>
<p>{intl-save_and_return}. {intl-also_saved_games}.</p>
<p><a href="/quiz/game/play/{game_id}/1">{intl-start_game}</a></p>
<p><a href="/quiz/game/scores/{game_id}">{intl-view_high_score}</a></p>
<!-- END start_item_tpl -->

<!-- BEGIN question_item_tpl -->
<h2>Question {placement}/{questions}: {question_name}</h2>
Alternatives:

<form method="post" action="/quiz/game/play/{game_id}/{next_question_num}/">
<table width="100%" cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN alternative_item_tpl -->
<tr>
<td width="1%"><input type="radio" value="{alternative_id}" name="AlternativeID"></td>
<td class="menutext" width="99%">{alternative_name}</td>
</tr>
<!-- END alternative_item_tpl -->

<tr>
        <td colspan="2">
        <input type="hidden" name="QuizID" value="{game_id}" />
        <input class="stdbutton" type="submit" value="{intl-next}" />
        </td>
</tr>
</table>
</form>
<!-- END question_item_tpl -->
