
<hr noshade size="4" />

	/
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	/
    <a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>
	/
	<a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>
	/	
    <a class="path" href="/forum/message/{message_id}/">{topic}</a>

<hr noshade size="4" />

<h1>{info}</h1>

<form action="/forum/reply/insert/{msg_id}/" method="post">
    
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <th>
    Emne:
    </th>
</tr>
<tr>
    <td>
    <input type="text" name="Topic" size="40" value="{topic}">
    </td>
</tr>
<tr>
	<th>
	Forfatter:
        </th>
<tr>
</tr>
	<td>
	{user}
        </td>
</tr>
<tr>
    <th>
    Besvarer:
    </th>
<tr>
</tr>
    <td>
    {replier}
    </td>
</tr>
<tr>
	<th>
	Tekst:
        </th>
<tr>
<tr>
	<td>
    <textarea wrap="soft" name="Body" rows="14" cols="70" rows="10">{body}</textarea>
    </td>
</tr>
    
<tr>
    <td colspan="2">
       <input type="checkbox" name="notice">Email notis
       <input type="submit" name="reply" value="Svar!">
      </td>
</tr>
</table>

<input type="hidden" name="Action" value="Insert" />
</form>
