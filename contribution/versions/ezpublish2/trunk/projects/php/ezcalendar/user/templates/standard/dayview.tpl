<h1>{intl-appointments}: {intl-day_view}</h1>
<hr noshade size="4" />

<form method="post" action="/calendar/dayview/">
<p class="boxtext">{intl-user}:</p>
<select name="GetByUserID">
<!-- BEGIN user_item_tpl -->
<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
<!-- END user_item_tpl -->
</select>

<input type="submit" Name="GetByUser" value="{intl-show}">

</form>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" width="33%">
	<h2>{long_date}</h2>
	</td>
	<td rowspan="2" width="33%" align="center">
		<table width="100" border="1" cellspacing="0" cellpadding="1">
		<!-- BEGIN week_tpl -->
		<tr>
			<!-- BEGIN day_tpl -->
			<td class="{td_class}">
			<a class="small" href="/calendar/dayview/{year_number}/{month_number}/{day_number}">{day_number}</a>
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
	<td rowspan="2"align="right" valign="bottom" width="33%">
	<a class="path" href="/calendar/dayview/{4_year_number}/{4_month_number}/{4_day_number}">{intl-next_month}&nbsp;&gt;&gt;</a>
	</td>
</tr>
<tr>
	<td valign="bottom">
	<a class="path" href="/calendar/dayview/{3_year_number}/{3_month_number}/{3_day_number}">&lt;&lt;&nbsp;{intl-previous_month}</a>
	</td>
</tr>
</table>

<!--
		<a href="/calendar/monthview/{year_number}/{month_number}">{month_name}:</a>
		<br />
-->
<br />

<form method="post" action="/calendar/appointmentedit/edit/">
<table width="100%" border="1" cellspacing="0" cellpadding="2" >
<!-- BEGIN time_table_tpl -->
<tr>
	<td class="{td_class}" width="10%">
	<a class="path" href="/calendar/appointmentedit/new/{year_number}/{month_number}/{day_number}/{start_time}">{short_time}</a>
	</td>

	<!-- BEGIN public_appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	<table width="100%" cellspacing="0" cellpadding="4" border="0" >
	<tr>
		<td width="98%" valign="top">
		<a href="/calendar/appointmentview/{appointment_id}/"><b>{appointment_name}</b></a><br />
		</td>
		<td width="1%" valign="top" align="right">
		<a href="/calendar/appointmentedit/edit/{appointment_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcal{appointment_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcal{appointment_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
		</td>
		<td width="1%" valign="top" align="right">
			<!-- BEGIN delete_check_tpl -->
			<input type="checkbox" name="AppointmentArrayID[]" value={appointment_id}>
			<!-- END delete_check_tpl -->
		</td>
	</tr>
	<tr>
		<td colspan="3">
		{appointment_description}
		</td>
	</tr>
	</table>

	</td>
	<!-- END public_appointment_tpl -->

	<!-- BEGIN private_appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	({intl-private_appointment})
	</td>
	<!-- END private_appointment_tpl -->

	<!-- BEGIN no_appointment_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" >
	</td>
	<!-- END no_appointment_tpl -->

</tr>
<!-- END time_table_tpl -->
</table>
<br />
<input type="submit" name="DeleteAppointments" value="{intl-delete_appointments}">
</form>

<form action="/calendar/appointmentedit/edit/">

<hr noshade size="4" />

<input type="submit" name="GoDay" value="{intl-day}">
<input type="submit" name="GoMonth" value="{intl-month}">
<input type="submit" name="GoYear" value="{intl-year}">
<input type="submit" name="GoToday" value="{intl-today}">
</form>

