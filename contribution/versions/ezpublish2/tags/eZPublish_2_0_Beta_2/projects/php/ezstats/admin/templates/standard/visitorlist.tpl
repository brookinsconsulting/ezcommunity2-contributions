<h1>{intl-top_visitor_list} - ({item_start}-{item_end}/{item_count})</h1>

<hr noshade size="4" />


<!-- BEGIN visitor_list_tpl -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th>
	{intl-remote_ip}:
	</th>
	<th>
	{intl-remote_hostname}:
	</th>
	<th>
	{intl-page_view_count}:
	</th>
</tr>
<!-- BEGIN visitor_tpl -->
<tr class="{bg_color}">
	<td>
	{remote_ip}
	</td>
	<td>
	{remote_host_name}
	</td>
	<td>
	{page_view_count}
	</td>
</tr>
<!-- END visitor_tpl -->

<tr>
	<td colspan="3">
<!-- BEGIN type_list_tpl -->
<table>
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a href="/stats/visitorlist/top/{item_limit}/{item_previous_index}">{intl-previous}</a>
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
	<a href="/stats/visitorlist/top/{item_limit}/{item_index}">{type_item_name}</a>
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
	<a href="/stats/visitorlist/top/{item_limit}/{item_next_index}/">{intl-next}</a>
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


<!-- END visitor_list_tpl -->

