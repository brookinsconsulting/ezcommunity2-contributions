<form action="/bug/priority/new/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN priority_item_tpl -->
<tr>
	<td class="{td_class}">
	{priority_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/priority/edit/{priority_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{priority_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="pt{priority_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/priority/delete/{priority_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{priority_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="pt{priority_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END priority_item_tpl -->
</table>

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-newpriority}"></form>
