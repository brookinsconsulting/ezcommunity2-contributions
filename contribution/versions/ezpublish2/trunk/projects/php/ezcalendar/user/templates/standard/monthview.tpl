
<h1>{intl-appointments}: {intl-month_view}</h1>
<hr noshade size="4" />

<h2>{month_name} {year_number}</h2>

<form method="post" action="/calendar/monthview/">
<p class="boxtext">{intl-user}:</p>
<select name="GetByUserID">
<!-- BEGIN user_item_tpl -->
<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
<!-- END user_item_tpl -->
</select>

<input class="stdbutton" type="submit" Name="GetByUser" value="{intl-show}">

</form>
<br />

<!-- BEGIN month_tpl -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<a class="path" href="/calendar/monthview/{prev_year_number}/{prev_month_number}">&lt;&lt; {intl-previous_month}</a>
	</td>
	<td align="right">
	<a class="path" href="/calendar/monthview/{next_year_number}/{next_month_number}">{intl-next_month} &gt;&gt;</a>
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
<a class="boxtext" href="/calendar/dayview/{year_number}/{month_number}/{day_number}">{day_number}</a>
<br />
<img src="/images/1x1.gif" height="4" width="2" border="0" alt="" /><br />

<!-- BEGIN private_appointment_tpl -->
{start_time} - {stop_time}<br />
<!-- END private_appointment_tpl -->

<!-- BEGIN public_appointment_tpl -->
<a class="small" href="/calendar/appointmentview/{appointment_id}/">{start_time} - {stop_time}</a><br />
<img src="/images/1x1.gif" height="4" width="2" border="0" alt="" /><br />

<!-- END public_appointment_tpl -->

<br />
<br />
<div align="right"><a class="path" href="/calendar/appointmentedit/new/{year_number}/{month_number}/{day_number}">+</a></div>
</td>
<!-- END day_tpl -->

</tr>
<!-- END week_tpl -->
</table>
<br />

<!-- END month_tpl -->

<form action="/calendar/appointmentedit/edit/">

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>

