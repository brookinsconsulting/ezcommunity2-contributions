<h1>{intl-high_score_list} {game_name}</h1>

<hr noshade="noshade" size="4" />
<!-- BEGIN error_item_tpl -->
<h2 class="error">{intl-error}</h2>
<p class="error">{error_message}</p>
<!-- END error_item_tpl -->

<!-- BEGIN score_list_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th width="1%">{intl-position}</td>
	<th>{intl-score}</td>
	<th>{intl-player}</td>
</tr>

<!-- BEGIN score_item_tpl -->
<tr class="{td_class}">
	<td width="1%">{score_position}</td>
	<td>{score}</td>
	<td>{player}<!-- {player_id} --></td>
</tr>
<!-- END score_item_tpl -->

</table>
<!-- END score_list_item_tpl -->

<!-- BEGIN no_scores_item_tpl -->
<h2>{intl-no_scores}</h2>
{intl-no_players}.
<!-- END no_scores_item_tpl -->

<!-- BEGIN not_closed_item_tpl -->
<h2>{intl-no_scores}</h2>
{intl-not_closed}. {intl-closes_at} {game_stop}.
<!-- END not_closed_item_tpl -->

<!-- BEGIN future_item_tpl -->
<h2>{intl-no_scores}</h2>
{intl-in_the_future}. {intl-opens_at} {game_start}.
<!-- END future_item_tpl -->
