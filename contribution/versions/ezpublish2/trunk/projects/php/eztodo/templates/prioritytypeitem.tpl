<tr>
	<td bgcolor="{bg_color}">
	{priority_type_name}
	</td>
	<td bgcolor="{bg_color}" align="right">

	<a href="index.php?page={document_root}prioritytypeedit.php&Action=edit&PriorityID={priority_type_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{phone_type_id}-red','','{document_root}images/redigerminimrk.gif',1)"><img name="pt{phone_type_id}-red" border="0" src="{document_root}images/redigermini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="#" onClick="verify( 'Slette prioritet?', 'index.php?page={document_root}prioritytypeedit.php&Action=delete&PriorityID={priority_type_id}'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{phone_type_id}-slett','','{document_root}images/slettminimrk.gif',1)"><img name="pt{phone_type_id}-slett" border="0" src="{document_root}images/slettmini.gif" width="16" height="16" align="top"></a>

	&nbsp;&nbsp;
	</td>
</tr>