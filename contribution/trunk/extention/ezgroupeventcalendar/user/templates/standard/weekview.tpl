<!-- goooooodbye worthless table, Love Dylan
<form method="get" action="{www_dir}{index}/search/">
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

</form>
-->

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<form method="post" action="{www_dir}{index}/groupeventcalendar/dayview/">
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
		<a class="menu" href="{www_dir}{index}/groupeventcalendar/dayview/{pm_year_number}/{pm_month_number}/{pm_day_number}/{group_print_id}/">&lt;&lt;&nbsp;</a>
		<a class="menu" href="{www_dir}{index}/groupeventcalendar/monthview/{year_number}/{month_number}/{group_print_id}/">{month_name}</a>
		<a class="menu" href="{www_dir}{index}/groupeventcalendar/dayview/{nm_year_number}/{nm_month_number}/{nm_day_number}/{group_print_id}/">&nbsp;&gt;&gt;</a>

		<table width="100" border="1" cellspacing="0" cellpadding="1">
		<!-- BEGIN week_tpl -->
		<tr>
			<!-- BEGIN day_tpl -->
			<td class="{td_class}">
			<a class="small" href="{www_dir}{index}/groupeventcalendar/dayview/{year_number}/{month_number}/{day_number}/{group_print_id}/">{day_number}</a>
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
<tr>
	<form method="post" action="{www_dir}{index}/groupeventcalendar/eventedit/edit/">
	<td align="right" colspan="10" style="padding: 5px;">
	 <input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">	 
         <input class="stdbutton" type="submit" name="GoWeek" value="{intl-week}">
	 <input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
	 <input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
	 <input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">

 	 <!-- BEGIN valid_editor_tpl -->
 	 <hr noshade size="4" />
	 <input class="stdbutton" type="submit" name="GoNew" value="{intl-new_event}">&nbsp;
	 <input class="stdbutton" type="submit" name="DeleteEvents" value="{intl-delete_events}">
	<!-- END valid_editor_tpl -->
	</td>
</tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid gray;">
<!-- BEGIN day_view_long_date_header_tpl -->
<tr>
	<td id="gcalDayViewLongDateHeader" style="background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;" colspan="10"><span class="gcalDayViewLongDateHeader"><a style="font-size: large; text-decoration: none;" href="{www_dir}{index}/groupeventcalendar/monthview/{year_number}/{month_number}/{group_print_id}/">{long_date}</a></span></td>
</tr><tr>
<td width="4.5%" class="gcalDayViewTopBar"><a class="gcalSmallLink" href="{www_dir}{index}/groupeventcalendar/dayview/{pd_year_number}/{pd_month_number}/{pd_day_number}/{group_print_id}/"> &lt;&lt; </a></td>
	<!-- BEGIN day_links_tpl -->
	<td width="13%" onmouseover="this.className='gcalDayViewTopBarSelect'"
    onmouseout="this.className='{class_name}'"
    onclick="location.href = '{www_dir}{index}/groupeventcalendar/dayview/{top_year_number}/{top_month_number}/{top_day_number}/{group_print_id}/'"
    class="{class_name}">{day_name}</td>
	<!-- END day_links_tpl -->
<td width="4.5%" class="gcalDayViewTopBar"><a class="gcalSmallLink" href="{www_dir}{index}/groupeventcalendar/dayview/{nd_year_number}/{nd_month_number}/{nd_day_number}/{group_print_id}/"> &gt;&gt; </a></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gcalBorder" style="background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalDayViewBg.png') repeat;">
<tr>
<td width=5% valign="top">
<table height="100%" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN time_display_tpl -->
<tr>
	<!-- BEGIN new_event_link_tpl -->
	<td class="{td_class}" width="100%" style="text-align: center; height: 60px; border: 1px solid gray; border-right: 2px solid gray; background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalShortTimeBg.png') repeat;">
	<a class="path" style="font-size: 10px;" href="{www_dir}{index}/groupeventcalendar/eventedit/new/{year_number}/{month_number}/{day_number}/{start_time}/{group_print_id}/">{short_time}</a>
	</td>
	<!-- END new_event_link_tpl -->

	<!-- BEGIN no_new_event_link_tpl -->
	<td class="{td_class}" width="100%" style="height: 58px; border: 1px solid gray; border-right: 2px solid gray;" >{short_time}</td>
	<!-- END no_new_event_link_tpl -->
	</tr>
<!-- END time_display_tpl -->
</table></td>
<td width="95%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN time_table_tpl -->
<tr><td style="height: 15px; width:0px; overflow: hidden;"></td>
   <!-- BEGIN public_event_tpl -->
	<td class="{td_class}" valign="top" rowspan="{rowspan_value}" style="border: 1px solid black; overflow: hidden;
    background-color: #6699CC; background: url('{www_dir}{index}/ezgroupeventcalendar/user/templates/standard/images/gcalEventTransBg.png') repeat;">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="98%" nowrap valign="top" class="gcalEventTopBar" style="overflow: hidden; height: 15px;">
		<a class='gcalDayEventText' href="{www_dir}{index}/groupeventcalendar/eventview/{event_id}/">&nbsp;{event_name}&nbsp;</a><br />

		</td>

		<!-- BEGIN delete_check_tpl -->
		<td width="1%" valign="top" align="right" class="gcalEventTopBar">
		  <a href="{www_dir}{index}/groupeventcalendar/eventedit/edit/{event_id}/">
          <img name="ezcal{event_id}-red" border="0" src="/images/redigermini.gif" width="12" height="12" align="top" alt="Edit" /></a>
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
		<td colspan="3"><div class="gcalDayEventText" style="font-weight: bold; overflow: hidden; height: {event_div_height}px;">
		&nbsp;{event_description}
        </div>
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
	<td valign="top" rowspan="{rowspan_value}" style="height:15px;"></td>
	<!-- END no_event_tpl -->
</tr>
<!-- END time_table_tpl -->
</td></tr>
</table></td></tr>
 </table>
</form>
