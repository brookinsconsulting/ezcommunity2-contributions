<h2>{head_line}</h2>
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<td>
	Dette ble stemt på:
	</td>
</tr>
<!-- BEGIN choice_item_tpl -->
<tr>
	<td>
	{choice_number}: {choice_name}
	</td>
<tr>
<!-- END choice_item_tpl -->
<tr>
	<td>
	Resultatene:
	</td>
</tr>
<!-- BEGIN result_item_tpl -->
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
	<td width="1%">
	{choice_number}: 
	</td>
	<td width="{choice_percent}%" bgcolor="#ffee00">
	&nbsp;
	</td>
	<td width="{choice_inverted_percent}%"  bgcolor="#ffffff">
	&nbsp;{choice_percent}% / {choice_vote} votes
	</td>
	</tr>
	</table>
	</td>
<tr>
<!-- END result_item_tpl -->
<tr>
	<td>
	</td>
</tr>
<tr>
	<td>
	Total votes: {total_votes}
	</td>
</tr>
</table>
