<h1>{intl-top_visitor_list}</h1>

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
<tr>
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
</table>


<!-- END visitor_list_tpl -->

