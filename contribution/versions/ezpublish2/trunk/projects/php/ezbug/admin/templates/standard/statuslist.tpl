<form action="/bug/status/new/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN status_item_tpl -->
<tr>
	<td class="{td_class}">
	{status_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/status/edit/{status_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{status_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="pt{status_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/status/delete/{status_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{status_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="pt{status_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END status_item_tpl -->
</table>

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-newstatus}">

</form>
