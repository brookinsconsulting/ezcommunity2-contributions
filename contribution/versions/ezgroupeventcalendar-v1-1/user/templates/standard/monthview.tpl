<form method="get" action="/search/">
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr>
    <td align="left"> 
     <h1>{month_name} - {current_year_number}</h1>
    </td>
    <td> 
	&nbsp;
	</td>
  </tr>
</table>
</form>

<form method="post" action="/groupeventcalendar/monthview/">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2">
	<table border="0" cellspacing="3" cellpadding="3">
	  <tr>
	    <td>
	      <p class="boxtext">{intl-group}:</p>
		<select name="GetByGroupID">
		<option value="0">{intl-default}</option>
		<!-- BEGIN group_item_tpl -->
		<option {group_is_selected} value="{group_id}">{group_name}</option>
		<!-- END group_item_tpl -->
		</select>
             </td>
	     <td>
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
	  </tr>
	</table>
    </td>
  </tr>
  <tr>
	<td>
	<a class="menu" href="/groupeventcalendar/monthview/{prev_year_number}/{prev_month_number}/">&lt;&lt; {intl-previous_month}</a>
	</td>
	<td align="right">
	<a class="menu" href="/groupeventcalendar/monthview/{next_year_number}/{next_month_number}/">{intl-next_month} &gt;&gt;</a>
	</td>
</tr>
</table>

</form>
<br />
<!-- BEGIN month_tpl -->
<table width="100%" border="0" cellspacing="3" cellpadding="2">
<tr>
<!-- BEGIN week_day_tpl -->
	<th width="14%" class='tablehead'>
	{week_day_name}
	</th>
<!-- END week_day_tpl -->
</tr>

<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}" valign="top" >

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <a class="boxtext" href="/groupeventcalendar/dayview/{year_number}/{month_number_p}/{day_number}/{selected_group_id}/">{day_number}</a>
    </td>
    <td>
     <!-- BEGIN new_event_link_tpl -->
      <div align="right"><a class="path" href="/groupeventcalendar/eventedit/new/{year_number}/{month_number_p}/{day_number}/">+</a></div>
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
   <td width="8" class="tdmini"><img src="/sitedesign/{sitedesign}/images/dot.gif" border="0" alt="" /></td>
   <td class="tdmini"><div class="small"><i>{appointment_group}</i> - <b>{intl-pvt_event}<br /></div></td>
 </tr>
<!-- END private_appointment_tpl -->

<!-- BEGIN public_appointment_tpl -->
 <tr valign="top">
   <td width="8"><img src="/sitedesign/{sitedesign}/images/dot.gif" border="0" alt="" /></td>
   <td><a class="small" href="/groupeventcalendar/eventview/{appointment_id}/">{appointment_group} - {appointment_name}</a></td>
 <tr>
<!-- END public_appointment_tpl -->
</table>
<br />
<br />
</td>
<!-- END day_tpl -->

</tr>
<!-- END week_tpl -->
</table>
<br />

<!-- END month_tpl -->

<form action="/groupeventcalendar/eventedit/edit/">

<!-- BEGIN new_event_form_tpl -->
<hr noshade size="4" />

<input class="stdbutton" type="submit" name="GoNew" value="{intl-new_event}">
<!-- END new_event_form_tpl -->

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>