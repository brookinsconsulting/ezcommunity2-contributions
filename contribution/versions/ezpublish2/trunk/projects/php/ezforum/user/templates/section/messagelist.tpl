<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" class="path" width="99%">

	<img src="/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="/forum/categorylist/">{intl-forum-main}</a>
	<img src="/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
    <a class="toppath" href="/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="/forum/messagelist/{forum_id}">{forum_name}</a>

	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
        <form action="/forum/search/" method="post">
           <input type="text" name="QueryString" size="12" />
           <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
        </form>
     </td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
	<td><p class="boxtext">({forum_start}-{forum_end}/{forum_total})</p></td>
     <td align="right">
     <form action="/forum/messagelist/{forum_id}/" method="post">
           <!-- BEGIN hide_threads_tpl -->
           <input class="stdbutton" type="submit" name="HideThreads" value="{intl-hide_threads}" />
           <!-- END hide_threads_tpl -->
           <!-- BEGIN show_threads_tpl -->
           <input class="stdbutton" type="submit" name="ShowThreads" value="{intl-show_threads}" />
           <!-- END show_threads_tpl -->
     </form>
     </td>
</tr>
</table>

<form action="/forum/userlogin/new/{forum_id}">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th width="45%">{intl-topic}:</th>
    <th width="25%">{intl-author}:</th>
    <th width="29%"><div align="right">{intl-time}:</div></th>
    <th width="1%"></th>
</tr>

<!-- BEGIN message_item_tpl -->
<tr>
    <td class="{td_class}">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="1%" valign="top">
		{spacer}{spacer}
				<!-- BEGIN new_icon_tpl -->
                <img src="/images/message_new.gif" width="16" height="16" border="0" alt="New message" />&nbsp;
		<!-- END new_icon_tpl -->
		<!-- BEGIN old_icon_tpl -->
                <img src="/images/message.gif" width="16" height="16" border="0" alt="Message" />&nbsp;
		<!-- END old_icon_tpl -->	

		</td>
		<td width="99%" valign="top">

                <a href="/forum/message/{message_id}/">{topic} {count_replies}</a>
                </td>
	</tr>
	</table>
    </td>
    <td class="{td_class}" valign="top">
        {user}
    </td>
    <td class="{td_class}" align="right" valign="top">
        <span class="small">{postingtime}</span>
    </td>
    <td class="{td_class}" align="right" valign="top">
		&nbsp;
        <!-- BEGIN edit_message_item_tpl -->
        <nobr><a href="/forum/messageedit/edit/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezfrm{message_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezfrm{message_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>&nbsp;<a href="/forum/messageedit/delete/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezfrm{message_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezfrm{message_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a></nobr>
        <!-- END edit_message_item_tpl -->
    </td>
</tr>
<!-- END message_item_tpl -->

</table>

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

<hr noshade size="4" />

<input class="stdbutton" type="submit" value="{intl-new-posting}" />
</form>


