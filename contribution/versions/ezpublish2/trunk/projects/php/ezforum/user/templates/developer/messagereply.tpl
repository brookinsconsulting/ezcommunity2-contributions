
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{topic}</h1>
     </td>
     <td align="right">
        <form action="/forum/search/" method="post">
           <input type="text" name="QueryString" size="12" />
           <input type="submit" name="search" value="{intl-search}" />
        </form>
     </td>
  </tr>
</table>

<hr noshade="noshade" size="4" />

	<img src="/images/path-arrow.gif" height="10" width="15" border="0">
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	<img src="/images/path-slash.gif" height="10" width="20" border="0">
    <a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="/images/path-slash.gif" height="10" width="20" border="0">
	<a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>
	<img src="/images/path-slash.gif" height="10" width="20" border="0">
    <a class="path" href="/forum/message/{message_id}/">{topic}</a>

<hr noshade="noshade" size="4" />
  
<form action="/forum/reply/insert/{msg_id}/" method="post">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-topic}:</p>
	<input type="text" name="Topic" size="40" value="{topic}">
	</td>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{user}
	</td>
</tr>
</table>

<p class="boxtext">{intl-text}:</p>
<textarea wrap="soft" name="Body" rows="15" cols="40" rows="10">{body}</textarea>
<br /><br />
    
<input type="checkbox" name="notice"> {intl-email_notice}
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="reply" value="{intl-answer}">
	<input type="hidden" name="Action" value="Insert" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/forum/messagelist/{forum_id}">
	<input class="okbutton" type="submit" value="{intl-abort}">
	</form>
	</td>
</tr>
</table>
