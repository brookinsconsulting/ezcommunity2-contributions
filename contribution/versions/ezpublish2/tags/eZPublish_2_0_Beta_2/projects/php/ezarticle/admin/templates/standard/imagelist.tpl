<form action="/article/articleedit/imageedit/storedef/{article_id}/" method="post">

<h1>{intl-images}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image_id}:</th>
	<th>{intl-image_caption}:</th>
	<th>{intl-image_preview}:</th>
	<th>{intl-image_mini}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{image_number}
	</td>
	<td width="95%" class="{td_class}">
	{image_name}
	</td>
	<td width="1%" class="{td_class}">
	<img src="{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td width="1%" class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/imageedit/edit/{image_id}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{image_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/imageedit/delete/{image_id}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="eztp{image_id}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top" border="0" alt="Delete"></a>
	</td>
</tr>
<!-- END image_tpl -->

</table>

<br/>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewImage"value="{intl-image_upload}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="/article/articleedit/edit/{article_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-abort}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

