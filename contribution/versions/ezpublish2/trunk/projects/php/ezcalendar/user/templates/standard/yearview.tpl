
<!-- BEGIN month_tpl -->
<a href="/calendar/monthview/{year_number}/{month_number}"> {intl-month}: {month_number} </a>
<br />
<table width="100%" border="1" cellspacing="0" cellpadding="2">
<!-- BEGIN week_tpl -->
<tr>

<!-- BEGIN day_tpl -->
<td class="{td_class}">
{day_number}
</td>
<!-- END day_tpl -->

</tr>
<!-- END week_tpl -->
</table>
<br />

<!-- END month_tpl -->