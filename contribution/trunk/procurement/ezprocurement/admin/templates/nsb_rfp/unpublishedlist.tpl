<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="{www_dir}{index}/rfp/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />



<!-- BEGIN rfp_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="2">{intl-rfp}:</th>
	<th>{intl-published}:</th>

	<th colspan="2">&nbsp;</th>
</tr>

<form method="post" action="{www_dir}{index}/rfp/unpublished/{current_category_id}/" enctype="multipart/form-data">
<!-- BEGIN rfp_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<img src="{www_dir}/admin/images/document.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td width="77%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/rfppreview/{rfp_id}/">
	{rfp_name}
	</a>
	</td>
	<td width="18%" class="{td_class}">
	<!-- BEGIN rfp_is_published_tpl -->
	{intl-is_published}
	<!-- END rfp_is_published_tpl -->
	<!-- BEGIN rfp_not_published_tpl -->
	{intl-not_published}
	<!-- END rfp_not_published_tpl -->
	</td>
	<!-- BEGIN rfp_edit_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/rfpedit/edit/{rfp_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{rfp_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{rfp_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="rfpArrayID[]" value="{rfp_id}">
	</td>
	<!-- END rfp_edit_tpl -->
</tr>
<!-- END rfp_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<input type="submit" class="stdbutton" Name="Deleterfps" value="{intl-deleterfps}">
</form>

<!-- END rfp_list_tpl -->


<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/rfp/unpublished/{archive_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/rfp/unpublished/{archive_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/rfp/unpublished/{archive_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
