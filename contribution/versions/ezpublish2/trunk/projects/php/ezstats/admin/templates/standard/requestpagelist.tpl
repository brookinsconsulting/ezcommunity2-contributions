<h1>{intl-request_list}</h1>

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
<tr>
	<td>
	<a target="_blank" href="http://{request_domain}{request_uri}">
	{request_uri}</a>
	</td>
	<td>
	{page_view_count}
	</td>
</tr>
<!-- END request_tpl -->
</table>


<!-- END request_list_tpl -->

