<form method="post" action="/imagecatalogue/unassigned/" enctype="multipart/form-data">

<input type="hidden" name="CategoryID" value="{main_category_id}">

<h1>{intl-images}</h1>


<hr noshade="noshade" size="4" />

<!-- BEGIN image_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN detail_view_tpl -->
<tr>
	<!-- BEGIN detail_read_tpl -->
	<td valign="top">
	<a href="/imagecatalogue/imageview/{image_id}/?RefererURL=/imagecatalogue/unassigned/"><img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /></a>
	</td>
	<td class="{td_class}">
	{image_size}&nbsp;{image_unit}
	</td>

	<td class="{td_class}">
	<select name="CategoryArrayID[]">
	<option	value="-1">{intl-do_not_update}</option>
	<!-- BEGIN value_tpl -->
	<option	value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>

	<td class="{td_class}" width="1%">
	<a href="/imagecatalogue/image/edit/{image_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezimg{image_id}-red','','/ezimagecatalogue/user/{image_dir}/redigerminimrk.gif',1)"><img name="ezimg{image_id}-red" border="0" src="/ezimagecatalogue/user/{image_dir}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%">
	<input type="hidden" name="ImageArrayID[]" value="{image_id}">
	</td>
	<!-- END detail_read_tpl -->
</tr>
<!-- END detail_view_tpl -->
</table>
<!-- END image_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}">

</form>


