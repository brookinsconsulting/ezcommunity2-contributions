<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{topic}</h1>
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

	<img src="/ezforum/images/path-arrow.gif" height="10" width="15" border="0">
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	<img src="/ezforum/images/path-slash.gif" height="10" width="20" border="0">
    <a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="/ezforum/images/path-slash.gif" height="10" width="20" border="0">
	<a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>
	<img src="/ezforum/images/path-slash.gif" height="10" width="20" border="0">	
    <a class="path" href="/forum/message/{message_id}/">{message_topic}</a>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>        
   	<td>
	<p class="boxtext">{intl-topic}:</p>
    {topic}
	</td>
    <td>
	<p class="boxtext">{intl-author}:</p>
    {user}
	</td>
	<td>
	<p class="boxtext">{intl-time}:</p>
	<span class="small">{postingtime}</span>
	</td>
</tr>
</table>


<p class="boxtext">{intl-text}</p>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>        
   	<td class="bglight">
	{body}
	</td>
</tr>
<tr>
	<td>
	<form method="post" action="/forum/messageedit/edit/{message_id}">
	<input class="stdbutton" type="submit" value="{intl-edit}">
	</forum>
	<form method="post" action="/forum/messageedit/delete/{message_id}">
	<input class="stdbutton" type="submit" value="{intl-delete}">
	</forum>
	</td>
</tr>

</table>

<br />

<br />

<h2>{intl-message_thread}</h2>

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th>{intl-reply-topic}:</th>
    <th>{intl-reply-author}:</th>
    <th>{intl-reply-time}:</th>
</tr>

    <!-- BEGIN message_item_tpl -->
<tr>
    	<td class="{td_class}">
	   {spacer}&nbsp;
	<a href="/forum/message/{message_id}/">
	{reply_topic}
	</a>
	</td>
    	<td class="{td_class}">
	{user}
	</td>
    	<td class="{td_class}">
	<span class="small">{postingtime}</span>
	</td>
</tr>
    <!-- END message_item_tpl -->
</table>


