<form method="post" action="/poll/vote/{poll_id}/">

<h2>{head_line}</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN vote_item_tpl -->
<tr>
	<td width="1%">
	<input type="radio" value="{choice_id}" name="ChoiceID">
	</td>
	<td class="small">
	{choice_name}
	</td>
</tr>
<!-- END vote_item_tpl -->
<tr>
	<td colspan="2">
	<br />
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input type="submit" value="Vote" />
	</td>
</tr>
</table>
</form>
