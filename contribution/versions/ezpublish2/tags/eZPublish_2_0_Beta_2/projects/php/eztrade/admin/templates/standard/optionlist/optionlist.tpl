<h1>{intl-optionlist}: {product_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-option}: </th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN option_tpl -->
<tr>
	<td class="{td_class}">
	{option_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/productedit/optionedit/edit/{option_id}/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{option_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezto{option_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/trade/productedit/optionedit/delete/{option_id}/{product_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{option_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="ezto{option_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END option_tpl -->

</table>



<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<form action="/trade/productedit/optionedit/new/{product_id}/" method="post">
<input class="stdbutton" type="submit" value="{intl-newoption}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
</form>
<form action="/trade/productedit/edit/{product_id}/" method="post">
<input class="okbutton" type="submit" value="{intl-back}" />
</form>
	</td>
</tr>
</table>

