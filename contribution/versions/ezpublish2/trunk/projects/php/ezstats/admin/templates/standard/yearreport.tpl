<h1>{intl-month_report} - {this_year}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN result_list_tpl -->
<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<span class="boxtext">{intl-total_page_views}:</span> {total_page_views}
	</td>
	<td align="right">
	<span class="boxtext">{intl-pages_pr_month}:</span> {pages_pr_month}
	</td>
</tr>
<tr>
	<td colspan="2">
	{year}
	</td>
</tr>
</table>

<!-- BEGIN month_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<!-- BEGIN month_link_tpl --><span class="path"><a href="/stats/monthreport/{this_year}/{this_month}">
		<!-- END month_link_tpl --><!-- BEGIN month_january_tpl -->{intl-january_month}:
		<!-- END month_january_tpl --><!-- BEGIN month_february_tpl -->{intl-february_month}:
		<!-- END month_february_tpl --><!-- BEGIN month_march_tpl -->{intl-march_month}:
		<!-- END month_march_tpl --><!-- BEGIN month_april_tpl -->{intl-april_month}:
		<!-- END month_april_tpl --><!-- BEGIN month_may_tpl -->{intl-may_month}:
		<!-- END month_may_tpl --><!-- BEGIN month_june_tpl -->{intl-june_month}:
		<!-- END month_june_tpl --><!-- BEGIN month_july_tpl -->{intl-july_month}:
		<!-- END month_july_tpl --><!-- BEGIN month_august_tpl -->{intl-august_month}:
		<!-- END month_august_tpl --><!-- BEGIN month_september_tpl -->{intl-september_month}:
		<!-- END month_september_tpl --><!-- BEGIN month_october_tpl -->{intl-october_month}:
		<!-- END month_october_tpl --><!-- BEGIN month_november_tpl -->{intl-november_month}:
		<!-- END month_november_tpl --><!-- BEGIN month_december_tpl -->{intl-december_month}:
		<!-- END month_december_tpl --><!-- BEGIN month_link_end_tpl --></a></span>
		<!-- END month_link_end_tpl -->
		</td>
		<td align="right">
		{page_view_count} {intl-pages} ({percent_count}%)
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<!-- BEGIN percent_marker_tpl -->
			<td width="{page_view_percent}%" bgcolor="#ffee00">
			<img src="/images/1x1.gif" width="1" height="10" border="0"></td>
			<!-- END percent_marker_tpl -->
			<!-- BEGIN no_percent_marker_tpl -->
			<td width="{page_view_percent}%" bgcolor="#ffee00">
			</td>
			<!-- END no_percent_marker_tpl -->
			<td width="{page_view_inverted_percent}%"  bgcolor="#eeeeee">
			<img src="/images/1x1.gif" width="1" height="10" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<!-- END month_tpl -->


<!-- BEGIN year_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<!-- BEGIN year_previous_tpl -->
	<td>
	<a class="path" href="/stats/yearreport/{previous_year}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END year_previous_tpl -->
	
	<!-- BEGIN year_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END year_previous_inactive_tpl -->

	<!-- BEGIN year_next_tpl -->
	<td align="right">
	<a class="path" href="/stats/yearreport/{next_year}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END year_next_tpl -->

	<!-- BEGIN year_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END year_next_inactive_tpl -->
</tr>
</table>
<!-- END year_tpl -->

<!-- END result_list_tpl -->
