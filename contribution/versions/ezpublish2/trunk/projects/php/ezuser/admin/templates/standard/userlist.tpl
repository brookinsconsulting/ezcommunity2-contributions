<h1>{intl-head_line} - ( {user_count} )</h1>

<hr noshade size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<form method="post" action="/user/userlist/{sort_order}">
	<select name="GroupID">
	<option value="0">{intl-all}</option>
	<!-- BEGIN group_item_tpl -->
	<option {is_selected} value="{group_id}">{group_name}</option>
	<!-- END group_item_tpl -->
	</select>
	<input class="stdbutton" type="submit" value="{intl-show}">
	</form>
	</td>
</tr>

<tr>
	<th>
	<a href="/user/userlist/name/{current_group_id}">{intl-name}:</a>
	</th>

	<th>
	<a href="/user/userlist/email/{current_group_id}">{intl-email}:</a>
	</th>

	<th>
	<a href="/user/userlist/login/{current_group_id}">{intl-login}:</a>
	</th>

	<th>
	&nbsp;
	</th>

	<th>
	&nbsp;
	</th>

</tr>
<form method="post" action="/user/useredit/edit/" enctype="multipart/form-data">
<!-- BEGIN user_item_tpl -->
<tr>
	<td class="{td_class}">
	{first_name} {last_name}
	</td>

	<td class="{td_class}">
	{email}
	</td>

	<td class="{td_class}">
	{login_name}
	</td>

	<td class="{td_class}" width="1%">
	<a href="/user/useredit/edit/{user_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{user_id}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="ezuser{user_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="UserArrayID[]" value="{user_id}">
	</td>
</tr>
<!-- END user_item_tpl -->
<tr>
    <td colspan="5">
	<hr noshade="noshade" size="4" />
	<input class="stdbutton" type="submit" Name="DeleteUsers" value="{intl-delete_users}">
	</td>
</tr>
</form>
</table>
