<tr>

<td bgcolor="#dcdcdc">
<a href="javascript:UpdateConsult( '{consult_title}', '{consult_id}', '{consult_body}' )">{consult_title}</a>
</td>

<td bgcolor="#dcdcdc" align="right">
<a href="index.php4?page={document_root}personedit.php4&PID={person_id}&ConsultAction=DeleteConsult&ConsultID={consult_id}&Action=edit" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ci{consult_id}-slett','','{document_root}images/slettminimrk.gif',1)"><img name="ci{consult_id}-slett" border="0" src="{document_root}images/slettmini.gif" width="16" height="16"></a>
&nbsp;&nbsp;
</td>
<td bgcolor="#dcdcdc" width="15" align="right">
<a href="javascript:NewWindow( 200, 150, '{document_root}consultinfo.php4?CID={consult_id}' );">[Vis]</a>
</td>


</tr>