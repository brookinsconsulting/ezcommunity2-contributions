<h1>{intl-calendar_list}</h1>

<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/calendar/delete/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN calendar_item_tpl -->
<tr>
	<td class="{td_class}">
	{calendar_name}
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/calendar/edit/{calendar_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{type_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="eztc{type_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CalendarArrayID[]" value="{calendar_id}">
	</td>
</tr>
<!-- END calendar_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteCalendars" value="{intl-delete_selected_calendars}">
</form>

