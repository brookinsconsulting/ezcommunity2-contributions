<h1>{intl-appointments}: {intl-day_view}</h1>
<hr noshade size="4" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
<h2>{long_date}</h2>
</td>
<td align="center">
<br />
	<a class="path" href="{www_dir}{index}/calendar/monthview/{calendar_id}/{pm_year_number}/{pm_month_number}/">&lt;&lt;&nbsp;</a>
	<a class="path" href="{www_dir}{index}/calendar/monthview/{calendar_id}/{year_number}/{month_number}">{month_name}</a>
	<a class="path" href="{www_dir}{index}/calendar/monthview/{calendar_id}/{nm_year_number}/{nm_month_number}/">&nbsp;&gt;&gt;</a>

	<table width="100" border="1" cellspacing="0" cellpadding="1">
	<!-- BEGIN week_tpl -->
	<tr>
		<!-- BEGIN day_tpl -->
		<td class="{td_class}">
		<a class="small" href="{www_dir}{index}/calendar/dayview/{calendar_id}/{year_number}/{month_number}/{day_number}">{day_number}</a>
		</td>
		<!-- END day_tpl -->
		<!-- BEGIN empty_day_tpl -->
		<td class="{td_class}">
		&nbsp;
		</td>
		<!-- END empty_day_tpl -->
	</tr>
	<!-- END week_tpl -->
	</table>
	</td>
</tr>
</table>
<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<a class="path" href="{www_dir}{index}/calendar/dayview/{calendar_id}/{pd_year_number}/{pd_month_number}/{pd_day_number}">&lt;&lt;&nbsp;{intl-previous_day}</a>
	</td>
	<td align="right">
	<a class="path" href="{www_dir}{index}/calendar/dayview/{calendar_id}/{nd_year_number}/{nd_month_number}/{nd_day_number}">{intl-next_day}&nbsp;&gt;&gt;</a>
	</td>
</tr>
</table>
<br />

<form method="post" action="{www_dir}{index}/calendar/appointmentedit/{calendar_id}/edit/">
<table width="100%" border="1" cellspacing="0" cellpadding="2" >
<!-- BEGIN time_table_tpl -->
<tr>
	<td class="{td_class}" width="10%">
	<a class="path" href="{www_dir}{index}/calendar/appointmentedit/{calendar_id}/new/{year_number}/{month_number}/{day_number}/{start_time}">{short_time}</a>
	</td>

	<!-- BEGIN public_appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	<table width="100%" cellspacing="0" cellpadding="4" border="0" >
	<tr>
		<td width="98%" valign="top">
		<a href="{www_dir}{index}/calendar/appointmentview/{calendar_id}/{appointment_id}/"><b>{appointment_name}</b></a><br />
		</td>
		<td width="1%" valign="top" align="right">
		<a href="{www_dir}{index}/calendar/appointmentedit/{calendar_id}/edit/{appointment_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcal{appointment_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcal{appointment_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
		</td>
		<td width="1%" valign="top" align="right">
			<!-- BEGIN delete_check_tpl -->
			<input type="checkbox" name="AppointmentArrayID[]" value="{appointment_id}">
			<!-- END delete_check_tpl -->
		</td>
	</tr>
	<tr>
		<td colspan="3">
		{appointment_description}&nbsp;
		</td>
	</tr>
	</table>

	</td>
	<!-- END public_appointment_tpl -->

	<!-- BEGIN private_appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	<a href="{www_dir}{index}/calendar/appointmentview/{calendar_id}/{appointment_id}/"><b>{appointment_name}</b></a><br />
	</td>
	<!-- END private_appointment_tpl -->

	<!-- BEGIN no_appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	&nbsp;
	</td>
	<!-- END no_appointment_tpl -->

</tr>
<!-- END time_table_tpl -->
</table>
<br />
<!-- BEGIN delete_button_tpl -->
<hr noshade size="4" />
<input class="stdbutton" type="submit" name="DeleteAppointments" value="{intl-delete_appointments}">
<!-- END delete_button_tpl -->
</form>

<form action="{www_dir}{index}/calendar/appointmentedit/{calendar_id}/edit/">

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>

