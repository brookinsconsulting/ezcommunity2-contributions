<tr>
	<td class="{td_class}">
	<a href="/todo/todoinfo/?TodoID={todo_id}">{todo_title}</a>
	</td>

	<td class="{td_class}">
	{todo_category_id}
	</td>

	<td class="{td_class}">
	{todo_date}
	</td>

	<td class="{td_class}">
	{todo_due}
	</td>

	<td class="{td_class}">
	{todo_priority_id}
	</td>

	<td class="{td_class}">
	{todo_permission}
	</td>

	<td class="{td_class}">
	<a href="/todo/todoedit/?Action=done&TodoID={todo_id}&Status={todo_status}">{todo_status}</a>
	</td>

	<td class="{td_class}">
	<a href="todoedit/?Action=edit&TodoID={todo_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-red','','/eztodo/images/redigerminimrk.gif',1)"><img name="et{todo_id}-red" border="0" src="/eztodo/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}">
	<a href="todoedit/?Action=delete&TodoID={todo_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('et{todo_id}-slett','','/eztodo/images/slettminimrk.gif',1)"><img name="et{todo_id}-slett" border="0" src="/eztodo/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
