<tr>
	<td bgcolor="{bg_color}">
	{persontype_name}
	</td>
	<td bgcolor="{bg_color}" align="right">
	<a href="index.php4?page={document_root}persontypeedit.php4&Action=edit&PID={persontype_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pti{persontype_id}-red','','{document_root}images/redigerminimrk.gif',1)"><img name="pti{persontype_id}-red" border="0" src="{document_root}images/redigermini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="verify( 'Slette person type?', 'index.php4?prePage={document_root}persontypeedit.php4&Action=delete&PID={persontype_id}'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pti{persontype_id}-slett','','{document_root}images/slettminimrk.gif',1)"><img name="pti{persontype_id}-slett" border="0" src="{document_root}images/slettmini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;
	</td>
</tr>
