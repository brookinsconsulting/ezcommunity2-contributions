<form method="post" action="{www_dir}{index}/imagecatalogue/unassigned/{offset}/{limit}/" enctype="multipart/form-data">

<h1>{intl-images}</h1>


<hr noshade="noshade" size="4" />

<!-- BEGIN image_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN detail_view_tpl -->
<tr>
	<!-- BEGIN detail_read_tpl -->
	<td valign="top" class="{td_class}">
	<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/imagecatalogue/unassigned/{offset}/{limit}"><img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /></a>
	</td>
	<td class="{td_class}">
	{image_size}&nbsp;{image_unit}
	</td>

	<td class="{td_class}">
	<select name="CategoryArrayID[]">
	<option	value="-1">{intl-do_not_update}</option>
	<!-- BEGIN value_tpl -->
	<option	value="{option_value}">{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/imagecatalogue/image/edit/{image_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezimg{image_id}-red','','/ezimagecatalogue/user/{image_dir}/redigerminimrk.gif',1)"><img name="ezimg{image_id}-red" border="0" src="{www_dir}/ezimagecatalogue/user/{image_dir}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%">
	<input type="hidden" name="ImageArrayID[]" value="{image_id}">
	</td>
	<!-- END detail_read_tpl -->
</tr>
<!-- END detail_view_tpl -->
</table>
<!-- END image_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
<tr>
<td width="50" align="left">
<!-- BEGIN prev_link_tpl -->
<a href="{www_dir}{index}/imagecatalogue/unassigned/{prev_offset}/{limit}/">{intl-prev}</a>
<!-- END prev_link_tpl -->
</td>
<td width="50%" align="right">
<!-- BEGIN next_link_tpl -->
<a href="{www_dir}{index}/imagecatalogue/unassigned/{next_offset}/{limit}/">{intl-next}</a>
<!-- END next_link_tpl -->
</td>
</tr>
</table>
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}">

</form>


