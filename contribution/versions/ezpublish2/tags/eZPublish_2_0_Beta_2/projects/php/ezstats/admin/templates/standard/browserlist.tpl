<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-browser_list} - ({item_start}-{item_end}/{item_count})</h1>
	</td>
</tr>
</table>

<hr noshade size="4" />


<!-- BEGIN browser_list_tpl -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th>
	{intl-browser_name}:
	</th>
	<th>
	{intl-page_view_count}:
	</th>
	<th>
	{intl-page_view_percent}:
	</th>
</tr>
<!-- BEGIN browser_tpl -->
<tr class="{bg_color}">
	<td>
	{browser_name}
	</td>
	<td>
	{page_view_count}
	</td>
	<td>
	{page_view_percent}%
	</td>
</tr>
<!-- END browser_tpl -->

<tr>
	<td colspan="3">
<!-- BEGIN type_list_tpl -->
<table>
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a href="/stats/browserlist/top/{item_limit}/{item_previous_index}">{intl-previous}</a>
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
	<a href="/stats/browserlist/top/{item_limit}/{item_index}">{type_item_name}</a>
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
	<a href="/stats/browserlist/top/{item_limit}/{item_next_index}">{intl-next}</a>
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


<!-- END browser_list_tpl -->

