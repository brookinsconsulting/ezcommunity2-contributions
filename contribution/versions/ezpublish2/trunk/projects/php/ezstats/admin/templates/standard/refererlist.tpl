<h1>{intl-referer_list}</h1>

<hr noshade size="4" />


<!-- BEGIN referer_list_tpl -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th>
	{intl-referer_domain}:
	</th>
	<th>
	{intl-referer_uri}:
	</th>
	<th>
	{intl-page_view_count}:
	</th>
</tr>
<!-- BEGIN referer_tpl -->
<tr>
	<td>
	<a target="_blank" href="http://{referer_domain}{referer_uri}">
	{referer_domain}</a>
	</td>
	<td>
	{referer_uri}
	</td>
	<td>
	{page_view_count}
	</td>
</tr>
<!-- END referer_tpl -->
</table>


<!-- END referer_list_tpl -->

