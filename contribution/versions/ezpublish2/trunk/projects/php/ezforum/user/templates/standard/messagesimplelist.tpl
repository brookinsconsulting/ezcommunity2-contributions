<form action="/forum/userlogin/newsimple/{forum_id}">

<h2>{intl-headline}</h2>

<hr noshade size="4" />

<!-- BEGIN message_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th width="40%">{intl-topic}:</th>
    <th width="30%">{intl-author}:</th>
    <th width="30%"><div align="right">{intl-time}:</div></th>
</tr>

    <!-- BEGIN message_item_tpl -->
    <tr>
    	<td class="{td_class}">
	   {spacer}{spacer}
		{topic}
		</td>
    	<td class="{td_class}">
	    {user}
	    </td>
    	<td class="{td_class}" align="right">
	   <span class="small">{postingtime}</span>
	   </td>
    </tr>
    <tr>
    <td colspan="3">
    <p>
    {body}
    </p>
    <a class="path" href="/forum/userlogin/replysimple/{forum_id}/{message_id}/?RedirectURL={redirect_url}">[ {intl-reply} ]</a><br />
	<br />
    </td>
    </tr>
    <!-- END message_item_tpl -->

</table>

<!-- END message_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a class="path" href="/forum/category/forum/{forum_id}/?Offset={prev_offset}&Limit={limit}">{previous}</a>
	</td>
	<td align="right">
	<a class="path" href="/forum/category/forum/{forum_id}/?Offset={next_offset}&Limit={limit}">{next}</a>
	</td>
</tr>
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" value="{intl-new-posting}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

