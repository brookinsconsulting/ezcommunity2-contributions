<table width="100%" cellspacing="0" cellpadding="0" border="0">
<form method="post" action="/poll/vote/{poll_id}/">
<tr>
	<td class="menuhead" bgcolor="#c82828">{intl-headline}</td>
</tr>
<tr>
<td>
<h3>{head_line}</h3>
<td>
</tr>
<!-- BEGIN vote_item_tpl -->
<tr>
	<td>
	<input type="radio" value="{choice_id}" name="ChoiceID">
	{choice_name}
	</td>
</tr>
<!-- END vote_item_tpl -->
<tr>
	<td>
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input type="submit" value="Vote" />
	</td>
</tr>
<tr>
	<td>
	<a href="/poll/result/{poll_id}">{intl-result}</a> <a href="/poll/polls">{intl-polls}</a>
	</td>
</tr>
</form>
</table>
