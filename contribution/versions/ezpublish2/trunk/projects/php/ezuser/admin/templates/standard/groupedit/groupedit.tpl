<form method="post" action="/user/groupedit/{action_value}/{group_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}</p>
<input type="text" size="20" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-description}</p>
<input type="text" size="20" name="Description" value="{description_value}"/>

	<!-- BEGIN module_list_header_tpl -->
	<h3>{module_name}</h3>

	<input type="hidden" name="ModuleArray[]" value="{module_name}">

	<!-- BEGIN permission_list_tpl -->

	<input type="checkbox" name="PermissionArray[]" value="{permission_id}" {is_enabled}><span class="check"> {permission_name}</span><br>

	<!-- END permission_list_tpl -->

	<!-- END module_list_header_tpl -->

<br />


<hr noshade size="4"/>

<input type="hidden" name="GroupID" value="{group_id}" />
<input class="okbutton" type="submit" value="OK" />
<form method="post" action="/user/grouplist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>

