
<h1>{intl-appointments}: {intl-month_view}</h1>
<hr noshade size="4" />

<form method="post" action="/calendar/monthview/">
<p class="boxtext">{intl-user}:</p>
<select name="GetByUserID">
<!-- BEGIN user_item_tpl -->
<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
<!-- END user_item_tpl -->
</select>

<input type="submit" Name="GetByUser" value="{intl-show}">

</form>

<!-- BEGIN month_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td rowspan="2">
	<h2>{month_name} {year_number}</h2>
	</td>
	<td align="right">
	<a href="/calendar/monthview/{prev_year_number}/{prev_month_number}">&lt;&lt; {intl-previous_month}</a>
	</td>
</tr>
<tr>
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

<hr noshade size="4" />

<form action=/calendar/appointmentedit/edit/">
<input type="submit" name="Day" value="{intl-day}">
<input type="submit" name="Month" value="{intl-month}">
<input type="submit" name="Year" value="{intl-year}">
</form>

