<form method="get" action="/search/">
<!-- goooooodbye worthless table, Love Dylan 
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr>
   <!-- BEGIN header_item_tpl --\commentbreaklovedylan\>
    <td align="left"> 
      <h1>{long_date}</h1>
    </td>
    <td align="right" height="19" width="50%"> 
	&nbsp;
	
    </td>
  </tr>
</table>
-->
</form>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<form method="post" action="/groupeventcalendar/dayview/">
		<p class="boxtext">{intl-group}:</p>
		<select name="GetByGroupID">
		<option value="0">{intl-default}</option>
		<!-- BEGIN group_item_tpl -->
		<option {group_is_selected} value="{group_id}">{group_name}</option>
		<!-- END group_item_tpl -->
		</select>

		<p class="boxtext">{intl-type}:</p>
		<select name="GetByTypeID">
		<option value="0">{intl-default_type}</option>
		<!-- BEGIN type_item_tpl -->
		<option {type_is_selected} value="{type_id}">{type_name}</option>
		<!-- END type_item_tpl -->
		</select>
		&nbsp;&nbsp;&nbsp;&nbsp;

		<input class="stdbutton" type="submit" Name="GetByGroup" value="{intl-show}">


	</td>
	<td align="center">
		<a class="menu" href="/groupeventcalendar/dayview/{pm_year_number}/{pm_month_number}/{pm_day_number}/{group_print_id}/">&lt;&lt;&nbsp;</a>
		<a class="menu" href="/groupeventcalendar/monthview/{year_number}/{month_number}/{group_print_id}/">{month_name}</a>
		<a class="menu" href="/groupeventcalendar/dayview/{nm_year_number}/{nm_month_number}/{nm_day_number}/{group_print_id}/">&nbsp;&gt;&gt;</a>

		<table width="100" border="1" cellspacing="0" cellpadding="1">
		<!-- BEGIN week_tpl -->
		<tr>
			<!-- BEGIN day_tpl -->
			<td class="{td_class}">
			<a class="small" href="/groupeventcalendar/dayview/{year_number}/{month_number}/{day_number}/{group_print_id}/">{day_number}</a>
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
</form>
<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN day_view_long_date_header_tpl -->
<tr>
	<td id="gcalDayViewLongDateHeader" colspan="10"><span class="gcalDayViewLongDateHeader">{long_date}</span></td> 
<tr>
	<!-- removing this shit so I can replace it with some more shit, love Dylan 
	<td class="gcalDayViewTopBar">
	<a class="menu" href="/groupeventcalendar/dayview/{pd_year_number}/{pd_month_number}/{pd_day_number}/{group_print_id}/">&lt;&lt;&nbsp;{intl-previous_day}</a>
	</td>
	//-->
	<!-- DUMMY links, love Dylan -->
	<td width="4.5%" class="gcalDayViewTopBar"><a class="gcalSmallLink" href="/groupeventcalendar/dayview/{pd_year_number}/{pd_month_number}/{pd_day_number}/{group_print_id}/"> &lt;&lt; </a></td>
	<td width="13%" onmouseover="this.className='gcalDayViewTopBarSelect'" onmouseout="this.className='gcalDayViewTopBar'" class="gcalDayViewTopBar">Monday</td>
	<td width="13%" class="gcalDayViewTopBar">Tuesday</td>
	<td width="13%" class="gcalDayViewTopBarSelect">Wedensday</td>
	<td width="13%" class="gcalDayViewTopBar">Thursday</td>
	<td width="13%" class="gcalDayViewTopBar">Friday</td>
	<td width="13%" class="gcalDayViewTopBar">Saturday
	</td>
	<td width="13%" class="gcalDayViewTopBar">Sunday
	</td>
	<td width="4.5%" class="gcalDayViewTopBar"><a class="gcalSmallLink" href="/groupeventcalendar/dayview/{nd_year_number}/{nd_month_number}/{nd_day_number}/{group_print_id}/"> &gt;&gt; </a></td>
	<!-- End dummy links -->
	<!-- removing this shit so I can replace it with some more shit, love Dylan 
	<td class="gcalDayViewTopBar">
	<a class="menu" href="/groupeventcalendar/dayview/{nd_year_number}/{nd_month_number}/{nd_day_number}/{group_print_id}/">{intl-next_day}&nbsp;&gt;&gt;</a>
	</td>
	-->
</tr>
</table>
<form method="post" action="/groupeventcalendar/eventedit/edit/">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="gcalBorder">
<!-- BEGIN time_table_tpl -->
<tr>
	<!-- BEGIN new_event_link_tpl -->
	<td class="{td_class}" width="5%" style="height: 80px; border: 1px solid gray; border-right: 2px solid gray;">
	<a class="path" href="/groupeventcalendar/eventedit/new/{year_number}/{month_number}/{day_number}/{start_time}/{group_print_id}/">{short_time}</a>
	</td>
	<!-- END new_event_link_tpl -->

	<!-- BEGIN no_new_event_link_tpl -->
	<td class="{td_class}" width="5%" style="height: 60px;" >{short_time}</td>
	<!-- END no_new_event_link_tpl -->

	<!-- BEGIN public_event_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" style="background-color: #6699CC;" >
	<table width="100%" cellspacing="0" cellpadding="4" border="0" >
	<tr>
		<td width="98%" valign="top" class="gcalEventTopBar">
		<a class='gcalDayEventText' href="/groupeventcalendar/eventview/{event_id}/">{event_groupName} - {event_name}</a><br />
		</td>

		<!-- BEGIN delete_check_tpl -->
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">
		  <a href="/groupeventcalendar/eventedit/edit/{event_id}/">
          <img name="ezcal{event_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
		</td>
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">
		  <input type="checkbox" name="eventArrayID[]" value={event_id}>
		</td>
		<!-- END delete_check_tpl -->
		
		<!-- BEGIN no_delete_check_tpl -->
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">&nbsp;</td>
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">&nbsp;</td>
		<!-- END no_delete_check_tpl -->
	</tr>
	<tr>
		<td colspan="3">
		{event_description}&nbsp;
		</td>
	</tr>
	</table>
	</td>
	<!-- END public_event_tpl -->
	<!-- BEGIN private_event_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}">
	<b><i>{event_groupName} - {intl-private_event}</i></b>
	</td>
	<!-- END private_event_tpl -->
	<!-- BEGIN no_event_tpl -->
	<td style="background: url('http://127.0.0.5/images/gcalDayViewBg.png') repeat;" valign="top" rowspan="{rowspan_value}">&nbsp;</td>
	<!-- END no_event_tpl -->
</tr>
<!-- END time_table_tpl -->
</table>
<br />

<!-- BEGIN valid_editor_tpl -->
<hr noshade size="4" />
<input class="stdbutton" type="submit" name="GoNew" value="{intl-new_event}">&nbsp;
<input class="stdbutton" type="submit" name="DeleteEvents" value="{intl-delete_events}">
<!-- END valid_editor_tpl -->

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>