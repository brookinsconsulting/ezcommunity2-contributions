<h1>{intl-appointment_view}</h1>

<hr noshade size="4" />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
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
	<td>
	{appointment_title}
	</td>
</tr>

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
	{appointment_time}
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
<tr>
	<th>
	{intl-description}:
	</th>
</tr>
<tr>
	<td>
	{appointment_description}
	</td>
</tr>

</table>


<hr noshade size="4" />
