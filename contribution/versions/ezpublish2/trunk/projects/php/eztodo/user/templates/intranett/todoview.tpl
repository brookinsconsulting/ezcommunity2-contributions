<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-name}</p>
	{todo_name}
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-owner}</p>
	{first_name} {last_name}
	<br><br>
	</td>
	<td class="br">
	<p class="boxtext">{intl-user}</p>
	{user_firstname} {user_lastname}
	<br><br>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-cat}</p>
	<!-- BEGIN category_select_tpl -->
	{todo_category}
	<!-- END category_select_tpl -->
	<br><br>
	</td>

	<td class="br">
	<p class="boxtext">{intl-pri}</p>
	<!-- BEGIN priority_select_tpl -->
	{todo_priority}
	<!-- END priority_select_tpl -->
	</td>
</tr>
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-desc}</p>
	<p>{todo_description}</p>
	</td>
</tr>
<tr>
	<td class="br">
	<p class="boxtext">{intl-status}:</p>
	{todo_status}
	</td>
	<td class="br">
	<p class="boxtext">{intl-view_others}:</p>
	{todo_permission}
	</td>
</tr>
</table>

<form method="post" action="/todo/todoedit/edit/{todo_id}">
<hr noshade size="4"/>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
        <td>
	<input class="okbutton" type="submit" Name="List" value="{intl-ok}">
	</td>
        <td>
	<input class="okbutton" type="submit" Name="Edit" value="{intl-edit}">
	</td>
	<!-- BEGIN mark_as_done -->
        <td>
	<input class="okbutton" type="submit" Name="Done" value="{intl-mark_as_done}">
	</td>
	<!-- END mark_as_done -->
</tr>
</table>
</form>

