<h1 style="text-align: center;">{month_name} - {current_year_number}</h1>
 <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <form method="post" action="{www_dir}{index}/groupeventcalendar/monthview/">
  <tr>
    <td colspan="2" align="right">
	<table border="0" cellspacing="3" cellpadding="3">
	  <tr>
            <td align="right" valign="bottom">
              <input class="stdbutton" type="submit" Name="GetByGroup" value="{intl-show}">
	    </td>
	     <td align="right">
		<div class="gcalBoxText">{intl-type}:</div>
		<select name="GetByTypeID">
		<option value="0">{intl-default_type}</option>
		<!-- BEGIN type_item_tpl -->
		<option {type_is_selected} value="{type_id}">{type_name}</option>
		<!-- END type_item_tpl -->
		</select>
	    </td>
	    <td align="right">
	      <div class="gcalBoxText">{intl-group}:</div>
		<select name="GetByGroupID">
		<option value="0">{intl-default}</option>
		<!-- BEGIN group_item_tpl -->
		<option {group_is_selected} value="{group_id}">{group_name}</option>
		<!-- END group_item_tpl -->
		</select>
             </td>
	  </tr>
	</table>
    </td>
  </tr>
 </form>
  <tr>
	<td colspan="2" align="right" style="padding:5px;">
	<div>
	  <form action="{www_dir}{index}/groupeventcalendar/eventedit/edit/">
  	     <input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
		<input class="stdbutton" type="submit" name="GoWeek" value="{intl-week}">
		<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
		<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
		<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">

		<!-- BEGIN new_event_form_tpl -->
		<input class="stdbutton" type="submit" name="GoNew" value="{intl-new_event}">
		<!-- END new_event_form_tpl -->
	  </form>
	</div>
	</td>
  </tr>
  <tr>
	<td align="left" style="padding-top: 10px; padding-bottom: 5px;">
	<a class="menu" href="{www_dir}{index}/groupeventcalendar/monthview/{prev_year_number}/{prev_month_number}/">&lt;&lt;</a>&nbsp; <a class="menu" href="{www_dir}{index}/groupeventcalendar/monthview/{prev_year_number}/{prev_month_number}/">{intl-previous_month}</a>
	</td>
	<td align="right" style="padding-top: 10px; padding-bottom: 5px;">
	<a class="menu" href="{www_dir}{index}/groupeventcalendar/monthview/{next_year_number}/{next_month_number}/">{intl-next_month}</a>&nbsp;<a class="menu" href="{www_dir}{index}/groupeventcalendar/monthview/{next_year_number}/{next_month_number}/">&gt;&gt;</a>
	</td>
</tr>
</table>

<!-- BEGIN month_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
<!-- BEGIN week_day_tpl -->
	<th width="14%" class='tablehead' style="text-align: center; border: #929292 solid 1px; background-color: #eee;">
	{week_day_name}
	</th>
<!-- END week_day_tpl -->
</tr>

<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}" valign="top" style="height: 100px; border: #929292 solid 1px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
     <!-- BEGIN day_link_tpl -->
      <a class="gcalBoxText" style="margin-left: 5px 5px 5px 5px;" href="{www_dir}{index}/groupeventcalendar/dayview/{year_number}/{month_number_p}/{day_number}/{selected_group_id}/" title="Dayview">{day_number}</a>
    <!-- END day_link_tpl -->
    <!-- BEGIN day_no_link_tpl -->
      <a class="gcalBoxText" style="margin-left: 5px 5px 5px 5px;" href="" onmouseover="window.status='No Events in Day'; return true" onmouseout="window.status=''; return true" title="No Events in Day">{day_number}</a>
    <!-- END day_no_link_tpl -->
    </td>
    <td>
     <!-- BEGIN new_event_link_tpl -->
      <div align="right"><a class="path" href="{www_dir}{index}/groupeventcalendar/eventedit/new/{year_number}/{month_number_p}/{day_number}/">+</a></div>
     <!-- END new_event_link_tpl -->

     <!-- BEGIN no_new_event_link_tpl -->
      &nbsp;
     <!-- END no_new_event_link_tpl -->

    </td>
  </tr>
  <tr>
    <td colspan="2">
      <img src="/images/1x1.gif" height="4" width="2" border="0" alt="" /><br />
    </td>
  </tr>
<!-- BEGIN private_appointment_tpl -->
 <tr valign="top">
   <td width="8" class="tdmini"><!-- <img src="/sitedesign/{sitedesign}/images/dot.gif" border="0" alt="" /> --></td>
   <td class="tdmini"><div class="small"><i>{appointment_group}</i> - <b>{intl-pvt_event}<br /></div></td>
 </tr>
<!-- END private_appointment_tpl -->

<!-- BEGIN public_appointment_tpl -->
 <tr valign="top">
   <td width="8"> <!-- <img src="/sitedesign/{sitedesign}/images/dot.gif" border="0" alt="" /> --> </td>
   <td style="padding-bottom:5px;"><a class="small" href="{www_dir}{index}/groupeventcalendar/eventview/{appointment_id}/"
onmouseover="return overlib('<div class=\'olList\'>Name</div>{appointment_full_name}<div class=\'olList\'>Time</div> {event_start_time} - {event_stop_time}<div class=\'olList\'>Description </div>{event_description}');"
  onmouseout="return nd();"
  ><!--{appointment_group} - -->{appointment_name}</a></td>
 <tr>
<!-- END public_appointment_tpl -->
</table>
</td>
<!-- END day_tpl -->

</tr>
<!-- END week_tpl -->
</table>
<!-- END month_tpl -->

