
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">

	<h1>{intl-head_line}: <span style="font-size: 10pt">{search_text}</span>
	<span style="font-size: 10pt">
		<br />Start - End - Total: &nbsp; &nbsp; &nbsp; ('{rfp_start}' - '{rfp_end}' / '{rfp_total}' ) 
	</span>
	</h1>
	</td>

	<td align="right" valign="top">
	<form action="{www_dir}{index}/rfp/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<!--
<tr>
        <td colspan="2" valign="top">
	<h1> {search_text}</h1>
	</td>
</tr>
-->
</table>

<form method="post" action="{www_dir}{index}/rfp/search/" enctype="multipart/form-data">

<!--
<p>
{current_category_description}
</p>
-->

<hr noshade="noshade" size="4" />

<!-- BEGIN rfp_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-rfp}:</th>
	<th>{intl-published}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN rfp_item_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{www_dir}/admin/images/document.gif" height="16" width="16" border="0" alt="" />&nbsp;
	<a href="{www_dir}{index}/rfp/rfppreview/{rfp_id}/">
	{rfp_name}
	</a>
	</td>
	<td class="{td_class}">
	<!-- BEGIN rfp_is_published_tpl -->
	{intl-is_published}
	<!-- END rfp_is_published_tpl -->
	<!-- BEGIN rfp_not_published_tpl -->
	{intl-not_published}
	<!-- END rfp_not_published_tpl -->
	&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/rfp/rfpedit/edit/{rfp_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{rfp_id}-red','','{www_dir}/ezrfp/admin/images/redigerminimrk.gif',1)"><img name="ezaa{rfp_id}-red" border="0" src="{www_dir}/ezrfp/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
          <input type="checkbox" name="rfpArrayID[]" value="{rfp_id}" />
	</td>
</tr>
<!-- END rfp_item_tpl -->

</table>
<!-- END rfp_list_tpl -->

<hr noshade="noshade" size="4">

<!-- BEGIN rfp_delete_tpl -->
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" />
<!-- END rfp_delete_tpl -->
<input type="hidden" name="SearchText" value="{search_text}" />
<input type="hidden" name="StartStamp" value="{url_start_stamp}" />
<input type="hidden" name="StopStamp" value="{url_stop_stamp}" />
<input type="hidden" name="SeperatedCategoryArray" value="{url_category_array}" />
<input type="hidden" name="ContentsWriterID" value="{url_contentswriter_id}" />
<input type="hidden" name="PhotographerID" value="{url_photographer_id}" />
</form>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/rfp/search/parent/{url_text}/{url_start_stamp}/{url_stop_stamp}/{url_category_array}/{url_contentswriter_id}/{url_photographer_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/rfp/search/parent/{url_text}/{url_start_stamp}/{url_stop_stamp}/{url_category_array}/{url_contentswriter_id}/{url_photographer_id}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/rfp/search/parent/{url_text}/{url_start_stamp}/{url_stop_stamp}/{url_category_array}/{url_contentswriter_id}/{url_photographer_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
