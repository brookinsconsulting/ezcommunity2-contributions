<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>Forum</h1>
    </td>
    <td align="right">
    <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4" />

	/
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>
	/
    <a class="path" href="/forum/category/{category_id}/">{category_name}</a>
	/
	<a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>
	/	
    <a class="path" href="/forum/message/{message_id}/">{message_topic}</a>

<hr noshade size="4" />

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>        
   	<td colspan="2">
	<p class="boxtext">{intl-topic}:</p>
    <p>{topic}</p>
	</td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-author}</p>
    <p>{user}</p>
	</td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-time}</p>
    <p>{postingtime}</p>
	</td>
</tr>
<tr>
 	<td colspan="2">
	<br />
	<p>
	{body}
	</p>
	<br />
	</td>
</tr>
<tr>
    <td colspan="2">
    <form method="post" action="/forum/reply/reply/{reply_id}/">
    <input class="okbutton" type="submit" value="{intl-answer}" />
    <!-- <a href="/forum/reply/reply/{reply_id}/">[{intl-reply}]</a> -->
    </form>

</tr>
</table>

<br /><br />

<h2>{intl-message_thread}</h2>

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th>{intl-reply-topic}</th>
    <th>{intl-reply-author}</th>
    <th>{intl-reply-time}</th>
</tr>

    <!-- BEGIN message_item_tpl -->
<tr>
    	<td class="{td_class}">
	   {spacer}
	<a href="/forum/category/forum/message/{message_id}/{forum_id}/">
	{reply_topic}
	</a>
	</td>
    	<td class="{td_class}">
	{user}
	</td>
    	<td class="{td_class}">
	{postingtime}
	</td>
</tr>
    <!-- END message_item_tpl -->

</table>

<form action="/forum/userlogin/{forum_id}/?Action=NewPost">

<hr noshade size="4" />
    
<input class="okbutton" type="submit" value="New posting" />

</form>


