<tr bgcolor="{color}">
    <td>
        <p>{list-Name}</p>
    </td>
    <td>
        <p>{list-Description}</p>
    </td>
    <td align="center">
        <p>{list-Private}</p>
    </td>
    
    <td width="120" align="right">
        <a href="index.php4?page={docroot}/admin/category.php4&action=modify&category_id={list-Id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ef{list-Id}-red','','/ezforum/images/redigerminimrk.gif',1)"><img name="ef{list-Id}-red" border="0" src="/ezforum/images/redigermini.gif" width="16" height="16" align="top"></a>
		&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="index.php4?page={docroot}/admin/category.php4&action=delete&category_id={list-Id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ef{list-Id}-slett','','/ezforum/images/slettminimrk.gif',1)"><img name="ef{list-Id}-slett" border="0" src="/ezforum/images/slettmini.gif" width="16" height="16" align="top"></a>
    	&nbsp;&nbsp;
	</td>
    
    <td>
        <a href="index.php4?page={docroot}/admin/forum.php4&category_id={list-Id}">[Forum]</a>
    </td>
</tr>
