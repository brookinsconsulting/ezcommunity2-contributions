<h1>{intl-appointment_view}</h1>

<hr noshade size="4" />
<table width="100%" cellspacing="0" cellpadding="0" border="0">

<tr><td colspan="3"><br /></td></tr>

<tr>
	<th colspan="2">
	{intl-title}:
	</th>
	<th>
	<!-- BEGIN private_tpl -->
	{intl-private}
	<!-- END private_tpl -->
	<!-- BEGIN public_tpl -->
	{intl-public}
	<!-- END public_tpl -->
	</th>
</tr>
<tr>
	<td colspan="2">
	{appointment_title}
	</td>
</tr>

<tr><td colspan="3"><br /></td></tr>

<tr>
	<th>
	{intl-date}:
	</th>
	<th>
	{intl-time}:
	</th>
	<th>
	{intl-priority}:
	</th>
</tr>
<tr>
	<td>
	{appointment_date}
	</td>
	<td>
	{appointment_starttime} - {appointment_stoptime}
	</td>
	<td>
	<!-- BEGIN low_tpl -->
	{intl-low}
	<!-- END low_tpl -->
	<!-- BEGIN normal_tpl -->
	{intl-normal}
	<!-- END normal_tpl -->
	<!-- BEGIN high_tpl -->
	{intl-high}
	<!-- END high_tpl -->
	</td>
</tr>

<tr><td colspan="3"><br /></td></tr>

<tr>
	<th colspan="3">
	{intl-description}:
	</th>
</tr>
<tr>
	<td colspan="3">
	{appointment_description}
	</td>
</tr>

<tr><td colspan="3"><br /></td></tr>

</table>

<form action="/calendar/appointmentedit/edit/{appointment_id}">
<input type="submit" value="{intl-edit_appointment}">
</form>

<hr noshade size="4" />

<form action="/calendar/appointmentedit/edit/">
<input type="submit" name="GoDay" value="{intl-day}">
<input type="submit" name="GoMonth" value="{intl-month}">
<input type="submit" name="GoYear" value="{intl-year}">
</form>

