<tr>
	<tr>
	<td bgcolor="{bg_color}">
	<a href="/todo/todoinfo/?TodoID={todo_id}">{todo_title}</a>
	</td>

	<td bgcolor="{bg_color}">
	{todo_category_id}
	</td>

	<td bgcolor="{bg_color}">
	{todo_date}
	</td>

	<td bgcolor="{bg_color}">
	{todo_due}
	</td>

	<td bgcolor="{bg_color}">
	{todo_priority_id}
	</td>

	<td bgcolor="{bg_color}">
	{todo_permission}
	</td>

	<td bgcolor="{bg_color}">
	<a href="/todo/todoedit/?Action=done&TodoID={todo_id}&Status={todo_status}">{todo_status}</a>
	</td>

	<td bgcolor="{bg_color}">
	<a href="todoedit/?Action=edit&TodoID={todo_id}">Rediger</a>
	</td>

	<td bgcolor="{bg_color}">
	<a href="todoedit/?Action=delete&TodoID={todo_id}">Slett</a>
	</td>

</tr>
</tr>