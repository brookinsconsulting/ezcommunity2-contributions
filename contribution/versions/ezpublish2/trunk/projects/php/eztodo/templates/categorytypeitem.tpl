<tr>
	<td bgcolor="{bg_color}">
	{category_type_name}
	</td>
	<td width="1%" bgcolor="{bg_color}">
	<a href="index.php?page={document_root}categorytypeedit.php&Action=edit&CategoryID={category_type_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ct{category_type_id}-red','','{document_root}images/redigerminimrk.gif',1)"><img name="ct{category_type_id}-red" border="0" src="{document_root}images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" bgcolor="{bg_color}">
	<a href="#" onClick="verify( 'Slette kategori?', 'index.php?page={document_root}categorytypeedit.php&Action=delete&CategoryID={category_type_id}'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ct{category_type_id}-slett','','{document_root}images/slettminimrk.gif',1)"><img name="ct{category_type_id}-slett" border="0" src="{document_root}images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
