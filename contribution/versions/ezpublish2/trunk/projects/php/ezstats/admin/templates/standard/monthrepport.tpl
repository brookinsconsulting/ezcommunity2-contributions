<h1>{intl-month_repport} - {this_month} / {this_year}</h1>

<hr noshade size="4" />

<!-- BEGIN result_list_tpl -->


<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN day_tpl -->
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<b>{intl-day}:</b> {current_day}
		</td>
		<td align="right">
		{page_view_count} {intl-pages}
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td width="{page_view_percent}%" bgcolor="#ffee00">
		&nbsp;
		</td>
		<td width="{page_view_inverted_percent}%"  bgcolor="#eeeeee">
		&nbsp;
		</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
<tr>
<!-- END day_tpl -->
<tr>
	<td>
	</td>
</tr>
<tr>
	<td>
	{intl-total_page_views}: {total_page_views}
	</td>
</tr>
</table>

<!-- END result_list_tpl -->
