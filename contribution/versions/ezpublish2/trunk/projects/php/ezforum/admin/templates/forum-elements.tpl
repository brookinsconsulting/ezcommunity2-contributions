   <tr bgcolor="{color}">
     <td><p>{name}</p></td>
     <td><p>{description}</p></td>
     <td><p>{moderated}</p></td>
     <td><p>{private}</p></td>
     <td width="120" align="right">
	 <a href="index.php4?page={docroot}/admin/forum.php4&modifyforum=yes&category_id={category_id}&forum_id={forum_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eff{forum_id}-red','','/ezforum/images/redigerminimrk.gif',1)"><img name="eff{forum_id}-red" border="0" src="/ezforum/images/redigermini.gif" width="16" height="16" align="top"></a>
     &nbsp;&nbsp;&nbsp;&nbsp;
	 <a href="index.php4?page={docroot}/admin/forum.php4&delete=yes&category_id={category_id}&forum_id={forum_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eff{forum_id}-slett','','/ezforum/images/slettminimrk.gif',1)"><img name="eff{forum_id}-slett" border="0" src="/ezforum/images/slettmini.gif" width="16" height="16" align="top"></a>
	 &nbsp;&nbsp;
	 </td>
     <td><a href="index.php4?page={docroot}/admin/message.php4&category_id={category_id}&forum_id={forum_id}">[Meldinger]</a></td>
   </tr>
