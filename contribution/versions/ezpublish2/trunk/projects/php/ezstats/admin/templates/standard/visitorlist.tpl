<h1>{intl-top_visitor_list} - ({item_start}-{item_end}/{item_count})</h1>

<hr noshade size="4" />


<!-- BEGIN visitor_list_tpl -->

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th>
	{intl-remote_ip}:
	</th>
	<th>
	{intl-remote_hostname}:
	</th>
	<td align="right">
	<b>{intl-page_view_count}:</b>
	</td>
</tr>
<!-- BEGIN visitor_tpl -->
<tr class="{bg_color}">
	<td>
	{remote_ip}
	</td>
	<td>
	{remote_host_name}
	</td>
	<td align="right">
	{page_view_count}
	</td>
</tr>
<!-- END visitor_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/stats/visitorlist/top/{item_limit}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	<a class="path" href="/stats/visitorlist/top/{item_limit}/{item_index}">{type_item_name}</a>
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
	| <a class="path" href="/stats/visitorlist/top/{item_limit}/{item_next_index}/">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->


<!-- END visitor_list_tpl -->

