<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-posted_headline}</h1>
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
	<a class="path" href="/forum/messagelist/{message_id}">{message_topic}</a>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN umoderated_item_tpl -->

{intl-posted_info_1} <a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>.
{intl-posted_info_2}. {intl-posted_info_2} <a class="path" href="/forum/messageedit/edit/{message_id}">{message_topic}</a>.

<!-- END unmoderated_item_tpl -->

<hr noshade="noshade" size="4" />

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
