<tr>
	<td bgcolor="{person_bg_color}">
		<img src="{document_root}images/1x1.gif" width="11" height="18" border="0"></td>

	<td bgcolor="{person_bg_color}">
		<a href="javascript:NewWindow( 250, 350, '{document_root}personinfo.php?PID={person_id}' );" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('{first_name}{last_name}-se','','{document_root}images/personminimrk.gif',1)">
			<img src="{document_root}images/1x1.gif" width="6" height="18" border="0">
			<img name="{first_name}{last_name}-se" border="0" src="{document_root}images/personmini.gif" width="16" height="16">
			<img src="{document_root}images/1x1.gif" width="4" height="18"  border="0">
			{first_name} {last_name}
		</a>
	</td>

	<td bgcolor="{person_bg_color}">
		<a href="index.php?page={document_root}personedit.php&Action=edit&PID={person_id}" onMouseOut="MM_swapImgRestore(); MM_swapImage('{first_name}{last_name}-se','','{document_root}images/personmini.gif',1)" onMouseOver="MM_swapImage('{first_name}{last_name}-se','','{document_root}images/personminimrk.gif',1); MM_swapImage('{first_name}{last_name}-red','','{document_root}images/redigerminimrk.gif',1)"><img name="{first_name}{last_name}-red" border="0" src="{document_root}images/redigermini.gif" width="16" height="16" align="top"></a>
		</a>
	</td>

	<td bgcolor="{person_bg_color}">
		{delete_person}
	</td>
</tr>