<h2>{intl-headline}</h2>

<!-- BEGIN message_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th width="45%">{intl-topic}:</th>
    <th width="25%">{intl-author}:</th>
    <th width="30%"><div align="right">{intl-time}:</div></th>
</tr>

    <!-- BEGIN message_item_tpl -->
    <tr>
    	<td class="{td_class}">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="1%">{spacer}{spacer}</td>
		<td width="99%">{topic}</td>
	</tr>
	</table>
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
    <div class="p">
    {body}
    </div>
	<br />
    </td>
    </tr>
    <!-- END message_item_tpl -->

</table>

<!-- END message_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a class="path" href="{www_dir}{index}/forum/category/forum/{forum_id}/?Offset={prev_offset}&Limit={limit}">{previous}</a>
	</td>
	<td align="right">
	<a class="path" href="{www_dir}{index}/forum/category/forum/{forum_id}/?Offset={next_offset}&Limit={limit}">{next}</a>
	</td>
</tr>
</table>

<br />
<form action="{www_dir}{index}/forum/userlogin/newsimple/{forum_id}">
<input class="stdbutton" type="submit" value="{intl-new-posting}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

