
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-search}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>


<hr noshade size="4" />

<h2>{intl-searchfor} "{query_string}"</h2>
<br>

<!-- BEGIN empty_result_tpl -->
<h3 class="error">{intl-empty_result}</h3>
<!-- END empty_result_tpl -->


<!-- BEGIN search_result_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
    <th>{intl-topic}:</th>
    <th>{intl-author}:</th>
    <th>{intl-time}:</th>
</tr>
    <!-- BEGIN message_tpl -->
<tr>
    	<td class="{td_class}">
	<a href="/forum/message/{message_id}/">
	{message_topic}
	</a>
	</td>
    	<td class="{td_class}">
	{user}
	</td>
    	<td class="{td_class}">
	{postingtime}
	</td>

     <td width="1%" class="{td_class}">
	 <a href="/forum/messageedit/edit/{message_id}/"  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-red','','/ezforum/images/redigerminimrk.gif',1)"><img name="efm{message_id}-red" border="0" src="/ezforum/images/redigermini.gif" width="16" height="16" align="top"></a>
     </td>
	 <td width="1%" class="{td_class}">
	 <a href="/forum/messageedit/delete/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-slett','','/ezforum/images/slettminimrk.gif',1)"><img name="efm{message_id}-slett" border="0" src="/ezforum/images/slettmini.gif" width="16" height="16" align="top"></a>
	 </td>

</tr>
    <!-- END message_tpl -->

</table>
<!-- END search_result_tpl -->

<!-- BEGIN previous_tpl -->
<a href="/forum/search/?Offset={prev_offset}&URLQueryString={url_query_string}">
{intl-prev}
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="/forum/search/?Offset={next_offset}&URLQueryString={url_query_string}">
{intl-next}
</a>
<!-- END next_tpl -->
