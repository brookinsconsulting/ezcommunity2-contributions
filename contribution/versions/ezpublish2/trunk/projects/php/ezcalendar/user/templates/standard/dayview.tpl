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
	<td width="10%">
	{hour_value} : {minute_value}
	</td>	

	<!-- BEGIN appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	<a href="/calendar/appointmentview/{appointment_id}/">{appointment_name}</a>
	<a href="/calendar/appointmentedit/edit/{appointment_id}/">{edit_button}</a>
<!--	<a href="/calendar/appointmentview/{appointment_id}/">{delete_button}</a><br /> -->
        <!-- BEGIN delete_check_tpl -->
        <input type="checkbox" name="AppointmentArrayID[]" value={appointment_id}><br />
        <!-- END delete_check_tpl -->
	{appointment_description}<br />
	</td>

	<!-- END appointment_tpl -->

</tr>
<!-- END time_table_tpl -->
</table>
<input type="submit" name="DeleteAppointments" value="{intl-delete_appointment}">
</form>
