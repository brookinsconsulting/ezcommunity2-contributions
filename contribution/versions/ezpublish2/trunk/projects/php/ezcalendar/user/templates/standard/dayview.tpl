<h1>{intl-appointments}: {intl-day_view}</h1>
<hr noshade size="4" />

<table width="100%" border="1" cellspacing="0" cellpadding="2" >
<!-- BEGIN time_table_tpl -->
<tr>
	<td width="10%">
	{hour_value} : {minute_value}
	</td>	
	<!-- BEGIN appointment_tpl -->
	<td class="{td_class}" rowspan="{rowspan_value}" >
	{appointment_id} {appointment_name}
	</td>
	<!-- END appointment_tpl -->
</tr>
<!-- END time_table_tpl -->
</table>
