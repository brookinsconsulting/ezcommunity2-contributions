<h1>{headline}</h1>
<form method="post" action="/forum/messageedit/{action_value}/{category_id}/{forum_id}/{message_id}">
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>        
	<th>
	{intl-topic}
	</th>

</tr>
<tr>
	<td>
	<input type="text" name="Topic" value="{message_topic}">
	</td>
</tr>
<tr>
	<th>
	{intl-author}
	</th>
</tr>
<tr>
	<td>
	<input type="text" name="User" value="{message_user}">
	</td>
</tr>

<tr>
	<th>
	{intl-time}
	</th>
</tr>
<tr>
	<td>
	{message_postingtime}
	</td>
</tr>

<tr>
	<th>
	{intl-body}
	</th>
</tr>
<tr>
	<td>
	<textarea rows="10" cols="80" name="Body">{message_body}</textarea>
	</td>
</tr>
<tr>
	<td>
	<input type="submit" value="OK">
	</td>
</tr>
</table>
</form>
