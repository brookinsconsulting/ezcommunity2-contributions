<h2>{intl-headline}</h2>

<hr noshade size="4" />

<!-- BEGIN message_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th>{intl-topic}:</th>
    <th>{intl-author}:</th>
    <th>{intl-time}:</th>
</tr>

    <!-- BEGIN message_item_tpl -->
    <tr>
    	<td class="{td_class}">
	   {spacer}{spacer}
		<a href="/forum/message/{message_id}/">
		{topic}
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

<!-- END message_list_tpl -->

<a href="/forum/category/forum/{forum_id}/?Offset={prev_offset}&Limit={limit}">
{previous}
</a>

<a href="/forum/category/forum/{forum_id}/?Offset={next_offset}&Limit={limit}">
{next}
</a>

<form action="/forum/userlogin/new/{forum_id}">

<hr noshade size="4" />

<input class="okbutton" type="submit" value="{intl-new-posting}" />
</form>

