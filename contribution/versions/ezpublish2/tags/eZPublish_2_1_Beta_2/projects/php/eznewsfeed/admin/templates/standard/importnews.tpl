<form method="post" action="/newsfeed/importnews/">

<h1>{intl-source_sites}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN source_site_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-source_name}:</td>
	<th>{intl-source_site}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN source_site_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/newsfeed/importnews/fetch/{source_site_id}/">{source_site_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{source_site_url}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="/newsfeed/sourcesite/edit/{source_site_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eznf{source_site_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eznf{source_site_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/newsfeed/sourcesite/delete/{source_site_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eznf{source_site_id}-slett','','/admin/images/{site_style}/slettminimrk.gif',1)"><img name="eznf{source_site_id}-slett" border="0" src="/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>

<!-- END source_site_tpl -->
</table>

<!-- END source_site_list_tpl -->

<hr noshade="noshade" size="4" />

<input type="hidden" name="Action" value="ImportNews" />
<input type="submit" class="okbutton" value="{intl-import_news}" />
</form>