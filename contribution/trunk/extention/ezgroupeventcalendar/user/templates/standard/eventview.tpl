<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr>
    <td align="left"> 
     <h1>{intl-event_view}</h1>
    </td>
  </tr>
  <tr>
    <td align="right">
	<form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/edit/">
	 <input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
	 <input class="stdbutton" type="submit" name="GoWeek" value="{intl-week}">
	 <input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
	 <input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
	 <input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
	</form>
    </td>
  </tr>
</table>

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
	<th>
	{intl-group}:
	</th>
        <th>
        {intl-type}:
        </th>
</tr>
<tr>
        <td>
        {event_owner}
        </td>
	<td>
	{event_type}
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>
<tr>
	<th width="50%">
	{intl-category}:
	</th>
	<th width="50%">
	{intl-priority}:
	</th>
</tr>
<tr>
	<td>
	{event_category}
	</td>
	<td>
        <!-- BEGIN lowest_tpl -->
        {intl-lowest}
        <!-- END lowest_tpl -->
	<!-- BEGIN low_tpl -->
	{intl-low}
	<!-- END low_tpl -->
	<!-- BEGIN normal_tpl -->
	{intl-normal}
	<!-- END normal_tpl -->
        <!-- BEGIN medium_tpl -->
        {intl-medium}
        <!-- END medium_tpl -->
	<!-- BEGIN high_tpl -->
	{intl-high}
	<!-- END high_tpl -->
        <!-- BEGIN highest_tpl -->
        {intl-highest}
        <!-- END highest_tpl -->
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
        <th>
        {intl-location}:
        </th>
        <th>
        {intl-status}:
        </th>
</tr>
<tr>
        <td>
        {event_location}
        </td>
        <td>
        <!-- BEGIN tentative_tpl -->
        {intl-tentative}
        <!-- END tentative_tpl -->
        <!-- BEGIN confirmed_tpl -->
        {intl-confirmed}
        <!-- END confirmed_tpl -->
        <!-- BEGIN cancelled_tpl -->
        {intl-cancelled}
        <!-- END cancelled_tpl -->
        </td>
</tr>

<tr><td colspan="2"><br /></td></tr>
<tr><th colspan="2">{intl-url}:</th>
</tr>
<tr>
<td colspan="2"><a href="{event_url}" target="_blank" style="text-decoration: none;">{event_url}</a></td></tr>
</table>

<!-- BEGIN recurring_event_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td width="50%">
<p class="boxtext">Recurrance Frequency</p>
{recur_freq}
</td>
<td width="50%">
<p class="boxtext">Recurrance Type</p>
{recur_type}
</td>
</tr>
<tr>
<!-- BEGIN recurring_days_week -->
<td>
<p class="boxtext">Days of Week (weekly only)</p>
<!-- BEGIN recurring_days -->
{recur_days_week}
<!-- END recurring_days -->
</td>
<!-- END recurring_days_week -->

<!-- BEGIN recurring_monthly_type -->
<td>
<p class="boxtext">Month Recurrance Type</p>
{recur_monthly_type}
</td>
<!-- END recurring_monthly_type -->
</tr>
<tr>
<td>
<p class="boxtext">{repeat_type}</p> 
{repeat_message}
</td>
<td>
<p class="boxtext">Recurring Exceptions</p>
<!-- BEGIN recurring_exceptions_tpl -->
<div class="gcalMultiListing">{recur_exception}</div>
<!-- END recurring_exceptions_tpl -->
</td>
</tr>
</table>
<!-- END recurring_event_tpl -->

<!-- BEGIN attached_file_list_tpl -->
<br />
<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr><th>{intl-attached_files}:</th></tr>
<!-- BEGIN attached_file_tpl -->
<tr>
     <td width="50%" class="{td_class}">
     <a style="text-decoration: none;" href="{www_dir}{index}/filemanager/download/{file_id}/{file_name}">{file_name}</a>
     </td>
     <td width="50%" class="{td_class}" align="right">
     <div class="p">( <a href="{www_dir}{index}/filemanager/download/{file_id}/{file_name}">{file_size}&nbsp;{file_unit}</a> )</div>
     </td>
</tr>
<tr>
     <td colspan="2" valign="top" class="{td_class}"> 
	{file_description}
     </td>
</tr>
<!-- END attached_file_tpl -->
</table>
<br />
<!-- END attached_file_list_tpl -->

<!-- BEGIN valid_editor_tpl -->
<hr noshade size="4" />

<form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/edit/{event_id}/">
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

<hr noshade size="4" />
<!-- END valid_editor_tpl -->

<!-- END view_tpl -->
