   <tr bgcolor="{color}">
     <td><p>{message_id}</p></td>
     <td><p>{topic}</p></td>
     <td><p>{parent}</p></td>
     <td><p>{user}</p></td>
     <td><p class="small">{postingtime}</p></td>
     <td><p>{emailnotice}</p></td>
     <td width="80" align="right">
	 <a href="index.php4?page={docroot}/admin/editmessage.php4&category_id={category_id}&forum_id={forum_id}&message_id={message_id}&modify=modify"  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-red','','/ezforum/images/redigerminimrk.gif',1)"><img name="efm{message_id}-red" border="0" src="/ezforum/images/redigermini.gif" width="16" height="16" align="top"></a>
     &nbsp;&nbsp;&nbsp;&nbsp;
	 <a href="index.php4?page={docroot}/admin/message.php4&category_id={category_id}&forum_id={forum_id}&message_id={message_id}&deletemessage=ok" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-slett','','/ezforum/images/slettminimrk.gif',1)"><img name="efm{message_id}-slett" border="0" src="/ezforum/images/slettmini.gif" width="16" height="16" align="top"></a>
	 &nbsp;&nbsp;
	 </td>
   </tr>