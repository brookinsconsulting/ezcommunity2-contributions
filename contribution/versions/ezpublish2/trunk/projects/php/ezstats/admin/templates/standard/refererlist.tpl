<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-referer_list} - ({item_start}-{item_end}/{item_count})</h1>
	</td>
	<td align="right">
	<form action="/stats/refererlist/{view_mode}/{view_limit}" method="post">
	{intl-exclude_domain}:
	<input type="text" value="" name="ExcludeDomain" />
	<input type="submit" value="{intl-ok}" />
	</form>
	</td>
</tr>
</table>

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

<tr>
	<td colspan="3">
<!-- BEGIN type_list_tpl -->
<table>
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a href="/stats/refererlist/top/{item_limit}/{item_previous_index}/{exclude_domain}">{intl-previous}</a>
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
	<a href="/stats/refererlist/top/{item_limit}/{item_index}/{exclude_domain}">{type_item_name}</a>
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
	<a href="/stats/refererlist/top/{item_limit}/{item_next_index}/{exclude_domain}">{intl-next}</a>
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


<!-- END referer_list_tpl -->

