<form method="post" action="/poll/userlogin/vote/{poll_id}/">
<h1>{head_line}</h1>

<hr noshade="noshade" size="4">

<p>{description}</p>

<table cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN vote_item_tpl -->
<tr>
	<td width="1%">
	<input type="radio" value="{choice_id}" name="ChoiceID">
	</td>
	<td width="99%">
	{choice_name}
	</td>
</tr>
<!-- END vote_item_tpl -->
<!-- BEGIN no_items_tpl -->
<p class="error">{intl-no_items_found}</p>
<!-- END no_items_tpl -->

</table>
<br />
<hr noshade="noshade" size="4">

<!-- BEGIN vote_buttons_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input class="okbutton" type="submit" value="{intl-vote}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="/poll/result/{poll_id}">
	<input class="okbutton" type="submit" value="{intl-result}">
	</form>
	</td>
</tr>
</table>
<!-- END vote_buttons_tpl -->
