<tr>
	<td bgcolor="{bg_color}">
	&nbsp;&nbsp;{user_group_name}
	</td>
	<td bgcolor="{bg_color}">
	{user_group_description}
	</td>
	<td bgcolor="{bg_color}" align="right">
	<a href="index.php?page={document_root}usergroupedit.php&Action=edit&UGID={user_group_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ugi{user_group_id}-red','','{document_root}images/redigerminimrk.gif',1)"><img name="ugi{user_group_id}-red" border="0" src="{document_root}images/redigermini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="verify( 'Slette brukergruppe?', 'index.php?page={document_root}usergroupedit.php&Action=delete&UGID={user_group_id}'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ugi{user_group_id}-slett','','{document_root}images/slettminimrk.gif',1)"><img name="ugi{user_group_id}-slett" border="0" src="{document_root}images/slettmini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;
	</td>

</tr>