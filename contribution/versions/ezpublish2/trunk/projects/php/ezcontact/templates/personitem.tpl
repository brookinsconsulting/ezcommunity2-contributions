<tr>
	<td bgcolor="{bg_color}">{first_name}</td>
	<td bgcolor="{bg_color}">{last_name}</td>	

	<td bgcolor="{bg_color}"><a href="javascript:NewWindow( 300, 250, '{document_root}personinfo.php4?PID={person_id}' );">mer info</a></td>	
	<td bgcolor="{bg_color}"><a href="index.php4?page={document_root}personedit.php4&Action=edit&PID={person_id}">Rediger</a></td>
	<td bgcolor="{bg_color}"><a href="#" onClick="verify( 'Slette kontakt person?', 'index.php4?prePage={document_root}personedit.php4&Action=delete&PID={person_id}'); return false;">Slette person type</a></td>
</tr>