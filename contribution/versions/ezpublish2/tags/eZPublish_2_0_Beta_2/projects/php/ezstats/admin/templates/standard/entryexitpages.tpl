<h1>{intl-entry_exit_page_report}</h1>

<hr noshade size="4" />

{month}

<h2>{intl-exit_pages}:</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-page_uri}:
	</th>
	<th align="right">
	{intl-exit_count}:
	</th>
</tr>

<!-- BEGIN exit_page_tpl -->
<tr class="{bg_color}">
	<td>
	{page_uri}
	</td>
	<td align="right">
	{exit_count}
	</td>
</tr>
<!-- END exit_page_tpl -->

</table>

<h2>{intl-entry_pages}:</h2>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-page_uri}:
	</th>
	<th align="right">
	{intl-entry_count}:
	</th>
</tr>

<!-- BEGIN entry_page_tpl -->
<tr class="{bg_color}">
	<td>
	{page_uri}
	</td>
	<td align="right">
	{entry_count}
	</td>
</tr>
<!-- END entry_page_tpl -->

<!-- BEGIN month_tpl -->
<table>
<tr>
	<!-- BEGIN month_previous_tpl -->
	<td>
	<a href="/stats/entryexitreport/{previous_year}/{previous_month}">{intl-previous}</a>
	</td>
	<!-- END month_previous_tpl -->
	
	<!-- BEGIN month_previous_inactive_tpl -->
	<td>
	{intl-previous}
	</td>
	<!-- END month_previous_inactive_tpl -->

	<!-- BEGIN month_next_tpl -->
	<td>
	<a href="/stats/entryexitreport/{next_year}/{next_month}">{intl-next}</a>
	</td>
	<!-- END month_next_tpl -->

	<!-- BEGIN month_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END month_next_inactive_tpl -->

</tr>
</table>
<!-- END month_tpl -->
	</td>
</tr>

</table>
