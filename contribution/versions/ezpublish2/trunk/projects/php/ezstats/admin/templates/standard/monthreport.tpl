<h1>{intl-month_repport} - {this_month} / {this_year}</h1>

<hr noshade size="4" />

<!-- BEGIN result_list_tpl -->


<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<b>{intl-total_page_views}</b>: {total_page_views}
	</td>
</tr>
<tr>
	<td>
	<b>{intl-pages_pr_day}</b>: {pages_pr_day}
	</td>
</tr>
<!-- BEGIN day_tpl -->
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<b>{intl-day}:</b> {current_day}
		</td>
		<td align="right">
		{page_view_count} {intl-pages} ({page_view_percent}%)
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td width="{page_view_percent}%" bgcolor="#ffee00">
		<img src="/images/1x1.gif" width="1" height="10" border="0"></td>
		<td width="{page_view_inverted_percent}%"  bgcolor="#eeeeee">
		<img src="/images/1x1.gif" width="1" height="10" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
<tr>
<!-- END day_tpl -->
</table>

<!-- END result_list_tpl -->
