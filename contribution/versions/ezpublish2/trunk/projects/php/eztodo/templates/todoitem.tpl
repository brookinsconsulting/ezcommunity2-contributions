<tr>
	<tr>
	<td bgcolor="{bg_color}">
	<a href="index.php?page={document_root}todoinfo.php&TodoID={todo_id}">{todo_title}</a>
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
	<a href="index.php?page={document_root}todoedit.php&Action=done&TodoID={todo_id}&Status={todo_status}">{todo_status}</a>
	</td>

	<td bgcolor="{bg_color}">
	<a href="index.php?page={document_root}todoedit.php&Action=edit&TodoID={todo_id}">Rediger</a>
	</td>

	<td bgcolor="{bg_color}">
	<a href="index.php?page={document_root}todoedit.php&Action=delete&TodoID={todo_id}">Slett</a>
	</td>

</tr>
</tr>