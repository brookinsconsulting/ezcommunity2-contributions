
<!-- BEGIN game_list_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>Name</th>
    <th>Description</th>
    <th>{intl-start_date}</th>
    <th>{intl-end_date}</th>
    <th>{intl-questions}</th>
    <th>{intl-players}</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>

<!-- BEGIN game_item_tpl -->
<tr class="{td_class}">
    <td><a href="/quiz/game/view/{game_id}">{game_name}&nbsp;</a></td>
    <td>{game_description}&nbsp;</td>
    <td>{game_start}&nbsp;</td>
    <td>{game_stop}&nbsp;</td>
    <td>{game_questions}&nbsp;</td>
    <td>{game_players}&nbsp;</td>
    <td><a href="/quiz/game/scores/{game_id}">{intl-scores}&nbsp;</a></td>
    <td><a href="/quiz/game/play/{game_id}">{intl-play}&nbsp;</a></td>
</tr>
<!-- END game_item_tpl -->
</table>
<!-- END game_list_item_tpl -->

<!-- BEGIN no_game_list_item_tpl -->

<!-- END no_game_list_item_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/quiz/game/list/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="/quiz/game/list/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="/quiz/game/list/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
