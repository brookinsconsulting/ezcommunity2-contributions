<h1>{intl-appointments}: {intl-day_view}</h1>
<hr noshade size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td rowspan="4">
	<h2>{long_date}</h2>
	</td>
	<td align="right">
	<a href="/calendar/dayview/{prev_year_number}/{curr_month_number}/{curr_day_number}">&lt;&lt; {intl-previous_year}</a>
	</td>
</tr>
<tr>
	<td align="right">
	<a href="/calendar/dayview/{next_year_number}/{curr_month_number}/{curr_day_number}">{intl-next_year} &gt;&gt;</a>
	</td>
</tr>
<tr>
	<td align="right">
	<a href="/calendar/dayview/{prev_myear_number}/{prev_month_number}/{curr_day_number}">&lt;&lt; {intl-previous_month}</a>
	</td>
</tr>
<tr>
	<td align="right">
	<a href="/calendar/dayview/{next_myear_number}/{next_month_number}/{curr_day_number}">{intl-next_month} &gt;&gt;</a>
	</td>
</tr>
</table>
<br />

<form method="post" action="/calendar/appointmentedit/edit/">
<table width="100%" border="1" cellspacing="0" cellpadding="2" >
<!-- BEGIN time_table_tpl -->
<tr>
	<td class="{td_class}" width="10%">
	<a href="/calendar/appointmentedit/new/{year_number}/{month_number}/{day_number}/{start_time}">{short_time}</a>
	</td>	

	<!-- BEGIN appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	<table width="100%" cellspacing="0" cellpadding="0" border="0" >
	<tr>
		<td valign="top">
		<h2>
			<a href="/calendar/appointmentview/{appointment_id}/">{appointment_name}</a>
		</h2>
		{appointment_description}<br />

		</td>				
		<td valign="top" align="right">			
			<a href="/calendar/appointmentedit/edit/{appointment_id}/">{edit_button}</a>
			<!-- BEGIN delete_check_tpl -->
			<input type="checkbox" name="AppointmentArrayID[]" value={appointment_id}>{intl-delete}<br />
			<!-- END delete_check_tpl -->
		</td>
	</tr>
	</table>			

	</td>

	<!-- END appointment_tpl -->

</tr>
<!-- END time_table_tpl -->
</table>
<input type="submit" name="DeleteAppointments" value="{intl-delete_appointments}">
</form>

<hr noshade size="4" />

<form action=/calendar/appointmentedit/edit/">
<input type="submit" name="GoDay" value="{intl-day}">
<input type="submit" name="GoMonth" value="{intl-month}">
<input type="submit" name="GoYear" value="{intl-year}">

<input type="hidden" name="CurrentYear" value="{year_number}" />
<input type="hidden" name="CurrentMonth" value="{month_number}" />
<input type="hidden" name="CurrentDay" value="{day_number}" />
</form>

