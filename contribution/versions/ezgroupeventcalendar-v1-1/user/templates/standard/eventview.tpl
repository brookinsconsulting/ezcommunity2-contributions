<form method="get" action="/search/">
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr>
    <td align="left"> 
     <h1>{intl-event_view}</h1>
    </td>
    <td> 
	&nbsp;
    </td>
  </tr>
</table>
</form>

<!-- BEGIN error_tpl -->
<p class="error">{intl-error}</p>
<!-- END error_tpl -->

<!-- BEGIN view_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	<h2>{event_title}</h2>
	</th>
	<td width="10%" align="right">
	<!-- BEGIN private_tpl -->
	<b><i>{intl-private}</i></b>
	<!-- END private_tpl -->
	<!-- BEGIN public_tpl -->
	<b><i>{intl-public}</i></b>
	<!-- END public_tpl -->
	</td>
</tr>
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th colspan="2">
	{intl-group}:
	</th>
</tr>
<tr>
	<td colspan="2">
	{event_owner}
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>
<tr>
	<th width="50%">
	{intl-type}:
	</th>
	<th width="50%">
	{intl-priority}:
	</th>
</tr>
<tr>
	<td>
	{event_type}
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

<tr><td colspan="2"><br /></td></tr>

<tr>
	<th>
	{intl-date}:
	</th>
	<th>
	{intl-time}:
	</th>
</tr>
<tr>
	<td>
	{event_date}
	</td>
	<td>
	{event_starttime} - {event_stoptime}
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<th colspan="2">
	{intl-description}:
	</th>
</tr>
<tr>
	<td colspan="3" class="bglight">

	<table width="100%" cellspacing="0" cellpadding="4" border="0">
	<tr>
		<td>
		{event_description}
		</td>
	</tr>
	</table>
	
	</td>
</tr>

</table>

<!-- BEGIN valid_editor_tpl -->
<hr noshade size="4" />
<form method="post" action="/groupeventcalendar/eventedit/edit/{event_id}/">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td>
	<input type="hidden" name="eventArrayID[]" value={event_id}>
	<input class="stdbutton" type="submit" value="{intl-edit_event}">
    </td>
    <td align="right">
	<input class="stdbutton" type="submit" name="DeleteEvents" value="{intl-delete_events}">
    </td>
</tr>
</table>
</form>
<!-- END valid_editor_tpl -->
<hr noshade size="4" />

<!-- END view_tpl -->
<form method="post" action="/groupeventcalendar/eventedit/edit/">
<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>
