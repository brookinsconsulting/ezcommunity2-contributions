<table width="100%" cellspacing="2" cellpadding="0" border="0">
<tr>
        <td colspan="2" align="center">
	<span style="font-size: 25px; font-weight: bold;">
	  {year_number}
	</span>
	</td>
</tr>
<tr>
        <td colspan="2" align="right">
	<form action="{www_dir}{index}/groupeventcalendar/eventedit/edit/">
 	  <input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
          <input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
	  <input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
	  <input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
	</form>
        </td>
</tr>
<tr>
	<td>
	<a class="menu" href="{www_dir}{index}/groupeventcalendar/yearview/{prev_year_number}/">&lt;&lt; {intl-previous_year}</a>
	</td>
	<td align="right">
	<a class="menu" href="{www_dir}{index}/groupeventcalendar/yearview/{next_year_number}/">{intl-next_year} &gt;&gt;</a>
	</td>
</tr>
</table>

<table width="100%" cellspacing="10">
{begin_tr}
<!-- BEGIN month_tpl -->
     <td valign="top">

<div class="gcalYearViewHeading"><a href="{www_dir}{index}/groupeventcalendar/monthview/{year_number}/{month_number}/">{month_name}:</a></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="gcalYearViewTable">
<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}">
<a class="{td_class}" href="{www_dir}{index}/groupeventcalendar/dayview/{year_number}/{month_number}/{day_number}/">{day_number}</a>
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
{end_tr}

<!-- END month_tpl -->
</table>
