<h1>{intl-entry_exit_page_report}</h1>

<hr noshade size="4" />

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
<tr>
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
<tr>
	<td>
	{page_uri}
	</td>
	<td align="right">
	{entry_count}
	</td>
</tr>
<!-- END entry_page_tpl -->

</table>
