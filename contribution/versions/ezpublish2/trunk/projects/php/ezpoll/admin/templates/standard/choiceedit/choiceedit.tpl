<h1>{head_line}</h1>


<form method="post" action="/poll/choiceedit/{action_value}/{poll_id}/{choice_id}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{intl-name}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<td>
	<br>{intl-offset}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Offset" value="{offset_value}"/>
	</td>
</tr>

<tr>
	<td>
	<input type="hidden" name="PollID" value="{poll_id}" />
	<br><input type="submit" value="OK" />
	</td>
</tr>

</table>
</form>


