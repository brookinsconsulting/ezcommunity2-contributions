<tr>
	
	<td bgcolor="{bg_color}">{company_name}</td>
	<td bgcolor="{bg_color}"><a href="javascript:NewWindow( 300, 250, '{document_root}companyinfo.php4?CID={company_id}' );">Mer info</a></td>	
	<td bgcolor="{bg_color}"><a href="index.php4?page={document_root}companyedit.php4&Action=edit&CID={company_id}">Rediger</a> </td>
	<td bgcolor="{bg_color}"><a href="#" onClick="verify( 'Slette firma?', 'index.php4?prePage={document_root}companyedit.php4&Action=delete&CID={company_id}'); return false;">Slette firma</a></td>
</tr>
<tr>
	<td>
	</td>
	<td colspan="3">

	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	{person_list}
	</table>
	</td>
</tr>

