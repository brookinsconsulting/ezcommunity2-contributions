<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-preview_headline}: {message_topic}</h1>
    </td>
    <td align="right">
    <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
    <a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
	<a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>
	<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />	
    {message_topic}

<hr noshade="noshade" size="4" />

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>        
   	<td>
	<p class="boxtext">{intl-topic}:</p>
    {message_topic}
	</td>
    <td>
	<p class="boxtext">{intl-author}:</p>
    {message_user}
	</td>
	<td>
	<p class="boxtext">{intl-time}:</p>
	<span class="small">{message_postingtime}</span>
	</td>
</tr>
</table>


<p class="boxtext">{intl-text}:</p>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>        
   	<td class="bglight">
	{message_body}
	</td>
</tr>
</table>

<br />


<hr noshade="noshade" size="4" />

<form method="post" action="/forum/messageedit/{action_value}/{message_id}/">
	<input type="hidden" name="MessageTopic" size="40" value="{message_topic}" />
	<input type="hidden" name="ForumID" value="{forum_id}" size="40" />
	<input type="hidden" name="MessageID" value="{message_id}" size="40" />
	<input type="hidden" name="PreviewID" value="{preview_id}" size="40" />
	<input type="hidden" name="ReplyToID" value="{reply_to_id}" size="40" />
	<input type="hidden" name="ActionValue" value="{action_value}" size="40" />
	<input type="hidden" name="NextAction" value="{next_action}" size="40" />
	<input type="hidden" name="PrevAction" value="{prev_action}" size="40" />
    <input type="hidden" name="TempID" value="{temp_id}" size="40" />
    <input type="hidden" name="MessageBody" value="{message_body}" />
    <input type="hidden" name="MessageNotice" value="{message_notice}">

    <input class="stdbutton" type="submit" name="post" value="{intl-post}" />
    &nbsp;
    <input class="stdbutton" type="submit" name="Edit" value="{intl-edit}" />
    &nbsp;
	<input class="okbutton" type="submit" name="Abort" value="{intl-abort}">
</form>
