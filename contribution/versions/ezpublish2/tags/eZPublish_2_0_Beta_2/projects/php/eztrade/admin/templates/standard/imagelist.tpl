<form action="/trade/productedit/imageedit/storedef/{product_id}/" method="post">

<h1>{intl-image} {product_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-nr}:</th>
	<th>{intl-imagetext}:</th>
	<th>{intl-preview}:</th>
	<th>{intl-mainimage}:</th>
	<th>{intl-miniimage}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td class="{td_class}">
	{image_number}
	</td>
	<td class="{td_class}">
	{image_name}&nbsp;
	</td>
	<td class="{td_class}">
	<img src="{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td class="{td_class}">
        <input type="radio" {main_image_checked} name="MainImageID" value="{image_id}" />
        </td>
	<td class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/productedit/imageedit/edit/{image_id}/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{image_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/trade/productedit/imageedit/delete/{image_id}/{product_id}/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="eztp{image_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END image_tpl -->

</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewImage"value="{intl-newimage}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="/trade/productedit/edit/{product_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-abort}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

