<hr noshade size="4" />
	/
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	/
    <a class="path" href="/forum/category/{category_id}/">{category_name}</a>
	/
	<a class="path" href="/forum/category/forum/{forum_id}">{forum_name}</a>

<hr noshade size="4" />
<h1>{intl-headline}</h1>
<form action="/forum/messageedit/insert/{forum_id}/" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <th>
    {intl-topic}
    </th>
</tr>
<tr>
    <td>
    <input type="text" name="Topic" size="25">
    </td>
</tr>
<tr>
	<th>
	{intl-author}
	</th>
</tr>
<tr>
        <td>
	{user}
	</td>
</tr>
<tr>
	<th>
	{intl-text}
	</td>
</tr>
<tr>
        <th>
        <textarea wrap="soft" name="Body" rows="14" cols="70" class="body"></textarea>
        </td>
</tr>
<tr>
        <td>
	<input type="checkbox" name="notice"> {intl-email-notice}&nbsp;&nbsp;&nbsp;
	<input type="submit" name="post" value="{intl-post}">
	</td>
</tr>
</table>
</form>
