<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4">

<br />

<!-- BEGIN result_list_tpl -->

<h2>{poll_name}</h2>

<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN result_item_tpl -->
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
	<td width="1%">
	{choice_number}: {choice_name}
	 {choice_percent}% / {choice_vote} votes
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td width="{choice_percent}%" bgcolor="#ffee00">
	&nbsp;
	</td>
	<td width="{choice_inverted_percent}%"  bgcolor="#eeeeee">
	&nbsp;
	</td>
	</tr>
	</table>
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

<form method="post" action="/poll/polls">
<input type="submit" value="{intl-more}">
</form>

<!-- END result_list_tpl -->
