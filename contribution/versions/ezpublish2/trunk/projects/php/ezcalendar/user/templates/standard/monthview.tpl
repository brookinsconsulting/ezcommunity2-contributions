
<h1>{intl-month_view}: {year_number} {month_number}</h1>
<!-- BEGIN month_tpl -->
<table width="100%" border="1" cellspacing="0" cellpadding="2">
<tr>
<!-- BEGIN week_day_tpl -->
	<th width="14%">
	{week_day_name}
	</th>
<!-- END week_day_tpl -->
</tr>

<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}">
{day_number}
<br />
<br />
<br />
<div align="right"><a href="/calendar/appointment/new/">+</a></div>
</td>
<!-- END day_tpl -->

</tr>
<!-- END week_tpl -->
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a href="/calendar/monthview/{prev_year_number}/{prev_month_number}">{intl-previous_month}</a>
	</td>
	<td align="right">
	<a href="/calendar/monthview/{next_year_number}/{next_month_number}">{intl-next_month}</a>
	</td>
</tr>
</table>
<!-- END month_tpl -->