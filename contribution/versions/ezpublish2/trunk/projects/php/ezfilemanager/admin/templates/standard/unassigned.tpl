<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<td>
	<h1>{intl-files}
	</td>
	<td align="right">
	<form action="/filemanager/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="/filemanager/unassigned/" >

<hr noshade="noshade" size="4" />

<!-- BEGIN file_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!--
<tr>
	<th>&nbsp;</th>
    <th>{intl-name}:</th>
    <th>{intl-size}:</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>
-->
<!-- BEGIN file_tpl -->
<tr>
	<td class="{td_class}" width="1%" valign="top">
	<img src="/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%" valign="top">
	<a href="/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="56%" valign="top">
	{file_description}&nbsp;
	<td class="{td_class}" width="1%" valign="top">
	{file_size}&nbsp;{file_unit}
	</td>

	<td class="{td_class}" valign="top">
	<select name="FolderArrayID[]">
	<option	value="-1">{intl-do_not_update}</option>
	<!-- BEGIN value_tpl -->
	<option	value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
	<td class="{td_class}" width="1%" valign="top">
	<input type="hidden" name="FileArrayID[]" value="{file_id}">
	</td>
	<td class="{td_class}" width="1%" valign="top">
	<a href="/filemanager/edit/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a><br />
	</td>
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->

<hr noshade="noshade" size="4" />

<input type="submit" name="Update" value="{intl-update}">&nbsp;

</form>
