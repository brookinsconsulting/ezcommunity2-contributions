<form action="/forum/messageedit/{action_value}/{message_id}/" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<h1>{intl-new_headline}</h1>
    </td>
</tr>
</table>

<hr noshade size="4" />

	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
    <a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
	<a class="path" href="/forum/messagelist/{forum_id}">{forum_name}</a>

<hr noshade size="4" />

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_missing_topic_item_tpl -->
<li>{intl-error_missing_topic}.
<!-- END error_missing_topic_item_tpl -->

<!-- BEGIN error_missing_body_item_tpl -->
<li>{intl-error_missing_body}.
<!-- END error_missing_body_item_tpl -->

</ul>

<br />
<hr noshade size="4" />

<!-- END errors_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-topic}:</p>
	<input type="text" name="MessageTopic" size="40" value="{message_topic}" />
	<input type="hidden" name="ForumID" value="{forum_id}" size="40" />
	<input type="hidden" name="MessageID" value="{message_id}" size="40" />
	<input type="hidden" name="PreviewID" value="{preview_id}" size="40" />
	<input type="hidden" name="ReplyToID" value="{reply_to_id}" size="40" />
	<input type="hidden" name="ActionValue" value="{action_value}" size="40" />
	<input type="hidden" name="NextAction" value="{next_action}" size="40" />
	<input type="hidden" name="PrevAction" value="{prev_action}" size="40" />
    <input type="hidden" name="TempID" value="{temp_id}" size="40" />
	</td>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{message_user}
	</td>
	<td>
	<p class="boxtext">{intl-posting_time}:</p>
	{message_postingtime}
	</td>
</tr>
</table>

<p class="boxtext">{intl-text}:</p>
<textarea wrap="soft" name="MessageBody" rows="15" cols="40" class="body">{message_body}</textarea>

<br /><br />

<input type="checkbox" name="MessageNotice" {message_notice}> <span class="check">{intl-email-notice}</span><br />
<br />

<hr noshade size="4" />

	<input class="okbutton" type="submit" name="post" value="{intl-preview}">
    &nbsp;
	<input class="okbutton" type="submit" name="Abort" value="{intl-abort}">
</form>
