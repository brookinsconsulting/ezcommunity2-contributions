<form action="/message/list/" method="post">

<h1>{intl-message_from} {from_user_first_name} {from_user_last_name} </h1>

<hr size="4" noshade="noshade" />
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-message_subject}:
	</th>

	<th>
	{intl-message_date}:
	</th>
</tr>
<tr>
	<td valign="top">
	{message_subject}
	</td>

	<td valign="top">
	{message_date}
	</td>
</tr>
<tr>
	<th colspan="2">
	{intl-message_message}:
	</th>

</tr>
<tr>
	<td valign="top" colspan="2">
	{message_message}
	</td>
</tr>
</tr>
</table>
<br />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

</form>
