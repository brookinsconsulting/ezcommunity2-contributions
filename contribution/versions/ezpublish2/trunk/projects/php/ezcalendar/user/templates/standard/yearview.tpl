<h1>{intl-appointments}: {intl-year_view}</h1>
<hr noshade size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td rowspan="2">
	<h2>{year_number}</h2>
	</td>
	<td align="right">
	<a href="/calendar/yearview/{prev_year_number}">&lt;&lt; {intl-previous_year}</a>
	</td>
</tr>
<tr>
	<td align="right">
	<a href="/calendar/yearview/{next_year_number}">{intl-next_year} &gt;&gt;</a>
	</td>
</tr>
</table>
<br />

<table width="100%" cellspacing="10">
{begin_tr}
<!-- BEGIN month_tpl -->
     <td>

<a href="/calendar/monthview/{year_number}/{month_number}">{month_name}:</a>
<br />
<table width="100%" border="1" cellspacing="0" cellpadding="2">
<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}">
<a href="/calendar/dayview/{year_number}/{month_number}/{day_number}">{day_number}</a>
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

<hr noshade size="4" />

<form action=/calendar/appointmentedit/edit/">
<input type="submit" name="Day" value="{intl-day}">
<input type="submit" name="Month" value="{intl-month}">
<input type="submit" name="Year" value="{intl-year}">
</form>

