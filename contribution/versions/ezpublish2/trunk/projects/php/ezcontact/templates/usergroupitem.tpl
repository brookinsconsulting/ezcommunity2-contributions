<tr>
	<td bgcolor="{bg_color}">
	{user_group_name}
	</td>
	<td bgcolor="{bg_color}">
	{user_group_description}
	</td>
	<td bgcolor="{bg_color}">
	<a href="index.php4?page={document_root}usergroupedit.php4&Action=edit&UGID={user_group_id}">Rediger</a>
	</td>
	</td>
	<td bgcolor="{bg_color}">

	<a href="#" onClick="verify( 'Slette bruker gruppe?', 'index.php4?prePage={document_root}usergroupedit.php4&Action=delete&UGID={user_group_id}'); return false;">Slette bruker gruppe</a>
	</td>

</tr>