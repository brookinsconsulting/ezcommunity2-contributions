
<div id="LayerContent" class="LayerContent" style="">

<h1>{intl-author_info}</h1>

<div style=" position:relative;width:90%;">

	<span class="boxtext">{intl-author_name}:</span>
	<br />
	<a href="mailto:{author_mail}">{author_name}</a>
	<br /><br />
	{intl-rfp_info}</div>




<!-- BEGIN rfp_item_header_one_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td><h2>{intl-head_line}</h2></td>
	<td width="10%" align="right"><nobr><b>({rfp_start}-{rfp_end}/{rfp_count})</b></nobr></td>
</tr>
</table>

<table class="list" width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th><a href="{www_dir}{index}/rfp/author/view/{author_id}/name">{intl-name}</a>:</th>
	<th><a href="{www_dir}{index}/rfp/author/view/{author_id}/category">{intl-category}</a>:</th>
	<th><div align="right"><a href="{www_dir}{index}/rfp/author/view/{author_id}/published">{intl-published}</a>:</div></th>
</tr>
<!-- END rfp_item_header_one_tpl -->


<!-- BEGIN rfp_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/rfp/rfpview/{rfp_id}/1/{category_id}">{rfp_name}</a>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/rfp/archive/{category_id}/">{rfp_category}</a>
	</td>
	<td class="{td_class}" align="right">
	<span class="small">{rfp_published}</span>
	</td>
</tr>
<!-- END rfp_item_tpl -->

<!-- BEGIN rfp_item_header_two_tpl -->
</table>
<!-- END rfp_item_header_two_tpl -->

<!-- BEGIN type_list_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/rfp/author/view/{author_id}/{sort}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	<a class="path" href="{www_dir}{index}/rfp/author/view/{author_id}/{sort}/{item_index}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	&lt;&nbsp;{type_item_name}&nbsp;&gt;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	| <a class="path" href="{www_dir}{index}/rfp/author/view/{author_id}/{sort}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

</div>