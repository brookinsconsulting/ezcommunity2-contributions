<tr>
	<td bgcolor="{bg_color}">
	{user_name}
	</td>
	<td bgcolor="{bg_color}">
	{user_group}
	</td>
	<td bgcolor="{bg_color}">
	<a href="index.php4?page={document_root}useredit.php4&Action=edit&UID={user_id}">Rediger</a>
	</td>
	</td>
	<td bgcolor="{bg_color}">

	<a href="#" onClick="verify( 'Slette bruker?', 'index.php4?prePage={document_root}useredit.php4&Action=delete&UID={user_id}'); return false;">Slette bruker</a>

	</td>
</tr>