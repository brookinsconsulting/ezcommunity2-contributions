<h1>{head_line}</h1>


<form method="post" action="/user/groupedit/{action_value}/{group_id}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{intl-name}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>

<tr>
	<td>
	{intl-description}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Description" value="{description_value}"/>
	</td>
</tr>

<tr>
	<td>
	<!-- BEGIN module_list_header_tpl -->
	<h3>{module_name}</h3>

	<input type="hidden" name="ModuleArray[]" value="{module_name}">

	<!-- BEGIN permission_list_tpl -->

	<input type="checkbox" name="PermissionArray[]" value="{permission_id}" {is_enabled}> {permission_name}<br>

	<!-- END permission_list_tpl -->

	<!-- END module_list_header_tpl -->
	</td>
</tr>
<tr>
	<td>
	<input type="hidden" name="GroupID" value="{group_id}" />
	<input type="submit" value="OK" />
	</td>
</tr>
<td>
</table>
</form>

