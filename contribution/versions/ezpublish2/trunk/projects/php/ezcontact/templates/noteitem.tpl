<tr>
	<td bgcolor="{bg_color}">
	{note_title}
	</td>
	<td bgcolor="{bg_color}">
	<a href="javascript:NewWindow( 200, 150, '{document_root}noteinfo.php4?NID={note_id}' );">mer info</a>
	</td>
	<td bgcolor="{bg_color}">
	<a href="index.php4?page={document_root}noteedit.php4&Action=edit&NID={note_id}' );">Rediger</a>
	</td>
	<td bgcolor="{bg_color}">
	<a href="#" onClick="verify( 'Slette notat?', 'index.php4?prePage={document_root}noteedit.php4&Action=delete&NID={note_id}'); return false;">Slette notat</a>
	</td>

</tr>