<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline} - ({forum_start}-{forum_end}/{forum_total})</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<img src="/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>

<img src="/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>

<img src="/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/forum/messagelist/{forum_id}/">{forum_name}</a>

<hr noshade="noshade" size="4" />

<form method="post" action="/forum/messageedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
     <th>{intl-subject}:</th> 
     <th>{intl-author}:</th>
     <th>{intl-time}:</th>
     <th>{intl-notice}:</th>
     <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN message_item_tpl -->
<tr bgcolor="{color}">
     <td width="33%" class="{td_class}">
     {spacer}
     <a href="/forum/message/{message_id}">{message_topic}</a>
     </td>
     <td width="32%" class="{td_class}">
     {message_user}
     </td>
     <td width="32%" class="{td_class}">
     <span class="small">{message_postingtime}</span>
     </td>
     <td width="1%" class="{td_class}">
     {emailnotice}&nbsp;
     </td class="{td_class}">
     <td width="1%" class="{td_class}">
	 <a href="/forum/messageedit/edit/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="efm{message_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="" alt="Edit" /></a>
     </td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="MessageArrayID[]" value="{message_id}">
	</td>
</tr>
<!-- END message_item_tpl -->
</table>

<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteMessages" value="{intl-deletemessages}">
</form>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/forum/messagelist/{forum_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="/forum/messagelist/{forum_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="/forum/messagelist/{forum_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->



