<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form method="post" action="/forum/messageedit/{action_value}/{message_id}">

<p class="error">{error_msg}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">

<tr>
	<td>
	<p class="boxtext">{intl-topic}:</p>
	<input type="text" size="40" name="Topic" value="{message_topic}">
	</td>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{message_user}
	</td>
</tr>
</table>
	
<p class="boxtext">{intl-time}:</p>
{message_postingtime}

<p class="boxtext">{intl-body}:</p>
<textarea rows="10" cols="80" name="Body">{message_body}</textarea>

<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/forum/messagelist/{forum_id}">
	<input class="okbutton" type="submit" value="{intl-cancel}">
	</form>
	</td>
</tr>
</table>
