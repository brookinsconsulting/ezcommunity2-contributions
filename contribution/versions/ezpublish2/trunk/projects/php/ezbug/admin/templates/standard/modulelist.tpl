<form action="/bug/module/new/">

<h1>{intl-headline}</h1>

<!-- BEGIN path_tpl -->

<hr noshade size="4" />

<img src="/images/{site_style}/path-arrow.gif" height="10" width="12" border="0">

<a class="path" href="/bug/module/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/{site_style}/path-slash.gif" height="10" width="16" border="0">

<a class="path" href="/bug/module/list/{module_id}/">{module_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN module_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/module/list/{module_id}">{module_name}</a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/module/edit/{module_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{module_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="pt{module_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/module/delete/{module_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{module_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="pt{module_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END module_item_tpl -->
</table>

<hr noshade size="4" />

<input class="okbutton" type="submit" value="{intl-newmodule}">

</form>
