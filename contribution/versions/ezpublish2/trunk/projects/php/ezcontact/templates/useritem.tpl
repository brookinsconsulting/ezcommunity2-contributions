<tr>
	<td bgcolor="{bg_color}">
	&nbsp;&nbsp;{user_name}
	</td>
	<td bgcolor="{bg_color}">
	{user_group}
	</td>
	<td bgcolor="{bg_color}" align="right">
	<a href="index.php4?page={document_root}useredit.php4&Action=edit&UID={user_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ui{user_id}-red','','{document_root}images/redigerminimrk.gif',1)"><img name="ui{user_id}-red" border="0" src="{document_root}images/redigermini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="verify( 'Slette bruker?', 'index.php4?prePage={document_root}useredit.php4&Action=delete&UID={user_id}'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ui{user_id}-slett','','{document_root}images/slettminimrk.gif',1)"><img name="ui{user_id}-slett" border="0" src="{document_root}images/slettmini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;
	</td>
</tr>