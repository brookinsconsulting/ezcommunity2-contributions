<form method="post" action="/forum/messageedit/{action_value}/{category_id}/{forum_id}/{message_id}">

<h1>{headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-topic}</p>
	<input type="text" size="40" name="Topic" value="{message_topic}">
	</td>
	<td>
	<p class="boxtext">{intl-author}</p>
	<input type="text" size="40" name="User" value="{message_user}">
	</td>
</tr>
</table>
	
<p class="boxtext">{intl-time}</p>
{message_postingtime}

<p class="boxtext">{intl-body}</p>
<textarea rows="10" cols="80" name="Body">{message_body}</textarea>

<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="OK">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	Avbryt!
	</td>
</tr>
</table>
