
<h1>{intl-appointments}: {intl-month_view}</h1>
<hr noshade size="4" />

<!-- BEGIN month_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h2>{month_name} {year_number}</h2>
	</td>
	<td align="right">
	<a href="/calendar/monthview/{prev_year_number}/{prev_month_number}">&lt;&lt; {intl-previous_month}</a>
	</td>
</tr>
<tr>
	<td>
	</td>
	<td align="right">
	<a href="/calendar/monthview/{next_year_number}/{next_month_number}">{intl-next_month} &gt;&gt;</a>
	</td>
</tr>
</table>
<br />

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
<td class="{td_class}" valign="top" >
<a href="/calendar/dayview/{year_number}/{month_number}/{day_number}">{day_number}</a>
<br />

<!-- BEGIN appointment_tpl -->
<a href="/calendar/appointmentview/{appointment_id}/">{start_time}</a><br />
<!-- END appointment_tpl -->
<br />
<br />
<div align="right"><a href="/calendar/appointmentedit/new/">+</a></div>
</td>
<!-- END day_tpl -->

</tr>
<!-- END week_tpl -->
</table>
<br />

<!-- END month_tpl -->