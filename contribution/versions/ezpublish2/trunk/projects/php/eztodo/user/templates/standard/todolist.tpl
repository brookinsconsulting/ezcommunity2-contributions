<h1>{intl-todo_overview}</h1>

<hr noshade size="4">

<form method="post" action="/todo/todolist/">
<p class="boxtext">{intl-user}:</p>
<select name="GetByUserID">
<!-- BEGIN user_item_tpl -->
<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
<!-- END user_item_tpl -->
</select>

<input type="hidden" name="Action" value="ShowTodosByUser">
<input class="stdbutton" type="submit" value="Vis">

</form>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-category}:</th>
	<th>{intl-date}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-view}:</th>
	<th>{intl-status}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN todo_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/todo/todoview/{todo_id}/">{todo_name}</a>
	</td>

	<td class="{td_class}">
	{todo_category_id}
	</td>

	<td class="{td_class}">
	<span class="small">{todo_date}</span>
	</td>

	<td class="{td_class}">
	{todo_priority_id}
	</td>

	<td class="{td_class}">
	{todo_permission}
	</td>

	<td class="{td_class}">
	{todo_status}
	</td>

	<td class="{td_class}">
	<a href="/todo/todoedit/edit/{todo_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-red','','/images/redigerminimrk.gif',1)"><img name="et{todo_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}">
	<a href="/todo/todoedit/delete/{todo_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-slett','','/images/slettminimrk.gif',1)"><img name="et{todo_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>
</tr>
<!-- END todo_item_tpl -->

<!-- BEGIN no_found_tpl -->
<tr>
	<td>
	<p class="error">{intl-noitem}</p>
	</td>
</tr>
<!-- END no_found_tpl -->
</table>

<form action="/todo/todoedit/new">

<hr noshade size="4">

<input class="stdbutton" type="submit" value="{intl-newtodo}">
</form>
