<tr>
	<td bgcolor="{bg_color}">
	<a href="javascript:NewWindow( 200, 150, '{document_root}noteinfo.php?NID={note_id}' );">{note_title}</a>
	</td>
	<td bgcolor="{bg_color}" align="right">
	<a href="index.php?page={document_root}noteedit.php&Action=edit&NID={note_id}' );" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ecn{note_id}-red','','/ezcontact/images/redigerminimrk.gif',1)"><img name="ecn{note_id}-red" border="0" src="/ezcontact/images/redigermini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="#" onClick="verify( 'Slette notat?', 'index.php?prePage={document_root}noteedit.php&Action=delete&NID={note_id}'); return false;"  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ecn{note_id}-slett','','/ezcontact/images/slettminimrk.gif',1)"><img name="ecn{note_id}-slett" border="0" src="/ezcontact/images/slettmini.gif" width="16" height="16" align="top"></a>
	&nbsp;&nbsp;
	</td>
</tr>