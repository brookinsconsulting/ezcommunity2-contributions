<h1>{intl-request_list} - ({item_start}-{item_end}/{item_count})</h1>

<hr noshade size="4" />


<!-- BEGIN request_list_tpl -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th>
	{intl-request_uri}:
	</th>
	<th>
	{intl-page_view_count}:
	</th>
</tr>
<!-- BEGIN request_tpl -->
<tr class="{bg_color}">
	<td>
	<a target="_blank" href="http://{request_domain}{request_uri}">
	{request_uri}</a>
	</td>
	<td>
	{page_view_count}
	</td>
</tr>
<!-- END request_tpl -->

<tr>
	<td colspan="3">
<!-- BEGIN type_list_tpl -->
<table>
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a href="/stats/refererlist/top/{item_limit}/{item_previous_index}">{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	{intl-previous}
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	<a href="/stats/refererlist/top/{item_limit}/{item_index}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	{type_item_name}
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	<a href="/stats/refererlist/top/{item_limit}/{item_next_index}">{intl-next}</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
	</td>
</tr>

</table>


<!-- END request_list_tpl -->

